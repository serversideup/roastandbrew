<?php

use Illuminate\Http\Request;

/*
  Public API Routes
*/
Route::group(['prefix' => 'v1'], function(){
  /*
  |-------------------------------------------------------------------------------
  | Get User
  |-------------------------------------------------------------------------------
  | URL:            /api/v1/user
  | Controller:     API\UsersController@getUser
  | Method:         GET
  | Description:    Gets the authenticated user
  */
  Route::get('/user', 'API\UsersController@getUser');

  /*
  |-------------------------------------------------------------------------------
  | Get All Cafes
  |-------------------------------------------------------------------------------
  | URL:            /api/v1/cafes
  | Controller:     API\CafesController@getCafes
  | Method:         GET
  | Description:    Gets all of the cafes in the application
  */
  Route::get('/cafes', 'API\CafesController@getCafes');

  /*
  |-------------------------------------------------------------------------------
  | Get An Individual Cafe
  |-------------------------------------------------------------------------------
  | URL:            /api/v1/cafes/{id}
  | Controller:     API\CafesController@getCafe
  | Method:         GET
  | Description:    Gets an individual cafe
  */
  Route::get('/cafes/{id}', 'API\CafesController@getCafe');

  /*
  |-------------------------------------------------------------------------------
  | Get All Brew methods
  |-------------------------------------------------------------------------------
  | URL:            /api/v1/brew-methods
  | Controller:     API\BrewMethodsController@getBrewMethods
  | Method:         GET
  | Description:    Gets all of the brew methods in the application
  */
  Route::get('/brew-methods', 'API\BrewMethodsController@getBrewMethods');

  /*
  |-------------------------------------------------------------------------------
  | Search Tags
  |-------------------------------------------------------------------------------
  | URL:            /api/v1/tags
  | Controller:     API\TagsController@getTags
  | Method:         GET
  | Description:    Searches the tags if a query is set otherwise returns all tags
  */
  Route::get('/tags', 'API\TagsController@getTags');
});

/*
  Authenticated API Routes.
*/
Route::group(['prefix' => 'v1', 'middleware' => 'auth:api'], function(){
  /*
  |-------------------------------------------------------------------------------
  | Updates a User's Profile
  |-------------------------------------------------------------------------------
  | URL:            /api/v1/user
  | Controller:     API\UsersController@putUpdateUser
  | Method:         PUT
  | Description:    Updates the authenticated user's profile
  */
  Route::put('/user', 'API\UsersController@putUpdateUser');

  /*
  |-------------------------------------------------------------------------------
  | Gets Editing Data for an Individual Cafe
  |-------------------------------------------------------------------------------
  | URL:            /api/v1/cafes/{id}/edit
  | Controller:     API\CafesController@getCafeEditData
  | Method:         GET
  | Description:    Gets an individual cafe's edit data
  */
  Route::get('/cafes/{id}/edit', 'API\CafesController@getCafeEditData');

  /*
  |-------------------------------------------------------------------------------
  | Adds a New Cafe
  |-------------------------------------------------------------------------------
  | URL:            /api/v1/cafes
  | Controller:     API\CafesController@postNewCafe
  | Method:         POST
  | Description:    Adds a new cafe to the application
  */
  Route::post('/cafes', 'API\CafesController@postNewCafe');

  /*
  |-------------------------------------------------------------------------------
  | Edits a Cafe
  |-------------------------------------------------------------------------------
  | URL:            /api/v1/cafes/{cafeID}
  | Controller:     API\CafesController@putEditCafe
  | Method:         PUT
  | Description:    Edits a cafe
  */
  Route::put('/cafes/{cafeID}', 'API\CafesController@putEditCafe');

  /*
  |-------------------------------------------------------------------------------
  | Likes a Cafe
  |-------------------------------------------------------------------------------
  | URL:            /api/v1/cafes/{id}/like
  | Controller:     API\CafesController@postLikeCafe
  | Method:         POST
  | Description:    Likes a cafe for the authenticated user.
  */
  Route::post('/cafes/{id}/like', 'API\CafesController@postLikeCafe');

  /*
  |-------------------------------------------------------------------------------
  | Un-Likes a Cafe
  |-------------------------------------------------------------------------------
  | URL:            /api/v1/cafes/{id}/like
  | Controller:     API\CafesController@deleteLikeCafe
  | Method:         DELETE
  | Description:    Un-Likes a cafe for the authenticated user.
  */
  Route::delete('/cafes/{id}/like', 'API\CafesController@deleteLikeCafe');

  /*
  |-------------------------------------------------------------------------------
  | Adds Tags To A Cafe
  |-------------------------------------------------------------------------------
  | URL:            /api/v1/cafes/{id}/tags
  | Controller:     API\CafesController@postAddTags
  | Method:         POST
  | Description:    Adds tags to a cafe for a user
  */
  Route::post('/cafes/{id}/tags', 'API\CafesController@postAddTags');

  /*
  |-------------------------------------------------------------------------------
  | Deletes A Cafe Tag
  |-------------------------------------------------------------------------------
  | URL:            /api/v1/cafes/{id}/tags/{tagID}
  | Controller:     API\CafesController@deleteCafeTag
  | Method:         DELETE
  | Description:    Deletes a tag from a cafe for a user
  */
  Route::delete('/cafes/{id}/tags/{tagID}', 'API\CafesController@deleteCafeTag');
});
