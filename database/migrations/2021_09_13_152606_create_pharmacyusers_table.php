<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePharmacyusersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('pharmacyusers')) return;
        Schema::create('pharmacyusers', function (Blueprint $table) {
            $table->integer('UserId')->unsigned();
            $table->integer('PharmacyId')->unsigned();
        });

        Schema::table('pharmacyusers', function($table) {
           $table->foreign('UserId')->references('Id')->on('users');
           $table->foreign('PharmacyId')->references('PharmacyId')->on('pharmacies');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pharmacyusers');
    }
}
