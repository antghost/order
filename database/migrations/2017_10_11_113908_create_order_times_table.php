<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderTimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_times', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedTinyInteger('type')->comment('1：早餐2：中餐3：晚餐');
            $table->time('book_time')->comment('开餐时限');
            $table->time('cancel_time')->comment('停餐时限');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_times');
    }
}
