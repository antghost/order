<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class PriceUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //随机更新所有人的收费标准
        $users = DB::table('users')->get();
        DB::table('price_users')->truncate();

        foreach ($users as $user){
            $dt = Carbon::create(2010,1,1,0);
            //随机取出一条收费标准
            $price = DB::table('prices')->inRandomOrder()->first();
            //更新人员收费标准
            DB::table('users')->where('id', $user->id)->update(['price_id' => $price->id]);
            //增加人员收费标准历史
            DB::table('price_users')->insert([
                //开始日期为2010-1-1加1~1825中随机一天
                'begin_date' => $dt->addDays(random_int(1, 1825)),
                'user_id' => $user->id,
                'price_id' => $price->id,
                'breakfast' => $price->breakfast,
                'lunch' => $price->lunch,
                'dinner' => $price->dinner,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

    }
}
