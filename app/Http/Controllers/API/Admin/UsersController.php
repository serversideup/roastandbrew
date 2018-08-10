<?php
/*
  Defines the namespace for the controller
*/
namespace App\Http\Controllers\API\Admin;

/*
  Uses the controller interface
*/
use App\Http\Controllers\Controller;

/*
  Defines the models used by the controller.
*/
use App\Models\User;

/*
  Defines the facades used by the controller.
*/
use Auth;
use Illuminate\Http\Request;
/*
  Defines the services used by the controller.
*/
use App\Services\UserService;

/**
 * Handles the retrieval, updating, and editing of users
 */
class UsersController extends Controller
{

  /*
  |-------------------------------------------------------------------------------
  | Searches All Users
  |-------------------------------------------------------------------------------
  | URL:            /api/v1/admin/users
  | Method:         GET
  | Description:    Gets all users in the application
  */
  public function getUsers( Request $request){
    /*
      If there is a search, then find all the users whose name
      matches the search. Otherwise get all of the users.
    */
    if( $request->has('search') ){
      $users = User::where('name', 'LIKE', '%'.$request->get('search').'%')
                   ->get();
    }else{
      $users = User::all();
    }

    /*
      Return all of the found users.
    */
    return response()->json( $users );
  }

  /*
  |-------------------------------------------------------------------------------
  | Gets A User
  |-------------------------------------------------------------------------------
  | URL:            /api/v1/admin/users/{user}
  | Method:         GET
  | Description:    Gets an individual user.
  */
  public function getUser( User $user ){
    $user = User::where('id', '=', $user->id )
                ->with('companiesOwned')
                ->first();

    return response()->json( $user );
  }

  /*
  |-------------------------------------------------------------------------------
  | Updates A User
  |-------------------------------------------------------------------------------
  | URL:            /api/v1/admin/users/{user}
  | Method:         PUT
  | Description:    Updates an individual user.
  */
  public function putUpdateUser( User $user, Request $request ){
    /*
      Updates the user.
    */
    UserService::updateUser( $user, $request->all() );

    /*
      Loads the updated user to return back.
    */
    $user = User::where('id', '=', $user->id )
                ->with('companiesOwned')
                ->first();

    return response()->json( $user );
  }
}
