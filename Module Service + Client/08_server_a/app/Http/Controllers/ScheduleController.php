<?php

namespace App\Http\Controllers;

use App\Place;
use App\Rules\CheckTimeRange;
use App\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $schedules = Schedule::all();
        foreach ($schedules as $schedule) {
            $schedule->from_place = Place::find($schedule->from_place_id)->name;
            $schedule->to_place = Place::find($schedule->to_place_id)->name;
        }
        return success($schedules);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $valid = Validator::make($data, [
            'line' => 'required|integer',
            'from_place_id' => 'required|integer|exists:places,id|different:to_place_id',
            'to_place_id' => 'required|integer|exists:places,id|different:from_place_id',
            'departure_time' => 'required|date_format:H:i:s',
            'arrival_time' => 'required|date_format:H:i:s|after:departure_time',
            'distance' => 'required|integer',
            'speed' => 'required|integer',
            'status' => 'nullable|in:AVAILABLE,UNAVAILABLE'
        ]);
        if ($valid->fails()) return valid();

        $valid = Validator::make($data, [
            'departure_time' => new CheckTimeRange(),
            'arrival_time' => new CheckTimeRange(),
        ]);
        if ($valid->fails()) return valid();

        unset($data['token']);

        Schedule::create($data);
        return success([
            'message' => 'create success'
        ]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $schedule = Schedule::find($id);
        if (!$schedule) return badUpdate();

        $data = $request->all();
        $data['from_place_id'] = !empty($data['from_place_id']) ? (int)$data['from_place_id'] : (int)$schedule->from_place_id;
        $data['to_place_id'] = !empty($data['to_place_id']) ? (int)$data['to_place_id'] : (int)$schedule->to_place_id;

        $valid = Validator::make($data, [
            'line' => 'nullable|integer',
            'from_place_id' => 'nullable|integer|exists:places,id|different:to_place_id',
            'to_place_id' => 'nullable|integer|exists:places,id|different:from_place_id',
            'departure_time' => 'nullable|date_format:H:i:s',
            'arrival_time' => 'nullable|date_format:H:i:s|after:departure_time',
            'distance' => 'nullable|integer',
            'speed' => 'nullable|integer',
            'status' => 'nullable|in:AVAILABLE,UNAVAILABLE'
        ]);

        if ($valid->fails()) return badUpdate();

        $valid = Validator::make($data, [
            'departure_time' => new CheckTimeRange(),
            'arrival_time' => new CheckTimeRange(),
        ]);
        if ($valid->fails()) return badUpdate();

        unset($data['token']);

       $schedule->update($data);
        return success([
            'message' => 'update success'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $schedule = Schedule::find($id);
        if(!$schedule) return badDeleted();
        $schedule->delete();
        return success([
            'message' => 'delete success'
        ]);
    }
}
