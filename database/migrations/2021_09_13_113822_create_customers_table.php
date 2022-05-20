<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('customers')) return;
        Schema::create('customers', function (Blueprint $table) {
            $table->id('CustomerId');
            $table->string('FirstName',100);
            $table->string('LastName',100);
            $table->longText('PhoneNumbers');
            $table->tinyInteger('Status')->default('1');
            $table->dateTime('CreatedDate', $precision = 0)->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customers');
    }
}
