<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ExecJobsMod;
use Illuminate\Support\Str;

class ExecJobs extends Controller
{
    public function get_all_data(){
        $pizzas = Pizza::all();
        var_dump($pizzas);
    }

    public function create_job(Request $request){
        $mandatory_params_job_creation = array('job_command','job_name','job_status','job_params');
        $allowed_job_status = array('start','end');

        $uuid = Str::uuid()->toString();

        if (! $request->has($mandatory_params_job_creation)) {
            error_log('Some of the params missing. Please check');
            return;
        }
        if ($request->job_status){
            if (! in_array($request->job_status, $allowed_job_status)) {
                error_log('Invalid job_status');
                redirect('http://127.0.0.1:8000/');
                return ;
            }
        }
        // error_log('Creating');
        $obj = new ExecJobsMod();
        $obj->uuid = $uuid;
        $obj->job_command = $request->job_command;
        $obj->job_name = $request->job_name;
        $obj->job_params = $request->job_params;
        $obj->additional_info = $request->additional_info;
        $obj->job_status = $request->job_status;
        $obj->start_time = $request->start_time;
        // $obj->end_time = $request->end_time;
        // $obj->duration = $request->duration;
        // $obj->total_items = $request->total_items;
        // $obj->completed_items = $request->completed_items;
        // $obj->error_info = $request->error_info;
        $obj->save();
        error_log('job created');
    }
}
