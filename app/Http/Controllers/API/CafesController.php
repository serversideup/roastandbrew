<?php
/*
	Defines the namespace for the controller.
*/
namespace App\Http\Controllers\API;

/*
	Uses the controller interface
*/
use App\Http\Controllers\Controller;

/*
	Defines the models used by the controller.
*/
use App\Models\Company;
use App\Models\Cafe;
use App\Models\CafePhoto;
use App\Models\CafeAction;

/*
	Defines the utilities used by the controller.
*/
use App\Utilities\Tagger;

/*
	Defines the services used by the controller.
*/
use App\Services\CafeService;
use App\Services\CafeActionService;

/*
	Defines the requests used by the controller.
*/
use App\Http\Requests\StoreCafeRequest;
use App\Http\Requests\EditCafeRequest;

/*
	Defines the facades used by the controller.
*/
use Request;
use Auth;
use DB;
use File;

/**
 * Defines the Cafes Controller. This handles all the routes
 * relating to the cafes.
 */
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


		/*
			If the cafe is not null, return the cafe otherwise return
			a 404 error.
		*/
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
			$cafe = CafeService::addCafe( $request->all(), Auth::user()->id );

			/*
				Create an already processed and approved action for the
				user since they have permission.
			*/
			CafeActionService::createApprovedAction( null, $cafe->company_id, 'cafe-added', $request->all() );

			/*
				Grab the company to return
			*/
			$company = Company::where('id', '=', $cafe->company_id)
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
			CafeActionService::createPendingAction( null, $request->get('company_id'), 'cafe-added', $request->all() );

			/*
				Return the flag that the cafe addition is pending
			*/
			return response()->json( ['cafe_add_pending' => $request->get('company_name') ], 202 );
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
		$cafe = Cafe::where('slug', '=', $slug)->with('brewMethods')->first();

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
			CafeActionService::createApprovedAction( $cafe->id, $cafe->company_id, 'cafe-updated', $content );

			$updatedCafe = CafeService::editCafe( $cafe->id, $request->all() );

			/*
				Load the company and return it.
			*/
		  $company = Company::where('id', '=', $updatedCafe->company_id)
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
			CafeActionService::createPendingAction( $cafe->id, $cafe->company_id, 'cafe-updated', $content );

			/*
				Return a flag for cafe updates pending
			*/
			return response()->json( ['cafe_updates_pending' => $request->get('company_name') ], 202 );
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
			CafeActionService::createApprovedAction( $cafe->id, $cafe->company_id, 'cafe-deleted', '' );

			return response()->json('', 204);
		}else{
			/*
				Get the cafe to create the action.
			*/
			$cafe = Cafe::where('slug', '=', $slug)->with('company')->first();

			/*
				Creates an action that tracks and approves a cafe deletion.
			*/
			CafeActionService::createPendingAction( $cafe->id, $cafe->company_id, 'cafe-deleted', '' );

			/*
				Return the cafe delete pending
			*/
			return response()->json( ['cafe_delete_pending' => $cafe->company->name ], 202 );
		}
	}
}
