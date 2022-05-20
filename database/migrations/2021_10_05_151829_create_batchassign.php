<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBatchassign extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('batchassign')) return;
        Schema::create('batchassign', function (Blueprint $table) {
            $table->increments('BatchAssignID')->index();
            $table->integer('BatchID')->unsigned();
            $table->integer('DriverID')->unsigned();
            $table->dateTime('AssignedDate', $precision = 0)->useCurrent();
        });

        Schema::table('batchassign', function($table) {
           $table->foreign('BatchID')->references('BatchID')->on('batch')->onDelete('cascade')->onUpdate('cascade');
           $table->foreign('DriverID')->references('Id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('batchassign');
    }
}
