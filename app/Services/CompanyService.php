<?php
/*
  Defines the namespace of the service
*/
namespace App\Services;

/*
  Defines the models used by the service.
*/
use App\Models\Company;
use App\Models\Cafe;
use App\Models\User;

/*
  Defines the facades used by the service.
*/
use Auth;

/**
 * Defines the company service.
 */
class CompanyService{

  /**
   * Updates a company in the database.
   *
   * @param int   $id  ID of the company being updated
   * @param array $data Array of the data containing the updates.
   */
  public static function updateCompany( $id, $data ){
    $company = Company::where( 'id', '=', $id )->first();

    /*
      If the user updates the cafe name, save the name.
    */
    if( isset( $data['name'] ) ){
      $company->name = $data['name'];
    }

    /*
      If the user updates the cafe type save the type.
    */
    if( isset( $data['type'] ) ){
      $company->roaster = $data['type'] == 'roaster' ? 1 : 0;
    }

    /*
      If the user updates the website, save the updates.
    */
    if( isset( $data['website'] ) ){
      $company->website = $data['website'];
    }

    /*
      If the owners are updated and the user has permission
      to update the owners, then run the update.
    */
    if( Auth::user()->can('updateOwners', $company ) ){
      if( isset( $data['owners'] ) ){
        /*
          Get the current owner ids.
        */
        $currentOwners = $company->ownedBy->pluck('id')->toArray();

        /*
          Get all the ids of the updated owners.
        */
        $updatedOwners = array();

        foreach( $data['owners'] as $owner ){
          array_push( $updatedOwners, $owner['id'] );
        }

        /*
          Check to see if any of the current owners have been removed.
          Owner, Admin, and Super admin can do this.
        */
        foreach( $currentOwners as $currentOwner ){
          if( !in_array( $currentOwner, $updatedOwners ) ){
            $company->ownedBy()->detach( $currentOwner );

            self::removeOwnerPermission( $currentOwner );
          }
        }

        /*
          Find out if an owner has been added to the company. They are
          added if they aren't in the current owners array.
        */
        $newOwners = array();

        foreach( $updatedOwners as $owner ){
          if( !in_array( $owner, $currentOwners ) ){
            $company->ownedBy()->attach( $owner );

            /*
              Get the new owner from the database.
            */
            $user = User::where('id', '=', $owner)->first();

            /*
              If the new owner has a permission of 0, update them
              to a permission of 1. We only do this if they have 0 because
              an admin could be an owner and we don't want to down grade them.
            */
            if( $user->permission == 0 ){
              $user->permission = 1;

              $user->save();
            }
          }
        }
      }
    }

    /*
      If the user deletes the company, flag the company as deleted
      and flag the cafes as deleted.
    */
    if( isset( $data['deleted'] ) ){
      $company->deleted = $data['deleted'];

      self::flagCafesDeleted( $id );
    }

    /*
      Save the company
    */
    $company->save();

    return $company;
  }

  /**
   * Updates all of a company's cafes as deleted.
   *
   * @param int $companyID  ID of the company being deleted.
   */
  private static function flagCafesDeleted( $companyID ){
    /*
      Get all cafes belonging to the company.
    */
    $cafes = Cafe::where('company_id', '=', $companyID)->get();

    /*
      Iterate over all cafes belonging to the company and flag
      them as deleted.
    */
    foreach( $cafes as $cafe ){
      $cafe->deleted = 1;
      $cafe->save();
    }
  }

  /**
   * Removes owner permission level from removed users.
   *
   * @param int $userID   ID of he user removed from the company
   */
   private static function removeOwnerPermission( $userID ){
     /*
        Grabs the user with the count of the companies owned.
     */
     $user = User::where('id', '=', $userID)
                 ->withCount('companiesOwned')
                 ->first();

     /*
      If the user has 0 owned companies and their current permission level
      is 1, set them back to 0. We do this only for 1 because we don't want
      to strip admin privileges.
     */
     if( $user->companies_owned_count == 0 && $user->permission == 1 ){
       $user->permission = 0;

       $user->save();
     }
   }
}
