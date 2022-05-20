<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVisitbatchTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('visitbatch')) return;
        Schema::create('visitbatch', function (Blueprint $table) {
            $table->integer('BatchID')->unsigned();
            $table->integer('CustomerVisitID')->unsigned();
            $table->integer('BatchStatusID')->default('0');
            $table->time('ArrivalLogTime')->nullable();
            $table->time('FinishLogTime')->nullable();
        });


        Schema::table('visitbatch', function($table) {
           $table->foreign('BatchID')->references('BatchID')->on('batch')->onDelete('cascade')->onUpdate('cascade');
           $table->foreign('CustomerVisitID')->references('CustomerVisitID')->on('customervisit')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('visitbatch');
    }
}
