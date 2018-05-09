<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

use App\Models\Company;
use App\Models\Cafe;
use App\Models\CafePhoto;

use App\Utilities\GoogleMaps;
use App\Utilities\Tagger;

use Request;
use Auth;
use DB;
use File;

/*
	Defines the requests used by the controller.
*/
use App\Http\Requests\StoreCafeRequest;
use App\Http\Requests\EditCafeRequest;

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
    $cafes = Cafe::with('brewMethods')
									->with(['tags' => function( $query ){
										$query->select('tag');
									}])
									->with('company')
									->withCount('userLike')
									->where('deleted', '=', 0)
									->get();

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
								->withCount('userLike')
								->with('tags')
								->with(['company' => function( $query ){
									$query->withCount('cafes');
								}])
								->withCount('likes')
								->where('deleted', '=', 0)
								->first();


		if( $cafe != null ){
			return response()->json( $cafe );
		}else{
			abort(404);
		}

  }

	/*
	|-------------------------------------------------------------------------------
	| Gets Editing Data for an Individual Cafe
	|-------------------------------------------------------------------------------
	| URL:            /api/v1/cafes/{id}/edit
	| Method:         GET
	| Description:    Gets an individual cafe's edit data
	| Parameters:
	|   $id   -> ID of the cafe we are retrieving
	*/
	public function getCafeEditData( $cafeID ){
		/*
			Grab the cafe with the parent of the cafe
		*/
		$cafe = Cafe::where('id', '=', $cafeID)
								->with('brewMethods')
								->withCount('userLike')
								->with(['company' => function( $query ){
									$query->withCount('cafes');
								}])
								->where('deleted', '=', 0)
								->first();

		/*
			Return the cafe queried.
		*/
		return response()->json($cafe);
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
		$companyID = $request->get('company_id');

		if( $companyID != '' ){
			$company = Company::where('id', '=', $companyID)->first();
		}else{
			$company = new Company();

			$company->name 				= $request->get('company_name');
			$company->roaster			= $request->get('company_type') == 'roaster' ? 1 : 0;
			$company->website 		= $request->get('website');
			$company->logo 				= '';
			$company->description = '';
			$company->added_by 		= Auth::user()->id;

			$company->save();
		}

		$address 			= $request->get('address');
		$city 				= $request->get('city');
		$state 				= $request->get('state');
		$zip 					= $request->get('zip');
		$locationName = $request->get('location_name');
		$brewMethods 	= json_decode( $request->get('brew_methods') );

		$lat = Request::get('lat') != '' ? Request::get('lat') : 0;
		$lng = Request::get('lng') != '' ? Request::get('lng') : 0;

		if( $lat == 0 && $lng == 0 ){
			$coordinates = GoogleMaps::geocodeAddress( $address, $city, $state, $zip );
			$lat = $coordinates['lat'];
			$lng = $coordinates['lng'];
		}

		$cafe = new Cafe();

		$cafe->company 					= $company->id;
		$cafe->location_name 		= $locationName != null ? $locationName : '';
		$cafe->address 					= $address;
		$cafe->city 						= $city;
		$cafe->state 						= $state;
		$cafe->zip 							= $zip;
		$cafe->latitude 				= $lat;
		$cafe->longitude 				= $lng;
		$cafe->added_by 				= Auth::user()->id;
		$cafe->deleted 					= 0;

		$cafe->save();

		/*
			Attach the brew methods
		*/
		$cafe->brewMethods()->sync( $brewMethods );

		$company = Company::where('id', '=', $company->id)
											->with('cafes')
											->first();

		/*
			Return the added cafes as JSON
		*/
    return response()->json( $company, 201);
  }

	/*
	|-------------------------------------------------------------------------------
	| Edits a Cafe
	|-------------------------------------------------------------------------------
	| URL:            /api/v1/cafes/{cafeID}
	| Method:         PUT
	| Description:    Edits a cafe
	*/
	public function putEditCafe( $cafeID, EditCafeRequest $request ){
		$companyID = $request->get('company_id');

		if( $companyID != '' ){
	    $company = Company::where('id', '=', $companyID)->first();

			$company->name 				= $request->get('company_name');
	    $company->roaster			= $request->get('company_type') == 'roaster' ? 1 : 0;
	    $company->website 		= $request->get('website');
	    $company->logo 				= '';
	    $company->description = '';

			$company->save();
	  }else{
	    $company = new Company();

	    $company->name 				= $request->get('company_name');
	    $company->roaster			= $request->get('company_type') == 'roaster' ? 1 : 0;
	    $company->website 		= $request->get('website');
	    $company->logo 				= '';
	    $company->description = '';
	    $company->added_by 		= Auth::user()->id;

	    $company->save();
	  }

		$address 			= $request->get('address');
	  $city 				= $request->get('city');
	  $state 				= $request->get('state');
	  $zip 					= $request->get('zip');
	  $locationName = $request->get('location_name');
	  $brewMethods 	= json_decode( $request->get('brew_methods') );

	  $lat = Request::get('lat') != '' ? Request::get('lat') : 0;
	  $lng = Request::get('lng') != '' ? Request::get('lng') : 0;

	  if( $lat == 0 && $lng == 0 ){
	    $coordinates = GoogleMaps::geocodeAddress( $address, $city, $state, $zip );
	    $lat = $coordinates['lat'];
	    $lng = $coordinates['lng'];
	  }

		$cafe = Cafe::where('id', '=', $cafeID)->first();

		$cafe->company 					= $company->id;
	  $cafe->location_name 		= $locationName != null ? $locationName : '';
	  $cafe->address 					= $address;
	  $cafe->city 						= $city;
	  $cafe->state 						= $state;
	  $cafe->zip 							= $zip;
	  $cafe->latitude 				= $lat;
	  $cafe->longitude 				= $lng;
	  $cafe->added_by 				= Auth::user()->id;

	  $cafe->save();

		/*
	    Attach the brew methods
	  */
	  $cafe->brewMethods()->sync( $brewMethods );

	  $company = Company::where('id', '=', $company->id)
	                    ->with('cafes')
	                    ->first();

	  /*
	    Return the edited cafes as JSON
	  */
	  return response()->json( $company, 200);
	}

	/*
  |-------------------------------------------------------------------------------
  | Likes a Cafe
  |-------------------------------------------------------------------------------
  | URL:            /api/v1/cafes/{id}/like
  | Method:         POST
  | Description:    Likes a cafe for the authenticated user.
  */
	public function postLikeCafe( $cafeID ){
		/*
			Gets the cafe the user is liking
		*/
		$cafe = Cafe::where('id', '=', $cafeID)->first();

		/*
			Checks to see if the user already likes the cafe
		*/
		if( !$cafe->likes->contains( Auth::user()->id ) ){
			/*
				If the user doesn't already like the cafe, attaches the cafe to the user's
				likes
			*/
			$cafe->likes()->attach( Auth::user()->id, [
				'created_at' 	=> date('Y-m-d H:i:s'),
				'updated_at'	=> date('Y-m-d H:i:s')
				] );
		}

		/*
			Return a response that the cafe was liked successfully.
		*/
		return response()->json( ['cafe_liked' => true], 201 );
	}

	/*
  |-------------------------------------------------------------------------------
  | Un-Likes a Cafe
  |-------------------------------------------------------------------------------
  | URL:            /api/v1/cafes/{id}/like
  | Method:         DELETE
  | Description:    Un-Likes a cafe for the authenticated user.
  */
	public function deleteLikeCafe( $cafeID ){
		/*
			Gets the cafe the user is liking
		*/
		$cafe = Cafe::where('id', '=', $cafeID)->first();

		/*
			Detaches the user from the cafe's likes
		*/
		$cafe->likes()->detach( Auth::user()->id );

		/*
			Return a proper response code for successful unliking
		*/
		return response(null, 204);
	}

	/*
	|-------------------------------------------------------------------------------
	| Adds Tags To A Cafe
	|-------------------------------------------------------------------------------
	| URL:            /api/v1/cafes/{id}/tags
	| Controller:     API\CafesController@postAddTags
	| Method:         POST
	| Description:    Adds tags to a cafe for a user
	*/
	public function postAddTags( $cafeID ){
		/*
			Grabs the tags array from the request
		*/
		$tags = Request::get('tags');

		/*
			Gets the cafe
		*/
		$cafe = Cafe::where('id', '=', $cafeID)->first();

		/*
			Tags the cafe
		*/
		Tagger::tagCafe( $cafe, $tags );

		/*
			Grabs the cafe with the brew methods, user like and tags
		*/
		$cafe = Cafe::where('id', '=', $cafeID)
								->with('brewMethods')
								->with('userLike')
								->with('tags')
								->first();

		/*
			Returns the cafe response as JSON.
		*/
		return response()->json($cafe, 201);
	}

	/*
	|-------------------------------------------------------------------------------
	| Deletes A Cafe Tag
	|-------------------------------------------------------------------------------
	| URL:            /api/v1/cafes/{id}/tags/{tagID}
	| Method:         DELETE
	| Description:    Deletes a tag from a cafe for a user
	*/
	public function deleteCafeTag( $cafeID, $tagID ){
		/*
			Delete the specific users tag for the cafe.
		*/
		DB::statement('DELETE FROM cafes_users_tags WHERE cafe_id = "'.$cafeID.'" AND tag_id = "'.$tagID.'" AND user_id = "'.Auth::user()->id.'"');

		/*
			Return a proper response code for successful untagging
		*/
		return response(null, 204);
	}

	public function deleteCafe( $cafeID ){
		$cafe = Cafe::where('id', '=', $cafeID)->first();

		$cafe->deleted = 1;

		$cafe->save();

		return response()->json('', 204);
	}
}
