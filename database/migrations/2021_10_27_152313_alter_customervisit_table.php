<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterCustomervisitTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customervisit', function (Blueprint $table) {
            $table->string('PaymentMode',100)->nullable()->after('VisitType');
            $table->string('ReceivedBy',100)->nullable()->after('VisitType');
            $table->string('RecipientName',100)->nullable()->after('VisitType');
            $table->text('SignPath')->nullable()->after('VisitType');
            $table->text('DeliveryComment')->nullable()->after('VisitType');
            $table->text('DeliveryNotes')->nullable()->after('VisitType');
            $table->integer('PackageScanStatus')->default(0)->after('VisitType');
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
