<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

use App\Models\BrewMethod;

class BrewMethodsController extends Controller
{
  public function getBrewMethods(){
    $brewMethods = BrewMethod::withCount('cafes')->get();

    return response()->json( $brewMethods );
  }
}
