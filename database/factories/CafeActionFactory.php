<?php

use Faker\Generator as Faker;

$factory->define(App\Models\CafeAction::class, function (Faker $faker) {
    return [
      'status' => 0,
      'processed_by' => null,
      'processed_on' => null,
      'content' => 0
    ];
});
