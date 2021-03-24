<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Page;
use Faker\Generator as Faker;

$factory->define(Page::class, function (Faker $faker) {
    return [
        'name'=>$faker->company,
        'slug'=>$faker->slug,
        'Description'=>$faker->text,
    ];
});
