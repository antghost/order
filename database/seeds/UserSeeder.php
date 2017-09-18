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
        $count = DB::table('users')->count();
        for ($i =1 ;$i<= $count ; $i++){
            DB::table('users')->where('id', $i)
                ->update(['dept_id' => $depts->random()->id]);
        }
    }
}
