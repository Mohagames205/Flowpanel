<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(Model::class, function (Faker $faker) {
    return [
        "rank_id" => $faker->numberBetween(1, 100000),
        "rank_name" => $faker->name(),
        "perm_id" => 0
    ];
});
