<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//         $this->call(UserSeeder::class);
//        $this->call(PriceUserSeeder::class);
//        $this->call(UserOrderStatusesSeeder::class);
        $this->call(BookMealSeeder::class);
    }
}
