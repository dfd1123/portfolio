<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\User;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    $faker = \Faker\Factory::create('ko_KR');
    return [
        //'register_kind' => (int) $faker->boolean,
        'register_kind' => $faker->randomElement([1, 2]),
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'name' => $faker->name,
        'password' => bcrypt('ehdrmf!@34'), // password
        'profile_img' => '[]',
        'nickname' => $faker->unique()->name,
        'mobile_number' => $faker->cellPhoneNumber,
        'gender' => $faker->randomElement(['woman', 'man']),
        'birthday' => $faker->date($format = 'Y-m-d', $max = 'now'),
        'level' => $faker->numberBetween($min=0, $max=4),
        'post_num' => $faker->postcode,
        'address' => $faker->address,
        'extra_addr'=>'',
        'addr_jibeon' => '',
        'ad_agree' => (int) $faker->boolean
        //'remember_token' => Str::random(10),
    ];
});
