<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGoodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('goods')) return;
        Schema::create('goods', function (Blueprint $table) {
            $table->increments('GoodsId');
            $table->integer('GoodsTypeId')->unsigned();
            $table->string('GoodsName',255);
            $table->decimal('Cost',$precision = 10, $scale = 2);
            $table->integer('Quantity');
        });

        Schema::table('goods', function($table) {
           $table->foreign('GoodsTypeId')->references('GoodsTypeId')->on('goodstype');
       });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('goods');
    }
}
