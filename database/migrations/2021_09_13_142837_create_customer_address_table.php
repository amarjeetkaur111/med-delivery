<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerAddressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('customersaddress')) return;
        Schema::create('customersaddress', function (Blueprint $table) {
            $table->id('CustomerAddressId');
            $table->bigInteger('CustomerId')->unsigned();
            $table->tinyInteger('SetAsPrimary')->default('1');
            $table->string('AddressLine',255);
            $table->string('UnitNumber',75)->nullable();
            $table->decimal('Longitude', $precision = 11, $scale = 8);
            $table->decimal('Latitude', $precision = 11, $scale = 8);
            $table->string('City',150);
            $table->string('Province',150);
            $table->string('PostalCode',150);
            $table->string('Country',75);
            $table->integer('PhoneTypeId')->nullable();
            $table->string('PhoneNumber',75)->nullable();
            $table->string('Extension',75)->nullable();
            $table->text('AdditionalInfo')->nullable();
            $table->string('DoorSecurityCode',75)->nullable();
        });

        Schema::table('customersaddress', function($table) {
           $table->foreign('CustomerId')->references('CustomerId')->on('customers');
       });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customersaddress');
    }
}
