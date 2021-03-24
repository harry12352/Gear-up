<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\File;
use Faker\Generator as Faker;

$factory->define(File::class, function (Faker $faker) {
    return [
        'resource_id' => $faker->randomDigitNotNull,
        'resource_name' => $faker->name,
        'path' => $faker->image()
    ];
});
