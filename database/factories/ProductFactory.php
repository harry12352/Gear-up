<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Product;
use Illuminate\Support\Arr;
use Faker\Generator as Faker;

$factory->define(Product::class, function (Faker $faker) {
    return [
        'brand_id' => $faker->randomDigit,
        'user_id' => $faker->randomDigit,
        'size_id' => $faker->randomDigit,
        'title' => $faker->text,
        'slug' => $faker->slug,
        'price' => $faker->randomDigitNotNull,
        'sale_price'=>$faker->randomDigitNotNull,
        'status' => Arr::random(['published','drafted']),
        'description' => $faker->paragraph
    ];
});
