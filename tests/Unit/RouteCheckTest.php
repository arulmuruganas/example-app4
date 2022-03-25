<?php

namespace Tests\Unit;

// use PHPUnit\Framework\TestCase;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;


class RouteCheckTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testBaseURL()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    public function testCreateJobWithOutParams()
    {
        $response = $this->get('/create_job');
        $response->assertStatus(400);
    }

    public function testCreateJobWithParams()
    {
        $response = $this->get('/create_job?job_command=ls&job_name=test_job&job_params=paramsinput&additional_info=additional_info');
        error_log($response->getContent());
        $response->assertStatus(200);
    }

    public function testSubmitJob()
    {
        $response = $this->get('/submit_job');
        $response->assertStatus(200);
    }

    public function testJobList()
    {
        $response = $this->get('/job_list');
        $response->assertStatus(200);
    }

    public function testLogViewer()
    {
        $response = $this->get('/log_viewer');
        $response->assertStatus(200);
    }

    public function testCheckNotExists()
    {
        $response = $this->get('/CheckNotExists');
        $response->assertStatus(404);
    }
}
