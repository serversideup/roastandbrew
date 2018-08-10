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
  Defines the models used in the controller
*/
use App\Models\Company;
use App\Models\Cafe;


/*
  Uses the Auth facade.
*/
use Auth;

/*
  Defines the requests used by the controller.
*/
use App\Http\Requests\Admin\EditCafeRequest;

/*
  Defines the services used by the controller.
*/
use App\Services\CafeService;
use App\Services\ActionService;

/**
 * Handles the retrieval, updating, and editing of cafes.
 */
class CafesController extends Controller
{
  /*
  |-------------------------------------------------------------------------------
  | Gets An Individual Cafe
  |-------------------------------------------------------------------------------
  | URL:            /api/v1/admin/companies/{id}/cafes/{id}
  | Method:         GET
  | Description:    Gets an individual cafe
  */
  public function getCafe( Company $company, Cafe $cafe ){
    $cafe = Cafe::where('id', '=', $cafe->id)
                ->with(['actions' => function( $query ){
                  $query->orderBy('created_at', 'DESC');
                }])
                ->with('brewMethods')
                ->withCount( 'likes' )
                ->first();

    return response()->json( $cafe );
  }

  /*
  |-------------------------------------------------------------------------------
  | Updates An Individual Cafe
  |-------------------------------------------------------------------------------
  | URL:            /api/v1/admin/companies/{id}/cafes/{id}
  | Method:         PUT
  | Description:    Submits admin side updates for an individual cafe.
  */
  public function putUpdateCafe( Company $company, Cafe $cafe, EditCafeRequest $request ){
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
    ActionService::createApprovedAction( $cafe->id, $cafe->company_id, 'cafe-updated', $content );

    $updatedCafe = CafeService::editCafe( $cafe->id, $request->all(), true );

    $cafe = Cafe::where('id', '=', $cafe->id)
                ->with(['actions' => function( $query ){
                  $query->orderBy('created_at', 'DESC');
                }])
                ->with('brewMethods')
                ->withCount( 'likes' )
                ->first();

    return response()->json( $cafe );
  }
}
