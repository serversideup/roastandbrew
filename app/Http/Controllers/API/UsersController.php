<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

use App\Http\Requests\EditUserRequest;

use App\Models\User;
use Auth;

class UsersController extends Controller
{
  /*
  |-------------------------------------------------------------------------------
  | Get User
  |-------------------------------------------------------------------------------
  | URL:            /api/v1/user
  | Method:         GET
  | Description:    Gets the authenticated user
  */
  public function getUser(){
    return Auth::guard('api')->user();
  }

  /*
  |-------------------------------------------------------------------------------
  | Get Users
  |-------------------------------------------------------------------------------
  | URL:            /api/v1/users
  | Method:         GET
  | Description:    Gets the users searched by the authenticated user.
  */
  public function getUsers(){
    $query = Request::get('search');

    $users = User::where('name', 'LIKE', '%'.$query.'%')
                 ->orWhere('email', 'LIKE', '%'.$query.'%')
                 ->get();


    return response()->json( $users );
  }
  /*
  |-------------------------------------------------------------------------------
  | Updates a User's Profile
  |-------------------------------------------------------------------------------
  | URL:            /api/v1/user
  | Method:         PUT
  | Description:    Updates the authenticated user's profile
  */
  public function putUpdateUser( EditUserRequest $request ){
    $user = Auth::user();

    $favoriteCoffee       = $request->get('favorite_coffee');
    $flavorNotes          = $request->get('flavor_notes');
    $profileVisibility    = $request->get('profile_visibility');
    $city                 = $request->get('city');
    $state                = $request->get('state');

    /*
      Ensure the user has entered a favorite coffee
    */
    if( $favoriteCoffee != '' ){
      $user->favorite_coffee    = $favoriteCoffee;
    }

    /*
      Ensure the user has entered some flavor notes
    */
    if( $flavorNotes != '' ){
      $user->flavor_notes       = $flavorNotes;
    }

    /*
      Ensure the user has submitted a profile visibility update
    */
    if( $profileVisibility != '' ){
      $user->profile_visibility = $profileVisibility;
    }

    /*
      Ensure the user has entered something for city.
    */
    if( $city != '' ){
      $user->city   = $city;
    }

    /*
      Ensure the user has entered something for state
    */
    if( $state != '' ){
      $user->state  = $state;
    }

    $user->save();

    /*
			Return a response that the user was updated successfully.
		*/
		return response()->json( ['user_updated' => true], 201 );
  }
}
