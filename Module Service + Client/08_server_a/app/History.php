<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    public $timestamps = false;
    protected $guarded = [];

    function place() {
        return $this->belongsTo('App\Place', 'place_id');
    }
}
