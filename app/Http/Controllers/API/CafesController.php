<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

use App\Models\Company;
use App\Models\Cafe;
use App\Models\CafePhoto;
use App\Models\CafeAction;

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
		/*
			Gets company that's adding the cafe
		*/
		$companyID = $request->get('company_id');

		/*
			Get the company. If its null, create a new company otherwise
			set to the company that exists.
		*/
		$company = Company::where('id', '=', $companyID)->first();
		$company = $company == null ? new Company() : $company;

		/*
			Determines if the user can create a cafe or not.
			If the user can create a cafe, then we let them otherwise
			we create an add cafe action.
		*/
		if( Auth::user()->can('create', [ Cafe::class, $company ] ) ){
			/*
				Grabs the company ID.
			*/
			$companyID = $request->get('company_id');

			/*
				If the company exists, load the company. If the company
				does not exist, create a new company with what was
				sent from the user.
			*/
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

			/*
				Grab all of the new cafe data
			*/
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

			/*
				Create a new cafe
			*/
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

			/*
				Create an already processed and approved action for the
				user since they have permission.
			*/
			$cafeAction 						= new CafeAction();

			$cafeAction->user_id				= Auth::user()->id;
			$cafeAction->status 				= 1;
			$cafeAction->type 					= 'cafe-added';
			$cafeAction->content 				= json_encode( $request->all() );
			$cafeAction->processed_by		= Auth::user()->id;
			$cafeAction->processed_on 	= date('Y-m-d H:i:s', time() );

			$cafeAction->save();

			/*
				Grab the company to return
			*/
			$company = Company::where('id', '=', $company->id)
												->with('cafes')
												->first();

			/*
				Return the added cafes as JSON
			*/
			return response()->json( $company, 201);
		}else{
			/*
				Create a new cafe action and save all of the data
				that the user has provided
			*/
			$cafeAction 						= new CafeAction();

			$cafeAction->user_id		= Auth::user()->id;
			$cafeAction->status 		= 0;
			$cafeAction->type 			= 'cafe-added';
			$cafeAction->content 		= json_encode( $request->all() );

			$cafeAction->save();

			/*
				Return the flag that the cafe addition is pending
			*/
			return response()->json( ['cafe_add_pending' => $request->get('company_name') ] );
		}
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
		/*
			Grab the cafe to be edited.
		*/
		$cafe = Cafe::where('slug', '=', $slug)->first();

		/*
			Confirms user can edit the cafe through the Cafes Policy
		*/
		if( Auth::user()->can('update', $cafe ) ){
			/*
				Get the company ID to check and see if the company
				exists.
			*/
			$companyID = $request->get('company_id');

			/*
				Set the before cafe to the data that was existing,
				and the after to what was set.
			*/
			$content['before'] 			= $cafe;
			$content['after'] 			= $request->all();

			/*
				Create a new cafe action and save the action for an
				admin to approve.
			*/
			$cafeAction 						= new CafeAction();

			$cafeAction->cafe_id 				= $cafe->id;
			$cafeAction->user_id				= Auth::user()->id;
			$cafeAction->status 				= 1;
			$cafeAction->type 					= 'cafe-updated';
			$cafeAction->content 				= json_encode( $content );
			$cafeAction->processed_by		= Auth::user()->id;
			$cafeAction->processed_on 	= date('Y-m-d H:i:s', time() );

			$cafeAction->save();

			/*
				If the company ID is not empty, load the company being
				edited.
			*/
			if( $companyID != '' ){
				/*
					Company we are updating the content for
				*/
		    $company = Company::where('id', '=', $companyID)->first();

				/*
					If the request has a company name, update the company name.
				*/
				if( $request->has('company_name') ){
					$company->name 				= $request->get('company_name');
				}

				/*
					If the request has a company type, update the company type.
				*/
				if( $request->has('company_type') ){
		    	$company->roaster			= $request->get('company_type') == 'roaster' ? 1 : 0;
				}

				/*
					If the request has a website, update the website.
				*/
				if( $request->has('website') ){
					$company->website 		= $request->get('website');
				}

		    $company->logo 				= '';
		    $company->description = '';

				/*
					Save the company
				*/
				$company->save();
		  }else{
				/*
					Create a new company
				*/
		    $company = new Company();

				/*
					If the request has a company name, update the company name.
				*/
				if( $request->has('company_name') ){
		    	$company->name 				= $request->get('company_name');
				}

				/*
					If the request has a company type, add the type but default to not a roaster.
				*/
				if( $request->has('company_type') ){
		    	$company->roaster			= $request->get('company_type') == 'roaster' ? 1 : 0;
				}else{
					$company->roaster 		= 0;
				}

				/*
					If the request has a website, add the company website.
				*/
				if( $request->has('website') ){
					$company->website 		= $request->get('website');
				}

		    $company->logo 				= '';
		    $company->description = '';
		    $company->added_by 		= Auth::user()->id;

				/*
					Save the company.
				*/
		    $company->save();
		  }

			/*
				Grab the cafe we are updating.
			*/
			$cafe = Cafe::where('slug', '=', $slug)->first();

			/*
				If the request has an address, update the address or
				using the existing address
			*/
			if( $request->has('address') ){
				$address = $request->get('address');
			}else{
				$address = $cafe->address;
			}

			/*
				If the request has an city, update the city or
				using the existing city
			*/
			if( $request->has('city') ){
				$city = $request->get('city');
			}else{
				$city = $cafe->city;
			}

			/*
				If the request has an city, update the city or
				using the existing city
			*/
			if( $request->has('state') ){
				$state = $request->get('state');
			}else{
				$state = $cafe->state;
			}

			/*
				If the request has an zip, update the zip or
				using the existing zip
			*/
			if( $request->has('zip') ){
				$zip = $request->get('zip');
			}else{
				$zip = $cafe->zip;
			}

			/*
				If the request has an location name, update the location name or
				using the existing location name
			*/
			if( $request->has('location_name') ){
				$locationName = $request->get('location_name');
			}else{
				$locationName = $cafe->location_name;
			}

			/*
				If the request has brew methods, decode and set to the brew methods
				variable.
			*/
			if( $request->has('brew_methods') ){
		  	$brewMethods 	= json_decode( $request->get('brew_methods') );
			}

			/*
				Grab the lat and lng from the request
			*/
		  $lat = Request::get('lat') != '' ? Request::get('lat') : 0;
		  $lng = Request::get('lng') != '' ? Request::get('lng') : 0;

			/*
				If needed, update the latitude and longitude if not set.
			*/
		  if( $lat == 0 && $lng == 0 ){
		    $coordinates = GoogleMaps::geocodeAddress( $address, $city, $state, $zip );
		    $lat = $coordinates['lat'];
		    $lng = $coordinates['lng'];
		  }

			/*
				Update all of the cafe data to the new data.
			*/
			$cafe->company_id 			= $company->id;
		  $cafe->location_name 		= $locationName != null ? $locationName : '';
		  $cafe->address 					= $address;
		  $cafe->city 						= $city;
		  $cafe->state 						= $state;
		  $cafe->zip 							= $zip;
		  $cafe->latitude 				= $lat;
		  $cafe->longitude 				= $lng;
		  $cafe->added_by 				= Auth::user()->id;

			/*
				If the request has matcha, apply the matcha flag.
			*/
			if( $request->has('matcha') ){
				$cafe->matcha = $request->get('matcha');
			}

			/*
				If the request has tea, apply the tea flag.
			*/
			if( $request->has('tea') ){
				$cafe->tea = $request->get('tea');
			}

			/*
				Save the cafe
			*/
		  $cafe->save();

			/*
				If the request has brew methods, sync the brew methods to what has
				been updated
			*/
			if( $request->has('brew_methods') ){
				/*
			    Attach the brew methods
			  */
			  $cafe->brewMethods()->sync( $brewMethods );
			}

			/*
				Load the company and return it.
			*/
		  $company = Company::where('id', '=', $company->id)
		                    ->with('cafes')
		                    ->first();

		  /*
		    Return the edited cafes as JSON
		  */
		  return response()->json( $company, 200);
		}else{
			/*
				Grab the cafe being updated
			*/
			$cafe = Cafe::where('slug', '=', $slug)->with('company')->first();

			/*
				Set the before cafe to the data that was existing,
				and the after to what was set.
			*/
			$content['before'] 			= $cafe;
			$content['after'] 			= $request->all();

			/*
				Create a new cafe action and save the action for an
				admin to approve.
			*/
			$cafeAction 						= new CafeAction();

			$cafeAction->cafe_id 		= $cafe->id;
			$cafeAction->user_id		= Auth::user()->id;
			$cafeAction->status 		= 0;
			$cafeAction->type 			= 'cafe-updated';
			$cafeAction->content 		= json_encode( $content );

			$cafeAction->save();

			/*
				Return a flag for cafe updates pending
			*/
			return response()->json( ['cafe_updates_pending' => $request->get('company_name') ] );
		}
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

	/*
  |-------------------------------------------------------------------------------
  | Deletes A Cafe
  |-------------------------------------------------------------------------------
  | URL:            /api/v1/cafes/{slug}
  | Method:         DELETE
  | Description:    Deletes a cafe
  */
	public function deleteCafe( $slug ){
		/*
			Grabs the Cafe to be deleted
		*/
		$cafe = Cafe::where('slug', '=', $slug)->first();

		/*
			Checks if the user can delete the cafe through
			our CafePolicy.
		*/
		if( Auth::user()->can('delete', $cafe ) ){
			$cafe->deleted = 1;

			$cafe->save();

			/*
				Creates an action that tracks and approves a cafe deletion.
			*/
			$cafeAction 							= new CafeAction();

			$cafeAction->cafe_id 			= $cafe->id;
			$cafeAction->user_id			= Auth::user()->id;
			$cafeAction->status 			= 1;
			$cafeAction->type 				= 'cafe-deleted';
			$cafeAction->content 			= '';
			$cafeAction->processed_by	= Auth::user()->id;
			$cafeAction->processed_on = date('Y-m-d H:i:s', time() );

			$cafeAction->save();

			return response()->json('', 204);
		}else{
			/*
				Get the cafe to create the action.
			*/
			$cafe = Cafe::where('slug', '=', $slug)->with('company')->first();

			/*
				Creates an action that tracks and approves a cafe deletion.
			*/
			$cafeAction 						= new CafeAction();

			$cafeAction->cafe_id 		= $cafe->id;
			$cafeAction->user_id		= Auth::user()->id;
			$cafeAction->status 		= 0;
			$cafeAction->type 			= 'cafe-deleted';
			$cafeAction->content 		= '';

			$cafeAction->save();

			/*
				Return the cafe delete pending
			*/
			return response()->json( ['cafe_delete_pending' => $cafe->company->name ] );
		}
	}
}
