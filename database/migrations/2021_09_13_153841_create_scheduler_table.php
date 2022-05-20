<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchedulerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('scheduler')) return;
        Schema::create('scheduler', function (Blueprint $table) {
            $table->increments('SchedulerID')->index();
            $table->tinyInteger('SchedulerTypeID')->unsigned();
            $table->date('StartDate');
            $table->date('EndDate')->nullable();
            $table->time('StartTime');
            $table->time('EndTime');
            $table->string('Amount',100);
            $table->string('OrderNote',255)->nullable();
            $table->string('Tags',100)->nullable();
            $table->string('EmployeeNumber',150);
            $table->dateTime('UpdatedDate');
            $table->dateTime('AddedDate');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('scheduler');
    }
}
