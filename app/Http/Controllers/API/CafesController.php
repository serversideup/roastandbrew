<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

use App\Models\Cafe;

use App\Utilities\GoogleMaps;

use Request;
use Auth;

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
    $cafes = Cafe::with('brewMethods')->get();

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
    $cafe = Cafe::where('id', '=', $id)
								->with('brewMethods')
								->first();

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
		$addedCafes = array();

		$locations = $request->get('locations');

		/*
			Create a parent cafe and grab the first location
		*/
		$parentCafe = new Cafe();

		$address  			= $locations[0]['address'];
		$city 					= $locations[0]['city'];
		$state 					= $locations[0]['state'];
		$zip 						= $locations[0]['zip'];
		$locationName		= $locations[0]['name'];
		$brewMethods 		= $locations[0]['methodsAvailable'];

		/*
			Get the Latitude and Longitude returned from the Google Maps Address.
		*/
		$coordinates = GoogleMaps::geocodeAddress( $address, $city, $state, $zip );

		$parentCafe->name 					= $request->get('name');
		$parentCafe->location_name	= $locationName != '' ? $locationName : '';
		$parentCafe->address 				= $address;
		$parentCafe->city 					= $city;
		$parentCafe->state 					= $state;
		$parentCafe->zip 						= $zip;
		$parentCafe->latitude 			= $coordinates['lat'];
		$parentCafe->longitude 			= $coordinates['lng'];
		$parentCafe->roaster 				= $request->get('roaster') != '' ? 1 : 0;
		$parentCafe->website 				= $request->get('website');
		$parentCafe->description		= $request->get('description') != '' ? $request->get('description') : '';
		$parentCafe->added_by 			= Auth::user()->id;

		/*
			Save parent cafe
		*/
		$parentCafe->save();

		/*
			Attach the brew methods
		*/
		$parentCafe->brewMethods()->sync( $brewMethods );

		array_push( $addedCafes, $parentCafe->toArray() );

		/*
			Now that we have the parent cafe, we add all of the other
			locations. We have to see if other locations are added.
		*/
		if( count( $locations ) > 1 ){
			/*
				We off set the counter at 1 since we already used the
				first location.
			*/
			for( $i = 1; $i < count( $locations ); $i++ ){
				/*
					Create a cafe and grab the location
				*/
				$cafe = new Cafe();

				$address  			= $locations[$i]['address'];
				$city 					= $locations[$i]['city'];
				$state 					= $locations[$i]['state'];
				$zip 						= $locations[$i]['zip'];
				$locationName		= $locations[$i]['name'];
				$brewMethods 		= $locations[$i]['methodsAvailable'];

				/*
					Get the Latitude and Longitude returned from the Google Maps Address.
				*/
				$coordinates = GoogleMaps::geocodeAddress( $address, $city, $state, $zip );

				$cafe->parent 				= $parentCafe->id;
				$cafe->name 					= $request->get('name');
				$cafe->location_name	= $locationName != '' ? $locationName : '';
				$cafe->address 				= $address;
				$cafe->city 					= $city;
				$cafe->state 					= $state;
				$cafe->zip 						= $zip;
				$cafe->latitude 			= $coordinates['lat'];
				$cafe->longitude 			= $coordinates['lng'];
				$cafe->roaster 				= $request->get('roaster') != '' ? 1 : 0;
				$cafe->website 				= $request->get('website');
				$cafe->description		= $request->get('description') != '' ? $request->get('description') : '';
				$cafe->added_by 			= Auth::user()->id;

				/*
					Save cafe
				*/
				$cafe->save();

				/*
					Attach the brew methods
				*/
				$cafe->brewMethods()->sync( $brewMethods );

				array_push( $addedCafes, $cafe->toArray() );
			}
		}

		/*
			Return the added cafes as JSON
		*/
    return response()->json($addedCafes, 201);
  }
}
