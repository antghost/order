<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReportDatasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('report_datas', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedSmallInteger('year');
            $table->unsignedSmallInteger('month');
            $table->unsignedSmallInteger('breakfasts')->comment('早餐天数');
            $table->unsignedSmallInteger('lunches')->comment('午餐天数');
            $table->unsignedSmallInteger('dinners')->comment('晚餐天数');
            $table->decimal('breakfast_price',10,2)->comment('早餐价格');
            $table->decimal('lunch_price',10,2)->comment('中餐价格');
            $table->decimal('dinner_price',10,2)->comment('晚餐价格');
            $table->decimal('breakfast_amount',10,2)->comment('早餐总额');
            $table->decimal('lunch_amount',10,2)->comment('中餐总额');
            $table->decimal('dinner_amount',10,2)->comment('晚餐总额');
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
        Schema::dropIfExists('report_datas');
    }
}
