<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
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
     * If the user is an admin or super admin, they can update users.
     */
     public function update( User $user ){
       if( $user->permission == 2 || $user->permission == 3 ){
         return true;
       }else{
         return false;
       }
     }


    /**
     * If the user is an admin or super admin, they can add other admins
     */
    public function addAdmins( User $user ){
      if( $user->permission == 2 || $user->permission == 3 ){
        return true;
      }else{
        return false;
      }
    }

    /**
     * If the user is a super admin, they can add other super admins.
     */
    public function addSuperAdmins( User $user ){
      if( $user->permission == 3 ){
        return true;
      }else{
        return false;
      }
    }
}
