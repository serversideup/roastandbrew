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
use App\Models\BrewMethod;

/*
  Uses the Auth facade.
*/
use Auth;

/*
  Defines the requests used by the controller.
*/
use App\Http\Requests\Admin\AddBrewMethodRequest;
use App\Http\Requests\Admin\EditBrewMethodRequest;

/**
 * Handles the retrieval, updating, and editing of brew methods
 */
class BrewMethodsController extends Controller
{
  /*
  |-------------------------------------------------------------------------------
  | Gets All Brew Methods
  |-------------------------------------------------------------------------------
  | URL:            /api/v1/admin/brew-methods
  | Method:         GET
  | Description:    Gets all brew methods in the application
  */
  public function getBrewMethods(){
    $brewMethods = BrewMethod::all();

    return response()->json( $brewMethods );
  }

  /*
  |-------------------------------------------------------------------------------
  | Gets A Brew Method
  |-------------------------------------------------------------------------------
  | URL:            /api/v1/admin/brew-methods/{method}
  | Method:         GET
  | Description:    Gets a specific brew method
  */
  public function getBrewMethod( BrewMethod $method ){
    return response()->json( $method );
  }

  /*
  |-------------------------------------------------------------------------------
  | Adds A Brew Method
  |-------------------------------------------------------------------------------
  | URL:            /api/v1/admin/brew-methods
  | Method:         POST
  | Description:    Adds a brew method
  */
  public function postAddBrewMethod( AddBrewMethodRequest $request ){
    $brewMethod = new BrewMethod();

    $brewMethod->method = $request->get('method');
    $brewMethod->icon = $request->get('icon');

    $brewMethod->save();

    return response()->json( $brewMethod );
  }

  /*
  |-------------------------------------------------------------------------------
  | Updates A Brew Method
  |-------------------------------------------------------------------------------
  | URL:            /api/v1/admin/brew-methods/{method}
  | Method:         PUT
  | Description:    Updates a brew method
  */
  public function putUpdateBrewMethod( BrewMethod $method, EditBrewMethodRequest $request ){
    $brewMethod = BrewMethod::where('id', '=', $method->id)->first();

    $brewMethod->method = $request->get('method');
    $brewMethod->icon = $request->get('icon');

    $brewMethod->save();

    return response()->json( $brewMethod );
  }
}
