<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;

class ExecJobsValidator {
    // TODO:Move to config file
    protected $allowed_job_status = array('STARTED', 'IN_PROGRESS', 'COMPLETED', 'FAILED');
    
    public function __construct(){
        // 
    }

    public function validateLogViewer($request){
        $validator = Validator::make($request->all(), [
            'uuid' => 'required|string|max:255'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        return null;
    }

    public function validateJobSubmitRequest($request){
        $validator = Validator::make($request->all(), [
            'uuid' => 'required|string|max:255'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        return null;
    }

    public function validateJobCreationRequest($request){
        $validator = Validator::make($request->all(), [
            'job_command' => 'required|max:255',
            'job_name' => 'required',
            'job_status' => 'required|in:'.implode(',', $this->allowed_job_status),
            'job_params' => 'required'
        ]);
 
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }else{
            return NULL;
        }
    }
}

?>