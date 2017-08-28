<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

class AppController extends Controller
{
	public function getApp(){
		return view('app');
	}

	public function getLogin(){
		return view('login');
	}
}
