<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Alter2CustomervisitTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customervisit', function (Blueprint $table) {
            if (!Schema::hasColumn('customervisit', 'ReceivedByRelation')) {
                $table->string('ReceivedByRelation',100)->nullable()->after('PackageScanStatus');
            } 
            if (!Schema::hasColumn('customervisit', 'CollectedAmount')) {
                $table->string('CollectedAmount',100)->nullable()->after('PackageScanStatus');
            }            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
