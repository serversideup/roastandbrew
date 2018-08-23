<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Company::class, function (Faker $faker) {
    return [
      'name' => $faker->name,
      'roaster' => 1,
      'subscription' => 0,
      'website' => $faker->url,
      'logo'   => '',
      'description' => ''
    ];
});
