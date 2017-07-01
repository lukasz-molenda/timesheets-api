<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\TimeEntry;
use App\Project;
use DB;
use Carbon\Carbon;

class Client extends Model
{
    public function timesheets()
    {
    	return $this->belongsToMany('App\Timesheet', 'client_timesheet')->withPivot('timesheet_id');
    }

    public function projects()
    {
    	return $this->hasMany('App\Project');
    }

    public function getAllTimeEntries(Client $client, $month, $year)
    {
    	$client_projects = Project::where('client_id', $this->id)->get()->toArray();

    	$ids = [];
        foreach ($client_projects as $client_project) {
            array_push($ids, $client_project['id']);
        }



        return $time_entries = TimeEntry::whereIn('project_id', $ids)->whereMonth('entry_date', '=', $month)->whereYear('entry_date', '=', $year)->orderBy('entry_date', 'desc')->get();


    }

    public function countTotal(Client $client)
    {
    	$client->total = 0;

    	foreach ($client->timesheets->sortBy('min') as $timesheet) {
           if ($client->hours >= $timesheet->min && $timesheet->min == 1 && $client->hours <= $timesheet->max) {
            $client->total += $client->hours * $timesheet->price_per_hour;
            }

            if ($client->hours >= $timesheet->min && $timesheet->min != 1 && $client->hours > $timesheet->max) {
                $client->total += ($timesheet->max - ($timesheet->min - 1)) * $timesheet->price_per_hour;
            }

            if ($client->hours >= $timesheet->min && $timesheet->min != 1 && $client->hours <= $timesheet->max) {
                $client->total += ($client->hours - ($timesheet->min - 1)) * $timesheet->price_per_hour;
            }

            if ($client->hours >= $timesheet->min && $timesheet->min == 1 && $client->hours > $timesheet->max) {
                $client->total += $timesheet->max * $timesheet->price_per_hour;
            } 
        }

        return $client->total;
    }

    public function getPaginate(Client $client, $month, $year)
    {
        $client_projects = Project::where('client_id', $this->id)->get()->toArray();

        $ids = [];
        foreach ($client_projects as $client_project) {
            array_push($ids, $client_project['id']);
        }



        return $time_entries = TimeEntry::whereIn('project_id', $ids)->whereMonth('entry_date', '=', $month)->whereYear('entry_date', '=', $year)->orderBy('entry_date', 'desc')->get();


    }

}
