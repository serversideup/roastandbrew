<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

use App\Models\Cafe;

use App\Utilities\GoogleMaps;

use Request;

/*
	Defines the requests used by the controller.
*/
use App\Http\Requests\StoreCafeRequest;

class CafesController extends Controller
{
  /*
  |-------------------------------------------------------------------------------
  | Get All Cafes
  |-------------------------------------------------------------------------------
  | URL:            /api/v1/cafes
  | Method:         GET
  | Description:    Gets all of the cafes in the application
  */
	public function getCafes(){
    $cafes = Cafe::all();

    return response()->json( $cafes );
  }

  /*
  |-------------------------------------------------------------------------------
  | Get An Individual Cafe
  |-------------------------------------------------------------------------------
  | URL:            /api/v1/cafes/{id}
  | Method:         GET
  | Description:    Gets an individual cafe
  | Parameters:
  |   $id   -> ID of the cafe we are retrieving
  */
  public function getCafe( $id ){
    $cafe = Cafe::where('id', '=', $id)->first();

    return response()->json( $cafe );
  }

  /*
  |-------------------------------------------------------------------------------
  | Adds a New Cafe
  |-------------------------------------------------------------------------------
  | URL:            /api/v1/cafes
  | Method:         POST
  | Description:    Adds a new cafe to the application
  */
  public function postNewCafe( StoreCafeRequest $request ){
		/*
			Get the Latitude and Longitude returned from the Google Maps Address.
		*/
		$coordinates = GoogleMaps::geocodeAddress( $request->get('address'),
																							 $request->get('city'),
																							 $request->get('state'),
																							 $request->get('zip') );

    $cafe = new Cafe();

    $cafe->name     	= $request->get('name');
    $cafe->address  	= $request->get('address');
    $cafe->city     	= $request->get('city');
    $cafe->state    	= $request->get('state');
    $cafe->zip      	= $request->get('zip');
		$cafe->latitude 	= $coordinates['lat'];
		$cafe->longitude	= $coordinates['lng'];

    $cafe->save();

    return response()->json($cafe, 201);
  }
}
