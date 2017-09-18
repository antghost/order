<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePriceUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('price_users', function (Blueprint $table) {
            $table->increments('id');
            $table->dateTime('begin_date')->comment('开始日期');
            $table->dateTime('valid_date')->nullable()->comment('有效日期');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('price_id');
            $table->decimal('breakfast', 10, 2);
            $table->decimal('lunch', 10, 2);
            $table->decimal('dinner', 10, 2);
            $table->softDeletes();
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
        Schema::dropIfExists('price_users');
    }
}
