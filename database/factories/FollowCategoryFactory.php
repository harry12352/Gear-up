<?php

/**
 * @var \Illuminate\Database\Eloquent\Factory $factory
 */

use App\Models\FollowCategory;
use Faker\Generator as Faker;

$factory->define(
    FollowCategory::class, function (Faker $faker) {
        return [
        'user_id' => $faker->randomDigitNotNull,
        'category_id' => $faker->randomDigitNotNull,
        ];
    }
);
