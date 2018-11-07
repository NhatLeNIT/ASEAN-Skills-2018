<?php

namespace App\Http\Controllers;

use App\History;
use App\Place;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use File;


class Poi
{
    private $latitude;

    private $longitude;

    public function __construct($latitude, $longitude)
    {
        $this->latitude = deg2rad($latitude);
        $this->longitude = deg2rad($longitude);
    }

    public function getLatitude()
    {
        return $this->latitude;
    }

    public function getLongitude()
    {
        return $this->longitude;
    }

    public function distanceTo(Poi $other)
    {
        $earthRadius = 6371000;

        $diffLatitude = $other->getLatitude() - $this->latitude;
        $diffLongitude = $other->getLongitude() - $this->longitude;

        $a = sin($diffLatitude / 2) * sin($diffLatitude / 2) +
            cos($other->getLatitude()) * cos($this->latitude) *
            sin($diffLongitude / 2) * sin($diffLongitude / 2);
        $c = 2 * asin(sqrt($a));

        return $c * $earthRadius;
    }
}

class PoiFactory
{
    private $start;

    private $end;

    private $startPoint;

    private $width;

    private $height;

    public function __construct($start = ['latitude' => 13.772478, 'longitude' => 100.482653], $end = ['latitude' => 13.736280, 'longitude' => 100.536051], $width = 1280, $height = 800)
    {
        $this->start = $start;
        $this->end = $end;
        $this->width = $width;
        $this->height = $height;
        $this->mapStartPoint();
    }

    private function calc($source, $target)
    {
        $_a = new Poi($source['latitude'], $target['longitude']);
        $a = new Poi($target['latitude'], $target['longitude']);

        $y = $_a->distanceTo($a);

        $_b = new Poi($source['latitude'], $source['longitude']);
        $b = new Poi($source['latitude'], $target['longitude']);

        $x = $_b->distanceTo($b);

        return [
            'x' => $x,
            'y' => $y
        ];
    }

    private function mapStartPoint()
    {
        $calc = $this->calc($this->start, $this->end);

        $this->startPoint = [
            'x' => $calc['x'] / $this->width,
            'y' => $calc['y'] / $this->height
        ];
    }

    public function calculate($target)
    {
        $calc = $this->calc($this->start, $target);

        return [
            'x' => floor($calc['x'] / $this->startPoint['x']),
            'y' => floor($calc['y'] / $this->startPoint['y'])
        ];
    }
}

class PlaceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = User::get();
        $histories = History::whereUserId($user->id)->orderBy('count', 'desc')->get();
        $places = [];
        $placeId = [];
        foreach ($histories as $history) {
            $places[] = $history->place;
            $placeId[] = $history->place_id;
        }

        $data = Place::whereNotIn('id', $placeId)->orderBy('name')->get();
        foreach ($data as $item)
            $places[] = $item;

        return success($places);
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
            'name' => 'required|max:100',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'open_time' => 'required|date_format:H:i',
            'close_time' => 'required|date_format:H:i',
            'image' => 'required|file|image',
            'type' => 'required|in:Attraction,Restaurant',
        ]);

        if ($valid->fails()) return valid();

        $file = $request->file('image');
        $name = time() . $file->getClientOriginalName();
        $file->move('public/uploads', $name);
        $data['image_path'] = $name;
        unset($data['image']);

        $poi = new PoiFactory();
        $coordinate = $poi->calculate([
            'latitude' => $data['latitude'],
            'longitude' => $data['longitude']
        ]);
        $data['x'] = $coordinate['x'];
        $data['y'] = $coordinate['y'];

        unset($data['token']);
        Place::create($data);
        return success([
            'message' => 'create success'
        ]);

    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $place = Place::find($id);
        if ($place) {
            $place = $place->withCount([
                'histories as num_searches' => function ($query) {
                    $query->select(DB::raw('SUM(count) as num_searches'));
                }
            ])->whereId($id)->first()->toArray();

            $place['num_searches'] = (int)$place['num_searches'];
            unset($place['id']);
            unset($place['latitude']);
            unset($place['longitude']);
        }
        return success($place);
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
        $place = Place::find($id);
        if (!$place) return badUpdate();
        $data = $request->all();
        $valid = Validator::make($data, [
            'name' => 'nullable|max:100',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'open_time' => 'nullable|date_format:H:i',
            'close_time' => 'nullable|date_format:H:i',
            'image' => 'nullable|file|image',
            'type' => 'nullable|in:Attraction,Restaurant',
        ]);

        if ($valid->fails()) return badUpdate();

        if (isset($request->image)) {
            if (file_exists(public_path() . '/uploads/' . $place->image_path))
                File::delete(public_path() . '/uploads/' . $place->image_path);

            $file = $request->file('image');
            $name = time() . $file->getClientOriginalName();
            $file->move('public/uploads', $name);
            $data['image_path'] = $name;
            unset($data['image']);
        }


        $data['latitude'] = !empty($data['latitude']) ? $data['latitude'] : $place->latitude;
        $data['longitude'] = !empty($data['longitude']) ? $data['longitude'] : $place->longitude;

        $poi = new PoiFactory();
        $coordinate = $poi->calculate([
            'latitude' => $data['latitude'],
            'longitude' => $data['longitude']
        ]);
        $data['x'] = $coordinate['x'];
        $data['y'] = $coordinate['y'];

        unset($data['token']);
        $place->update($data);
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
        $place = Place::find($id);
        if (!$place) return badDeleted();
        if (file_exists(public_path() . '/uploads/' . $place->image_path))
            File::delete(public_path() . '/uploads/' . $place->image_path);
        $place->delete();

        return success([
            'message' => 'delete success'
        ]);
    }
}
