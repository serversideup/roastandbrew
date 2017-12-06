<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Auth;

class AppController extends Controller
{
	public function getApp(){
		return view('app');
	}

	public function getLogout(){
		Auth::logout();
		return redirect('/');
	}
}
