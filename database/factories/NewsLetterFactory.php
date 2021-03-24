<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\NewsLetter;
use Faker\Generator as Faker;

$factory->define(NewsLetter::class, function (Faker $faker) {
    return [
        'email' => $faker->unique()->safeEmail,
    ];
});
