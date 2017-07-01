<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Client;
use App\Timesheet;
use App\Project;
use Session;
use URL;
use Carbon\Carbon;

class ClientsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['single']]);
    }

    public function store(Request $request)
    {
        $request->user()->authorizeRoles('admin');

        $client = new Client;

        $clients = Client::all();

        if (isset($request->name)) {
            $client->name = $request->name;

            $client->md5 = md5($request->name);

            $client->save();

            Session::flash('success', 'Klient został dodany');
        } else {
            Session::flash('error', 'Proszę podać nazwę klienta');
        }
        
        return redirect()->route('clients.index')->withClients($clients);
    }

    public function index(Request $request)
    {
        $request->user()->authorizeRoles('admin');

    	$clients = Client::all();
        
    	return view('clients.index')->withClients($clients);
    }

    public function destroy(Request $request, $id)
    {
        $request->user()->authorizeRoles('admin');

    	$client = Client::find($id);

        $client->timesheets()->detach();

        $client->delete();

        return redirect()->route('clients.index');
    }

    public function show(Request $request, $id)
    {
        $request->user()->authorizeRoles('admin');

        $client = Client::find($id);
        if ($client->timesheets->isNotEmpty()) {
            $min_value = ($client->timesheets->last()->max) + 1;
        } else {
            $min_value = 1;
        }
        return view('timesheets.single')->withClient($client)->withMinValue($min_value);
    }

    public function update(Request $request, $id)
    {
        $request->user()->authorizeRoles('admin');

        $client = Client::find($id);

        
        $client->max = $request->max;
        
        
        $client->save(); 

        return redirect()->route('clients.edit', $id);
    }

    public function single($md5)
    {

        $client = Client::where('md5', $md5)->get()->first();

        return view('clients.sheet')->withClient($client);
    }

    public function edit(Request $request, $id)
    {
        $request->user()->authorizeRoles('admin');

        if (Session::exists('carbon')) {
           $month = session('month');
           $year = session('year');
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


        $main = URL::to('/');

        $link = $main.'/'.$client->md5;

        if ($client->timesheets->isNotEmpty()) {
            $min_value = ($client->timesheets->last()->max) + 1;
        } else {
            $min_value = 1;
        }

        $client->hours = array_sum(array_column($time_entries->toArray(), 'hours'));
        $client->save();

        $client->total = $client->countTotal($client);

        $client->save();

        return view('timesheets.single')->withMinValue($min_value)->withClient($client)->withLink($link)->withProjects($projects)->withTimeEntries($time_entries);
    }
}
