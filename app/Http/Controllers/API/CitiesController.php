<?php
/*
	Defines the namespace for the controller.
*/
namespace App\Http\Controllers\API;

use App\Models\City;

/*
	Uses the controller interface
*/
use App\Http\Controllers\Controller;

class CitiesController extends Controller
{
  /*
  |-------------------------------------------------------------------------------
  | Gets All Cities
  |-------------------------------------------------------------------------------
  | URL:            /api/v1/cities
  | Controller:     API\CitiesController@getCities
  | Method:         GET
  | Description:    Get all cities
  */
  public function getCities(){
    $cities = City::orderBy('name', 'asc')->get();

    return response()->json( $cities );
  }

  /*
  |-------------------------------------------------------------------------------
  | Gets An Individual City
  |-------------------------------------------------------------------------------
  | URL:            /api/v1/cities/{slug}
  | Controller:     API\CitiesController@getCity
  | Method:         GET
  | Description:    Gets an individual city
  */
  public function getCity( $slug ){
    $city = City::where('slug', '=', $slug )
                ->with(['cafes' => function( $query ){
                  $query->with('company');
                }])
                ->first();

    if( $city != null ){
      return response()->json( $city );
    }else{
      return response()->json(nul, 404);
    }

  }
}
