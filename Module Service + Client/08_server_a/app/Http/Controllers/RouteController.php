<?php

namespace App\Http\Controllers;

use App\History;
use App\Place;
use App\Schedule;
use App\Selection;
use App\SelectionSchedule;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ScheduleData
{
    public $visits = [], $schedules = [];

    function lastVisit()
    {
        return $this->visits[count($this->visits) - 1];
    }

    function lastSchedule()
    {
        return $this->schedules[count($this->schedules) - 1];
    }

    function isVisited($place)
    {
        return in_array($place, $this->visits);
    }
}

class RouteController extends Controller
{
    private $fromPlaceId, $toPlaceId, $departureTime;
    private $routes = [], $places = [];

    public function __construct()
    {
        $data = Place::all();
        foreach ($data as $item)
            $this->places[$item->id] = $item;
    }

    function search($from, $to, $depart = '')
    {
        $data = [
            'from_place_id' => $from,
            'to_place_id' => $to,
            'departure_time' => $depart
        ];
        $valid = Validator::make($data, [
            'from_place_id' => 'required|integer|exists:places,id|different:to_place_id',
            'to_place_id' => 'required|integer|exists:places,id|different:from_place_id',
            'departure_time' => 'nullable|date_format:H:i:s'
        ]);
        if ($valid->fails()) return unauthorized();

        //date_default_timezone_set('Asia/Bangkok');

        $this->fromPlaceId = $from;
        $this->toPlaceId = $to;
        $this->departureTime = $depart ? $depart : date('H:i:s');

        $startSchedules = Schedule::getSchedules($this->fromPlaceId, $this->departureTime);
        foreach ($startSchedules as $start) {
            $start->from_place = $this->places[$start->from_place_id];
            $start->to_place = $this->places[$start->to_place_id];
            $start->travel_time = $this->getTravelTime($start->departure_time, $start->arrival_time);

            $scheduleData = new ScheduleData();
            $scheduleData->visits = [$this->fromPlaceId, $start->to_place_id];
            $scheduleData->schedules[] = $start;
            $this->findRoute($scheduleData);
        }

        $user = User::get();
        $historyFrom = History::whereUserId($user->id)->wherePlaceId($this->fromPlaceId);
        if ($historyFrom->first()) $historyFrom->update(['count' => $historyFrom->first()->count + 1]);
        else History::create([
            'user_id' => $user->id,
            'place_id' => $this->fromPlaceId
        ]);

        $historyTo = History::whereUserId($user->id)->wherePlaceId($this->toPlaceId);
        if ($historyTo->first()) $historyTo->update(['count' => $historyTo->first()->count + 1]);
        else History::create([
            'user_id' => $user->id,
            'place_id' => $this->toPlaceId
        ]);

        $routeResults = [];
        foreach ($this->routes as $route) {
            $scheduleIdArray = [];
            $scheduleResults = [];
            $numberOfHistory = 0;
            foreach ($route->schedules as $schedule) {
                $scheduleIdArray[] = $schedule->id;
                unset($schedule->from_place_id);
                unset($schedule->to_place_id);
                unset($schedule->distance);
                unset($schedule->speed);
                unset($schedule->status);
                $scheduleResults[] = $schedule;
            }

            $selectionId = $this->getSelectionId($this->fromPlaceId, $this->toPlaceId, $scheduleIdArray);
            if ($selectionId)
                $numberOfHistory = Selection::find($selectionId)->count;

            $routeResults[] = [
                'number_of_history' => $numberOfHistory,
                'schedules' => $scheduleResults
            ];
        }

        usort($routeResults, function ($route1, $route2) {
            $time1 = $route1['schedules'][count($route1['schedules']) - 1]->arrival_time;
            $time2 = $route2['schedules'][count($route2['schedules']) - 1]->arrival_time;
            $timeDiff = $this->timeDiff($time1, $time2);
            if ($timeDiff > 0) return 1;
            else if ($timeDiff < 0) return -1;
            else return 0;
        });

        $routeResults = array_slice($routeResults, 0, 5);
        return success($routeResults);
    }

    function selection(Request $request)
    {
        $valid = Validator::make($request->all(), [
            'from_place_id' => 'required|integer|exists:places,id|different:to_place_id',
            'to_place_id' => 'required|integer|exists:places,id|different:from_place_id',
            'schedule_id' => 'required|array'
        ]);
        if ($valid->fails()) return valid();

        $selectionId = $this->getSelectionId($request->from_place_id, $request->to_place_id, $request->schedule_id);
        if ($selectionId) {
            $selection = Selection::find($selectionId);
            $selection->count = $selection->count + 1;
            $selection->save();
        } else {
            $selection = Selection::create([
                'from_place_id' => $request->from_place_id,
                'to_place_id' => $request->to_place_id
            ]);
            foreach ($request->schedule_id as $item)
                SelectionSchedule::create([
                    'selection_id' => $selection->id,
                    'schedule_id' => $item
                ]);
        }
        return success([
            'message' => 'create success'
        ]);
    }

    private function getTravelTime($departure_time, $arrival_time)
    {
        $time1 = new \DateTime(date('Y-m-d ' . $departure_time));
        $time2 = new \DateTime(date('Y-m-d ' . $arrival_time));
        return date_diff($time2, $time1)->format('%H:%I:%S');
    }

    private function findRoute(ScheduleData $scheduleData)
    {
        $current = $scheduleData->lastSchedule();
        if ($current->to_place_id == $this->toPlaceId) {
            $this->routes[] = $scheduleData;
            return;
        }
        if (count($scheduleData->schedules) > 20) return;

        $nextPlaceId = $scheduleData->lastVisit();
        $arrivalTime = $current->arrival_time;
        $nextSchedules = Schedule::getNextSchedules($nextPlaceId, $arrivalTime);

        foreach ($nextSchedules as $next) {
            if ($this->checkSchedule($scheduleData, $next)) {
                $next->from_place = $this->places[$next->from_place_id];
                $next->to_place = $this->places[$next->to_place_id];
                $next->travel_time = $this->getTravelTime($next->departure_time, $next->arrival_time);

                $clone = clone $scheduleData;
                $clone->visits[] = $next->to_place_id;
                $clone->schedules[] = $next;
                $this->findRoute($clone);
            }
        }
    }

    private function checkSchedule(ScheduleData $scheduleData, $next)
    {
        if ($scheduleData->isVisited($next->to_place_id)) return false;
        return true;
    }

    private function getSelectionId($fromPlaceId, $toPlaceId, array $scheduleIdArray)
    {
        $selections = Selection::whereFromPlaceId($fromPlaceId)->whereToPlaceId($toPlaceId)->get();

        foreach ($selections as $selection) {
            $scheduleIdArraySelf = [];
            $selectionSchedules = SelectionSchedule::whereSelectionId($selection->id)->get();
            foreach ($selectionSchedules as $selectionSchedule)
                $scheduleIdArraySelf[] = $selectionSchedule->schedule_id;

            sort($scheduleIdArray);
            sort($scheduleIdArraySelf);
            if ($scheduleIdArray === $scheduleIdArraySelf) return $selection->id;
        }
        return 0;
    }

    private function timeDiff($time1, $time2)
    {
        $time1 = strtotime('1970-01-01 ' . $time1);
        $time2 = strtotime('1970-01-01 ' . $time2);
        return $time1 - $time2;
    }
}
