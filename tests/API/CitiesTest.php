<?php

namespace Tests\API;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Faker\Generator as Faker;

class CitiesTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    protected $admin;
    protected $city;

    protected $brewMethod;

    /**
     * Sets up our testing scenario by building some
     * entities.
     */
    public function setUp(){
      parent::setUp();

      /*
        Creates an admin for testing
      */
      $this->admin = factory(\App\Models\User::class)->create();

      /*
        Creates a city for testing.
      */
      $this->city = factory(\App\Models\City::class)->create([
        'name'      => 'Milwaukee',
        'state'     => 'WI',
        'country'   => 'US',
        'latitude'  => 43.03890250,
        'longitude' => -87.90647360,
        'radius'    => 25.00
      ]);

      /*
        Creates a brew method for testing
      */
      $this->brewMethod = factory(\App\Models\BrewMethod::class)->create([
          'method' => 'Hario V60 Dripper',
          'icon' => ''
      ]);
    }

    /**
     * Ensures a cafe is added to a city.
     *
     * @return void
     */
     function testCafeIsAddedToACity(){
       /*
         Run the request to add the cafe
       */
       $response = $this->actingAs($this->admin, 'api')
                         ->json('POST', '/api/v1/cafes', [
                           'company_name' => 'Collectivo',
                           'company_type' => 'roaster',
                           'website'      => 'https://collectivocoffee.com/',
                           'instagram_url' => 'https://instagram.com',
                           'facebook_url'  => 'https://facebook.com',
                           'twitter_url'   => 'https://twitter.com',
                           'added_by'     => $this->admin->id,
                           'address'      => '2301 S. Kinnickinnic Ave.',
                           'city'         => 'Milwaukee',
                           'state'        => 'WI',
                           'zip'          => '53207',
                           'location_name' => 'Bayview',
                           'latitude'     => 43.00273540,
                           'longitude'    => -87.90443130,
                           'subscription' => 0,
                           'brew_methods' => json_encode( [$this->brewMethod->id] )
                         ]);

       /*
         Confirm that the cafe doesn't have matcha or tea flags.
       */
       $response->assertJSONFragment([
           'city_id' => $this->city->id
         ]);
     }

    /**
     * Ensures a cafe is not added to a city because too far.
     *
     * @return void
     */
     function testCafeIsNotAddedToACity(){
       /*
         Run the request to add the cafe
       */
       $response = $this->actingAs($this->admin, 'api')
                         ->json('POST', '/api/v1/cafes', [
                           'company_name' => 'Ruby',
                           'company_type' => 'roaster',
                           'website'      => 'https://rubycoffeeroasters.com/',
                           'instagram_url' => 'https://instagram.com',
                           'facebook_url'  => 'https://facebook.com',
                           'twitter_url'   => 'https://twitter.com',
                           'added_by'     => $this->admin->id,
                           'address'      => '9515 Water St',
                           'city'         => 'Amherst Junction',
                           'state'        => 'WI',
                           'zip'          => '54407',
                           'location_name' => 'Tasting Room',
                           'subscription' => 0,
                           'latitude'     => 44.49388680,
                           'longitude'    => -89.30944590,
                           'brew_methods' => json_encode( [$this->brewMethod->id] )
                         ]);

       /*
         Confirm that the cafe doesn't have matcha or tea flags.
       */
       $response->assertJSONFragment([
           'city_id' => null
         ]);
     }

}
