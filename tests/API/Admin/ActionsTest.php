<?php

namespace Tests\API\Admin;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ActionsTest extends TestCase
{
    use RefreshDatabase;

    protected $generalUser;
    protected $owner;
    protected $admin;

    protected $brewMethod;

    protected $companyGeneralUser;
    protected $companyOwnedByUser;

    protected $cafeGeneralUser;
    protected $cafeOwnedByUser;
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
        $this->companyGeneralUser = factory(\App\Models\Company::class)->create([
          'added_by' => $this->generalUser->id
        ]);

        /*
          Adds a cafe to the company by the general user.
        */
        $this->cafeGeneralUser = factory(\App\Models\Cafe::class)->create([
          'company_id' => $this->companyGeneralUser->id,
          'added_by' => $this->generalUser->id
        ]);

        /*
          Adds a company that will be owned by a user.
        */
        $this->companyOwnedByUser = factory(\App\Models\Company::class)->create([
          'added_by' => $this->admin->id
        ]);

        /*
          Adds a cafe owned by a user
        */
        $this->cafeOwnedByUser = factory(\App\Models\Cafe::class)->create([
          'company_id' => $this->companyOwnedByUser->id,
          'added_by' => $this->admin->id
        ]);

        /*
          Adds the company owned by the user to the user it is owned by.
        */
        $this->owner->companiesOwned()->attach( $this->companyOwnedByUser->id );
    }

    /**
     * If the user is an admin, grab all actions that haven't been
     * processed
     *
     * @return void
     */
    public function testRetrieveActionsAsAdmin(){
      /*
        Create 5 actions so we can return all of them
      */
      $actions = factory(\App\Models\CafeAction::class, 5)->create([
        'user_id' => $this->generalUser->id,
        'cafe_id' => $this->cafeGeneralUser->id,
        'company_id' => $this->companyGeneralUser->id,
        'type' => 'cafe-added'
      ]);

      /*
        Get the response from the request.
      */
      $response = $this->actingAs( $this->admin, 'api' )
                       ->get('/api/v1/admin/actions');

      /*
        Assert that all 5 actions were returned since we are an admin.
      */
      $response->assertJsonCount(5);
    }

    /**
     * If the user is an owner, grab only the actions on cafes owned
     * by the user
     *
     * @return void
     */
    public function testRetrieveActionsAsOwner(){
      /*
        Create 3 actions so we can return all of them that are on
        the company owned by the user.
      */
      $actions = factory(\App\Models\CafeAction::class, 3)->create([
        'user_id' => $this->owner->id,
        'cafe_id' => $this->cafeOwnedByUser->id,
        'company_id' => $this->companyOwnedByUser->id,
        'type' => 'cafe-updated'
      ]);

      /*
        Create 2 actions that we don't return because they are
        not owned by the user.
      */
      $actions = factory(\App\Models\CafeAction::class, 3)->create([
        'user_id' => $this->owner->id,
        'cafe_id' => $this->cafeGeneralUser->id,
        'company_id' => $this->companyGeneralUser->id,
        'type' => 'cafe-updated'
      ]);

      /*
        Get the response from the request.
      */
      $response = $this->actingAs( $this->owner, 'api' )
                       ->get('/api/v1/admin/actions');

      /*
        Assert that all 5 actions were returned since we are an admin.
      */
      $response->assertJsonCount(3);
    }

    /**
     * If a user is a general user, they should receive a 403
     * if they access this admin route due to our middleware.
     *
     * @return void
     */
    public function testRetrieveActionsAsGeneralUser(){
      /*
        Create 1 cafe to test
      */
      $cafe = factory(\App\Models\Cafe::class)->create([
        'company_id' => $this->companyGeneralUser->id,
        'added_by' => $this->generalUser->id
      ]);

      /*
        Create 5 actions so we can return all of them
      */
      $actions = factory(\App\Models\CafeAction::class, 5)->create([
        'user_id' => $this->generalUser->id,
        'cafe_id' => $this->cafeGeneralUser->id,
        'company_id' => $this->companyGeneralUser->id,
        'type' => 'cafe-added'
      ]);

      /*
        Get the response from the request.
      */
      $response = $this->actingAs( $this->generalUser, 'api' )
                       ->get('/api/v1/admin/actions');

      /*
        Assert that the status was 403 and the user was denied.
      */
      $response->assertStatus(403);
    }

    /**
     * Testing approval of an added cafe by an admin
     *
     * @return void
     */
    public function testApproveCafeAddedActionAsAdmin(){
      /*
        A new cafe to be added and confirm it's in the db.
      */
      $newCafe = array();

      $newCafe['company_name']      = 'Test';
      $newCafe['company_id']        = null;
      $newCafe['company_type']      = 'roaster';
      $newCafe['website']           = 'http://www.google.com';
      $newCafe['location_name']     = 'Brew House';
      $newCafe['address']           = '325 E Peltason Dr';
      $newCafe['city']              = 'Irvine';
      $newCafe['state']             = 'CA';
      $newCafe['zip']               = '92697';
      $newCafe['lat']               = '33.6426511';
      $newCafe['lng']               = '-117.84149450000001';
      $newCafe['brew_methods']      = json_encode([$this->brewMethod->id]);
      $newCafe['matcha']            = 0;
      $newCafe['tea']               = 0;

      /*
        Create 1 actions to approve
      */
      $action = factory(\App\Models\CafeAction::class)->create([
        'user_id' => $this->generalUser->id,
        'type' => 'cafe-added',
        'content' => json_encode( $newCafe )
      ]);

      /*
        Get the response from the request.
      */
      $response = $this->actingAs( $this->admin, 'api' )
                       ->json('PUT', '/api/v1/admin/actions/'.$action->id.'/approve', []);

      /*
        Confirm the action was approved.
      */
      $this->assertDatabaseHas('cafes_actions', [
        'id' => $action->id,
        'type' => 'cafe-added',
        'status' => 1,
        'processed_by' => $this->admin->id
      ]);

      /*
        Confirm a new cafe is in the database
      */
      $this->assertDatabaseHas('cafes', [
        'location_name' => $newCafe['location_name'],
        'added_by' => $this->generalUser->id,
        'address' => $newCafe['address']
      ]);
    }

    /**
     * Testing approval of an edited cafe by an admin
     *
     * @return void
     */
    public function testApproveCafeEditedActionAsAdmin(){
      /*
        Create an udpated cafe array with information.
      */
      $updatedCafe = array();

      $updatedCafe['location_name'] = 'Updated Location';
      $updatedCafe['address']       = 'UPDATED ADDRESS';
      $updatedCafe['company_id']    = $this->cafeGeneralUser->company_id;

      $content['before'] = $this->cafeGeneralUser;
      $content['after'] = $updatedCafe;

      /*
        Create 1 actions to approve
      */
      $action = factory(\App\Models\CafeAction::class)->create([
        'user_id' => $this->generalUser->id,
        'type' => 'cafe-updated',
        'cafe_id' => $this->cafeGeneralUser->id,
        'company_id' => $this->companyGeneralUser->id,
        'content' => json_encode( $content )
      ]);

      /*
        Get the response from the request.
      */
      $response = $this->actingAs( $this->admin, 'api' )
                       ->json('PUT', '/api/v1/admin/actions/'.$action->id.'/approve', []);

       /*
         Confirm the action was approved.
       */
       $this->assertDatabaseHas('cafes_actions', [
         'id' => $action->id,
         'type' => 'cafe-updated',
         'status' => 1,
         'processed_by' => $this->admin->id
       ]);

       /*
         Confirm a cafe has been updated
       */
       $this->assertDatabaseHas('cafes', [
         'location_name' => $updatedCafe['location_name'],
         'address' => $updatedCafe['address']
       ]);
    }

    /**
     * Testing approval of a deleted cafe by an admin
     *
     * @return void
     */
    public function testApproveCafeDeletedActionAsAdmin(){
      /*
        Create 1 actions to approve
      */
      $action = factory(\App\Models\CafeAction::class)->create([
        'user_id' => $this->generalUser->id,
        'type' => 'cafe-deleted',
        'cafe_id' => $this->cafeGeneralUser->id,
        'company_id' => $this->companyGeneralUser->id
      ]);

      /*
        Get the response from the request.
      */
      $response = $this->actingAs( $this->admin, 'api' )
                       ->json('PUT', '/api/v1/admin/actions/'.$action->id.'/approve', []);

       /*
         Confirm the action was approved.
       */
       $this->assertDatabaseHas('cafes_actions', [
         'id' => $action->id,
         'type' => 'cafe-deleted',
         'status' => 1,
         'processed_by' => $this->admin->id
       ]);

       /*
         Confirm a cafe has been updated
       */
       $this->assertDatabaseHas('cafes', [
         'id' => $this->cafeGeneralUser->id,
         'deleted' => 1
       ]);
    }

    /**
     * Testing approval of an added cafe by an the owner
     *
     * @return void
     */
    public function testApproveCafeAddedActionAsValidOwner(){
      /*
        A new cafe to be added and confirm it's in the db.
      */
      $newCafe = array();

      $newCafe['company_name']      = 'Test';
      $newCafe['company_id']        = $this->companyOwnedByUser->id;
      $newCafe['company_type']      = 'roaster';
      $newCafe['website']           = 'http://www.google.com';
      $newCafe['location_name']     = 'Brew House';
      $newCafe['address']           = '325 E Peltason Dr';
      $newCafe['city']              = 'Irvine';
      $newCafe['state']             = 'CA';
      $newCafe['zip']               = '92697';
      $newCafe['lat']               = '33.6426511';
      $newCafe['lng']               = '-117.84149450000001';
      $newCafe['brew_methods']      = json_encode( [$this->brewMethod->id] );
      $newCafe['matcha']            = 0;
      $newCafe['tea']               = 0;

      /*
        Create 1 actions to approve
      */
      $action = factory(\App\Models\CafeAction::class)->create([
        'user_id' => $this->generalUser->id,
        'type' => 'cafe-added',
        'cafe_id' => $this->cafeOwnedByUser->id,
        'company_id' => $this->companyOwnedByUser->id,
        'content' => json_encode( $newCafe )
      ]);

      /*
        Get the response from the request.
      */
      $response = $this->actingAs( $this->owner, 'api' )
                       ->json('PUT', '/api/v1/admin/actions/'.$action->id.'/approve', []);

      /*
        Confirm the action was approved.
      */
      $this->assertDatabaseHas('cafes_actions', [
        'id' => $action->id,
        'type' => 'cafe-added',
        'status' => 1,
        'processed_by' => $this->owner->id
      ]);

      /*
        Confirm a new cafe is in the database
      */
      $this->assertDatabaseHas('cafes', [
        'location_name' => $newCafe['location_name'],
        'added_by' => $this->generalUser->id,
        'address' => $newCafe['address']
      ]);
    }

    /**
     * Testing approval of an edited cafe by an the owner
     *
     * @return void
     */
    public function testApproveCafeEditedActionAsValidOwner(){
      /*
        Create an udpated cafe array with information.
      */
      $updatedCafe = array();

      $updatedCafe['location_name'] = 'Updated Location';
      $updatedCafe['address']       = 'UPDATED ADDRESS';
      $updatedCafe['company_id']    = $this->cafeOwnedByUser->company_id;

      $content['before'] = $this->cafeGeneralUser;
      $content['after'] = $updatedCafe;

      /*
        Create 1 actions to approve
      */
      $action = factory(\App\Models\CafeAction::class)->create([
        'user_id' => $this->generalUser->id,
        'type' => 'cafe-updated',
        'cafe_id' => $this->cafeOwnedByUser->id,
        'company_id' => $this->cafeOwnedByUser->company_id,
        'content' => json_encode( $content )
      ]);

      /*
        Get the response from the request.
      */
      $response = $this->actingAs( $this->owner, 'api' )
                       ->json('PUT', '/api/v1/admin/actions/'.$action->id.'/approve', []);

       /*
         Confirm the action was approved.
       */
       $this->assertDatabaseHas('cafes_actions', [
         'id' => $action->id,
         'type' => 'cafe-updated',
         'status' => 1,
         'processed_by' => $this->owner->id
       ]);

       /*
         Confirm a cafe has been updated
       */
       $this->assertDatabaseHas('cafes', [
         'location_name' => $updatedCafe['location_name'],
         'address' => $updatedCafe['address']
       ]);
    }

    /**
     * Test approval of deleted cafe by valid owner
     *
     * @return void
     */
    public function testApproveCafeDeletedActionAsValidOwner(){
      /*
        Create 1 actions to approve
      */
      $action = factory(\App\Models\CafeAction::class)->create([
        'user_id' => $this->generalUser->id,
        'type' => 'cafe-deleted',
        'cafe_id' => $this->cafeOwnedByUser->id,
        'company_id' => $this->companyOwnedByUser->id
      ]);

      /*
        Get the response from the request.
      */
      $response = $this->actingAs( $this->owner, 'api' )
                       ->json('PUT', '/api/v1/admin/actions/'.$action->id.'/approve', []);

      /*
        Confirm the action was approved.
      */
      $this->assertDatabaseHas('cafes_actions', [
        'id' => $action->id,
        'type' => 'cafe-deleted',
        'status' => 1,
        'processed_by' => $this->owner->id
      ]);

      /*
        Confirm a cafe has been updated
      */
      $this->assertDatabaseHas('cafes', [
        'id' => $this->cafeOwnedByUser->id,
        'deleted' => 1
      ]);
    }

    /**
     * Should be a 403 response code since the owner is invalid.
     *
     * @return void
     */
    public function testApproveActionAsInvalidOwner(){
      /*
        Create 1 actions to approve
      */
      $action = factory(\App\Models\CafeAction::class)->create([
        'user_id' => $this->generalUser->id,
        'type' => 'cafe-deleted',
        'cafe_id' => $this->cafeGeneralUser->id,
        'company_id' => $this->companyGeneralUser->id
      ]);

      /*
        Get the response from the request.
      */
      $response = $this->actingAs( $this->owner, 'api' )
                       ->json('PUT', '/api/v1/admin/actions/'.$action->id.'/approve', []);

      /*
        Confirm the owner can not approve actions that they don't own the company for.
      */
      $response->assertStatus(403);
    }

    /**
     * Should be a 403 response code since the user is invalid.
     *
     * @return void
     */
    public function testApproveActionAsGeneralUser(){
      /*
        Create 1 actions to approve
      */
      $action = factory(\App\Models\CafeAction::class)->create([
        'user_id' => $this->generalUser->id,
        'type' => 'cafe-deleted',
        'cafe_id' => $this->cafeGeneralUser->id,
        'company_id' => $this->companyGeneralUser->id
      ]);

      /*
        Get the response from the request.
      */
      $response = $this->actingAs( $this->generalUser, 'api' )
                       ->json('PUT', '/api/v1/admin/actions/'.$action->id.'/approve', []);

      /*
        Confirm the general user can not approve actions.
      */
      $response->assertStatus(403);
    }

    /**
     * Ensures that an admin can deny any action
     *
     * @return void
     */
    public function testDenyActionAsAdmin(){
      /*
        Create 1 actions to deny
      */
      $action = factory(\App\Models\CafeAction::class)->create([
        'user_id' => $this->generalUser->id,
        'type' => 'cafe-deleted',
        'cafe_id' => $this->cafeGeneralUser->id,
        'company_id' => $this->companyGeneralUser->id
      ]);

      /*
        Get the response from the request.
      */
      $response = $this->actingAs( $this->admin, 'api' )
                       ->json('PUT', '/api/v1/admin/actions/'.$action->id.'/deny', []);

      /*
        Confirm the action was approved.
      */
      $this->assertDatabaseHas('cafes_actions', [
        'id' => $action->id,
        'type' => 'cafe-deleted',
        'status' => 2,
        'processed_by' => $this->admin->id
      ]);
    }

    /**
     * Ensures a valid owner can deny an action on the shop they own.
     *
     * @return void
     */
    public function testDenyActionAsValidOwner(){
      /*
        Create 1 actions to deny
      */
      $action = factory(\App\Models\CafeAction::class)->create([
        'user_id' => $this->generalUser->id,
        'type' => 'cafe-deleted',
        'cafe_id' => $this->cafeOwnedByUser->id,
        'company_id' => $this->companyOwnedByUser->id
      ]);

      /*
        Get the response from the request.
      */
      $response = $this->actingAs( $this->owner, 'api' )
                       ->json('PUT', '/api/v1/admin/actions/'.$action->id.'/deny', []);

      /*
        Confirm the action was approved.
      */
      $this->assertDatabaseHas('cafes_actions', [
        'id' => $action->id,
        'type' => 'cafe-deleted',
        'status' => 2,
        'processed_by' => $this->owner->id
      ]);
    }

    /**
     * Ensures an invalid owner can not deny actions
     *
     * @return void
     */
    public function testDenyActionAsInvalidOwner(){
      /*
        Create 1 actions to deny
      */
      $action = factory(\App\Models\CafeAction::class)->create([
        'user_id' => $this->generalUser->id,
        'type' => 'cafe-deleted',
        'cafe_id' => $this->cafeGeneralUser->id,
        'company_id' => $this->companyGeneralUser->id
      ]);

      /*
        Get the response from the request.
      */
      $response = $this->actingAs( $this->owner, 'api' )
                       ->json('PUT', '/api/v1/admin/actions/'.$action->id.'/deny', []);

      /*
        Confirm the action was approved.
      */
      $response->assertStatus(403);
    }

    /**
     * Ensures a general user cannot deny actions
     *
     * @return void
     */
    public function testDenyActionAsGeneralUser(){
      /*
        Create 1 actions to deny
      */
      $action = factory(\App\Models\CafeAction::class)->create([
        'user_id' => $this->generalUser->id,
        'type' => 'cafe-deleted',
        'cafe_id' => $this->cafeGeneralUser->id,
        'company_id' => $this->companyGeneralUser->id
      ]);

      /*
        Get the response from the request.
      */
      $response = $this->actingAs( $this->generalUser, 'api' )
                       ->json('PUT', '/api/v1/admin/actions/'.$action->id.'/deny', []);

      /*
        Confirm the action was approved.
      */
      $response->assertStatus(403);
    }
}
