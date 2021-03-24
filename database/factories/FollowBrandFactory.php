<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\FollowBrand;
use Faker\Generator as Faker;

$factory->define(FollowBrand::class, function (Faker $faker) {
    return [
        'user_id' => $faker->randomDigitNotNull,
        'brand_id' => $faker->randomDigitNotNull
    ];
});
