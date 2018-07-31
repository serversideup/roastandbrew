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

    /**
     * Tests Getting Cafes
     *
     * @return void
     */
    public function testGetCafes(){
      /*
        Creates a user for the test
      */
      $user = factory(\App\Models\User::class)->create();

      /*
        Creates a company for the test which is
        added by the user.
      */
      $company = factory(\App\Models\Company::class)->create([
        'added_by' => $user->id
      ]);

      /*
        Create 3 cafes so we can return all of them.
      */
      $cafes = factory(\App\Models\Cafe::class, 3)->create([
        'company_id' => $company->id,
        'added_by' => $user->id
      ]);

      /*
        Get the response from the request.
      */
      $response = $this->actingAs($user, 'api')
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
        Create a user for the test.
      */
      $user = factory(\App\Models\User::class)->create();

      /*
        Create a company for the test.
      */
      $company = factory(\App\Models\Company::class)->create([
        'added_by' => $user->id
      ]);

      /*
        Creates 3 cafes for the test.
      */
      $cafes = factory(\App\Models\Cafe::class, 3)->create([
        'company_id' => $company->id,
        'added_by' => $user->id
      ]);

      /*
        Get the slug of the first cafe.
      */
      $cafeSlug = $cafes[0]->slug;

      /*
        As the user, grab the new cafe
      */
      $response = $this->actingAs($user, 'api')
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
        Create a user to run the test
      */
      $user = factory(\App\Models\User::class)->create();

      /*
        Create a company to run the test.
      */
      $company = factory(\App\Models\Company::class)->create([
        'added_by' => $user->id
      ]);

      /*
        Create 3 cafes to run the test.
      */
      $cafes = factory(\App\Models\Cafe::class, 3)->create([
        'company_id' => $company->id,
        'added_by' => $user->id
      ]);

      /*
        Get the first cafe's slug
      */
      $cafeSlug = $cafes[0]->slug;

      /*
        Get the response from getting the cafe selected.
      */
      $response = $this->actingAs($user, 'api')
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
        Create a user to run the test.
      */
      $user = factory(\App\Models\User::class)->create();

      /*
        Create a brew method to add to the cafe.
      */
      $method = factory(\App\Models\BrewMethod::class)->create([
          'method' => 'Hario V60 Dripper',
          'icon' => ''
      ]);

      /*
        Run the request to add the cafe
      */
      $response = $this->actingAs($user, 'api')
                        ->json('POST', '/api/v1/cafes', [
                          'company_name' => 'Ruby',
                          'company_type' => 'roaster',
                          'website'      => 'https://rubycoffeeroasters.com/',
                          'added_by'     => $user->id,
                          'address'      => '9515 Water St',
                          'city'         => 'Amherst Junction',
                          'state'        => 'WI',
                          'zip'          => '54407',
                          'location_name' => 'Tasting Room',
                          'brew_methods' => json_encode( [$method->id] )
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
        Create a user to run the test.
      */
      $user = factory(\App\Models\User::class)->create();

      /*
        Create a brew method to add to the cafe.
      */
      $method = factory(\App\Models\BrewMethod::class)->create([
          'method' => 'Hario V60 Dripper',
          'icon' => ''
      ]);

      /*
        Run the request to add the cafe
      */
      $response = $this->actingAs($user, 'api')
                        ->json('POST', '/api/v1/cafes', [
                          'company_name' => 'Ruby',
                          'company_type' => 'roaster',
                          'website'      => 'https://rubycoffeeroasters.com/',
                          'added_by'     => $user->id,
                          'address'      => '9515 Water St',
                          'city'         => 'Amherst Junction',
                          'state'        => 'WI',
                          'zip'          => '54407',
                          'location_name' => 'Tasting Room',
                          'brew_methods' => json_encode( [$method->id] )
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
        $this->assertDatabaseHas('cafes_actions', [
          'user_id' => $user->id,
          'type' => 'cafe-added',
          'status' => 1,
          'processed_by' => $user->id
        ]);
    }

    /**
     * Tests adding a cafe as a regular user.
     *
     * @return void
     */
    public function testAddCafeNewCompanyAsRegularUser(){
      /*
        Creates a user with no permissions to run the test.
      */
      $user = factory(\App\Models\User::class)->create([
        'permission' => 0
      ]);

      /*
        Creates a brew method to add to the cafe.
      */
      $method = factory(\App\Models\BrewMethod::class)->create([
          'method' => 'Hario V60 Dripper',
          'icon' => ''
      ]);

      /*
        Add the cafe as a user who doesn't have permissions.
      */
      $response = $this->actingAs($user, 'api')
                        ->json('POST', '/api/v1/cafes', [
                          'company_name' => 'Ruby',
                          'company_type' => 'roaster',
                          'website'      => 'https://rubycoffeeroasters.com/',
                          'added_by'     => $user->id,
                          'address'      => '9515 Water St',
                          'city'         => 'Amherst Junction',
                          'state'        => 'WI',
                          'zip'          => '54407',
                          'location_name' => 'Tasting Room',
                          'brew_methods' => json_encode( [$method->id] )
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
      $this->assertDatabaseHas('cafes_actions', [
        'user_id' => $user->id,
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
        Create a user to add a cafe with.
      */
      $user = factory(\App\Models\User::class)->create();

      /*
        Add a brew method for the cafe to have.
      */
      $method = factory(\App\Models\BrewMethod::class)->create([
          'method' => 'Hario V60 Dripper',
          'icon' => ''
      ]);

      /*
        Add the cafe.
      */
      $response = $this->actingAs($user, 'api')
                        ->json('POST', '/api/v1/cafes', [
                          'company_name' => 'Ruby',
                          'company_type' => 'roaster',
                          'website'      => 'https://rubycoffeeroasters.com/',
                          'added_by'     => $user->id,
                          'address'      => '9515 Water St',
                          'city'         => 'Amherst Junction',
                          'state'        => 'WI',
                          'zip'          => '54407',
                          'location_name' => 'Tasting Room',
                          'matcha'       => 1,
                          'brew_methods' => json_encode( [$method->id] )
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
        Create a user to add a cafe with.
      */
      $user = factory(\App\Models\User::class)->create();

      /*
        Add a brew method to be added to the cafe.
      */
      $method = factory(\App\Models\BrewMethod::class)->create([
          'method' => 'Hario V60 Dripper',
          'icon' => ''
      ]);

      /*
        Run the request to add the cafe.
      */
      $response = $this->actingAs($user, 'api')
                        ->json('POST', '/api/v1/cafes', [
                          'company_name' => 'Ruby',
                          'company_type' => 'roaster',
                          'website'      => 'https://rubycoffeeroasters.com/',
                          'added_by'     => $user->id,
                          'address'      => '9515 Water St',
                          'city'         => 'Amherst Junction',
                          'state'        => 'WI',
                          'zip'          => '54407',
                          'location_name' => 'Tasting Room',
                          'matcha'       => 1,
                          'tea'          => 1,
                          'brew_methods' => json_encode( [$method->id] )
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
        Create a new user to run the test with.
      */
      $user = factory(\App\Models\User::class)->create();

      /*
        Create a method to add to the cafe.
      */
      $method = factory(\App\Models\BrewMethod::class)->create([
          'method' => 'Hario V60 Dripper',
          'icon' => ''
      ]);

      /*
        Add a cafe with a tea flag.
      */
      $response = $this->actingAs($user, 'api')
                        ->json('POST', '/api/v1/cafes', [
                          'company_name' => 'Ruby',
                          'company_type' => 'roaster',
                          'website'      => 'https://rubycoffeeroasters.com/',
                          'added_by'     => $user->id,
                          'address'      => '9515 Water St',
                          'city'         => 'Amherst Junction',
                          'state'        => 'WI',
                          'zip'          => '54407',
                          'location_name' => 'Tasting Room',
                          'tea'           => 1,
                          'brew_methods'  => json_encode( [$method->id] )
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
        Create a user to run the test
      */
      $user = factory(\App\Models\User::class)->create();

      /*
        Create a company to run the test
      */
      $company = factory(\App\Models\Company::class)->create([
        'added_by' => $user->id,
        'name' => 'Existing Company'
      ]);

      /*
        Create a brew method to add to the cafe.
      */
      $method = factory(\App\Models\BrewMethod::class)->create([
          'method' => 'Hario V60 Dripper',
          'icon' => ''
      ]);

      /*
        Run the request to add the cafe.
      */
      $response = $this->actingAs($user, 'api')
                        ->json('POST', '/api/v1/cafes', [
                          'company_id'   => $company->id,
                          'added_by'     => $user->id,
                          'address'      => '9515 Water St',
                          'city'         => 'Amherst Junction',
                          'state'        => 'WI',
                          'zip'          => '54407',
                          'location_name' => 'Tasting Room',
                          'brew_methods' => json_encode( [$method->id] )
                        ]);

      /*
        Confirm the cafe has been added to an existing company
      */
      $response->assertJSON([
        'name' => $company->name,
        'website' => $company->website
      ]);
    }

    /**
     * Test adding a cafe company owner
     *
     * @return void
     */
    public function testAddCafeExistingCompanyOwner(){
      /*
        Create a user to run the test
      */
      $user = factory(\App\Models\User::class)->create([
        'permission' => 1
      ]);

      /*
        Create a company to run the test
      */
      $company = factory(\App\Models\Company::class)->create([
        'added_by' => $user->id,
        'name' => 'Existing Company'
      ]);

      /*
        Create a brew method to add to the cafe.
      */
      $method = factory(\App\Models\BrewMethod::class)->create([
          'method' => 'Hario V60 Dripper',
          'icon' => ''
      ]);

      /*
         Bind the company to the user's owned company
      */
      $user->companiesOwned()->attach( $company->id );

      /*
        Run the request to add the cafe.
      */
      $response = $this->actingAs($user, 'api')
                        ->json('POST', '/api/v1/cafes', [
                          'company_id'   => $company->id,
                          'added_by'     => $user->id,
                          'address'      => '9515 Water St',
                          'city'         => 'Amherst Junction',
                          'state'        => 'WI',
                          'zip'          => '54407',
                          'location_name' => 'Tasting Room',
                          'brew_methods' => json_encode( [$method->id] )
                        ]);

      /*
        Confirm the cafe has been added to an existing company
      */
      $response->assertJSON([
        'name' => $company->name,
        'website' => $company->website
      ]);
    }

    /**
     * Test editing a cafe
     *
     * @return void
     */
    public function testEditCafe(){
      /*
        Creates a user to run the test
      */
      $user = factory(\App\Models\User::class)->create();

      /*
        Creates a company to run the test
      */
      $company = factory(\App\Models\Company::class)->create([
        'added_by' => $user->id
      ]);

      /*
        Create a cafe to run the test to edit.
      */
      $cafes = factory(\App\Models\Cafe::class)->create([
        'company_id' => $company->id,
        'added_by' => $user->id
      ]);

      /*
        Create a brew method to add to the cafe
      */
      $method = factory(\App\Models\BrewMethod::class)->create([
          'method' => 'Hario V60 Dripper',
          'icon' => ''
      ]);

      /*
        Grab the slug from the cafe.
      */
      $cafeSlug = $cafes->slug;

      /*
        Run the request to edit the cafe
      */
      $response = $this->actingAs($user, 'api')
                        ->json('PUT', '/api/v1/cafes/'.$cafeSlug, [
                          'company_id'   => $company->id,
                          'company_name' => 'EDITED name',
                          'added_by'     => $user->id,
                          'address'      => 'EDITED 9515 Water St',
                          'city'         => 'EDITED Amherst Junction',
                          'state'        => 'WI',
                          'zip'          => '54407',
                          'location_name' => 'EDITED Tasting Room',
                          'brew_methods' => json_encode( [$method->id] )
                        ]);

      /*
        Confirm that the cafe has the edited name.
      */
      $response->assertJSON([
                  'name' => 'EDITED name',
                ]);
    }

    /**
     * Test editing a cafe and action approved
     *
     * @return void
     */
    public function testEditCafeAndActionApproved(){
      /*
        Creates a user to run the test
      */
      $user = factory(\App\Models\User::class)->create();

      /*
        Creates a company to run the test
      */
      $company = factory(\App\Models\Company::class)->create([
        'added_by' => $user->id
      ]);

      /*
        Create a cafe to run the test to edit.
      */
      $cafes = factory(\App\Models\Cafe::class)->create([
        'company_id' => $company->id,
        'added_by' => $user->id
      ]);

      /*
        Create a brew method to add to the cafe
      */
      $method = factory(\App\Models\BrewMethod::class)->create([
          'method' => 'Hario V60 Dripper',
          'icon' => ''
      ]);

      /*
        Grab the slug from the cafe.
      */
      $cafeSlug = $cafes->slug;

      /*
        Run the request to edit the cafe
      */
      $response = $this->actingAs($user, 'api')
                        ->json('PUT', '/api/v1/cafes/'.$cafeSlug, [
                          'company_id'   => $company->id,
                          'company_name' => 'EDITED name',
                          'added_by'     => $user->id,
                          'address'      => 'EDITED 9515 Water St',
                          'city'         => 'EDITED Amherst Junction',
                          'state'        => 'WI',
                          'zip'          => '54407',
                          'location_name' => 'EDITED Tasting Room',
                          'brew_methods' => json_encode( [$method->id] )
                        ]);

      /*
        Confirm the database has an action for the added cafe.
      */
      $this->assertDatabaseHas('cafes_actions', [
        'user_id' => $user->id,
        'type' => 'cafe-updated',
        'status' => 1,
        'processed_by' => $user->id
      ]);
    }

    /**
     * Test changing a cafe to have matcha
     *
     * @return void
     */
     public function testChangingCafeToHaveMatcha(){
       /*
          Create a user to run the test.
       */
       $user = factory(\App\Models\User::class)->create();

       /*
          Create a company to run the test.
       */
       $company = factory(\App\Models\Company::class)->create([
         'added_by' => $user->id
       ]);

       /*
          Create a cafe to run the test on
       */
       $cafes = factory(\App\Models\Cafe::class)->create([
         'company_id' => $company->id,
         'added_by' => $user->id
       ]);

       /*
          Create a brew method to add to the cafe.
       */
       $method = factory(\App\Models\BrewMethod::class)->create([
           'method' => 'Hario V60 Dripper',
           'icon' => ''
       ]);

       /*
          Grab the slug from the cafe.
       */
       $cafeSlug = $cafes->slug;

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
       $response = $this->actingAs($user, 'api')
                         ->json('PUT', '/api/v1/cafes/'.$cafeSlug, [
                           'matcha' => 1,
                           'company_name' => 'EDITED name',
                           'website'      => 'https://rubycoffeeroasters.com/',
                           'added_by'     => $user->id,
                           'address'      => 'EDITED 9515 Water St',
                           'city'         => 'EDITED Amherst Junction',
                           'state'        => 'WI',
                           'zip'          => '54407',
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
           Create a user to run the test.
        */
        $user = factory(\App\Models\User::class)->create();

        /*
           Create a company to run the test.
        */
        $company = factory(\App\Models\Company::class)->create([
          'added_by' => $user->id
        ]);

        /*
           Create a cafe to run the test on
        */
        $cafes = factory(\App\Models\Cafe::class)->create([
          'company_id' => $company->id,
          'added_by' => $user->id
        ]);

        /*
           Create a brew method to add to the cafe.
        */
        $method = factory(\App\Models\BrewMethod::class)->create([
            'method' => 'Hario V60 Dripper',
            'icon' => ''
        ]);

        /*
           Grab the slug from the cafe.
        */
        $cafeSlug = $cafes->slug;

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
        $response = $this->actingAs($user, 'api')
                          ->json('PUT', '/api/v1/cafes/'.$cafeSlug, [
                            'tea' => 1,
                            'company_name' => 'EDITED name',
                            'website'      => 'https://rubycoffeeroasters.com/',
                            'added_by'     => $user->id,
                            'address'      => 'EDITED 9515 Water St',
                            'city'         => 'EDITED Amherst Junction',
                            'state'        => 'WI',
                            'zip'          => '54407',
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
         Create a user to run the test.
      */
      $user = factory(\App\Models\User::class)->create();

      /*
         Create a company to run the test.
      */
      $company = factory(\App\Models\Company::class)->create([
        'added_by' => $user->id
      ]);

      /*
         Create a cafe to run the test on
      */
      $cafe = factory(\App\Models\Cafe::class)->create([
        'company_id' => $company->id,
        'added_by' => $user->id
      ]);

      /*
        Run the request to like a cafe.
      */
      $response = $this->actingAs($user, 'api')
                        ->json('POST', '/api/v1/cafes/'.$cafe->slug.'/like');

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
                'user_id' => $user->id,
                'cafe_id' => $cafe->id
            ]);
    }

    /**
     * Test delete like cafe
     *
     * @return void
     */
    public function testDeleteLikeCafe(){
      /*
         Create a user to run the test.
      */
      $user = factory(\App\Models\User::class)->create();

      /*
         Create a company to run the test.
      */
      $company = factory(\App\Models\Company::class)->create([
        'added_by' => $user->id
      ]);

      /*
         Create a cafe to run the test on
      */
      $cafe = factory(\App\Models\Cafe::class)->create([
        'company_id' => $company->id,
        'added_by' => $user->id
      ]);

      /*
        Run the request to like a cafe.
      */
      $response = $this->actingAs($user, 'api')
                        ->json('POST', '/api/v1/cafes/'.$cafe->slug.'/like');

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
                'user_id' => $user->id,
                'cafe_id' => $cafe->id
            ]);

      /*
        Run the request to unlike the cafe
      */
      $this->actingAs($user, 'api')
            ->json('DELETE', '/api/v1/cafes/'.$cafe->slug.'/like');

      /*
        Confirms the database has no record of the like.
      */
      $this->assertDatabaseMissing('users_cafes_likes', [
                'user_id' => $user->id,
                'cafe_id' => $cafe->id
            ]);

    }

    /**
     * Test delete a cafe
     *
     * @return void
     */
    public function testDeleteCafe(){
      /*
         Create a user to run the test.
      */
      $user = factory(\App\Models\User::class)->create();

      /*
         Create a company to run the test.
      */
      $company = factory(\App\Models\Company::class)->create([
        'added_by' => $user->id
      ]);

      /*
         Create a cafe to run the test on
      */
      $cafe = factory(\App\Models\Cafe::class)->create([
        'company_id' => $company->id,
        'added_by' => $user->id
      ]);

      /*
        Sends a request to delete a cafe.
      */
      $this->actingAs( $user, 'api' )
           ->json('DELETE', '/api/v1/cafes/'.$cafe->slug);

      /*
        Confirms the database has a soft delete of the cafe.
      */
      $this->assertDatabaseHas('cafes', [
               'id' => $cafe->id,
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
         Create a user to run the test.
      */
      $user = factory(\App\Models\User::class)->create();

      /*
         Create a company to run the test.
      */
      $company = factory(\App\Models\Company::class)->create([
        'added_by' => $user->id
      ]);

      /*
         Create a cafe to run the test on
      */
      $cafe = factory(\App\Models\Cafe::class)->create([
        'company_id' => $company->id,
        'added_by' => $user->id
      ]);

      /*
        Sends a request to delete a cafe.
      */
      $this->actingAs( $user, 'api' )
           ->json('DELETE', '/api/v1/cafes/'.$cafe->slug);

       /*
         Confirm the database has an action for the added cafe.
       */
       $this->assertDatabaseHas('cafes_actions', [
         'user_id' => $user->id,
         'type' => 'cafe-deleted',
         'status' => 1,
         'processed_by' => $user->id
       ]);

    }

    /**
     * Tests cafe owner can update cafe
     *
     * @return void
     */
     public function testCafeOwnerCanUpdateCafe(){
       /*
          Create a user to run the test that doesn't have
          permission to edit the cafe
       */
       $user = factory(\App\Models\User::class)->create([
         'permission' => 0
       ]);

       /*
          Create a company to run the test.
       */
       $company = factory(\App\Models\Company::class)->create([
         'added_by' => $user->id
       ]);

       /*
          Bind the company to the user's owned company
       */
       $user->companiesOwned()->attach( $company->id );

       /*
          Create a cafe to run the test on
       */
       $cafe = factory(\App\Models\Cafe::class)->create([
         'company_id' => $company->id,
         'added_by' => $user->id
       ]);

       /*
         Create a brew method to add to the cafe
       */
       $method = factory(\App\Models\BrewMethod::class)->create([
           'method' => 'Hario V60 Dripper',
           'icon' => ''
       ]);

       /*
         Grab the slug from the cafe.
       */
       $cafeSlug = $cafe->slug;

       /*
         Run the request to edit the cafe
       */
       $response = $this->actingAs($user, 'api')
                         ->json('PUT', '/api/v1/cafes/'.$cafeSlug, [
                           'company_id'   => $company->id,
                           'company_name' => 'EDITED name',
                           'added_by'     => $user->id,
                           'address'      => 'EDITED 9515 Water St',
                           'city'         => 'EDITED Amherst Junction',
                           'state'        => 'WI',
                           'zip'          => '54407',
                           'location_name' => 'EDITED Tasting Room',
                           'brew_methods' => json_encode( [$method->id] )
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
          Create a user to run the test that doesn't have
          permission to edit the cafe
       */
       $user = factory(\App\Models\User::class)->create([
         'permission' => 0
       ]);

       /*
          Create a company to run the test.
       */
       $company = factory(\App\Models\Company::class)->create([
         'added_by' => $user->id
       ]);

       /*
          Create a cafe to run the test on
       */
       $cafe = factory(\App\Models\Cafe::class)->create([
         'company_id' => $company->id,
         'added_by' => $user->id
       ]);

       /*
         Create a brew method to add to the cafe
       */
       $method = factory(\App\Models\BrewMethod::class)->create([
           'method' => 'Hario V60 Dripper',
           'icon' => ''
       ]);

       /*
         Grab the slug from the cafe.
       */
       $cafeSlug = $cafe->slug;

       /*
         Run the request to edit the cafe
       */
       $response = $this->actingAs($user, 'api')
                         ->json('PUT', '/api/v1/cafes/'.$cafeSlug, [
                           'company_id'   => $company->id,
                           'company_name' => 'EDITED name',
                           'added_by'     => $user->id,
                           'address'      => 'EDITED 9515 Water St',
                           'city'         => 'EDITED Amherst Junction',
                           'state'        => 'WI',
                           'zip'          => '54407',
                           'location_name' => 'EDITED Tasting Room',
                           'brew_methods' => json_encode( [$method->id] )
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
        $this->assertDatabaseHas('cafes_actions', [
          'cafe_id' => $cafe->id,
          'type' => 'cafe-updated'
        ]);

        /*
          Confirms the cafe has not been edited
        */
        $this->assertDatabaseMissing('companies', [
          'id' => $cafe->id,
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
           Create a user to run the test that doesn't have
           permission to edit the cafe
        */
        $user = factory(\App\Models\User::class)->create([
          'permission' => 0
        ]);

        /*
           Create a company to run the test.
        */
        $company = factory(\App\Models\Company::class)->create([
          'added_by' => $user->id
        ]);

        /*
           Bind the company to the user's owned company
        */
        $user->companiesOwned()->attach( $company->id );

        /*
           Create a cafe to run the test on
        */
        $cafe = factory(\App\Models\Cafe::class)->create([
          'company_id' => $company->id,
          'added_by' => $user->id
        ]);

        /*
          Create a brew method to add to the cafe
        */
        $method = factory(\App\Models\BrewMethod::class)->create([
            'method' => 'Hario V60 Dripper',
            'icon' => ''
        ]);

        /*
          Grab the slug from the cafe.
        */
        $cafeSlug = $cafe->slug;

        /*
          Run the request to delete the cafe
        */
        $response = $this->actingAs($user, 'api')
                          ->json('DELETE', '/api/v1/cafes/'.$cafeSlug);

        /*
          Confirms the database has a soft delete of the cafe.
        */
        $this->assertDatabaseHas('cafes', [
                 'id' => $cafe->id,
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
           Create a user to run the test that doesn't have
           permission to edit the cafe
        */
        $user = factory(\App\Models\User::class)->create([
          'permission' => 0
        ]);

        /*
           Create a company to run the test.
        */
        $company = factory(\App\Models\Company::class)->create([
          'added_by' => $user->id
        ]);

        /*
           Create a cafe to run the test on
        */
        $cafe = factory(\App\Models\Cafe::class)->create([
          'company_id' => $company->id,
          'added_by' => $user->id
        ]);

        /*
          Create a brew method to add to the cafe
        */
        $method = factory(\App\Models\BrewMethod::class)->create([
            'method' => 'Hario V60 Dripper',
            'icon' => ''
        ]);

        /*
          Grab the slug from the cafe.
        */
        $cafeSlug = $cafe->slug;

        /*
          Run the request to delete the cafe
        */
        $response = $this->actingAs($user, 'api')
                          ->json('DELETE', '/api/v1/cafes/'.$cafeSlug);

         /*
           Confirm that the response states the deletion is
           pending
         */
         $response->assertJSON([
           'cafe_delete_pending' => $cafe->company->name,
         ]);

         /*
           Confirms that the action exists in the database
         */
         $this->assertDatabaseHas('cafes_actions', [
           'cafe_id' => $cafe->id,
           'type' => 'cafe-deleted'
         ]);

         /*
           Confirms the cafe has not been deleted
         */
         $this->assertDatabaseMissing('cafes', [
           'id' => $cafe->id,
           'deleted' => 1
         ]);
      }
}
