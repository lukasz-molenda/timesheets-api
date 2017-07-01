<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Client;
use App\Timesheet;
use App\ClientTimesheet;
use App\Project;
use App\TimeEntry;
use Session;
use Illuminate\Support\Facades\Route;
use URL;
use Carbon\Carbon;

class TimesheetsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function store(Request $request, $id)
    {
        $request->user()->authorizeRoles('admin');

    	$client = Client::find($id);

        $client->total = $client->countTotal($client);

        $client->save();

        if (($request->min) >= ($request->max)) {
            Session::flash('error', 'Wartość maksymalna musi być większa od minimalnej.');
            return redirect()->route('timesheets.edit', $id);
         }

        if (Timesheet::where('min', $request->min)->where('max', $request->max)->where('price_per_hour', $request->price_per_hour)->exists()) {

            $timesheet = Timesheet::where('min', $request->min)->where('max', $request->max)->where('price_per_hour', $request->price_per_hour)->first();

            if (ClientTimesheet::where('client_id', $id)->where('timesheet_id', $timesheet->id)->exists()) {
                Session::flash('error', 'Ten klient już posiada taki przedział.');
                return redirect()->route('timesheets.edit', $id);
            }

            $client->timesheets()->attach($timesheet->id);

        } else {
            $new_timesheet = new Timesheet;
            $new_timesheet->min = $request->min;
            $new_timesheet->max = $request->max;
            $new_timesheet->price_per_hour = $request->price_per_hour;
            $new_timesheet->save();
            $client->timesheets()->attach($new_timesheet->id);
        }

        Session::flash('success', 'Przedział został dodany.');
        return redirect()->route('timesheets.edit', $id);

    }

    public function update(Request $request, $id, $client_id)
    {
        $request->user()->authorizeRoles('admin');

        $client = Client::find($client_id);


         if (($request->min) >= ($request->max)) {
            Session::flash('error', 'Wartość maksymalna musi być większa od minimalnej.');
            return redirect()->route('timesheets.edit', $client_id);
         }

         if (Timesheet::where('min', $request->min)->where('max', $request->max)->where('price_per_hour', $request->price_per_hour)->exists()) {
            $new_timesheet = Timesheet::where('min', $request->min)->where('max', $request->max)->where('price_per_hour', $request->price_per_hour)->first();
            $client->timesheets()->detach($id);
            $client->timesheets()->attach($new_timesheet->id);
            Session::flash('success', 'Przedział został zmieniony');
            $client->total = $client->countTotal($client);
            $client->save();
            return redirect()->route('timesheets.edit', $client_id);
        } else {
            $timesheet = new Timesheet;
            $timesheet->min = $request->min;
            $timesheet->max = $request->max;
            $timesheet->price_per_hour = $request->price_per_hour;
            $timesheet->save();
            $client->timesheets()->detach($id);
            $client->timesheets()->attach($timesheet->id);
            Session::flash('success', 'Przedział został zmieniony.');
            $client->total = $client->countTotal($client);
            $client->save();
            return redirect()->route('timesheets.edit', $client_id);
        }
    }

    public function destroy(Request $request, $id, $client_id)
    {
        $request->user()->authorizeRoles('admin');

        $client = Client::find($client_id);

        $timesheet = Timesheet::find($id);

        $query = ClientTimesheet::where('timesheet_id', $timesheet->id)->get();

        if (count($query) == 0) {
            $timesheet->delete();
        }

        $client->timesheets()->detach($timesheet->id);

        Session::flash('success', 'Przedział został usunięty.');
        return redirect()->route('timesheets.edit', $client_id);
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
        

        return view('timesheets.edit')->withMinValue($min_value)->withClient($client)->withProjects($projects)->withTimeEntries($time_entries);
    }
}
