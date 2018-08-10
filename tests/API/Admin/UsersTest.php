<?php

namespace Tests\API\Admin;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UsersTest extends TestCase
{
    use RefreshDatabase;

    protected $generalUser;
    protected $owner;
    protected $admin;
    protected $superAdmin;

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
        $this->admin = factory(\App\Models\User::class)->create([
          'permission' => 2
        ]);

        /*
          Creates a super admin for testing
        */
        $this->superAdmin = factory(\App\Models\User::class)->create([
          'permission' => 3
        ]);

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
     * Ensure that admins can retrieve users
     */
     public function testAdminsCanRetrieveUsers(){
       /*
         Send a request to the API as an admin.
       */
       $response = $this->actingAs( $this->admin, 'api' )
                        ->get('/api/v1/admin/users');

       /*
         Ensure that 3 users are returned (all of them);
       */
       $response->assertJsonCount(4);
     }

    /**
     * Ensure that owners can not retrieve users
     */
     public function testOwnersCanNotRetrieveUsers(){
       /*
         Send a request to the API as an owner.
       */
       $response = $this->actingAs( $this->owner, 'api' )
                        ->get('/api/v1/admin/users');

       /*
         Ensure that the response is a 403
       */
       $response->assertStatus(403);
     }

    /**
     * Ensure that general users can not retrieve users.
     */
     public function testUsersCanNotRetrieveUsers(){
       /*
         Send a request to the API as a general user.
       */
       $response = $this->actingAs( $this->generalUser, 'api' )
                        ->get('/api/v1/admin/users');

       /*
         Ensure that the response is a 403
       */
       $response->assertStatus(403);
     }

     /**
      * Ensures the admin can update permissions.
      */
      public function testAdminCanUpdatePermissions(){
        /*
          Send a request to the API as an admin
        */
        $response = $this->actingAs( $this->admin, 'api' )
                         ->json('PUT', '/api/v1/admin/users/'.$this->generalUser->id, [
                           'permission' => 2
                         ]);

        /*
          Ensure the response is 200
        */
        $response->assertStatus(200);

        /*
          Ensure the database is updated.
        */
        $this->assertDatabaseHas('users', [
          'id' => $this->generalUser->id,
          'permission' => 2
        ]);
      }

     /**
      * Ensures the admin can promote to admin.
      */
      public function testAdminCanPromoteToAdmin(){
        /*
          Send a request to the API as an admin
        */
        $response = $this->actingAs( $this->admin, 'api' )
                         ->json('PUT', '/api/v1/admin/users/'.$this->generalUser->id, [
                           'permission' => 2
                         ]);

        /*
          Ensure the response is 200
        */
        $response->assertStatus(200);

        /*
          Ensure the database is updated.
        */
        $this->assertDatabaseHas('users', [
          'id' => $this->generalUser->id,
          'permission' => 2
        ]);
      }

     /**
      * Ensures the admin can NOT promote to super admin.
      */
      public function testAdminCanNotPromoteToSuperAdmin(){
        /*
          Send a request to the API as an admin
        */
        $response = $this->actingAs( $this->admin, 'api' )
                         ->json('PUT', '/api/v1/admin/users/'.$this->generalUser->id, [
                           'permission' => 3
                         ]);

        /*
          Ensure the response is 200
        */
        $response->assertStatus(200);

        /*
          Ensure the database is updated.
        */
        $this->assertDatabaseHas('users', [
          'id' => $this->generalUser->id,
          'permission' => 0
        ]);
      }

     /**
      * Ensures the super admin can update permissions.
      */
      public function testSuperAdminCanUpdatePermissions(){
        /*
          Send a request to the API as a super admin
        */
        $response = $this->actingAs( $this->superAdmin, 'api' )
                         ->json('PUT', '/api/v1/admin/users/'.$this->generalUser->id, [
                           'permission' => 2
                         ]);

        /*
          Ensure the response is 200
        */
        $response->assertStatus(200);

        /*
          Ensure the database is updated.
        */
        $this->assertDatabaseHas('users', [
          'id' => $this->generalUser->id,
          'permission' => 2
        ]);
      }

     /**
      * Ensures the super admin can promote to admin.
      */
      public function testSuperAdminCanPromoteToAdmin(){
        /*
          Send a request to the API as a super admin
        */
        $response = $this->actingAs( $this->superAdmin, 'api' )
                         ->json('PUT', '/api/v1/admin/users/'.$this->generalUser->id, [
                           'permission' => 2
                         ]);

        /*
          Ensure the response is 200
        */
        $response->assertStatus(200);

        /*
          Ensure the database is updated.
        */
        $this->assertDatabaseHas('users', [
          'id' => $this->generalUser->id,
          'permission' => 2
        ]);
      }

     /**
      * Ensures the super admin can promote to super admin.
      */
      public function testSuperAdminCanPromoteToSuperAdmin(){
        /*
          Send a request to the API as a super admin
        */
        $response = $this->actingAs( $this->superAdmin, 'api' )
                         ->json('PUT', '/api/v1/admin/users/'.$this->generalUser->id, [
                           'permission' => 3
                         ]);

        /*
          Ensure the response is 200
        */
        $response->assertStatus(200);

        /*
          Ensure the database is updated.
        */
        $this->assertDatabaseHas('users', [
          'id' => $this->generalUser->id,
          'permission' => 3
        ]);
      }

     /**
      * Ensures the owner can not update permissions.
      */
      public function testOwnerCanNotUpdatePermissions(){
        /*
          Send a request to the API as an owner
        */
        $response = $this->actingAs( $this->owner, 'api' )
                         ->json('PUT', '/api/v1/admin/users/'.$this->generalUser->id, [
                           'permission' => 3
                         ]);

        /*
          Ensure the response is 403
        */
        $response->assertStatus(403);
      }

     /**
      * Ensures the user can not update permissions.
      */
      public function testUserCanNotUpdatePermissions(){
        /*
          Send a request to the API as a general user
        */
        $response = $this->actingAs( $this->generalUser, 'api' )
                         ->json('PUT', '/api/v1/admin/users/'.$this->generalUser->id, [
                           'permission' => 3
                         ]);

        /*
          Ensure the response is 403
        */
        $response->assertStatus(403);
      }

    /**
     * Ensures that companies can be added that are owned by the user.
     */
     public function testAddingCompanyToUser(){
       /*
        Build companies array to add.
       */
       $companies = array();
       array_push( $companies, $this->companyNotOwned );

       /*
          Send a request to the API as an admin.
       */
       $response = $this->actingAs( $this->admin, 'api' )
                        ->json('PUT', '/api/v1/admin/users/'.$this->generalUser->id, [
                          'companies' => $companies
                        ]);
       /*
          Ensure general user is updated to a 1 owner level.
       */
       $this->assertDatabaseHas('users', [
         'id' => $this->generalUser->id,
         'permission' => 1
       ]);

       /*
          Ensure the general user now owns the company.
       */
       $this->assertDatabaseHas('companies_owners', [
         'user_id' => $this->generalUser->id,
         'company_id' => $this->companyNotOwned->id
       ]);
     }
}
