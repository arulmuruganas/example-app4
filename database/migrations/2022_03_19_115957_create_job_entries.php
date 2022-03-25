<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobEntries extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_entries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('uuid');
            $table->string('job_status');
            $table->string('start_time')->nullable();
            $table->string('end_time')->nullable();
            $table->integer('duration')->unsigned()->nullable();
            $table->integer('total_items')->unsigned()->nullable();
            $table->integer('completed_items')->unsigned()->nullable();
            $table->text('error_info')->nullable();
            $table->timestamps();
            $table->foreign('uuid')->references('uuid')->on('execute_jobs');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('job_entries');
    }
}
