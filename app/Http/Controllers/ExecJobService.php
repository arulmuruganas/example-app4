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
        // error_log('Creating');
        $obj = new ExecJobsMod();
        $obj->uuid = $uuid;
        $obj->job_command = $request->job_command;
        $obj->job_name = $request->job_name;
        $obj->job_params = $request->job_params;
        $obj->additional_info = $request->additional_info;
        // $obj->job_status = $request->job_status;
        // $obj->start_time = $request->start_time;
        $obj->save();
        error_log('job created');
    }

    public function _update_job($options){
        error_log('Updating');
        if (isset($options['job_status']) && $options['job_status'] == 'STARTED'){
            $obj = new ExecJobsEntriesMod();
            $obj->uuid = $options['uuid'];
            $obj->job_status = $options['job_status'];
            $obj->save();
            $last_id = $obj->id;
            error_log('job entry created'.$last_id);
            return $last_id;
            // $jobs = ExecJobsEntriesMod::where(['uuid'=> $options['uuid'],'id'=>$last_id])->get();
            // error_log($jobs);
            // return $jobs[0];
        }else{
            $allowed_fields_to_update = array('job_status', 'start_time', 'end_time', 'total_items', 'completed_items', 'error_info');
            $fields_to_update = array();
            foreach( $allowed_fields_to_update as $field){
                if (isset($options[$field])){
                    $fields_to_update[$field] = $options[$field];
                }
            }
            $jobs = ExecJobsEntriesMod::where(['uuid'=> $options['uuid'],'id'=>$options['id']])->update($fields_to_update);
        }
    }

    public function update_job($request){
        $this->_update_job(['uuid'=>$request->uuid,'job_status'=>$request->job_status]);
    }

    public function submit_job($request){
        $uuid = $request->uuid;
        error_log($uuid);
        $jobs = ExecJobsMod::where('uuid',$uuid)->get();
        $job_id = $this->_update_job(['uuid'=>$jobs[0]->uuid, 'job_status'=>'STARTED']);
        error_log($jobs);
        if (count($jobs)){
            foreach ($jobs as $job){
                error_log('Execute_job_id: '.$job->uuid);
                try{
                    $this->_update_job(['uuid'=>$job->uuid, 'id'=>$job_id, 'job_status'=>'IN_PROGRESS', 'start_time'=>DB::raw('NOW()')]);
                    SubmitAsyncJob::dispatch($uuid, $job_id);
                    $this->_update_job(['uuid'=>$job->uuid, 'id'=>$job_id, 'job_status'=>'COMPLETED', 'end_time'=>DB::raw('NOW()')]);
                }catch(Exception $e){
                    $errorMsg  = $e->getMessage();
                    $this->_update_job(['uuid'=>$job->uuid, 'id'=>$job_id, 'job_status'=>'FAILED', 'end_time'=>DB::raw('NOW()'), 'error_info'=>$errorMsg ]);
                    error_log($e);
                }
            }
        }else{
            error_log('job not found');
        }
    }
}

?>