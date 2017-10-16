<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

use App\Models\User;
use Auth;

class UsersController extends Controller
{
  /*
  |-------------------------------------------------------------------------------
  | Get User
  |-------------------------------------------------------------------------------
  | URL:            /api/v1/user
  | Method:         GET
  | Description:    Gets the authenticated user
  */
  public function getUser(){
    return Auth::user();
  }
}
