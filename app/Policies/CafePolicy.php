<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Cafe;
use App\Models\Company;

use Illuminate\Auth\Access\HandlesAuthorization;

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
}
