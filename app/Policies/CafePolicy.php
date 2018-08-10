<?php
/*
  Defines the namespace for the policy
*/
namespace App\Policies;

/*
  Defines the models used by the policy.
*/
use App\Models\User;
use App\Models\Cafe;
use App\Models\Company;

/*
  Handles authorization.
*/
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Defines the cafe policy
 */
class CafePolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * If a user is an admin or a super admin they can create
     * a cafe.
     *
     * @param \App\Models\User  $user
     * @param \App\Models\Company $company
     * @return bool
     */
    public function create( User $user, Company $company ){
      if( $user->permission == 2 || $user->permission == 3 ){
        return true;
      }else if( $company != null && $user->companiesOwned->contains( $company->id ) ){
        return true;
      }else{
        return false;
      }
    }

    /**
     * If a user is an admin or super admin OR they own the cafe
     * company then can edit the cafe.
     *
     * @param \App\Models\User  $user
     * @param \App\Models\Cafe  $cafe
     * @return bool
     */
    public function update( User $user, Cafe $cafe ){
      if( $user->permission == 2 || $user->permission == 3 ){
        return true;
      }else if( $user->companiesOwned->contains( $cafe->company_id ) ){
        return true;
      }else{
        return false;
      }
    }

    /**
     * If a user is an admin or super admin OR they own the cafe company
     * then they can delete the cafe.
     *
     * @param \App\Models\User  $user
     * @param \App\Models\Cafe  $cafe
     * @return bool
     */
    public function delete( User $user, Cafe $cafe ){
      if( $user->permission == 2 || $user->permission == 3 ){
        return true;
      }else if( $user->companiesOwned->contains( $cafe->company_id ) ){
        return true;
      }else{
        return false;
      }
    }

    /**
     * Determines if the admin can view the cafe. This is an admin
     * only route.
     *
     * @param  \App\Models\User   $user;
     * @param  \App\Models\Cafe   $cafe;
     * @return bool
     */
     public function view( User $user, Cafe $cafe ){
       if( $user->permission == 2 || $user->permission == 3 ){
         return true;
       }else if( $user->companiesOwned->contains( $cafe->company_id ) ){
         return true;
       }else{
         return false;
       }
     }

}
