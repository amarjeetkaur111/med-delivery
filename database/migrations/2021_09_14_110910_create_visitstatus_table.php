<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVisitstatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('visitstatus')) return;
        Schema::create('visitstatus', function (Blueprint $table) {
            $table->increments('VisitID')->index();
            $table->integer('CustomerVisitID')->unsigned();
            $table->integer('GoodsID')->unsigned();
            $table->integer('GoodsSubID')->default('0');
            $table->integer('GoodsStatusID');
            $table->longText('Notes')->nullable();
            $table->integer('GoodsTypeID')->nullable();
        });

        Schema::table('visitstatus', function($table) {
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
        Schema::dropIfExists('visitstatus');
    }
}
