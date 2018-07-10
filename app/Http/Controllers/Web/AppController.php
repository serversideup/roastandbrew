<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Auth;
use Request;

class AppController extends Controller
{
	public function getApp(){
		if( Request::has('ref') ){
			return redirect('/');
		}
		return view('app');
	}

	public function getLogout(){
		Auth::logout();
		return redirect('/');
	}
}
