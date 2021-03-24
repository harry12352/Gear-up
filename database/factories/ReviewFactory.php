<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Review;
use Faker\Generator as Faker;

$factory->define(Review::class, function (Faker $faker) {
    return [
        'user_id' => $faker->randomDigitNotNull,
        'product_id' => $faker->randomDigitNotNull,
        'content' => $faker->paragraph,
        'rating' => $faker->randomNumber(),
    ];
});
