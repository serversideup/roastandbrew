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
use App\Models\CafeAction;

/*
  Defines the servies used in the controller
*/
use App\Services\CafeService;
use App\Services\CafeActionService;

/*
  Uses the Auth facade.
*/
use Auth;

/**
 * Handles the retrieval, approval, and denial of actions
 */
class ActionsController extends Controller
{
  /**
   * Gets all of the unprocessed actions for a user
   * URL: /api/v1/admin/actions
   * Method: GET
   */
  public function getActions(){
    /*
      If the user is an admin grab all of the actions that haven't been
      processed.
    */
    if( Auth::user()->permission >= 2 ){
      $actions = CafeAction::with(['cafe' => function( $query ){
                                $query->with('brewMethods');
                            }])
                            ->with('company')
                            ->where('status', '=', 0)
                            ->with('by')
                            ->get();
    }else{
      /*
        Geta all of the un processed actions owned by the user.
      */
      $actions = CafeAction::with(['cafe' => function( $query ){
                                $query->with('brewMethods');
                            }])
                           ->with('company')
                           ->whereIn('company_id', Auth::user()->companiesOwned()->pluck('id')->toArray())
                           ->where('status', '=', 0)
                           ->with('by')
                           ->get();
    }

    return response()->json( $actions );
  }

  /**
   * Approves an action for a user
   * URL: /api/v1/admin/actions/{action}/approve
   * Method: PUT
   *
   * @param \App\Models\CafeAction $action
   */
  public function putApproveAction( CafeAction $action ){
    /*
      Determine the proper action based on action type.
    */
    switch( $action->type ){
      case 'cafe-added':
        /*
          Unserialize the new cafe data
        */
        $newCafeActionData = json_decode( $action->content, true );

        /*
          Add the cafe
        */
        CafeService::addCafe( $newCafeActionData, $action->user_id );

        /*
          Approve the action
        */
        CafeActionService::approveAction( $action );
      break;
      case 'cafe-updated':
        /*
          Unserialize the content.
        */
        $cafeActionData = json_decode( $action->content, true );

        /*
          Get the updated data for the cafe.
        */
        $updatedCafeActionData = $cafeActionData['after'];

        /*
          Apply updates to the cafe
        */
        CafeService::editCafe( $action->cafe_id, $updatedCafeActionData );

        /*
          Approve the action
        */
        CafeActionService::approveAction( $action );
      break;
      case 'cafe-deleted':
        /*
          Grab the cafe and flag it as deleted.
        */
        $cafe = $cafe = Cafe::where('id', '=', $action->cafe_id)->first();

        $cafe->deleted = 1;
        $cafe->save();

        /*
          Approve the action
        */
        CafeActionService::approveAction( $action );
      break;
    }

    /*
      Returns a successful no content response.
    */
    return response()->json('', 204);
  }

  /**
   * Denies an action for the user.
   * URL: /api/v1/admin/actions/{action}/deny
   * Method: PUT
   *
   * @param \App\Models\CafeAction $action
   */
  public function putDenyAction( CafeAction $action ){
    /*
      Denies the action
    */
    CafeActionService::denyAction( $action );

    /*
      Returns a successful no content response.
    */
    return response()->json('', 204);
  }
}
