<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use App\ExecJobsMod;
use App\jobs\SubmitAsyncJob;

class ExecJobs extends Controller
{
    protected $allowed_job_status = array('pending', 'running', 'failed', 'succeeded');
    public function create_job(Request $request){
        // $mandatory_params_job_creation = array('job_command','job_name','job_status','job_params');
        // $allowed_job_status = array('start','end');

        $validator = Validator::make($request->all(), [
            'job_command' => 'required|max:255',
            'job_name' => 'required',
            'job_status' => 'required|in:'.implode(',', $this->allowed_job_status),
            'job_params' => 'required'
        ]);
 
        if ($validator->fails()) {
            error_log($validator->errors());
            return redirect('hello')
                        ->withErrors($validator)
                        ->withInput();
        }

        $uuid = Str::uuid()->toString();
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

    public function _update_job($options){
        error_log('Updating');
        $jobs = ExecJobsMod::where('uuid', $options['uuid'])->update(['job_status'=>$options['job_status']]);
        error_log($jobs);
    }

    public function update_job(Request $request){
        $this->_update_job(['uuid'=>$request->uuid,'job_status'=>$request->job_status]);
    }

    public function submit_job(Request $request){
        $uuid = $request->uuid;
        error_log($uuid);
        $jobs = ExecJobsMod::where('uuid',$uuid)->get();
        error_log($jobs);
        if ( count($jobs)){
            foreach ($jobs as $job){
                error_log('Execute_job_id: '.$job->uuid);
                SubmitAsyncJob::dispatch($uuid);
            }
        }else{
            error_log('job not found');
        }
    }
}
