<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('users')) return;
        Schema::create('users', function (Blueprint $table) {
            $table->increments('Id');
            $table->string('UserType',150);
            $table->string('FirstName',150);
            $table->string('LastName',150);
            $table->string('Password',250);
            $table->string('Email',255)->unique();
            $table->string('PhoneNumber',150);
            $table->dateTime('CreatedDate')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
