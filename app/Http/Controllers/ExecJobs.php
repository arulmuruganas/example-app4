<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\ExecJobsMod;
use App\jobs\SubmitAsyncJob;
use App\Http\Controllers\ExecJobsValidator;
use App\Http\Controllers\ExecJobService;

class ExecJobs extends Controller
{
    protected $validator,$job_service;

    public function __construct(){
        $this->validator = new ExecJobsValidator();
        $this->job_service = new ExecJobService();
    }

    // TODO : Controller functins are duplicated in service file, refactor it
    public function create_job(Request $request){
        $result = $this->validator->validateJobCreationRequest($request);
        if($result) return $result;
        error_log('Request validated');
        return $this->job_service->create_job($request);
    }

    public function submit_job(Request $request){
        $result = $this->validator->validateJobSubmitRequest($request);
        if($result) return $result;
        return $this->job_service->submit_job($request);
    }

    public function update_job(Request $request){
        $this->job_service->update_job($request);
    }

    public function job_list(Request $request){
        return $this->job_service->job_list($request);
    }
  
    public function log_viewer(Request $request){
        $result = $this->validator->validateLogViewer($request);
        if($result) return $result;
        return $this->job_service->log_viewer($request);
    }
}
