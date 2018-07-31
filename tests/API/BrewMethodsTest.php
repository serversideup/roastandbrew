<?php

namespace Tests\API;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BrewMethodsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Ensures Brew Methods Can Be Added
     *
     * @return void
     */
    public function testAddBrewMethods()
    {
      $method = factory(\App\Models\BrewMethod::class)->create([
          'method' => 'Hario V60 Dripper',
          'icon' => ''
      ]);

      $this->assertDatabaseHas('brew_methods', [
          'method' => 'Hario V60 Dripper'
      ]);
    }
}
