<?php

use Illuminate\Http\Request;

Route::group(['prefix' => 'v1', 'middleware' => 'auth:api'], function(){
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
  | Adds a New Cafe
  |-------------------------------------------------------------------------------
  | URL:            /api/v1/cafes
  | Controller:     API\CafesController@postNewCafe
  | Method:         POST
  | Description:    Adds a new cafe to the application
  */
  Route::post('/cafes', 'API\CafesController@postNewCafe');
});
