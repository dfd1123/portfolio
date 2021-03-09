<?php

use Faker\Generator as Faker;

$factory->define(TLCfund\Category::class, function (Faker $faker) {
    return [
        'ca_name' => $faker->name,
        'ca_discript' => $faker->text,
        'ca_icon' => str_random(10).".png",
    ];
});
