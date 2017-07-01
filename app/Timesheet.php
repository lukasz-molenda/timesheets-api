<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Timesheet extends Model
{
    public function clients()
    {
    	return $this->belongsToMany('App\Client', 'client_timesheet');
    }
}
