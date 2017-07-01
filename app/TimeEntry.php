<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TimeEntry extends Model
{
    protected $table = 'time_entries';

    public function project()
    {
    	return $this->belongsTo('App\Project');
    }
}
