<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\SellerInfo;
use Faker\Generator as Faker;

$factory->define(SellerInfo::class, function (Faker $faker) {
    $faker = \Faker\Factory::create('ko_KR');
    return [
        'company_name' => $faker->company,
        'cp_type' => $faker->text(20),
        'cp_sectors' => $faker->text(20),
        'cp_number' => $faker->text(20),
        'cp_file' => '[]',
        'ceo_name' => $faker->name,
        'email' => $faker->email,
        'tel' => $faker->phoneNumber,
        'fax_num' => $faker->phoneNumber,
        'post_num' => $faker->postcode,
        'address' => $faker->address,
        'extra_addr' => '',
        'addr_jibeon' => '',
        'image' => '[]',
        'profile_img' => '[]',
        'intro' => $faker->realText($maxNbChars = 200, $indexSize = 5)
    ];
});
