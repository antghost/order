<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\User;

class BookMealSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('book_breakfasts')->truncate();
        DB::table('book_lunches')->truncate();
        DB::table('book_dinners')->truncate();

        $users = User::all();
        //给每个用户造几条开餐数据
        $beginTime = Carbon::now();
        echo 'begin    at: '.$beginTime.PHP_EOL;

        foreach ($users as $user){
            $beginDate = $user->priceUsers()->select('begin_date')->min('begin_date');
            $dt = Carbon::parse($beginDate);
            $dtLunch = Carbon::parse($beginDate);
            $dtDinner = Carbon::parse($beginDate);

            //早餐
            $user->bookBreakfasts()->create([
                'begin_date' => $beginDate,
                'end_date' => $dt->addDays(random_int(2, 30)),
            ]);
            //午餐
            $user->bookLunches()->create([
                'begin_date' => $beginDate,
                'end_date' => $dtLunch->addDays(random_int(2, 30)),
            ]);
            //晚餐
            $user->bookDinners()->create([
                'begin_date' => $beginDate,
                'end_date' => $dtDinner->addDays(random_int(2, 30)),
            ]);

            if ($dt->lte(Carbon::create(2017, 7, 1))) {
                if ($dt->between(Carbon::create(2010, 1, 1), Carbon::create(2011, 12, 31))) $max = 60;
                if ($dt->between(Carbon::create(2012, 1, 1), Carbon::create(2013, 12, 31))) $max = 40;
                if ($dt->between(Carbon::create(2014, 1, 1), Carbon::create(2015, 12, 31))) $max = 20;
                if ($dt->between(Carbon::create(2016, 1, 1), Carbon::create(2017, 7, 1))) $max = 10;
                $num = random_int(35, 50);
                for ($i = 1; $i <= $num; $i++) {
                    $s = $dt->addDays(random_int(10, 30))->copy();
                    $e = $dt->addDays(random_int(10, $max))->copy();
                    $sLunch = $dtLunch->addDays(random_int(10, 30))->copy();
                    $eLunch = $dtLunch->addDays(random_int(10, $max))->copy();
                    $sDinner = $dtDinner->addDays(random_int(10, 30))->copy();
                    $eDinner = $dtDinner->addDays(random_int(10, $max))->copy();

                    //早餐
                    $user->bookBreakfasts()->create([
                        'begin_date' => $s,
                        'end_date' => $e,
                    ]);
                    //午餐
                    $user->bookLunches()->create([
                        'begin_date' => $sLunch,
                        'end_date' => $eLunch,
                    ]);
                    //晚餐
                    $user->bookDinners()->create([
                        'begin_date' => $sDinner,
                        'end_date' => $eDinner,
                    ]);
                }
            }

            $user->bookBreakfasts()->create([
                'begin_date' => $dt->addDays(random_int(5, 20)),
                'end_date' => null,
            ]);
            $user->bookLunches()->create([
                'begin_date' => $dtLunch->addDays(random_int(5, 20)),
                'end_date' => null,
            ]);
            $user->bookDinners()->create([
                'begin_date' => $dtDinner->addDays(random_int(5, 20)),
                'end_date' => null,
            ]);
        }

        $endTime = Carbon::now();
        echo 'finished at: '.$endTime.PHP_EOL;
        echo 'time used: '.$endTime->diffInMinutes($beginTime).' Minutes'.PHP_EOL;
        echo 'time used: '.$endTime->diffInSeconds($beginTime).' Seconds'.PHP_EOL;
    }
}
