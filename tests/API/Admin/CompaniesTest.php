<?php

namespace Tests\API\Admin;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CompaniesTest extends TestCase
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
        $this->cafeGeneralUser = factory(\App\Models\Cafe::class)->create([
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
     * Ensures that admins can get all companies
     */
    public function testAdminsGetAllCompanies(){
      /*
        Send a request to the API as an admin.
      */
      $response = $this->actingAs( $this->admin, 'api' )
                       ->get('/api/v1/admin/companies');

      /*
        Ensure that 2 companies are returned (all of them);
      */
      $response->assertJsonCount(2);
    }

    /**
     * Ensures that owners can only get companies they own.
     */
    public function testOwnersGetOnlyCompaniesOwned(){
      /*
        Make a whole bunch of companies the user can't see. Should
        only get one in return.
      */
      $companies = factory(\App\Models\Company::class, 5)->create([
        'added_by' => $this->generalUser->id
      ]);

      /*
        Send a request as an owner to get only the company they owned.
      */
      $response = $this->actingAs( $this->owner, 'api' )
                       ->get('/api/v1/admin/companies');

      /*
        Ensure that there is only one company returned.
      */
      $response->assertJsonCount(1);
    }

    /**
     * Ensures that general users get a 403
     */
    public function testUsersGetTurnedDown(){
      /*
        Send a request as a general user. Ensure they get a 403.
      */
      $response = $this->actingAs( $this->generalUser, 'api' )
                       ->get('/api/v1/admin/companies');

      /*
        Ensure the response code is 403
      */
      $response->assertStatus(403);
    }

    /**
     * Ensures an admin can load a company.
     */
    public function testAdminCanLoadCompany(){
      /*
        Send a request as an admin and ensure they get the company
        back.
      */
      $response = $this->actingAs( $this->admin, 'api' )
                       ->get('/api/v1/admin/companies/'.$this->companyNotOwned->id);

      /*
        Ensure the company is returned.
      */
      $response->assertJSON([
        'id' => $this->companyNotOwned->id
      ]);
    }

    /**
     * Ensures an owner can load a company they own.
     */
    public function testOwnerCanLoadCompanyOwned(){
      /*
        Send a request as an owner and ensure they get the company
        back.
      */
      $response = $this->actingAs( $this->admin, 'api' )
                       ->get('/api/v1/admin/companies/'.$this->companyOwned->id);

      /*
        Ensure the company is returned.
      */
      $response->assertJSON([
        'id' => $this->companyOwned->id
      ]);
    }

    /**
     * Ensures an owner can not load the company they do not own.
     */
    public function testOwnerCanNotLoadCompanyNotOwned(){
      /*
        Send a request as an owner who does not own the company and ensure they
        get a 403
      */
      $response = $this->actingAs( $this->owner, 'api' )
                       ->get('/api/v1/admin/companies/'.$this->companyNotOwned->id);

      /*
        Assert the response status is a 403
      */
      $response->assertStatus(403);
    }

    /**
     * Ensures a user gets a 403 when trying to load a company.
     */
    public function testUserCanNotLoadAnyCompanies(){
      /*
        Send a request as a general user. Ensure they get a 403.
      */
      $response = $this->actingAs( $this->generalUser, 'api' )
                       ->get('/api/v1/admin/companies/'.$this->companyNotOwned->id );

      /*
        Ensure the response code is 403
      */
      $response->assertStatus(403);
    }

    /**
     * Ensures invalid data does not get sent to the update
     * Company route
     */
     public function testInvalidDataUpdatingCompany(){
       /*
        Send request with an invalid website and ensure we get a 422.
       */
       $response = $this->actingAs( $this->admin, 'api' )
                        ->json('PUT', '/api/v1/admin/companies/'.$this->companyNotOwned->id, [
                          'website' => 'asdf'
                        ]);

       /*
        Ensure the response is a 422 which is unprocessable with
        an invalid website.
       */
       $response->assertStatus(422);
     }

     /**
      * Ensures that an admin's updates go through
      */
     public function testAdminCanUpdateCompany(){
       /*
        Send request with updated name and website and ensure it's in
        the database correctly.
       */
       $response = $this->actingAs( $this->admin, 'api' )
                        ->json('PUT', '/api/v1/admin/companies/'.$this->companyNotOwned->id, [
                          'website' => 'http://www.google.com',
                          'name' => 'Updated Cafe Name'
                        ]);

       /*
        Ensures the database has the updated information.
       */
       $this->assertDatabaseHas('companies', [
         'id' => $this->companyNotOwned->id,
         'website' => 'http://www.google.com',
         'name' => 'Updated Cafe Name'
       ]);
     }

     /**
      * Ensures that an admin's updates go through and an action is created.
      */
     public function testAdminCanUpdateCompanyAndApprovedActionIsCreated(){
       /*
        Send request with updated name and website and ensure it's in
        the database correctly.
       */
       $response = $this->actingAs( $this->admin, 'api' )
                        ->json('PUT', '/api/v1/admin/companies/'.$this->companyOwned->id, [
                          'website' => 'http://www.apple.com',
                          'name' => 'Updated Owned Cafe Name'
                        ]);

       /*
        Ensures the database has the updated information.
       */
       $this->assertDatabaseHas('companies', [
         'id' => $this->companyOwned->id,
         'website' => 'http://www.apple.com',
         'name' => 'Updated Owned Cafe Name'
       ]);

       /*
        Ensures an approved action has been created.
       */
       $this->assertDatabaseHas('actions', [
         'company_id' => $this->companyOwned->id,
         'status' => 1
       ]);
     }

     /**
      * Ensures that an owner's updates go through
      */
     public function testOwnerCanUpdateCompany(){
       /*
        Send request with updated name and website and ensure it's in
        the database correctly.
       */
       $response = $this->actingAs( $this->owner, 'api' )
                        ->json('PUT', '/api/v1/admin/companies/'.$this->companyOwned->id, [
                          'website' => 'http://www.apple.com',
                          'name' => 'Updated Owned Cafe Name'
                        ]);

       /*
        Ensures the database has the updated information.
       */
       $this->assertDatabaseHas('companies', [
         'id' => $this->companyOwned->id,
         'website' => 'http://www.apple.com',
         'name' => 'Updated Owned Cafe Name'
       ]);
     }

     /**
      * Ensures that an owner's updates go through and an action is created.
      */
     public function testOwnerCanUpdateCompanyAndApprovedActionIsCreated(){
       /*
        Send request with updated name and website and ensure it's in
        the database correctly.
       */
       $response = $this->actingAs( $this->owner, 'api' )
                        ->json('PUT', '/api/v1/admin/companies/'.$this->companyOwned->id, [
                          'website' => 'http://www.apple.com',
                          'name' => 'Updated Owned Cafe Name'
                        ]);

       /*
        Ensures the database has the updated information.
       */
       $this->assertDatabaseHas('companies', [
         'id' => $this->companyOwned->id,
         'website' => 'http://www.apple.com',
         'name' => 'Updated Owned Cafe Name'
       ]);

       /*
        Ensures an approved action has been created.
       */
       $this->assertDatabaseHas('actions', [
         'company_id' => $this->companyOwned->id,
         'status' => 1
       ]);
     }

     /**
      * Ensures an admin can remove the company's owners
      */
     public function testAdminCanRemoveOwners(){
       $response = $this->actingAs( $this->admin, 'api' )
                         ->json('PUT', '/api/v1/admin/companies/'.$this->companyOwned->id, [
                           'owners' => [],
                           'name' => $this->companyOwned->name,
                           'website' => $this->companyOwned->website
                         ]);

       /*
        Ensure the database doesn't have a record for the company owner.
       */
       $this->assertDatabaseMissing('companies_owners', [
         'company_id' => $this->companyOwned->id,
         'user_id' => $this->owner->id
       ]);

       /*
        Ensure the database has the proper user permission.
       */
       $this->assertDatabaseHas('users', [
         'id' => $this->owner->id,
         'permission' => 0
       ]);
     }

     /**
      * Ensures an admin can add the company's owners
      */
     public function testAdminCanAddOwners(){
       /*
        Make an owner to add to the coffee shop.
       */
       $addedOwner = factory(\App\Models\User::class)->create([
         'permission' => 0
       ]);

       /*
          Add the owner to the array.
       */
       $owners = array();

       array_push( $owners, $addedOwner );

       /*
        Send a request to update the owners.
       */
       $response = $this->actingAs( $this->admin, 'api' )
                        ->json('PUT', '/api/v1/admin/companies/'.$this->companyNotOwned->id, [
                          'owners' => $owners,
                          'name' => $this->companyNotOwned->name,
                          'website' => $this->companyNotOwned->website
                        ]);

       /*
        Ensure the database has the owner bound to the company.
       */
       $this->assertDatabaseHas('companies_owners', [
         'company_id' => $this->companyNotOwned->id,
         'user_id' => $addedOwner->id
       ]);

       /*
        Ensure the database has the proper user permission.
       */
       $this->assertDatabaseHas('users', [
         'id' => $addedOwner->id,
         'permission' => 1
       ]);
     }

     /**
      * Ensures owners can not update company's owners
      */
     public function testOwnerCanNotUpdateCompanyOwners(){
       /*
        Make an owner to add to the coffee shop.
       */
       $addedOwner = factory(\App\Models\User::class)->create([
         'permission' => 0
       ]);

       /*
          Add the owner to the array.
       */
       $owners = array();

       array_push( $owners, $addedOwner );

       /*
        Send request with updated name and website and ensure it's in
        the database correctly.
       */
       $response = $this->actingAs( $this->owner, 'api' )
                        ->json('PUT', '/api/v1/admin/companies/'.$this->companyOwned->id, [
                          'website' => 'http://www.apple.com',
                          'name' => 'Updated Owned Cafe Name',
                          'owners' => $owners
                        ]);

        /*
         Ensures the database has the updated information.
        */
        $this->assertDatabaseHas('companies', [
          'id' => $this->companyOwned->id,
          'website' => 'http://www.apple.com',
          'name' => 'Updated Owned Cafe Name'
        ]);

        /*
          Ensures the database doesn't have any owner changes.
        */
        $this->assertDatabaseHas('companies_owners', [
          'user_id' => $this->owner->id,
          'company_id' => $this->companyOwned->id
        ]);

        /*
          Ensures the database doesn't have the new owner.
        */
        $this->assertDatabaseMissing('companies_owners', [
          'user_id' => $addedOwner->id,
          'company_id' => $this->companyOwned->id
        ]);
     }

     /**
      * Ensures an owner can not update a company they don't
      * own.
      */
     public function testOwnerCanNotUpdateCompanyNotOwned(){
       /*
        Send request with updated name and website and ensure that it's
        denied since it's not owned.
       */
       $response = $this->actingAs( $this->owner, 'api' )
                        ->json('PUT', '/api/v1/admin/companies/'.$this->companyNotOwned->id, [
                          'website' => 'http://www.apple.com',
                          'name' => 'Updated Owned Cafe Name'
                        ]);

       /*
        Ensure the response is a 403
       */
       $response->assertStatus( 403 );
     }

     /**
      * Ensures that a user can not update a company
      */
     public function testUserCanNotUpdateCompany(){
       /*
        Send request with updated name and website and ensure it's in
        the database correctly.
       */
       $response = $this->actingAs( $this->generalUser, 'api' )
                        ->json('PUT', '/api/v1/admin/companies/'.$this->companyNotOwned->id, [
                          'website' => 'http://www.apple.com',
                          'name' => 'Updated Owned Cafe Name'
                        ]);

       /*
        Ensure the response is a 403
       */
       $response->assertStatus( 403 );
     }
}
