<?php

namespace App\Http\Controllers;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use App\ExecJobsMod;
use App\ExecJobsEntriesMod;
use App\jobs\SubmitAsyncJob;
use Illuminate\Support\Facades\DB;

class ExecJobService{
    public function __construct(){
        // 
    }

    public function create_job($request){
        $uuid = Str::uuid()->toString();
        $obj = new ExecJobsMod();
        $obj->uuid = $uuid;
        $obj->job_command = $request->job_command;
        $obj->job_name = $request->job_name;
        $obj->job_params = $request->job_params;
        $obj->additional_info = $request->additional_info;
        $obj->save();
        return response()->json(['uuid'=>$uuid], 200);
    }

    public function _update_job($options){
        $allowed_fields_to_update = array('job_status', 'start_time', 'end_time', 'total_items', 'completed_items', 'error_info');
        $fields_to_update = array();
        foreach( $allowed_fields_to_update as $field){
            if (isset($options[$field])){
                $fields_to_update[$field] = $options[$field];
            }
        }
        $jobs = ExecJobsEntriesMod::where(['uuid'=> $options['uuid'],'id'=>$options['id']])->update($fields_to_update);
    }

    public function create_run_entry($options){
        $obj = new ExecJobsEntriesMod();
        $obj->uuid = $options['uuid'];
        $obj->job_status = $options['job_status'];
        $obj->save();
        $last_id = $obj->id;
        return $last_id;
    }

    public function update_job($request){
        $this->_update_job(['uuid'=>$request->uuid,'job_status'=>$request->job_status]);
    }

    public function submit_job($request){
        $uuid = $request->uuid;
        $jobs = ExecJobsMod::where('uuid',$uuid)->get();
        if (count($jobs)){
            $job = $jobs[0];
            $job['id'] = $this->create_run_entry(['uuid'=>$job['uuid'], 'job_status'=>'STARTED']);
            try{
                $this->_update_job(['uuid'=>$job['uuid'], 'id'=>$job['id'], 'job_status'=>'IN_PROGRESS', 'start_time'=>DB::raw('NOW()')]);
                SubmitAsyncJob::dispatchNow($job);
                $this->_update_job(['uuid'=>$job['uuid'], 'id'=>$job['id'], 'job_status'=>'COMPLETED', 'end_time'=>DB::raw('NOW()')]);
            }catch(Exception $e){
                $errorMsg  = $e->getMessage();
                $this->_update_job(['uuid'=>$job['uuid'], 'id'=>$job['id'], 'job_status'=>'FAILED', 'end_time'=>DB::raw('NOW()'), 'error_info'=>$errorMsg]);
                error_log($e);
            }
            return response()->json(['uuid'=>$uuid, 'job_id'=>$job['id'], 'status'=>'Job is running'], 200);
        }else{
            return response()->json(['uuid'=>$uuid,'status'=>'uuid not found'], 200);
        }
    }

    public function log_viewer($request){
        $uuid = $request->uuid;
        $jobs = ExecJobsEntriesMod::where('uuid',$uuid)->get();
        $response = array();
        error_log($jobs);
        if (count($jobs)){
            foreach ($jobs as $job){
                $response[] = $job;
            }
        }else{
            error_log('job not found');
        }
        return response()->json($response);
    }

    public function job_list($request){
        $jobs = ExecJobsMod::all();
        $response = array();
        error_log($jobs);
        if (count($jobs)){
            foreach ($jobs as $job){
                $response[] = $job;
            }
        }else{
            error_log('job not found');
        }
        return response()->json($response);
    }
}


?>