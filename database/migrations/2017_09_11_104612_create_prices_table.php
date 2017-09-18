<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prices', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100)->unique();
            $table->decimal('breakfast', 10, 2);
            $table->decimal('lunch', 10, 2);
            $table->decimal('dinner', 10, 2);
            $table->dateTime('begin_date')->nullable()->comment('生效日期');
            $table->string('status', 1)->nullable()->comment('状态');
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
        Schema::dropIfExists('prices');
    }
}
