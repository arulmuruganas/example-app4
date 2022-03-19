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
    // TODO : Controller functins are duplicated in service file, refactor it
    public function create_job(Request $request){
        $validator = new ExecJobsValidator();
        $service = new ExecJobService();
        $result = $validator->validateJobCreationRequest($request);
        if ($result){
            return $result;
        }
        error_log($result);
        error_log('Request validated');
        $service->create_job($request);
    }

    public function submit_job(Request $request){
        $service = new ExecJobService();
        $service->submit_job($request);
    }

    public function update_job(Request $request){
        $service = new ExecJobService();
        $service->update_job($request);
    }
  
}
