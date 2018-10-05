<?php

namespace Tests\API\Admin;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Faker\Generator as Faker;

class CitiesTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    protected $generalUser;
    protected $owner;
    protected $admin;
    protected $superAdmin;

    protected $city;
    protected $brewMethod;

    /**
     * Sets up our testing scenario by building some
     * entities.
     */
    public function setUp(){
      parent::setUp();

      /*
        Creates a general user for testing.
      */
      $this->generalUser = factory(\App\Models\User::class)->create([
        'permission' => 0
      ]);

      /*
        Creates a coffee shop owner for testing
      */
      $this->owner = factory(\App\Models\User::class)->create([
        'permission' => 1
      ]);

      /*
        Creates an admin for testing
      */
      $this->admin = factory(\App\Models\User::class)->create([
        'permission' => 2
      ]);

      /*
        Creates an admin for testing
      */
      $this->superAdmin = factory(\App\Models\User::class)->create([
        'permission' => 3
      ]);

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
     * Ensures all cities are returned
     *
     * @return void
     */
    function testCitiesReturned(){
      /*
        Send a request to the API as a super admin.
      */
      $response = $this->actingAs( $this->superAdmin, 'api' )
                       ->get('/api/v1/admin/cities');

      /*
        Ensure that there is a city returned
      */
      $response->assertJSONCount(1);
    }

    /**
     * Ensures a city can be added by a super admin
     *
     * @return void
     */
     function testCityCanBeAddedBySuperAdmin(){
       /*
        Send a request to the API as a super admin.
       */
       $response = $this->actingAs( $this->superAdmin, 'api' )
                        ->json( 'POST', '/api/v1/admin/cities', [
                            'name' => 'Los Angeles',
                            'state' => 'CA',
                            'country' => 'US',
                            'latitude' => 34.05223420,
                            'longitude' => -118.24368490,
                            'radius' => 45.00
                        ]);

        $response->assertJSONFragment([
          'name' => 'Los Angeles'
        ]);
     }

    /**
     * Ensures a city can not be added by an admin.
     *
     * @return void
     */
     function testCityCanNotBeAddedByAnAdmin(){
       /*
        Send a request to the API as an admin
       */
       $response = $this->actingAs( $this->admin, 'api' )
                        ->json( 'POST', '/api/v1/admin/cities', [
                            'name' => 'Los Angeles',
                            'state' => 'CA',
                            'country' => 'US',
                            'latitude' => 34.05223420,
                            'longitude' => -118.24368490,
                            'radius' => 45.00
                        ]);

        /*
          Ensure that the status is 403
        */
        $response->assertStatus(403);
     }

    /**
     * Ensures a city can not be added by an owner
     *
     * @return void
     */
     function testCityCanNotBeAddedByAnOwner(){
       /*
        Send a request to the API as an owner
       */
       $response = $this->actingAs( $this->owner, 'api' )
                        ->json( 'POST', '/api/v1/admin/cities', [
                            'name' => 'Los Angeles',
                            'state' => 'CA',
                            'country' => 'US',
                            'latitude' => 34.05223420,
                            'longitude' => -118.24368490,
                            'radius' => 45.00
                        ]);

        /*
          Ensure that the status is 403
        */
        $response->assertStatus(403);
     }

    /**
     * Ensures a city can not be added by a user
     *
     * @return void
     */
     function testCityCanNotBeAddedByAUser(){
       /*
        Send a request to the API as a user
       */
       $response = $this->actingAs( $this->generalUser, 'api' )
                        ->json( 'POST', '/api/v1/admin/cities', [
                            'name' => 'Los Angeles',
                            'state' => 'CA',
                            'country' => 'US',
                            'latitude' => 34.05223420,
                            'longitude' => -118.24368490,
                            'radius' => 45.00
                        ]);

        /*
          Ensure that the status is 403
        */
        $response->assertStatus(403);
     }

    /**
     * Ensures a city can be edited by a super admin
     *
     * @return void
     */
     function testCityCanBeEditedByASuperAdmin(){
       /*
         Send an add brew method request as an admin.
       */
       $response = $this->actingAs( $this->superAdmin, 'api' )
                        ->json( 'PUT', '/api/v1/admin/cities/'.$this->city->id, [
                          'name' => 'UPDATED City Name',
                          'state' => 'CA',
                          'country' => 'US',
                          'latitude' => 34.05223420,
                          'longitude' => -118.24368490,
                          'radius' => 45.00
                        ]);

       /*
        Confirms the updates in the database.
       */
       $this->assertDatabaseHas( 'cities', [
         'name'   => 'UPDATED City Name',
         'id'     => $this->city->id
       ]);
     }

    /**
     * Ensures a city can be deleted by a super admin
     *
     * @return void
     */
     function testCityCanBeDeletedByASuperAdmin(){
       /*
         Send an add brew method request as an admin.
       */
       $response = $this->actingAs( $this->superAdmin, 'api' )
                        ->json( 'DELETE', '/api/v1/admin/cities/'.$this->city->id );

        $this->assertDatabaseMissing( 'cities', [
          'id'  => $this->city->id
        ]);
     }

    /**
     * Ensures a city gets closest cafes
     *
     * @return void
     */
     function testCityGetsClosestCafes(){
       /*
         Run the request to add the cafe
       */
       $response = $this->actingAs($this->superAdmin, 'api')
                         ->json('POST', '/api/v1/cafes', [
                           'company_name' => 'LA Mill',
                           'company_type' => 'roaster',
                           'website'      => 'https://lamillcoffee.com/',
                           'instagram_url' => 'https://instagram.com',
                           'facebook_url'  => 'https://facebook.com',
                           'twitter_url'   => 'https://twitter.com',
                           'added_by'     => $this->superAdmin->id,
                           'address'      => '1636 Silver Lake Blvd',
                           'city'         => 'Los Angeles',
                           'state'        => 'CA',
                           'zip'          => '90026',
                           'location_name' => 'Shop',
                           'latitude'     => 34.08882610,
                           'longitude'    => -118.26860750,
                           'subscription' => 0,
                           'brew_methods' => json_encode( [$this->brewMethod->id] )
                         ]);
       /*
        Send a request to the API as a super admin.
       */
       $cityResponse = $this->actingAs( $this->superAdmin, 'api' )
                        ->json( 'POST', '/api/v1/admin/cities', [
                            'name' => 'Los Angeles',
                            'state' => 'CA',
                            'country' => 'US',
                            'latitude' => 34.05223420,
                            'longitude' => -118.24368490,
                            'radius' => 45.00
                        ]);

        $newCity = json_decode( $cityResponse->getContent() );
        /*
          Ensure the city is added to the cafes in the area.
        */
        $this->assertDatabaseHas( 'cafes', [
          'city_id'  => $newCity->id
        ]);
    }
}
