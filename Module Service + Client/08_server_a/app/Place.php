<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
    public $timestamps = false;
    protected $guarded = [];

    function histories()
    {
        return $this->hasMany('App\History', 'place_id');
    }

}
