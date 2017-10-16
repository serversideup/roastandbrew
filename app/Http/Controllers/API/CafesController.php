<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

use App\Models\Cafe;
use Request;

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
  public function postNewCafe(){
    $cafe = new Cafe();

    $cafe->name     = Request::get('name');
    $cafe->address  = Request::get('address');
    $cafe->city     = Request::get('city');
    $cafe->state    = Request::get('state');
    $cafe->zip      = Request::get('zip');

    $cafe->save();

    return response()->json($cafe, 201);
  }
}
