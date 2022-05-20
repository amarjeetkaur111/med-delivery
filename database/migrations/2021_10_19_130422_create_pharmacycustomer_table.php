<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePharmacycustomerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('pharmacycustomer')) return;
        Schema::create('pharmacycustomer', function (Blueprint $table) {
            $table->bigInteger('CustomerId')->unsigned()->index();
            $table->integer('PharmacyId')->unsigned();
        });

        Schema::table('pharmacycustomer', function($table) {
           $table->foreign('CustomerId')->references('CustomerId')->on('customers')->onDelete('cascade');
           $table->foreign('PharmacyId')->references('PharmacyId')->on('pharmacies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pharmacycustomer');
    }
}
