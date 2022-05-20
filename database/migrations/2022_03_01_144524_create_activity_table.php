<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActivityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('activities_history')) return;
        Schema::create('activities_history', function (Blueprint $table) {
            $table->increments('ActivityID')->unsigned()->index();
            $table->smallInteger('ActivityGenerateBy')->unsigned()->comment('1 = Web , 2 = App');
            $table->bigInteger('UserID')->unsigned();
            $table->integer('UserType')->unsigned()->comment('1=Admin ,2=Patient,3=Pharmacy,4=Nurse,5=Staff');
            $table->bigInteger('PatientVisitID')->unsigned()->nullable();
            $table->smallInteger('EntityID')->unsigned()->comment('1 = PatientInfo , 2 = PatientHistory , 3 = Schedule , 4 = Caretask, 5 = Reports');	
            $table->string('EntityType',255)->comment('Section name');
            $table->integer('EntityFlag')->unsigned()->nullable();
            $table->integer('EntityStatus')->unsigned()->nullable();
            $table->text('EntityComment')->nullable();
            $table->bigInteger('CaretaskID')->unsigned();
            $table->string('UpdatedByUser',255);
            $table->text('CommentByStystem')->nullable();
            $table->dateTime('UpdatedTime', $precision = 0)->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('activity');
    }
}
