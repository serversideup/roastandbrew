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

    /*
      Tests getting cafes
    */
    public function testGetCafes(){
      $user = factory(\App\Models\User::class)->create();

      $company = factory(\App\Models\Company::class)->create([
        'added_by' => $user->id
      ]);

      $cafes = factory(\App\Models\Cafe::class, 3)->create([
        'company_id' => $company->id,
        'added_by' => $user->id
      ]);

      $response = $this->actingAs($user, 'api')
                        ->get('/api/v1/cafes')
                        ->assertJsonCount(3);
    }

    /*
      Tests getting a cafe's data
    */
    public function testGetCafe(){
      $user = factory(\App\Models\User::class)->create();

      $company = factory(\App\Models\Company::class)->create([
        'added_by' => $user->id
      ]);

      $cafes = factory(\App\Models\Cafe::class, 3)->create([
        'company_id' => $company->id,
        'added_by' => $user->id
      ]);

      $cafeSlug = $cafes[0]->slug;


      $response = $this->actingAs($user, 'api')
                       ->get('/api/v1/cafes/'.$cafeSlug)
                       ->assertJSON([
                         'slug' => $cafeSlug
                       ]);
    }

    /*
      Tests getting data for a cafe to edit
    */
    public function testGetCafeEditData(){
      $user = factory(\App\Models\User::class)->create();

      $company = factory(\App\Models\Company::class)->create([
        'added_by' => $user->id
      ]);

      $cafes = factory(\App\Models\Cafe::class, 3)->create([
        'company_id' => $company->id,
        'added_by' => $user->id
      ]);

      $cafeSlug = $cafes[0]->slug;


      $response = $this->actingAs($user, 'api')
                       ->get('/api/v1/cafes/'.$cafeSlug)
                       ->assertJSON([
                         'slug' => $cafeSlug,
                         'deleted' => 0
                       ]);
    }

    /*
      Test adding a cafe new company
    */
    public function testAddCafeNewCompany(){
      $user = factory(\App\Models\User::class)->create();

      $method = factory(\App\Models\BrewMethod::class)->create([
          'method' => 'Hario V60 Dripper',
          'icon' => ''
      ]);

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
                        ])
                        ->assertJSON([
                          'name' => 'Ruby',
                          'roaster' => '1',
                          'website' => 'https://rubycoffeeroasters.com/'
                        ])
                        ->assertJSONFragment([
                          'matcha' => 0,
                          'tea' => 0
                        ]);
    }

    /*
      Test adding a cafe with matcha
    */
    public function testAddCafeWithMatcha(){
      $user = factory(\App\Models\User::class)->create();

      $method = factory(\App\Models\BrewMethod::class)->create([
          'method' => 'Hario V60 Dripper',
          'icon' => ''
      ]);

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
                        ])
                        ->assertJSONFragment([
                          'matcha' => 1
                        ]);
    }

    /*
      Test adding a cafe with matcha and tea
    */
    public function testAddingCafeWithMatchaAndTea(){
      $user = factory(\App\Models\User::class)->create();

      $method = factory(\App\Models\BrewMethod::class)->create([
          'method' => 'Hario V60 Dripper',
          'icon' => ''
      ]);

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
                        ])
                        ->assertJSONFragment([
                          'matcha' => 1,
                          'tea' => 1
                        ]);
    }

    /*
      Test adding a cafe with tea
    */
    public function testAddingCafeWithTea(){
      $user = factory(\App\Models\User::class)->create();

      $method = factory(\App\Models\BrewMethod::class)->create([
          'method' => 'Hario V60 Dripper',
          'icon' => ''
      ]);

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
                          'tea'       => 1,
                          'brew_methods' => json_encode( [$method->id] )
                        ])
                        ->assertJSONFragment([
                          'tea' => 1
                        ]);
    }

    /*
      Test adding a cafe existing company
    */
    public function testAddCafeExistingCompany(){
      $user = factory(\App\Models\User::class)->create();

      $company = factory(\App\Models\Company::class)->create([
        'added_by' => $user->id,
        'name' => 'Existing Company'
      ]);

      $method = factory(\App\Models\BrewMethod::class)->create([
          'method' => 'Hario V60 Dripper',
          'icon' => ''
      ]);

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
                        ])
                        ->assertJSON([
                          'name' => $company->name,
                          'website' => $company->website
                        ]);
    }

    /*
      Test editing a cafe
    */
    public function testEditCafe(){
      $user = factory(\App\Models\User::class)->create();

      $company = factory(\App\Models\Company::class)->create([
        'added_by' => $user->id
      ]);

      $cafes = factory(\App\Models\Cafe::class)->create([
        'company_id' => $company->id,
        'added_by' => $user->id
      ]);

      $method = factory(\App\Models\BrewMethod::class)->create([
          'method' => 'Hario V60 Dripper',
          'icon' => ''
      ]);

      $cafeSlug = $cafes->slug;

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
                        ])
                        ->assertJSON([
                          'name' => 'EDITED name',
                        ]);
    }

    /**
     * Test changing a cafe to have matcha
     */
     public function testChangingCafeToHaveMatcha(){
       $user = factory(\App\Models\User::class)->create();

       $company = factory(\App\Models\Company::class)->create([
         'added_by' => $user->id
       ]);

       $cafes = factory(\App\Models\Cafe::class)->create([
         'company_id' => $company->id,
         'added_by' => $user->id
       ]);

       $method = factory(\App\Models\BrewMethod::class)->create([
           'method' => 'Hario V60 Dripper',
           'icon' => ''
       ]);

       $cafeSlug = $cafes->slug;

       $this->assertDatabaseHas('cafes', [
         'slug' => $cafeSlug,
         'matcha' => 0
       ]);

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
                         ])
                         ->assertJSONFragment([
                           'matcha' => 1
                         ]);
     }

     /**
      * Test changing a cafe to have tea
      */
      public function testChangingCafeToHaveTea(){
        $user = factory(\App\Models\User::class)->create();

        $company = factory(\App\Models\Company::class)->create([
          'added_by' => $user->id
        ]);

        $cafes = factory(\App\Models\Cafe::class)->create([
          'company_id' => $company->id,
          'added_by' => $user->id
        ]);

        $method = factory(\App\Models\BrewMethod::class)->create([
            'method' => 'Hario V60 Dripper',
            'icon' => ''
        ]);

        $cafeSlug = $cafes->slug;

        $this->assertDatabaseHas('cafes', [
          'slug' => $cafeSlug,
          'tea' => 0
        ]);

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
                          ])
                          ->assertJSONFragment([
                            'tea' => 1
                          ]);
      }

    /*
      Test like cafe
    */
    public function testLikeCafe(){
      $user = factory(\App\Models\User::class)->create();

      $company = factory(\App\Models\Company::class)->create([
        'added_by' => $user->id
      ]);

      $cafe = factory(\App\Models\Cafe::class)->create([
        'company_id' => $company->id,
        'added_by' => $user->id
      ]);

      $response = $this->actingAs($user, 'api')
                        ->json('POST', '/api/v1/cafes/'.$cafe->slug.'/like')
                        ->assertJSON([
                          'cafe_liked' => 'true',
                        ]);

      $this->assertDatabaseHas('users_cafes_likes', [
                'user_id' => $user->id,
                'cafe_id' => $cafe->id
            ]);
    }

    /*
      Test delete like cafe
    */
    public function testDeleteLikeCafe(){
      $user = factory(\App\Models\User::class)->create();

      $company = factory(\App\Models\Company::class)->create([
        'added_by' => $user->id
      ]);

      $cafe = factory(\App\Models\Cafe::class)->create([
        'company_id' => $company->id,
        'added_by' => $user->id
      ]);

      $this->actingAs($user, 'api')
            ->json('POST', '/api/v1/cafes/'.$cafe->slug.'/like')
            ->assertJSON([
              'cafe_liked' => 'true',
            ]);

      $this->assertDatabaseHas('users_cafes_likes', [
                'user_id' => $user->id,
                'cafe_id' => $cafe->id
            ]);

      $this->actingAs($user, 'api')
            ->json('DELETE', '/api/v1/cafes/'.$cafe->slug.'/like');

      $this->assertDatabaseMissing('users_cafes_likes', [
                'user_id' => $user->id,
                'cafe_id' => $cafe->id
            ]);

    }

    /*
      Test delete cafe
    */
    public function testDeleteCafe(){
      $user = factory(\App\Models\User::class)->create();

      $company = factory(\App\Models\Company::class)->create([
        'added_by' => $user->id
      ]);

      $cafe = factory(\App\Models\Cafe::class)->create([
        'company_id' => $company->id,
        'added_by' => $user->id
      ]);

      $this->actingAs( $user, 'api' )
           ->json('DELETE', '/api/v1/cafes/'.$cafe->slug);

     $this->assertDatabaseHas('cafes', [
               'id' => $cafe->id,
               'deleted' => 1
           ]);

    }
}
