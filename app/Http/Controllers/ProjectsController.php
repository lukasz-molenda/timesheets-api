<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Project;
use App\TimeEntry;
use App\Client;
use Carbon\Carbon;
use Session;

class ProjectsController extends Controller
{
    public function time_entry_store(Request $request, $id)
    {
        $time_entry =  new TimeEntry;
    	$time_entry->project_id = $request->project_id;
    	$time_entry->entry_date = $request->entry_date;
    	$time_entry->hours = $request->hours;
    	$time_entry->save();

    	$client = Client::find($id);
    	$client->hours += $request->hours;
    	$client->save();

    	$client->total = $client->countTotal($client);
    	$client->save();

        Session::flash('success', 'Godziny zostały dodane do projektu.');

    	return redirect()->route('work_hours.edit', $id);
    }

    public function project_store(Request $request, $id)
    {   
        $client = Client::find($id);
        $client->total = $client->countTotal($client);

        $client->save();
        
    	$project = new Project;
    	$project->name = $request->name;
    	$project->description = $request->description;
    	$project->client_id =  $id;

    	$project->save();

    	return redirect()->route('projects.edit', $id);
    }

    public function edit(Request $request, $id)
    {
        $request->user()->authorizeRoles('admin');
        
        if (Session::exists('carbon')) {
           $month = session('carbon')->month;
           $year = session('carbon')->year;
           $date = session('carbon');
           $carbon = new Carbon($date);
           $carbon = $carbon->format('Y-m');
        } else {
            $month = Carbon::now()->month;
            $year = Carbon::now()->year;
            $carbon = Carbon::now()->format('Y-m');
        }

        $client = Client::find($id);
        
        $projects = Project::where('client_id', $id)->get();

        $time_entries = $client->getAllTimeEntries($client, $month, $year);


        if ($client->timesheets->isNotEmpty()) {
            $min_value = ($client->timesheets->last()->max) + 1;
        } else {
            $min_value = 1;
        }

        $client->hours = array_sum(array_column($time_entries->toArray(), 'hours'));
        $client->save();

        $client->total = $client->countTotal($client);



        $client->save();

        return view('projects.edit')->withMinValue($min_value)->withClient($client)->withProjects($projects)->withTimeEntries($time_entries)->withCarbon($carbon);
    }

    public function change_month(Request $request, $id)
    {

        $date = $request->month;
        $carbon = new Carbon($date);
        Session::put('carbon', $carbon);
        return redirect()->route('work_hours.edit', $id)->withCarbon($carbon);

    }

    public function work_hours_edit(Request $request, $id)
    {
        $request->user()->authorizeRoles('admin');
        
        if (Session::exists('carbon')) {
           $month = session('carbon')->month;
           $year = session('carbon')->year;
           $date = session('carbon');
           $carbon = new Carbon($date);
           $carbon = $carbon->format('Y-m');
        } else {
            $month = Carbon::now()->month;
            $year = Carbon::now()->year;
            $carbon = Carbon::now()->format('Y-m');
        }

        $client = Client::find($id);

        $projects = Project::where('client_id', $id)->get();

        $time_entries = $client->getAllTimeEntries($client, $month, $year);
        $time_entries_pag = $client->getPaginate($client, $month, $year);

        return view('work_hours.edit')->withClient($client)->withProjects($projects)->withCarbon($carbon)->withTimeEntries($time_entries)->withTimeEntriesPag($time_entries_pag);
    }

    public function project_destroy(Request $request, $client_id, $id)
    {
        $project = Project::find($id);

        $query = TimeEntry::where('project_id', $id)->get();

        if ($query->count() == 0) {
            $project->delete();
            Session::flash('success', 'Projekt został usunięty.');
            return redirect()->route('projects.edit', $client_id);
        } else {
            Session::flash('error', 'W projekcie istnieją przepracowane godziny. Proszę je usunąć przed usunięciem projektu.');
            return redirect()->route('projects.edit', $client_id);
        }
    }

    public function project_edit($client_id, $id)
    {
        $client = Client::find($client_id);
        $project = Project::find($id);
        return view('projects.single', [$client->id, $project->id])->withClient($client)->withProject($project);
    }

    public function project_update(Request $request, $id, $client_id)
    {
        $client = Client::find($client_id);
        $project = Project::find($id);
        $project->name = $request->name;
        $project->description = $request->description;
        $project->save();

        return redirect()->route('projects.edit', $client_id);
    }

    public function time_entries_destroy(Request $request, $client_id, $id)
    {
        $time_entry = TimeEntry::find($id);

        $time_entry->delete();
        Session::flash('success', 'Wpis godzinowy został usunięty.');
        return redirect()->route('work_hours.edit', $client_id);
        
    }
}
