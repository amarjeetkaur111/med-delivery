<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomervisitTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('customervisit')) return;
        Schema::create('customervisit', function (Blueprint $table) {
            $table->increments('CustomerVisitID')->index();
            $table->bigInteger('CustomerID')->unsigned();
            $table->date('VisitDate')->nullable();
            $table->string('EmployeeID',150)->nullable();
            $table->integer('VisitStatusID')->default('1');
            $table->integer('SchedulerID')->unsigned();
            $table->time('ArrivalTime')->nullable();
            $table->time('FinishTime')->nullable();
            $table->time('ArrivalLogTime')->nullable();
            $table->time('FinishLogTime')->nullable();
            $table->integer('VisitType')->default('1');
        });

        Schema::table('customervisit', function($table) {
            $table->foreign('SchedulerID')->references('SchedulerID')->on('scheduler');
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
        Schema::dropIfExists('customervisit');
    }
}
