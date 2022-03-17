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
    private $uuid;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $uuid)
    {
        $this->uuid = $uuid;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $uuid = $this->uuid;
        error_log($uuid);
        error_log('Hello');
        // TODO: Add code to update the job status and end_time
        // TODO: Querying job table twice one to validate uuid and other to get command to run, reduce it to one query
        $jobs = ExecJobsMod::where('uuid',$uuid)->get();
        error_log($jobs);
    } 
}
