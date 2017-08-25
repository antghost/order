<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'username' => $faker->unique()->userName,
        'name' => $faker->name,
        'nickname' => $faker->lastName.$faker->randomNumber(),
        'active' => '1',
        'email' => $faker->unique()->safeEmail,
        'tag' => $faker->city,
        'telephone' => $faker->numberBetween(66666666,99999999),
        'ip_address' => $faker->ipv4,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});
