<?php
/*
  Defines the namespace for the controller
*/
namespace App\Http\Controllers\API\Admin;

/*
  Uses the controller interface
*/
use App\Http\Controllers\Controller;

/*
  Defines the requests used by the controller.
*/
use App\Http\Requests\Admin\EditCompanyRequest;

/*
  Defines the models used in the controller
*/
use App\Models\Company;

/*
  Defines the services used by the controller.
*/
use App\Services\CompanyService;
use App\Services\ActionService;

/*
  Uses the Auth facade.
*/
use Auth;
use Illuminate\Http\Request;

/**
 * Handles the retrieval, updating, and editing of companies.
 */
class CompaniesController extends Controller
{
  /*
  |-------------------------------------------------------------------------------
  | Gets All Companies
  |-------------------------------------------------------------------------------
  | URL:            /api/v1/admin/companies
  | Method:         GET
  | Description:    Gets all of the companies a user has access to.
  */
  public function getCompanies( Request $request ){
    /*
      If the user is an admin grab all of the actions that haven't been
      processed.
    */
    if( Auth::user()->permission >= 2 ){
      if( $request->has('search') ){
        $companies = Company::withCount('cafes')
                            ->withCount(['actions' => function( $query ){
                              $query->where('status', '=', 0)
                                    ->with('by');
                            }])
                            ->with('ownedBy')
                            ->where('name', 'LIKE', '%'.$request->get('search').'%')
                            ->get();
      }else{
        $companies = Company::withCount('cafes')
                            ->withCount(['actions' => function( $query ){
                              $query->where('status', '=', 0)
                                    ->with('by');
                            }])
                            ->with('ownedBy')
                            ->get();
      }
    }else{
      $companies = Company::withCount('cafes')
                          ->withCount(['actions' => function( $query ){
                            $query->where('status', '=', 0)
                                  ->with('by');
                          }])
                          ->with('ownedBy')
                          ->whereIn('id', Auth::user()->companiesOwned()->pluck('id')->toArray() )
                          ->get();
    }

    return response()->json( $companies );
  }

  /*
  |-------------------------------------------------------------------------------
  | Gets An Individual Company
  |-------------------------------------------------------------------------------
  | URL:            /api/v1/admin/companies/{id}
  | Method:         GET
  | Description:    Gets an individual company.
  */
  public function getCompany( Company $company ){
    $company = Company::with(['cafes' => function( $query ){
                            $query->withCount(['actions' => function( $query ){
                              $query->where('status', '=', 0)
                                    ->with('by');
                            }]);
                        }])
                        ->with('ownedBy')
                        ->where('id', '=', $company->id)
                        ->first();

    return response()->json( $company );
  }

  /*
  |-------------------------------------------------------------------------------
  | Updates An Individual Company
  |-------------------------------------------------------------------------------
  | URL:            /api/v1/admin/companies/{id}
  | Method:         PUT
  | Description:    Updates an individual company.
  */
  public function putUpdateCompany( Company $company, EditCompanyRequest $request ){
    /*
      Grabs the company before the updates.
    */
    $beforeCompany = Company::where( 'id', '=', $company->id )
                             ->with('ownedBy')
                             ->first();

    /*
      Updates the companies.
    */
    CompanyService::updateCompany( $company->id, $request->all() );

    /*
      Gets the updated company to return.
    */
    $updatedCompany = Company::where( 'id', '=', $company->id )
                             ->with('ownedBy')
                             ->with(['cafes' => function( $query ){
                                 $query->withCount(['actions' => function( $query ){
                                   $query->where('status', '=', 0)
                                         ->with('by');
                                 }]);
                             }])
                             ->first();

    /*
      Sets the before and after for the content.
    */
    $content['before'] = $beforeCompany;
    $content['after']  = Company::where( 'id', '=', $company->id )
                             ->with('ownedBy')
                             ->first();

    /*
      Creates an updated company action that's already approved.
    */
    ActionService::createApprovedAction( null, $company->id, 'company-updated', $content );

    /*
      Returns the updated company.
    */
    return response()->json( $updatedCompany );
  }
}
