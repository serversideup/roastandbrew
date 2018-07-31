<?php

/*
	Defines the namespace for the controller.
*/
namespace App\Http\Controllers\Web;

/*
	Uses the controller interface.
*/
use App\Http\Controllers\Controller;

/*
	Defines the facades used by the controller.
*/
use Auth;
use Request;

/**
 * The app controller loads up the application.
 */
class AppController extends Controller
{
	/**
	 * Gets the view that displays the app.
	 */
	public function getApp(){
		/*
			If the request has a ref variable, redirect to the
			homepage. This is so the SPA doesn't break.
		*/
		if( Request::has('ref') ){
			return redirect('/');
		}

		/*
			Return the view
		*/
		return view('app');
	}

	/**
	 * Logs out the user and redirects them home.
	 */
	public function getLogout(){
		Auth::logout();
		return redirect('/');
	}
}
