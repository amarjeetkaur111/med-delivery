<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchedulerrecurrenceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('schedulerrecurrence')) return;
        Schema::create('schedulerrecurrence', function (Blueprint $table) {
            $table->id('SchedulerRecurrenceID');
            $table->integer('SchedulerID')->unsigned();
            $table->integer('GoodsID')->unsigned();
            $table->integer('RecurrenceTypeID');
            $table->string('RecurrenceSelectedDays',100)->nullable();
        });

        Schema::table('schedulerrecurrence', function($table) {
           $table->foreign('SchedulerID')->references('SchedulerID')->on('scheduler');
           $table->foreign('GoodsID')->references('GoodsId')->on('goods');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('schedulerrecurrence');
    }
}
