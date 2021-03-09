<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\ItemOption;
use App\Item;
use Faker\Generator as Faker;

$factory->define(ItemOption::class, function (Faker $faker) {

    $tax_mny = $faker->randomElement([1000, 2000, 3000, 4000, 5000]);
    $vat_mny =  bcmul($tax_mny, 0.1);
    $price = bcadd($tax_mny, $vat_mny);
    $fee_price = bcadd($price, bcmul($price, 0.1));

    return [
        'subject' => '색상, 사이즈',
        'image' => '[]',
        'tax_mny' => $tax_mny,
        'vat_mny' => $vat_mny,
        'fee_price' => $fee_price,
        'price' => $price
    ];
});
