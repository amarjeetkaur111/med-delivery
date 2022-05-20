<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePharmaciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('pharmacies')) return;
        Schema::create('pharmacies', function (Blueprint $table) {
            $table->increments('PharmacyId');
            $table->string('PharmacyName',255);
            $table->text('PharmacyAddress');
            $table->string('PharmacyManager',255);
            $table->string('PharmacyPhone',255);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pharmacies');
    }
}
