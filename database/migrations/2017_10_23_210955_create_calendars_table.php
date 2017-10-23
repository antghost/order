<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCalendarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('calendars', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100);
            $table->unsignedTinyInteger('type')->comment('0:假期1:工作日');
            $table->date('begin_date')->comment('开始日期');
            $table->date('end_date')->comment('结束日期');
            $table->unsignedInteger('user_id')->index();
            $table->softDeletes();
            $table->timestamps();
            $table->index(['begin_date', 'end_date']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('calendars');
    }
}
