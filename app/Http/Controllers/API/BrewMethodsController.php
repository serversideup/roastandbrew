<?php
/*
  Defines the namespace for the controller.
*/
namespace App\Http\Controllers\API;

/*
  Uses the controller interface.
*/
use App\Http\Controllers\Controller;

/*
  Uses the brew method model.
*/
use App\Models\BrewMethod;

/**
 * Defines the brew methods controller.
 */
class BrewMethodsController extends Controller
{
  /**
   * Gets all of the brew methods and the count of cafes
   * that have the brew method.
   *
   * URL: /api/v1/brew-methods
   * METHOD: GET
   */
  public function getBrewMethods(){
    /*
      Gets all of the brew methods with the count of the cafes.
    */
    $brewMethods = BrewMethod::withCount('cafes')->get();

    /*
      Returns the brew methods as JSON.
    */
    return response()->json( $brewMethods );
  }
}
