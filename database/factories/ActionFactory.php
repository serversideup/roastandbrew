<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Action::class, function (Faker $faker) {
    return [
      'status' => 0,
      'processed_by' => null,
      'processed_on' => null,
      'content' => 0
    ];
});
