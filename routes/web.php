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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/create_job', 'ExecJobs@create_job');

Route::get('/submit_job', 'ExecJobs@submit_job');

Route::get('/update_job', 'ExecJobs@update_job');

Route::get('/job_list', 'ExecJobs@job_list');

Route::get('/log_viewer', 'ExecJobs@log_viewer');