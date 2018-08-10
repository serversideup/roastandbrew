<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Company;

use Illuminate\Auth\Access\HandlesAuthorization;

class CompanyPolicy
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
    * If the user is an admin they can load a company. If the user
    * owns the company they can view the company as well.
    *
    * @param \App\Models\User $user
    * @param \App\Models\Company $company
    */
    public function view( User $user, Company $company ){
      if( $user->permission == 2 || $user->permission == 3 ){
       return true;
      }else if( $user->companiesOwned->contains( $company->id ) ){
       return true;
      }else{
       return false;
      }
    }

    /**
    * If the user is an admin they can update a company. If the user
    * owns the company they can update the company as well.
    *
    * @param \App\Models\User $user
    * @param \App\Models\Company $company
    */
    public function update( User $user, Company $company ){
      if( $user->permission == 2 || $user->permission == 3 ){
        return true;
      }else if( $user->companiesOwned->contains( $company->id ) ){
        return true;
      }else{
        return false;
      }
    }

    /**
    * If the user is an admin they can update owners on a company.
    *
    * @param \App\Models\User $user
    */
    public function updateOwners( User $user ){
      if( $user->permission == 2 || $user->permission == 3 ){
        return true;
      }else{
        return false;
      }
    }
}
