<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExecuteJobs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('execute_jobs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('uuid')->unique();
            $table->string('job_command');
            $table->string('job_name');
            $table->string('job_params');
            $table->text('additional_info');
            $table->string('job_status');
            $table->string('start_time')->nullable();
            $table->string('end_time')->nullable();
            $table->integer('duration')->unsigned()->nullable();
            $table->integer('total_items')->unsigned()->nullable();
            $table->integer('completed_items')->unsigned()->nullable();
            $table->text('error_info')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('execute_jobs');
    }
}
