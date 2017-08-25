<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username',50)->unique();
            $table->string('name',100);
            $table->string('nickname',100)->unique();
            $table->unsignedTinyInteger('active');
            $table->string('email')->unique();
            $table->string('tag')->nullable();
            $table->string('telephone', 20)->nullable();
            $table->integer('mobilephone')->nullable();
            $table->string('ip_address', 16)->nullable();
            $table->string('password');
            $table->dateTime('last_login_at')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
