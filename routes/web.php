<?php
/*
|-------------------------------------------------------------------------------
| Displays the home page
|-------------------------------------------------------------------------------
| URL:            /
| Controller:     Web\AppController@getApp
| Method:         GET
| Description:    Displays the homepage with the cafes listing. This kicks off
|                 the single page application.
*/
Route::get( '/', 'Web\AppController@getApp' );

/*
|-------------------------------------------------------------------------------
| Logout
|-------------------------------------------------------------------------------
| URL:            /logout
| Controller:     Web\AppController@getLogout
| Method:         GET
| Description:    Logs out the authenticated user.
*/
Route::get( '/logout', 'Web\AppController@getLogout' )
      ->name('logout');

/*
|-------------------------------------------------------------------------------
| Social Login
|-------------------------------------------------------------------------------
| URL:            /login/{social}
| Controller:     Web\AuthenticationController@getSocialRedirect
| Method:         GET
| Description:    Initializes the social login defined by the user. Can be
|                 Facebook, Google +, or Twitter.
*/
Route::get( '/login/{social}', 'Web\AuthenticationController@getSocialRedirect' )
      ->middleware('guest');

/*
|-------------------------------------------------------------------------------
| Social Login
|-------------------------------------------------------------------------------
| URL:            /login/{social}
| Controller:     Web\AuthenticationController@getSocialCallback
| Method:         GET
| Description:    Handles the callback from a social login request
*/
Route::get( '/login/{social}/callback', 'Web\AuthenticationController@getSocialCallback' )
      ->middleware('guest');
