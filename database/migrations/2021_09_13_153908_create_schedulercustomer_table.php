<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchedulercustomerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('schedulercustomer')) return;
        Schema::create('schedulercustomer', function (Blueprint $table) {
            $table->increments('SchedulerCustomerID')->index();
            $table->integer('SchedulerID')->unsigned();
            $table->bigInteger('CustomerID')->unsigned();
            $table->integer('Status')->default('0');
        });

        Schema::table('schedulercustomer', function($table) {
           $table->foreign('SchedulerID')->references('SchedulerID')->on('scheduler')->onDelete('cascade')->onUpdate('cascade');
           $table->foreign('CustomerID')->references('CustomerId')->on('customers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('schedulercustomer');
    }
}
