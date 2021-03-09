<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Item;
use App\Category;
use App\User;
use App\SellerInfo;
use Faker\Generator as Faker;

$factory->define(Item::class, function (Faker $faker) {
    $faker = \Faker\Factory::create('ko_KR');
    $category = Category::all()->random(1)->first();
    $sellerInfo = SellerInfo::all()->random(1)->first();
    $tax_mny = $faker->numberBetween($min=100, $max=100000);
    $cust_price = bcmul($tax_mny, 2);
    $vat_mny =  bcmul($tax_mny, 0.1);
    $price = bcadd($tax_mny, $vat_mny);
    $fee_price = bcadd($price, bcmul($price, 0.1));

    return [
        'ca_id' => $category->id,
        'ca_name' => $category->ca_name,
        'seller_id' => $sellerInfo->uid,
        'seller_name' => $faker->name,
        'store_id' => $sellerInfo->store_id,
        'company_name' => $sellerInfo->brandname,
        'company_profile_img' => '[]',
        'title' => $faker->title,
        'images' => '[]',
        'simple_intro' => $faker->realText($maxNbChars = 100, $indexSize = 5),
        'introduce_pc' => $faker->realText($maxNbChars = 500, $indexSize = 5),
        'introduce_mobile' => $faker->realText($maxNbChars = 500, $indexSize = 5),
        'make_company' => $faker->company,
        'orgin_range' => $faker->country,
        'brand' =>  $faker->company,
        'min_size' => $faker->numberBetween($min=10, $max=40),
        'max_size' => $faker->numberBetween($min=44, $max=115),
        'color' => '빨강,파랑,노랑',
        'gender' => $faker->randomElement(['woman', 'man']),
        'age' => '10,20,30,40,50',
        'cust_price' => $cust_price,
        'tax_mny' => $tax_mny,
        'vat_mny' => $vat_mny,
        'notax' => 0,
        'price' => $price,
        'fee_price' => $fee_price,
        'buy_min_qty' => 1,
        'buy_max_qty' => 1000,
        'stock_qty' => 99999,
        'noti_qty' => 10,
        'sell_yn' => (int) $faker->boolean,
        'self_type' => 0,
        'option_subject' => '색상,사이즈'
    ];
});
