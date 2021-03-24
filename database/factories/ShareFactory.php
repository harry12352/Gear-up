<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Share;
use Faker\Generator as Faker;

$factory->define(Share::class, function (Faker $faker) {
    return [
        'user_id' => $faker->randomDigit,
        'product_id' => $faker->randomDigit,
    ];
});
