<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Offer;
use Faker\Generator as Faker;

$factory->define(Offer::class, function (Faker $faker) {
    return [
        'user_id' => $faker->randomDigitNotNull,
        'product_id' => $faker->randomDigitNotNull,
        'offered_price'=>$faker->randomNumber(),
        'status'=>$faker->text
    ];
});
