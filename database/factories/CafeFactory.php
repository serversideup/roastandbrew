<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Cafe::class, function (Faker $faker) {
    return [
      'location_name' => 'Test',
      'address' => $faker->streetAddress,
      'slug' => $faker->slug,
      'city' => $faker->city,
      'state' => $faker->stateAbbr,
      'zip' => $faker->postcode,
      'latitude' => $faker->latitude($min = -90, $max = 90),
      'longitude' => $faker->longitude($min = -180, $max = 180),
      'tea' => 0,
      'matcha' => 0
    ];
});
