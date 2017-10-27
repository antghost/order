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
        foreach ($users as $user){
            $beginDate = $user->priceUsers()->select('begin_date')->min('begin_date');
            $dt = Carbon::parse($beginDate);

            //早餐
            $user->bookBreakfasts()->create([
                'begin_date' => $beginDate,
                'end_date' => $dt->addDays(random_int(2, 20)),
            ]);

            $max = random_int(2, 5);
            for ($i = 1; $i <= $max; $i++){
                $s = $dt->addDays(random_int(2, 20));
                $e = $s->addDays(random_int(2,30));
                $user->bookBreakfasts()->create([
                    'begin_date' => $dt->addDays(random_int(2, 20)),
                    'end_date' => $dt->addDays(random_int(2, 20)),
                ]);
            }

            $user->bookBreakfasts()->create([
                'begin_date' => $dt->addDays(random_int(2, 20)),
                'end_date' => null,
            ]);

            //午餐

            //晚餐

        }
    }
}
