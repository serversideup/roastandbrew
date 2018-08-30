<?php

namespace Tests\API;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Faker\Generator as Faker;

class CafeTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    protected $generalUser;
    protected $owner;
    protected $admin;

    protected $company;

    protected $generalUserCompany;

    protected $generalUserCafes;

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
      $this->admin = factory(\App\Models\User::class)->create();


      /*
        Creates a company for the test which is
        added by the user.
      */
      $this->generalUserCompany = factory(\App\Models\Company::class)->create([
        'added_by' => $this->generalUser->id
      ]);

      /*
        Creates some cafes for testing.
      */
      $this->generalUserCafes = factory(\App\Models\Cafe::class, 3)->create([
        'company_id' => $this->generalUserCompany->id,
        'added_by' => $this->generalUser->id
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
     * Tests Getting Cafes
     *
     * @return void
     */
    public function testGetCafes(){
      /*
        Get the response from the request.
      */
      $response = $this->actingAs($this->generalUser, 'api')
                        ->get('/api/v1/cafes');

      /*
        Asset that the response has 3 cafes when we get
        all of them.
      */
      $response->assertJsonCount(3);
    }

    /**
     * Tests getting an individual cafe
     *
     * @return void
     */
    public function testGetCafe(){
      /*
        Creates 3 cafes for the test.
      */
      $cafes = factory(\App\Models\Cafe::class, 3)->create([
        'company_id' => $this->generalUserCompany->id,
        'added_by' => $this->generalUser->id
      ]);

      /*
        Get the slug of the first cafe.
      */
      $cafeSlug = $cafes[0]->slug;

      /*
        As the user, grab the new cafe
      */
      $response = $this->actingAs($this->generalUser, 'api')
                       ->get('/api/v1/cafes/'.$cafeSlug);


      /*
        Asser that the cafe has been returned by
        confirming the slug is present in the JSON.
      */
      $response->assertJSON([
                    'slug' => $cafeSlug
                  ]);
    }

    /**
     * Tests getting data for a cafe to edit
     *
     * @return void
     */
    public function testGetCafeEditData(){
      /*
        Get the first cafe's slug
      */
      $cafeSlug = $this->generalUserCafes[0]->slug;

      /*
        Get the response from getting the cafe selected.
      */
      $response = $this->actingAs($this->generalUser, 'api')
                       ->get('/api/v1/cafes/'.$cafeSlug);

      /*
        Confirm the individaul cafe was returned.
      */
      $response->assertJSON([
        'slug' => $cafeSlug,
        'deleted' => 0
      ]);
    }

    /**
     * Test adding a cafe new company
     *
     * @return void
     */
    public function testAddCafeNewCompany(){
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
                          'brew_methods' => json_encode( [$this->brewMethod->id] )
                        ]);

      /*
        Confirm the cafe has been added because it will have been returned.
      */
      $response->assertJSON([
          'name' => 'Ruby',
          'roaster' => '1',
          'website' => 'https://rubycoffeeroasters.com/',
          'instagram_url' => 'https://instagram.com',
          'facebook_url' => 'https://facebook.com',
          'twitter_url' => 'https://twitter.com'
        ]);

      /*
        Confirm that the cafe doesn't have matcha or tea flags.
      */
      $response->assertJSONFragment([
          'matcha' => 0,
          'tea' => 0
        ]);
    }

    /**
     * Test adding a cafe new company that has a subscription
     *
     * @return void
     */
    public function testAddCafeNewCompanyWithSubscription(){
      /*
        Run the request to add the cafe
      */
      $response = $this->actingAs($this->admin, 'api')
                        ->json('POST', '/api/v1/cafes', [
                          'company_name' => 'Ruby',
                          'company_type' => 'roaster',
                          'website'      => 'https://rubycoffeeroasters.com/',
                          'added_by'     => $this->admin->id,
                          'address'      => '9515 Water St',
                          'city'         => 'Amherst Junction',
                          'state'        => 'WI',
                          'zip'          => '54407',
                          'location_name' => 'Tasting Room',
                          'subscription' => 1,
                          'brew_methods' => json_encode( [$this->brewMethod->id] )
                        ]);

      /*
        Confirm the cafe has been added because it will have been returned.
      */
      $response->assertJSON([
          'name' => 'Ruby',
          'roaster' => '1',
          'website' => 'https://rubycoffeeroasters.com/',
          'subscription' => 1
        ]);

      /*
        Confirm that the cafe doesn't have matcha or tea flags.
      */
      $response->assertJSONFragment([
          'matcha' => 0,
          'tea' => 0
        ]);
    }

    /**
     * Test adding a cafe new company and confirming action is added
     * and approved.
     *
     * @return void
     */
    public function testAddCafeNewCompanyConfirmActionApproved(){
      /*
        Run the request to add the cafe
      */
      $response = $this->actingAs($this->admin, 'api')
                        ->json('POST', '/api/v1/cafes', [
                          'company_name' => 'Ruby',
                          'company_type' => 'roaster',
                          'website'      => 'https://rubycoffeeroasters.com/',
                          'added_by'     => $this->admin->id,
                          'address'      => '9515 Water St',
                          'city'         => 'Amherst Junction',
                          'state'        => 'WI',
                          'zip'          => '54407',
                          'location_name' => 'Tasting Room',
                          'subscription' => 0,
                          'brew_methods' => json_encode( [$this->brewMethod->id] )
                        ]);

      /*
        Confirm the cafe has been added because it will have been returned.
      */
      $response->assertJSON([
          'name' => 'Ruby',
          'roaster' => '1',
          'website' => 'https://rubycoffeeroasters.com/'
        ]);

        /*
          Confirm the database has an action for the added cafe.
        */
        $this->assertDatabaseHas('actions', [
          'user_id' => $this->admin->id,
          'type' => 'cafe-added',
          'status' => 1,
          'processed_by' => $this->admin->id
        ]);
    }

    /**
     * Tests adding a cafe as a regular user.
     *
     * @return void
     */
    public function testAddCafeNewCompanyAsRegularUser(){
      /*
        Add the cafe as a user who doesn't have permissions.
      */
      $response = $this->actingAs($this->generalUser, 'api')
                        ->json('POST', '/api/v1/cafes', [
                          'company_name' => 'Ruby',
                          'company_type' => 'roaster',
                          'website'      => 'https://rubycoffeeroasters.com/',
                          'added_by'     => $this->generalUser->id,
                          'address'      => '9515 Water St',
                          'city'         => 'Amherst Junction',
                          'state'        => 'WI',
                          'zip'          => '54407',
                          'location_name' => 'Tasting Room',
                          'subscription' => 0,
                          'brew_methods' => json_encode( [$this->brewMethod->id] )
                        ]);

      /*
        Confirm the response has a pending flag
      */
      $response->assertJSON([
        'cafe_add_pending' => 'Ruby'
      ]);

      /*
        Confirm the database has an action for the added cafe.
      */
      $this->assertDatabaseHas('actions', [
        'user_id' => $this->generalUser->id,
        'type' => 'cafe-added',
        'status' => 0
      ]);
    }

    /**
     * Test adding a cafe with matcha
     *
     * @return void
     */
    public function testAddCafeWithMatcha(){
      /*
        Add the cafe.
      */
      $response = $this->actingAs($this->admin, 'api')
                        ->json('POST', '/api/v1/cafes', [
                          'company_name' => 'Ruby',
                          'company_type' => 'roaster',
                          'website'      => 'https://rubycoffeeroasters.com/',
                          'added_by'     => $this->admin->id,
                          'address'      => '9515 Water St',
                          'city'         => 'Amherst Junction',
                          'state'        => 'WI',
                          'zip'          => '54407',
                          'location_name' => 'Tasting Room',
                          'subscription' => 0,
                          'matcha'       => 1,
                          'brew_methods' => json_encode( [$this->brewMethod->id] )
                        ]);

      /*
        Confirm the response has a flag for matcha
      */
      $response->assertJSONFragment([
        'matcha' => 1
      ]);
    }

    /**
     * Test adding a cafe with matcha and tea
     *
     * @return void
     */
    public function testAddingCafeWithMatchaAndTea(){
      /*
        Run the request to add the cafe.
      */
      $response = $this->actingAs($this->admin, 'api')
                        ->json('POST', '/api/v1/cafes', [
                          'company_name' => 'Ruby',
                          'company_type' => 'roaster',
                          'website'      => 'https://rubycoffeeroasters.com/',
                          'added_by'     => $this->admin->id,
                          'address'      => '9515 Water St',
                          'city'         => 'Amherst Junction',
                          'state'        => 'WI',
                          'zip'          => '54407',
                          'location_name' => 'Tasting Room',
                          'matcha'       => 1,
                          'tea'          => 1,
                          'brew_methods' => json_encode( [$this->brewMethod->id] )
                        ]);

      /*
        Confirm the cafe has tea and matcha
      */
      $response->assertJSONFragment([
        'matcha' => 1,
        'tea' => 1
      ]);
    }

    /**
     * Test adding a cafe with tea
     *
     * @return void
     */
    public function testAddingCafeWithTea(){
      /*
        Add a cafe with a tea flag.
      */
      $response = $this->actingAs($this->admin, 'api')
                        ->json('POST', '/api/v1/cafes', [
                          'company_name' => 'Ruby',
                          'company_type' => 'roaster',
                          'website'      => 'https://rubycoffeeroasters.com/',
                          'added_by'     => $this->admin->id,
                          'address'      => '9515 Water St',
                          'city'         => 'Amherst Junction',
                          'state'        => 'WI',
                          'zip'          => '54407',
                          'location_name' => 'Tasting Room',
                          'tea'           => 1,
                          'brew_methods'  => json_encode( [$this->brewMethod->id] ),
                          'subscription'  => 0
                        ]);

      /*
        Confirm the tea flag is present in the response.
      */
      $response->assertJSONFragment([
        'tea' => 1
      ]);
    }

    /**
     * Test adding a cafe existing company
     *
     * @return void
     */
    public function testAddCafeExistingCompany(){
      /*
        Run the request to add the cafe.
      */
      $response = $this->actingAs($this->admin, 'api')
                        ->json('POST', '/api/v1/cafes', [
                          'company_id'   => $this->generalUserCompany->id,
                          'added_by'     => $this->admin->id,
                          'address'      => '9515 Water St',
                          'city'         => 'Amherst Junction',
                          'state'        => 'WI',
                          'zip'          => '54407',
                          'location_name' => 'Tasting Room',
                          'subscription' => 0,
                          'brew_methods' => json_encode( [$this->brewMethod->id] )
                        ]);

      /*
        Confirm the cafe has been added to an existing company
      */
      $response->assertJSON([
        'name' => $this->generalUserCompany->name,
        'website' => $this->generalUserCompany->website
      ]);
    }

    /**
     * Test adding a cafe company owner
     *
     * @return void
     */
    public function testAddCafeExistingCompanyOwner(){
      /*
         Bind the company to the user's owned company
      */
      $this->generalUser->companiesOwned()->attach( $this->generalUserCompany->id );

      /*
        Run the request to add the cafe.
      */
      $response = $this->actingAs($this->generalUser, 'api')
                        ->json('POST', '/api/v1/cafes', [
                          'company_id'   => $this->generalUserCompany->id,
                          'added_by'     => $this->generalUser->id,
                          'address'      => '9515 Water St',
                          'city'         => 'Amherst Junction',
                          'state'        => 'WI',
                          'zip'          => '54407',
                          'location_name' => 'Tasting Room',
                          'subscription' => 0,
                          'brew_methods' => json_encode( [$this->brewMethod->id] )
                        ]);

      /*
        Confirm the cafe has been added to an existing company
      */
      $response->assertJSON([
        'name' => $this->generalUserCompany->name,
        'website' => $this->generalUserCompany->website
      ]);
    }

    /**
     * Test editing a cafe
     *
     * @return void
     */
    public function testEditCafe(){
      /*
        Grab the slug from the cafe.
      */
      $cafeSlug = $this->generalUserCafes[0]->slug;

      /*
        Run the request to edit the cafe
      */
      $response = $this->actingAs($this->admin, 'api')
                        ->json('PUT', '/api/v1/cafes/'.$cafeSlug, [
                          'company_id'   => $this->generalUserCompany->id,
                          'company_name' => 'EDITED name',
                          'instagram_url' => 'https://instagram.com',
                          'facebook_url' => 'https://facebook.com',
                          'twitter_url' => 'https://twitter.com',
                          'added_by'     => $this->admin->id,
                          'address'      => 'EDITED 9515 Water St',
                          'city'         => 'EDITED Amherst Junction',
                          'state'        => 'WI',
                          'zip'          => '54407',
                          'location_name' => 'EDITED Tasting Room',
                          'subscription' => 0,
                          'brew_methods' => json_encode( [$this->brewMethod->id] )
                        ]);

      /*
        Confirm that the cafe has the edited name.
      */
      $response->assertJSON([
                  'name' => 'EDITED name',
                  'instagram_url' => 'https://instagram.com',
                  'facebook_url' => 'https://facebook.com',
                  'twitter_url' => 'https://twitter.com'
                ]);
    }

    /**
     * Test editing a cafe and adding a subscription
     *
     * @return void
     */
    public function testEditCafeAddSubscriptionToCompany(){
      /*
        Grab the slug from the cafe.
      */
      $cafeSlug = $this->generalUserCafes[0]->slug;

      /*
        Run the request to edit the cafe
      */
      $response = $this->actingAs($this->admin, 'api')
                        ->json('PUT', '/api/v1/cafes/'.$cafeSlug, [
                          'company_id'   => $this->generalUserCompany->id,
                          'company_name' => 'EDITED name',
                          'added_by'     => $this->admin->id,
                          'address'      => 'EDITED 9515 Water St',
                          'city'         => 'EDITED Amherst Junction',
                          'state'        => 'WI',
                          'zip'          => '54407',
                          'location_name' => 'EDITED Tasting Room',
                          'subscription' => 1,
                          'brew_methods' => json_encode( [$this->brewMethod->id] )
                        ]);

      /*
        Confirm that the cafe has the edited name.
      */
      $response->assertJSON([
                  'name' => 'EDITED name',
                  'subscription' => 1
                ]);
    }

    /**
     * Test editing a cafe and action approved
     *
     * @return void
     */
    public function testEditCafeAndActionApproved(){
      /*
        Grab the slug from the cafe.
      */
      $cafeSlug = $this->generalUserCafes[0]->slug;

      /*
        Run the request to edit the cafe
      */
      $response = $this->actingAs($this->admin, 'api')
                        ->json('PUT', '/api/v1/cafes/'.$cafeSlug, [
                          'company_id'   => $this->generalUserCompany->id,
                          'company_name' => 'EDITED name',
                          'added_by'     => $this->admin->id,
                          'address'      => 'EDITED 9515 Water St',
                          'city'         => 'EDITED Amherst Junction',
                          'state'        => 'WI',
                          'zip'          => '54407',
                          'location_name' => 'EDITED Tasting Room',
                          'subscription' => 0,
                          'brew_methods' => json_encode( [$this->brewMethod->id] )
                        ]);

      /*
        Confirm the database has an action for the added cafe.
      */
      $this->assertDatabaseHas('actions', [
        'user_id' => $this->admin->id,
        'type' => 'cafe-updated',
        'status' => 1,
        'processed_by' => $this->admin->id
      ]);
    }

    /**
     * Test changing a cafe to have matcha
     *
     * @return void
     */
     public function testChangingCafeToHaveMatcha(){
       /*
          Grab the slug from the cafe.
       */
       $cafeSlug = $this->generalUserCafes[0]->slug;

       /*
          Confirm the database has the cafe with no matcha flag.
       */
       $this->assertDatabaseHas('cafes', [
         'slug' => $cafeSlug,
         'matcha' => 0
       ]);

       /*
          Run the request to add the matcha flag.
       */
       $response = $this->actingAs($this->admin, 'api')
                         ->json('PUT', '/api/v1/cafes/'.$cafeSlug, [
                           'matcha' => 1,
                           'company_name' => 'EDITED name',
                           'website'      => 'https://rubycoffeeroasters.com/',
                           'added_by'     => $this->admin->id,
                           'address'      => 'EDITED 9515 Water St',
                           'city'         => 'EDITED Amherst Junction',
                           'state'        => 'WI',
                           'zip'          => '54407',
                           'subscription' => 0,
                           'location_name' => 'EDITED Tasting Room',
                         ]);

        /*
          Confirm the cafe now has matcha
        */
        $response->assertJSONFragment([
          'matcha' => 1
        ]);
     }

     /**
      * Test changing a cafe to have tea
      *
      * @return void
      */
      public function testChangingCafeToHaveTea(){
        /*
           Grab the slug from the cafe.
        */
        $cafeSlug = $this->generalUserCafes[0]->slug;

        /*
          Confirm the database has the cafe with no tea flag.
        */
        $this->assertDatabaseHas('cafes', [
          'slug' => $cafeSlug,
          'tea' => 0
        ]);

        /*
          Run the request to add a tea flag.
        */
        $response = $this->actingAs($this->admin, 'api')
                          ->json('PUT', '/api/v1/cafes/'.$cafeSlug, [
                            'tea' => 1,
                            'company_name' => 'EDITED name',
                            'website'      => 'https://rubycoffeeroasters.com/',
                            'added_by'     => $this->admin->id,
                            'address'      => 'EDITED 9515 Water St',
                            'city'         => 'EDITED Amherst Junction',
                            'state'        => 'WI',
                            'zip'          => '54407',
                            'subscription' => 0,
                            'location_name' => 'EDITED Tasting Room',
                          ]);

        /*
          Confirm the cafe has a tea flag as active.
        */
        $response->assertJSONFragment([
          'tea' => 1
        ]);
    }

    /**
     * Test the liking of a cafe.
     *
     * @return void
     */
    public function testLikeCafe(){
      /*
        Run the request to like a cafe.
      */
      $response = $this->actingAs($this->generalUser, 'api')
                        ->json('POST', '/api/v1/cafes/'.$this->generalUserCafes[0]->slug.'/like');

      /*
        Confirm the cafe has been liked
      */
      $response->assertJSON([
        'cafe_liked' => 'true',
      ]);

      /*
        Confirms the database has a record of the like.
      */
      $this->assertDatabaseHas('users_cafes_likes', [
                'user_id' => $this->generalUser->id,
                'cafe_id' => $this->generalUserCafes[0]->id
            ]);
    }

    /**
     * Test delete like cafe
     *
     * @return void
     */
    public function testDeleteLikeCafe(){
      /*
        Run the request to like a cafe.
      */
      $response = $this->actingAs($this->generalUser, 'api')
                        ->json('POST', '/api/v1/cafes/'.$this->generalUserCafes[0]->slug.'/like');

      /*
        Confirm the response has a liked cafe.
      */
      $response->assertJSON([
        'cafe_liked' => 'true',
      ]);

      /*
        Confirms the database has a record of the like.
      */
      $this->assertDatabaseHas('users_cafes_likes', [
                'user_id' => $this->generalUser->id,
                'cafe_id' => $this->generalUserCafes[0]->id
            ]);

      /*
        Run the request to unlike the cafe
      */
      $this->actingAs($this->generalUser, 'api')
            ->json('DELETE', '/api/v1/cafes/'.$this->generalUserCafes[0]->slug.'/like');

      /*
        Confirms the database has no record of the like.
      */
      $this->assertDatabaseMissing('users_cafes_likes', [
                'user_id' => $this->generalUser->id,
                'cafe_id' => $this->generalUserCafes[0]->id
            ]);

    }

    /**
     * Test delete a cafe
     *
     * @return void
     */
    public function testDeleteCafe(){
      /*
        Sends a request to delete a cafe.
      */
      $this->actingAs( $this->admin, 'api' )
           ->json('DELETE', '/api/v1/cafes/'.$this->generalUserCafes[0]->slug);

      /*
        Confirms the database has a soft delete of the cafe.
      */
      $this->assertDatabaseHas('cafes', [
               'id' => $this->generalUserCafes[0]->id,
               'deleted' => 1
           ]);

    }

    /**
     * Test delete a cafe and action added
     *
     * @return void
     */
    public function testDeleteCafeAndActionAdded(){
      /*
        Sends a request to delete a cafe.
      */
      $this->actingAs( $this->admin, 'api' )
           ->json('DELETE', '/api/v1/cafes/'.$this->generalUserCafes[0]->slug);

       /*
         Confirm the database has an action for the added cafe.
       */
       $this->assertDatabaseHas('actions', [
         'user_id' => $this->admin->id,
         'type' => 'cafe-deleted',
         'status' => 1,
         'processed_by' => $this->admin->id
       ]);

    }

    /**
     * Tests cafe owner can update cafe
     *
     * @return void
     */
     public function testCafeOwnerCanUpdateCafe(){
       /*
          Bind the company to the user's owned company
       */
       $this->generalUser->companiesOwned()->attach( $this->generalUserCompany->id );

       /*
         Grab the slug from the cafe.
       */
       $cafeSlug = $this->generalUserCafes[0]->slug;

       /*
         Run the request to edit the cafe
       */
       $response = $this->actingAs($this->generalUser, 'api')
                         ->json('PUT', '/api/v1/cafes/'.$cafeSlug, [
                           'company_id'   => $this->generalUserCompany->id,
                           'company_name' => 'EDITED name',
                           'added_by'     => $this->generalUser->id,
                           'address'      => 'EDITED 9515 Water St',
                           'city'         => 'EDITED Amherst Junction',
                           'state'        => 'WI',
                           'zip'          => '54407',
                           'location_name' => 'EDITED Tasting Room',
                           'subscription' => 0,
                           'brew_methods' => json_encode( [$this->brewMethod->id] )
                         ]);

       /*
         Confirm that the cafe has the edited name.
       */
       $response->assertJSON([
                   'name' => 'EDITED name',
                 ]);
     }

    /**
     * Tests cafe-edited action created when user doesn't have
     * permission to edit the cafe.
     *
     * @return void
     */
     public function testUserEditCafeActionCreated(){
       /*
         Grab the slug from the cafe.
       */
       $cafeSlug = $this->generalUserCafes[0]->slug;

       /*
         Run the request to edit the cafe
       */
       $response = $this->actingAs($this->generalUser, 'api')
                         ->json('PUT', '/api/v1/cafes/'.$cafeSlug, [
                           'company_id'   => $this->generalUserCompany->id,
                           'company_name' => 'EDITED name',
                           'added_by'     => $this->generalUser->id,
                           'address'      => 'EDITED 9515 Water St',
                           'city'         => 'EDITED Amherst Junction',
                           'state'        => 'WI',
                           'zip'          => '54407',
                           'location_name' => 'EDITED Tasting Room',
                           'subscription' => 0,
                           'brew_methods' => json_encode( [$this->brewMethod->id] )
                         ]);

        /*
          Confirm that the response states the update is
          pending
        */
        $response->assertJSON([
          'cafe_updates_pending' => 'EDITED name',
        ]);

        /*
          Confirms that the action exists in the database
        */
        $this->assertDatabaseHas('actions', [
          'cafe_id' => $this->generalUserCafes[0]->id,
          'type' => 'cafe-updated'
        ]);

        /*
          Confirms the cafe has not been edited
        */
        $this->assertDatabaseMissing('companies', [
          'id' => $this->generalUserCafes[0]->id,
          'name' => 'EDITED cafe'
        ]);
     }

     /**
      * Tests cafe owner can delete cafe
      *
      * @return void
      */
      public function testCafeOwnerCanDeleteCafe(){
        /*
           Bind the company to the user's owned company
        */
        $this->generalUser->companiesOwned()->attach( $this->generalUserCompany->id );

        /*
          Grab the slug from the cafe.
        */
        $cafeSlug = $this->generalUserCafes[0]->slug;

        /*
          Run the request to delete the cafe
        */
        $response = $this->actingAs($this->generalUser, 'api')
                          ->json('DELETE', '/api/v1/cafes/'.$cafeSlug);

        /*
          Confirms the database has a soft delete of the cafe.
        */
        $this->assertDatabaseHas('cafes', [
                 'id' => $this->generalUserCafes[0]->id,
                 'deleted' => 1
             ]);
      }

     /**
      * Tests cafe-deleted action created when user doesn't have
      * permission to delete the cafe.
      *
      * @return void
      */
      public function testUserDeleteCafeActionCreated(){
        /*
          Grab the slug from the cafe.
        */
        $cafeSlug = $this->generalUserCafes[0]->slug;

        /*
          Run the request to delete the cafe
        */
        $response = $this->actingAs($this->generalUser, 'api')
                          ->json('DELETE', '/api/v1/cafes/'.$cafeSlug);

         /*
           Confirm that the response states the deletion is
           pending
         */
         $response->assertJSON([
           'cafe_delete_pending' => $this->generalUserCafes[0]->company->name,
         ]);

         /*
           Confirms that the action exists in the database
         */
         $this->assertDatabaseHas('actions', [
           'cafe_id' => $this->generalUserCafes[0]->id,
           'type' => 'cafe-deleted'
         ]);

         /*
           Confirms the cafe has not been deleted
         */
         $this->assertDatabaseMissing('cafes', [
           'id' => $this->generalUserCafes[0]->id,
           'deleted' => 1
         ]);
      }
}
