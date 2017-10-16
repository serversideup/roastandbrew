<?php
Route::get( '/', 'Web\AppController@getApp' )
      ->middleware('auth');

Route::get( '/login', 'Web\AppController@getLogin' )
      ->name('login')
      ->middleware('guest');
      
Route::get( '/logout', 'Web\AppController@getLogout' )
      ->name('logout');

Route::get( '/login/{social}', 'Web\AuthenticationController@getSocialRedirect' )
      ->middleware('guest');

Route::get( '/login/{social}/callback', 'Web\AuthenticationController@getSocialCallback' )
      ->middleware('guest');
