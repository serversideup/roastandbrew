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
  | Get Users
  |-------------------------------------------------------------------------------
  | URL:            /api/v1/users
  | Controller:     API\UsersController@getUsers
  | Method:         GET
  | Description:    Gets the users searched by the authenticated user.
  */
  Route::get('/users', 'API\UsersController@getUsers');

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
  | URL:            /api/v1/cafes/{slug}
  | Controller:     API\CafesController@getCafe
  | Method:         GET
  | Description:    Gets an individual cafe
  */
  Route::get('/cafes/{slug}', 'API\CafesController@getCafe');

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
  | Handles a Company Search
  |-------------------------------------------------------------------------------
  | URL:            /api/v1/companies/search
  | Controller:     API\CompaniesController@getCompanySearch
  | Method:         GET
  | Description:    Handles a search for a company.
  */
  Route::get('/companies/search', 'API\CompaniesController@getCompanySearch');

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
  | URL:            /api/v1/cafes/{slug}/edit
  | Controller:     API\CafesController@getCafeEditData
  | Method:         GET
  | Description:    Gets an individual cafe's edit data
  */
  Route::get('/cafes/{slug}/edit', 'API\CafesController@getCafeEditData');

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
  | URL:            /api/v1/cafes/{slug}
  | Controller:     API\CafesController@putEditCafe
  | Method:         PUT
  | Description:    Edits a cafe
  */
  Route::put('/cafes/{slug}', 'API\CafesController@putEditCafe');

  /*
  |-------------------------------------------------------------------------------
  | Likes a Cafe
  |-------------------------------------------------------------------------------
  | URL:            /api/v1/cafes/{slug}/like
  | Controller:     API\CafesController@postLikeCafe
  | Method:         POST
  | Description:    Likes a cafe for the authenticated user.
  */
  Route::post('/cafes/{slug}/like', 'API\CafesController@postLikeCafe');

  /*
  |-------------------------------------------------------------------------------
  | Un-Likes a Cafe
  |-------------------------------------------------------------------------------
  | URL:            /api/v1/cafes/{slug}/like
  | Controller:     API\CafesController@deleteLikeCafe
  | Method:         DELETE
  | Description:    Un-Likes a cafe for the authenticated user.
  */
  Route::delete('/cafes/{slug}/like', 'API\CafesController@deleteLikeCafe');

  /*
  |-------------------------------------------------------------------------------
  | Adds Tags To A Cafe
  |-------------------------------------------------------------------------------
  | URL:            /api/v1/cafes/{slug}/tags
  | Controller:     API\CafesController@postAddTags
  | Method:         POST
  | Description:    Adds tags to a cafe for a user
  */
  Route::post('/cafes/{slug}/tags', 'API\CafesController@postAddTags');

  /*
  |-------------------------------------------------------------------------------
  | Deletes A Cafe Tag
  |-------------------------------------------------------------------------------
  | URL:            /api/v1/cafes/{slug}/tags/{tagID}
  | Controller:     API\CafesController@deleteCafeTag
  | Method:         DELETE
  | Description:    Deletes a tag from a cafe for a user
  */
  Route::delete('/cafes/{slug}/tags/{tagID}', 'API\CafesController@deleteCafeTag');

  /*
  |-------------------------------------------------------------------------------
  | Deletes A Cafe
  |-------------------------------------------------------------------------------
  | URL:            /api/v1/cafes/{slug}
  | Controller:     API\CafesController@deleteCafe
  | Method:         DELETE
  | Description:    Deletes a cafe
  */
  Route::delete('/cafes/{slug}', 'API\CafesController@deleteCafe');
});

/*
  Owner Routes. Must be at least a company owner to access these routes.
*/
Route::group(['prefix' => 'v1/admin', 'middleware' => ['auth:api', 'owner']], function(){
  /*
  |-------------------------------------------------------------------------------
  | Gets All Unprocessed Actions
  |-------------------------------------------------------------------------------
  | URL:            /api/v1/admin/actions
  | Controller:     API\Admin\ActionsController@getActions
  | Method:         GET
  | Description:    Gets all of the unprocessed actions for a user.
  */
  Route::get('/actions', 'API\Admin\ActionsController@getActions');

  /*
  |-------------------------------------------------------------------------------
  | Approves an action
  |-------------------------------------------------------------------------------
  | URL:            /api/v1/admin/actions/{action}/approve
  | Controller:     API\Admin\ActionsController@putApproveAction
  | Method:         PUT
  | Description:    Approves an action for a user.
  | Middleware:     Only runs if the user is authorized to approve the action.
  */
  Route::put('/actions/{action}/approve', 'API\Admin\ActionsController@putApproveAction')
       ->middleware('can:approve,action');

   /*
   |-------------------------------------------------------------------------------
   | Denies an action
   |-------------------------------------------------------------------------------
   | URL:            /api/v1/admin/actions/{action}/deny
   | Controller:     API\Admin\ActionsController@putDenyAction
   | Method:         PUT
   | Description:    Denies an action for a user.
   | Middleware:     Only runs if the user is authorized to deny the action.
   */
  Route::put('/actions/{action}/deny', 'API\Admin\ActionsController@putDenyAction')
       ->middleware('can:deny,action');
});

/*
  Admin Routes
*/
Route::group(['prefix' => 'v1/admin', 'middleware' => ['auth:api', 'admin']], function(){

});
