<?php
/*
  Defines the namespace for the controller.
*/
namespace App\Http\Controllers\API;

/*
  Uses the controller interface.
*/
use App\Http\Controllers\Controller;

/*
  Defines the models used by the controller.
*/
use App\Models\Tag;

/*
  Defines the facades used by the controller.
*/
use Request;

/**
 * Handles all of the tagging operations in the application for cafes.
 */
class TagsController extends Controller
{
  /**
   * Searches the tags database
   */
  public function getTags(){
    $query = Request::get('search');

    /*
      If the query is not set or is empty, load all the tags.
      Otherwise load the tags that match the query
    */
    if( $query == null || $query == '' ){
      $tags = Tag::all();
    }else{
      $tags = Tag::where('tag', 'LIKE', '%'.$query.'%')->get();
    }

    /*
      Return all of the tags as JSON
    */
    return response()->json( $tags );
  }
}
