<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Schedule extends Model
{
    public $timestamps = false;
    protected $guarded = [];
    protected $hidden = ['id', 'from_place_id', 'to_place_id'];

    public static function getSchedules($fromPlaceId, $departureTime, $status = 'AVAILABLE')
    {
        return DB::select('SELECT * FROM schedules WHERE (to_place_id, arrival_time) IN (
                                        SELECT to_place_id, min(arrival_time)
                                        FROM schedules
                                        WHERE from_place_id = ? AND departure_time >= ? AND status = ?
                                        GROUP BY to_place_id)
                                    ORDER BY departure_time', [$fromPlaceId, $departureTime, $status]);
    }

    public static function getNextSchedules($fromPlaceId, $departureTime, $status = 'AVAILABLE')
    {
        return DB::select('SELECT * FROM schedules WHERE (to_place_id, arrival_time) IN (
                                        SELECT to_place_id, min(arrival_time)
                                        FROM schedules
                                        WHERE from_place_id = ? AND departure_time >= ? AND status = ?
                                        GROUP BY to_place_id)', [$fromPlaceId, $departureTime, $status]);
    }
}
