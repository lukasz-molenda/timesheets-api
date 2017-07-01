<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    public function client()
    {
    	return $this->belongsTo('App\Client');
    }

    public function time_entries()
    {
    	return $this->hasMany('App\TimeEntry');
    }
}
