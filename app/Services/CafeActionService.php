<?php
/*
  Defines the service namespace.
*/
namespace App\Services;

/*
  Defines the models used by the service.
*/
use App\Models\CafeAction;

/*
  Defines the facades used by the service.
*/
use Auth;

/**
 * Defines the Cafe Action Service.
 */
class CafeActionService{
  /**
   * Creates a pending action.
   *
   * @param int $cafeID   ID of the cafe we are creating the action for.
   * @param int $companyID  ID of the company we are creating the action for.
   * @param string $type  Type of action being created.
   * @param string $content  Data content for the action
   */
  public static function createPendingAction( $cafeID, $companyID, $type, $content ){
    $cafeAction 						= new CafeAction();

    $cafeAction->cafe_id 		= $cafeID;
    $cafeAction->company_id = $companyID;
    $cafeAction->user_id		= Auth::user()->id;
    $cafeAction->status 		= 0;
    $cafeAction->type 			= $type;
    $cafeAction->content 		= json_encode( $content );

    $cafeAction->save();
  }

  /**
   * Creates an approved action.
   *
   * @param int $cafeID   ID of the cafe we are creating the action for.
   * @param int $companyID  ID of the company we are creating the action for.
   * @param string $type  Type of action being created.
   * @param string $content  Data content for the action
   */
  public static function createApprovedAction( $cafeID, $companyID, $type, $content ){
    $cafeAction = new CafeAction();

    $cafeAction->cafe_id 				= $cafeID;
    $cafeAction->company_id 		= $companyID;
    $cafeAction->user_id				= Auth::user()->id;
    $cafeAction->status 				= 1;
    $cafeAction->type 					= $type;
    $cafeAction->content 				= json_encode( $content );
    $cafeAction->processed_by		= Auth::user()->id;
    $cafeAction->processed_on 	= date('Y-m-d H:i:s', time() );

    $cafeAction->save();
  }

  /**
   * Approves an action
   *
   * @param \App\Models\CafeAction $action  Action being approved.
   */
  public static function approveAction( $action ){
    /*
      Approve the action
    */
    $action->status = 1;
    $action->processed_by = Auth::user()->id;
    $action->processed_on = date('Y-m-d H:i:s', time() );

    $action->save();
  }

  /**
   * Denies an action
   *
   * @param \App\Models\CafeAction $action  Action being denied.
   */
  public static function denyAction( $action ){
    $action->status = 2;
    $action->processed_by = Auth::user()->id;
    $action->processed_on = date('Y-m-d H:i:s', time() );

    $action->save();
  }
}
