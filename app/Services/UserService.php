<?php
/*
  Defines the namespace of the service
*/
namespace App\Services;

/*
  Defines the models used by the service.
*/
use App\Models\Company;
use App\Models\User;

/*
  Defines the facades used by the service.
*/
use Auth;

/**
 * Defines the user service
 */
class UserService{
  /**
   * Updates a user
   * @param App\Models\User $user   User being updated.
   */
   public static function updateUser( $user, $data ){
     /*
      Checks to see if we are udpating the permissions
     */
     if( isset( $data['permission'] ) ){
       /*
        Checks to see if adding an admin is allowed by
        the authenticated user.
       */
       if( $data['permission'] == 2 && Auth::user()->can('addAdmins', $user ) ){
         $user->permission = 2;
       }

       /*
        Checks to see if adding a super admin is allowed by
        the authenticated user.
       */
       if( $data['permission'] == 3 && Auth::user()->can('addSuperAdmins', $user ) ){
         $user->permission = 3;
       }
     }


     /*
        Checks to see if some companies are set that the user owns.
     */
     if( isset( $data['companies'] ) ){
       /*
          Build an array of company IDs
       */
       $companyIDs = array();

       /*
          Iterate over all of the companies and add the ID.
       */
       foreach( $data['companies'] as $company ){
         array_push( $companyIDs, $company['id'] );
       }

       /*
          Sync the companies owned.
       */
       $user->companiesOwned()->sync( $companyIDs );

       /*
          Promote the user to an owner IF they are a general user.
          An admin or super admin will already have owner permissions.
       */
       if( $user->permission == 0 ){
         $user->permission = 1;
       }
     }

     /*
      Save the updates.
     */
     $user->save();

     /*
      Return the user.
     */
     return $user;
   }
}
