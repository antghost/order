<?php

use Illuminate\Database\Seeder;
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
        }
    }
}
