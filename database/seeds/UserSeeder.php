<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
//        factory('App\User', 300)->create([
//            'password' => bcrypt('123456'),
//        ]);
        //批量随机更新人员所属部门
        $depts = DB::table('depts')->select('id')->get();
        $users = DB::table('users')->get();
        foreach ($users as $user){
            DB::table('users')->where('id', $user->id)
                ->update(['dept_id' => $depts->random()->id]);
        }
    }
}
