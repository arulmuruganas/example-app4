<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;

class ExecJobsValidator {
    protected $allowed_job_status = array('STARTED', 'IN_PROGRESS', 'COMPLETED', 'FAILED');
    
    public function __construct(){
        // 
    }

    public function validateJobCreationRequest($request){
        error_log('Validating request');
        $validator = Validator::make($request->all(), [
            'job_command' => 'required|max:255',
            'job_name' => 'required',
            'job_status' => 'required|in:'.implode(',', $this->allowed_job_status),
            'job_params' => 'required'
        ]);
 
        if ($validator->fails()) {
            error_log($validator->errors());
            return redirect('hello');
        }else{
            error_log('All is well');
            return NULL;
        }
    }
}

?>