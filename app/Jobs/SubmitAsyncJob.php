<?php

namespace App\Jobs;
use App\ExecJobsMod;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SubmitAsyncJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $job_command;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($job_command)
    {
        $this->job_command = $job_command;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $job_details = $this->job_command;
        exec($job_details['job_command'], $output, $return_var);
        if ( $return_var == 1 ) {
            throw new Exception('Job failed');
        }else{
            return True;
        }
    }

    // public function failed(Exception $exception)
    // {
    //     // print"Error";
    //     file_put_contents('debug.log', $exception->getMessage(),FILE_APPEND | LOCK_EX);
    //     // print "Job failed";
    //     // error_log('Failed');
    //     // error_log($exception);
    // }
}

?>