<?php

use Faker\Generator as Faker;

$factory->define(App\Models\User::class, function (Faker $faker) {
  return [
      'permission' => 3,
      'name' => $faker->name,
      'email' => $faker->unique()->safeEmail,
      'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
      'remember_token' => str_random(10),
      'provider' => 'google',
      'provider_id' => $faker->numberBetween(1000, 9000),
      'avatar' => $faker->url,
      'favorite_coffee' => '',
      'flavor_notes' => '',
      'city' => $faker->city,
      'state' => $faker->stateAbbr
  ];
});
