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
use App\Models\Company;

/*
  Defines the facades used by the controller.
*/
use Request;
use Auth;

/**
 * The companies controller handles all routes pertaining to the
 * copmanies.
 */
class CompaniesController extends Controller{
  /**
   * Searches for a specific company in the database.
   */
  public function getCompanySearch(){
    /*
      Grabs the search term.
    */
    $term = Request::get('search');

    /*
      Search for the company and grab the count of the cafes.
    */
    $companies = Company::where('name', 'LIKE', '%'.$term.'%')
                        ->withCount('cafes')
                        ->get();

    /*
      Return the companies found as JSON.
    */
    return response()->json( ['companies' => $companies ] );
  }
}
