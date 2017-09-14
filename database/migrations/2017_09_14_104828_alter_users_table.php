<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            //更换部门为一对多（原来为多对多），增加管理员字段
            $table->unsignedInteger('dept_id')->after('mobilephone')->index();
            $table->boolean('is_admin')->after('dept_id');
            $table->boolean('active')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
            $table->unsignedTinyInteger('active')->change();
            $table->dropColumn('is_admin');
            $table->dropColumn('dept_id');
        });
    }
}
