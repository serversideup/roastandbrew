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

use \Cviebrock\EloquentSluggable\Services\SlugService;

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
  | URL:            /api/v1/cafes/{slug}
  | Method:         GET
  | Description:    Gets an individual cafe
  | Parameters:
  |   $slug   -> Unique Slug of the cafe we are retrieving
  */
  public function getCafe( $slug ){
    $cafe = Cafe::where('slug', '=', $slug)
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
	| URL:            /api/v1/cafes/slug}/edit
	| Method:         GET
	| Description:    Gets an individual cafe's edit data
	| Parameters:
	|   $slug  -> Unique slug of the cafe we are retrieving
	*/
	public function getCafeEditData( $slug ){
		/*
			Grab the cafe with the parent of the cafe
		*/
		$cafe = Cafe::where('slug', '=', $slug)
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

		$cafe->company_id 					= $company->id;

		$cafe->slug 						= SlugService::createSlug(Cafe::class, 'slug', $company->name.' '.$locationName.' '.$address.' '.$city.' '.$state);
		$cafe->location_name 		= $locationName != null ? $locationName : '';
		$cafe->address 					= $address;
		$cafe->city 						= $city;
		$cafe->state 						= $state;
		$cafe->zip 							= $zip;
		$cafe->latitude 				= $lat;
		$cafe->longitude 				= $lng;
		$cafe->added_by 				= Auth::user()->id;
		$cafe->tea 							= $request->has('tea') ? $request->get('tea') : 0;
		$cafe->matcha 					= $request->has('matcha') ? $request->get('matcha') : 0;
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
	| URL:            /api/v1/cafes/{slug}
	| Method:         PUT
	| Description:    Edits a cafe
	*/
	public function putEditCafe( $slug, EditCafeRequest $request ){
		$companyID = $request->get('company_id');

		if( $companyID != '' ){
	    $company = Company::where('id', '=', $companyID)->first();

			if( $request->has('company_name') ){
				$company->name 				= $request->get('company_name');
			}

			if( $request->has('company_type') ){
	    	$company->roaster			= $request->get('company_type') == 'roaster' ? 1 : 0;
			}

			if( $request->has('website') ){
				$company->website 		= $request->get('website');
			}

	    $company->logo 				= '';
	    $company->description = '';

			$company->save();
	  }else{
	    $company = new Company();

			if( $request->has('company_name') ){
	    	$company->name 				= $request->get('company_name');
			}

			if( $request->has('company_type') ){
	    	$company->roaster			= $request->get('company_type') == 'roaster' ? 1 : 0;
			}else{
				$company->roaster 		= 0;
			}

			if( $request->has('website') ){
				$company->website 		= $request->get('website');
			}

	    $company->logo 				= '';
	    $company->description = '';
	    $company->added_by 		= Auth::user()->id;

	    $company->save();
	  }

		$cafe = Cafe::where('slug', '=', $slug)->first();

		if( $request->has('address') ){
			$address = $request->get('address');
		}else{
			$address = $cafe->address;
		}

		if( $request->has('city') ){
			$city = $request->get('city');
		}else{
			$city = $cafe->city;
		}

		if( $request->has('state') ){
			$state = $request->get('state');
		}else{
			$state = $cafe->state;
		}

		if( $request->has('zip') ){
			$zip = $request->get('zip');
		}else{
			$zip = $cafe->zip;
		}

		if( $request->has('location_name') ){
			$locationName = $request->get('location_name');
		}else{
			$locationName = $cafe->location_name;
		}

		if( $request->has('brew_methods') ){
	  	$brewMethods 	= json_decode( $request->get('brew_methods') );
		}

	  $lat = Request::get('lat') != '' ? Request::get('lat') : 0;
	  $lng = Request::get('lng') != '' ? Request::get('lng') : 0;

	  if( $lat == 0 && $lng == 0 ){
	    $coordinates = GoogleMaps::geocodeAddress( $address, $city, $state, $zip );
	    $lat = $coordinates['lat'];
	    $lng = $coordinates['lng'];
	  }


		$cafe->company_id 			= $company->id;
	  $cafe->location_name 		= $locationName != null ? $locationName : '';
	  $cafe->address 					= $address;
	  $cafe->city 						= $city;
	  $cafe->state 						= $state;
	  $cafe->zip 							= $zip;
	  $cafe->latitude 				= $lat;
	  $cafe->longitude 				= $lng;
	  $cafe->added_by 				= Auth::user()->id;

		if( $request->has('matcha') ){
			$cafe->matcha = $request->get('matcha');
		}

		if( $request->has('tea') ){
			$cafe->tea = $request->get('tea');
		}

	  $cafe->save();

		if( $request->has('brew_methods') ){
			/*
		    Attach the brew methods
		  */
		  $cafe->brewMethods()->sync( $brewMethods );
		}

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
  | URL:            /api/v1/cafes/{slug}/like
  | Method:         POST
  | Description:    Likes a cafe for the authenticated user.
  */
	public function postLikeCafe( $slug ){
		/*
			Gets the cafe the user is liking
		*/
		$cafe = Cafe::where('slug', '=', $slug)->first();

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
  | URL:            /api/v1/cafes/{slug}/like
  | Method:         DELETE
  | Description:    Un-Likes a cafe for the authenticated user.
  */
	public function deleteLikeCafe( $slug ){
		/*
			Gets the cafe the user is liking
		*/
		$cafe = Cafe::where('slug', '=', $slug)->first();

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
	| URL:            /api/v1/cafes/{slug}/tags
	| Controller:     API\CafesController@postAddTags
	| Method:         POST
	| Description:    Adds tags to a cafe for a user
	*/
	public function postAddTags( $slug ){
		/*
			Grabs the tags array from the request
		*/
		$tags = Request::get('tags');

		/*
			Gets the cafe
		*/
		$cafe = Cafe::where('slug', '=', $slug)->first();

		/*
			Tags the cafe
		*/
		Tagger::tagCafe( $cafe, $tags );

		/*
			Grabs the cafe with the brew methods, user like and tags
		*/
		$cafe = Cafe::where('slug', '=', $slug)
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
	| URL:            /api/v1/cafes/{slug}/tags/{tagID}
	| Method:         DELETE
	| Description:    Deletes a tag from a cafe for a user
	*/
	public function deleteCafeTag( $slug, $tagID ){
		$cafe = Cafe::where('slug', '=', $slug)->first();
		/*
			Delete the specific users tag for the cafe.
		*/
		DB::statement('DELETE FROM cafes_users_tags WHERE cafe_id = "'.$cafe->id.'" AND tag_id = "'.$tagID.'" AND user_id = "'.Auth::user()->id.'"');

		/*
			Return a proper response code for successful untagging
		*/
		return response(null, 204);
	}

	public function deleteCafe( $slug ){
		$cafe = Cafe::where('slug', '=', $slug)->first();

		$cafe->deleted = 1;

		$cafe->save();

		return response()->json('', 204);
	}
}
