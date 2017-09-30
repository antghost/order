<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\User;

class UserOrderStatusesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('user_order_statuses')->truncate();
        DB::table('book_breakfasts')->truncate();
        DB::table('book_lunches')->truncate();
        DB::table('book_dinners')->truncate();
        DB::table('cancel_breakfasts')->truncate();
        DB::table('cancel_lunches')->truncate();
        DB::table('cancel_dinners')->truncate();
        $users = User::all();
        foreach ($users as $user){
            $beginDate = $user->priceUsers()->select('begin_date')->min('begin_date');
            //用餐状态
            $userOrder = $user->userOrderStatuses()->create([
                'breakfast' => 1,
                'lunch' => random_int(0, 1),
                'dinner' => random_int(0, 1),
            ]);
            //早餐
            if ($userOrder->breakfast){
                $user->bookBreakfasts()->create([
                    'begin_date' => $beginDate,
                ]);
            } else {
                $user->cancelBreakfasts()->create([
                    'begin_date' => $beginDate,
                ]);
            }
            //午餐
            if ($userOrder->lunch){
                $user->bookLunches()->create([
                    'begin_date' => $beginDate,
                ]);
            } else {
                $user->cancelLunches()->create([
                    'begin_date' => $beginDate,
                ]);
            }
            //晚餐
            if ($userOrder->dinner){
                $user->bookDinners()->create([
                    'begin_date' => $beginDate,
                ]);
            } else {
                $user->CancelDinners()->create([
                    'begin_date' => $beginDate,
                ]);
            }
        }
    }
}
