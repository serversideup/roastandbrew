<?php

namespace Tests\API\Admin;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BrewMethodsTest extends TestCase
{
    use RefreshDatabase;

    protected $generalUser;
    protected $owner;
    protected $admin;
    protected $superAdmin;

    protected $brewMethods;

    /**
     * Sets up our testing scenario by building some entities
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
          Creates a super admin for testing.
        */
        $this->superAdmin = factory(\App\Models\User::class)->create();

        /*
          Create some brew methods
        */
        $this->brewMethods = factory(\App\Models\BrewMethod::class, 10)->create([
            'icon' => ''
        ]);
      }

    /**
     * Ensures a super admin can get brew methods
     */
     public function testSuperAdminCanGetBrewMethods(){
       /*
         Send a request to the API as a super admin
       */
       $response = $this->actingAs( $this->superAdmin, 'api' )
                        ->get('/api/v1/admin/brew-methods');

       /*
         Ensure that there are 10 returned
       */
       $response->assertJSONCount(10);
     }

    /**
     * Ensures an admin can not get brew methods.
     */
    public function testAdminCanNotGetBrewMethods(){
      /*
        Send a request to the API as an admin
      */
      $response = $this->actingAs( $this->admin, 'api' )
                       ->get('/api/v1/admin/brew-methods');

      /*
        Ensure that the status is 403
      */
      $response->assertStatus(403);
    }

    /**
     * Ensures an owner can not get brew methods.
     */
    public function testOwnerCanNotGetBrewMethods(){
      /*
        Send a request to the API as an owner
      */
      $response = $this->actingAs( $this->owner, 'api' )
                       ->get('/api/v1/admin/brew-methods');

      /*
        Ensure that the status is 403
      */
      $response->assertStatus(403);
    }

    /**
     * Ensures a general user can not get brew methods.
     */
    public function testGeneralUserCanNotGetBrewMethods(){
      /*
        Send a request to the API as a general user
      */
      $response = $this->actingAs( $this->generalUser, 'api' )
                       ->get('/api/v1/admin/brew-methods');

      /*
        Ensure that the status is 403
      */
      $response->assertStatus(403);
    }

    /**
     * Ensures a super admin can add a brew method.
     */
    public function testSuperAdminCanAddBrewMethods(){
      /*
        Send an add brew method request as an admin.
      */
      $response = $this->actingAs( $this->superAdmin, 'api' )
                       ->json( 'POST', '/api/v1/admin/brew-methods', [
                         'method' => 'New Brew Method',
                         'icon' => '/brew/method/icon.svg'
                       ]);

      /*
        Confirm the brew method is in the database.
      */
      $this->assertDatabaseHas('brew_methods', [
        'method' => 'New Brew Method',
        'icon' => '/brew/method/icon.svg'
      ]);
    }

    /**
     * Ensures a super admin can update a brew method.
     */
    public function testSuperAdminCanUpdateBrewMethod(){
      /*
        Send an add brew method request as an admin.
      */
      $response = $this->actingAs( $this->superAdmin, 'api' )
                       ->json( 'PUT', '/api/v1/admin/brew-methods/'.$this->brewMethods[0]->id, [
                         'method' => 'UPDATED Brew Method',
                         'icon' => '/updated-brew/method/icon.svg'
                       ]);

      /*
        Confirm the brew method is in the database.
      */
      $this->assertDatabaseHas('brew_methods', [
        'method' => 'UPDATED Brew Method',
        'icon' => '/updated-brew/method/icon.svg'
      ]);
    }

    /**
     * Ensures a super admin can retrieve a brew method.
     */
    public function testSuperAdminCanRetrieveBrewMethod(){
      /*
        Send a request to the API as a super admin
      */
      $response = $this->actingAs( $this->superAdmin, 'api' )
                       ->get('/api/v1/admin/brew-methods/'.$this->brewMethods[0]->id);

      /*
        Ensure the proper brew method was returned.
      */
      $response->assertJSON([
        'id' => $this->brewMethods[0]->id
      ]);
    }
}
