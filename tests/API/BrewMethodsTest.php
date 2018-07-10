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

    /*
    public function testBrewMethodCount(){

      $user = factory(\App\Models\User::class)->create();

      $method = factory(\App\Models\BrewMethod::class)->create([
          'method' => 'Hario V60 Dripper',
          'icon' => ''
      ]);

      $method2 = factory(\App\Models\BrewMethod::class)->create([
          'method' => 'Chemex',
          'icon' => ''
      ]);

      $cafes = factory(\App\Models\Cafe::class, 10)
                  ->create();

      foreach( $cafes as $cafe ){
        $cafe->sync( $method );
      }

      $this->actingAs($user, 'api')
           ->get('/api/v1/brew-methods');
    }
    */

}
