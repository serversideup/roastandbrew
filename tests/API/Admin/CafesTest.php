<?php

namespace Tests\API\Admin;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CafesTest extends TestCase
{
    use RefreshDatabase;

    protected $generalUser;
    protected $owner;
    protected $admin;

    protected $brewMethod;

    protected $companyNotOwned;
    protected $companyOwned;

    protected $cafeNotOwned;
    protected $cafeOwned;

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
        $this->admin = factory(\App\Models\User::class)->create();

        /*
          Create a brew method to add to the cafe
        */
        $this->brewMethod = factory(\App\Models\BrewMethod::class)->create([
            'method' => 'Hario V60 Dripper',
            'icon' => ''
        ]);

        /*
          Adds a company by a general user.
        */
        $this->companyNotOwned = factory(\App\Models\Company::class)->create([
          'added_by' => $this->generalUser->id
        ]);

        /*
          Adds a cafe to the company by the general user.
        */
        $this->cafeNotOwned = factory(\App\Models\Cafe::class)->create([
          'company_id' => $this->companyNotOwned->id,
          'added_by' => $this->generalUser->id
        ]);

        /*
          Adds a company that will be owned by a user.
        */
        $this->companyOwned = factory(\App\Models\Company::class)->create([
          'added_by' => $this->admin->id
        ]);

        /*
          Adds a cafe owned by a user
        */
        $this->cafeOwned = factory(\App\Models\Cafe::class)->create([
          'company_id' => $this->companyOwned->id,
          'added_by' => $this->admin->id
        ]);

        /*
          Adds the company owned by the user to the user it is owned by.
        */
        $this->owner->companiesOwned()->attach( $this->companyOwned->id );
    }

    /**
     * Ensures an admin can view the cafe
     */
     public function testAdminViewCafe(){
       /*
          Send a request to the API as an admin
       */
       $response = $this->actingAs( $this->admin, 'api' )
                        ->get('/api/v1/admin/companies/'.$this->companyNotOwned->id.'/cafes/'.$this->cafeNotOwned->id );

       /*
          Ensures the cafe was returned
       */
       $response->assertJSON([
         'id' => $this->cafeNotOwned->id
       ]);
     }

     /**
      * Ensures an owner can view the cafe they own.
      */
     public function testOwnerCanViewCafe(){
       /*
          Send a request to the API as an owner
       */
       $response = $this->actingAs( $this->owner, 'api' )
                        ->get('/api/v1/admin/companies/'.$this->companyOwned->id.'/cafes/'.$this->cafeOwned->id );

       /*
          Ensures the cafe was returned
       */
       $response->assertJSON([
         'id' => $this->cafeOwned->id
       ]);
     }

     /**
      * Ensures an owner can not view a cafe they do not own.
      */
     public function testOwnerCanNotViewCafeTheyDontOwn(){
       /*
          Send a request to the API as an owner
       */
       $response = $this->actingAs( $this->owner, 'api' )
                        ->get('/api/v1/admin/companies/'.$this->companyNotOwned->id.'/cafes/'.$this->cafeNotOwned->id );

       /*
          Ensures the cafe was returned
       */
       $response->assertStatus(403);
     }

     /**
      * Ensures a general user gets a 403
      */
     public function testGeneralCantLoadCafe(){
       /*
          Send a request to the API as a general user
       */
       $response = $this->actingAs( $this->generalUser, 'api' )
                        ->get('/api/v1/admin/companies/'.$this->companyNotOwned->id.'/cafes/'.$this->cafeNotOwned->id );

       /*
          Ensures the cafe was returned
       */
       $response->assertStatus(403);
     }

     /**
      * Ensures the admin can update the cafe.
      */
     public function testAdminCanUpdateCafe(){
       /*
        Send request with updated information and ensure it's in the
        database correctly.
       */
       $response = $this->actingAs( $this->admin, 'api')
                        ->json('PUT', '/api/v1/admin/companies/'.$this->companyNotOwned->id.'/cafes/'.$this->cafeNotOwned->id , [
                          'company_id' => $this->companyNotOwned->id,
                          'address' => $this->cafeNotOwned->address,
                          'city' => $this->cafeNotOwned->city,
                          'state' => $this->cafeNotOwned->state,
                          'zip' => $this->cafeNotOwned->zip,
                          'location_name' => 'NEW LOCATION NAME'
                        ]);

       /*
        Confirms the updates are in the database.
       */
       $this->assertDatabaseHas('cafes', [
         'id' => $this->cafeNotOwned->id,
         'location_name' => 'NEW LOCATION NAME'
       ]);
     }

     /**
      * Ensures the owner can update a cafe.
      */
     public function testOwnerCanUpdateCafe(){
       /*
        Send request with updated information and ensure it's in the
        database correctly.
       */
       $response = $this->actingAs( $this->owner, 'api')
                        ->json('PUT', '/api/v1/admin/companies/'.$this->companyOwned->id.'/cafes/'.$this->cafeOwned->id , [
                          'company_id' => $this->companyOwned->id,
                          'address' => $this->cafeOwned->address,
                          'city' => $this->cafeOwned->city,
                          'state' => $this->cafeOwned->state,
                          'zip' => $this->cafeOwned->zip,
                          'location_name' => 'NEW LOCATION NAME'
                        ]);

       /*
        Confirms the updates are in the database.
       */
       $this->assertDatabaseHas('cafes', [
         'id' => $this->cafeOwned->id,
         'location_name' => 'NEW LOCATION NAME'
       ]);
     }

     /**
      * Ensures that a cafe can not be updated by someone who doesn't own it.
      */
     public function testCafeCanNotBeUpdatedByNonOwner(){
       /*
        Send request with updated information and ensure the response is a 403
       */
       $response = $this->actingAs( $this->owner, 'api')
                        ->json('PUT', '/api/v1/admin/companies/'.$this->companyNotOwned->id.'/cafes/'.$this->cafeNotOwned->id , [
                          'company_id' => $this->companyNotOwned->id,
                          'address' => $this->cafeNotOwned->address,
                          'city' => $this->cafeNotOwned->city,
                          'state' => $this->cafeNotOwned->state,
                          'zip' => $this->cafeNotOwned->zip,
                          'location_name' => 'NEW LOCATION NAME'
                        ]);

       /*
        Ensure the user gets a 403 response.
       */
       $response->assertStatus(403);
     }

     /**
      * Ensures that general users get blocked.
      */
     public function testGeneralUsersCanNotUpdateCafes(){
       /*
        Send request with updated information and ensure the response is a 403
       */
       $response = $this->actingAs( $this->generalUser, 'api')
                        ->json('PUT', '/api/v1/admin/companies/'.$this->companyNotOwned->id.'/cafes/'.$this->cafeNotOwned->id , [
                          'company_id' => $this->companyNotOwned->id,
                          'address' => $this->cafeNotOwned->address,
                          'city' => $this->cafeNotOwned->city,
                          'state' => $this->cafeNotOwned->state,
                          'zip' => $this->cafeNotOwned->zip,
                          'location_name' => 'NEW LOCATION NAME'
                        ]);

       /*
        Ensure the user gets a 403 response.
       */
       $response->assertStatus(403);
     }
}
