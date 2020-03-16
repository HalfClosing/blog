<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Timeline extends Model
{
    protected $guarded = [];

    public function getCreatedAtAttribute($date)
    {
        return substr($date, 0, 13).' 时';
    }
}
