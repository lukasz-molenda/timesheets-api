<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::post('clients/index', 'ClientsController@store')->name('clients.store');
Route::get('clients/index', 'ClientsController@index')->name('clients.index');
Route::get('clients/{id}', 'ClientsController@show')->name('clients.show');
Route::put('client/{id}/timesheets/store', 'TimesheetsController@store')->name('timesheets.store');
Route::put('clients/{client_id}/{id}', 'TimesheetsController@update')->name('timesheets.update');
Route::delete('timesheets/{client_id}/{id}', 'TimesheetsController@destroy')->name('timesheets.destroy');
Route::delete('clients/{id}', 'ClientsController@destroy')->name('clients.destroy');
Route::post('clients/{id}/update', 'ClientsController@update')->name('clients.update');
Route::get('client/{id}/edit', 'ClientsController@edit')->name('clients.edit');

Auth::routes();

Route::get('/', 'HomeController@index')->name('home');
Route::get('/{md5}', 'ClientsController@single')->name('clients.single');
Route::post('project/{id}/time_entry_store', 'ProjectsController@time_entry_store')->name('time_entry.store');

Route::post('project/{id}/project_store', 'ProjectsController@project_store')->name('project.store');

Route::get('client/{id}/timesheets_edit', 'TimesheetsController@edit')->name('timesheets.edit');
Route::get('client/{id}/projects_edit', 'ProjectsController@edit')->name('projects.edit');
Route::post('client/{id}/change_month', 'ProjectsController@change_month')->name('projects.change_month');
Route::get('client/{id}/work_hours_edit', 'ProjectsController@work_hours_edit')->name('work_hours.edit');
Route::delete('client/{client_id}/project/{id}/delete', 'ProjectsController@project_destroy')->name('projects.destroy');
Route::get('client/{client_id}/project/{id}/edit', 'ProjectsController@project_edit')->name('project.edit');
Route::put('client/{client_id}/project/{id}/update', 'ProjectsController@project_update')->name('projects.update');
Route::delete('client/{client_id}/time_entry/{id}/delete', 'ProjectsController@time_entries_destroy')->name('time_entries.destroy');