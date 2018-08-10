<?php
/*
  Defines the service namespace.
*/
namespace App\Services;

/*
  Defines the models used by the service.
*/
use App\Models\Action;

/*
  Defines the facades used by the service.
*/
use Auth;

/**
 * Defines the Action Service.
 */
class ActionService{
  /**
   * Creates a pending action.
   *
   * @param int $cafeID   ID of the cafe we are creating the action for.
   * @param int $companyID  ID of the company we are creating the action for.
   * @param string $type  Type of action being created.
   * @param string $content  Data content for the action
   */
  public static function createPendingAction( $cafeID, $companyID, $type, $content ){
    $action 						= new Action();

    $action->cafe_id 		= $cafeID;
    $action->company_id = $companyID;
    $action->user_id		= Auth::user()->id;
    $action->status 		= 0;
    $action->type 			= $type;
    $action->content 		= json_encode( $content );

    $action->save();
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
    $action = new Action();

    $action->cafe_id 				= $cafeID;
    $action->company_id 		= $companyID;
    $action->user_id				= Auth::user()->id;
    $action->status 				= 1;
    $action->type 					= $type;
    $action->content 				= json_encode( $content );
    $action->processed_by		= Auth::user()->id;
    $action->processed_on 	= date('Y-m-d H:i:s', time() );

    $action->save();
  }

  /**
   * Approves an action
   *
   * @param \App\Models\Action $action  Action being approved.
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
   * @param \App\Models\Action $action  Action being denied.
   */
  public static function denyAction( $action ){
    $action->status = 2;
    $action->processed_by = Auth::user()->id;
    $action->processed_on = date('Y-m-d H:i:s', time() );

    $action->save();
  }
}
