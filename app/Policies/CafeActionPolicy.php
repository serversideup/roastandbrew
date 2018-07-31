<?php
/*
  Defines the namespace for the policy.
*/
namespace App\Policies;

/*
  Defines the models used by the policy.
*/
use App\Models\User;
use App\Models\CafeAction;

/*
  Defines that the policy can handle authorization.
*/
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Define the cafe action policy.
 */
class CafeActionPolicy
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
     * If a user is an admin or super admin they can approve all actions.
     * If the user owns the company that owns the cafe then they can approve actions.
     *
     * @param \App\Models\User $user
     * @param \App\Models\CafeAction $cafeAction
     * @return bool
     */
    public function approve( User $user, CafeAction $cafeAction ){
      if( $user->permission == 2 || $user->permission == 3 ){
        return true;
      }else if( $user->companiesOwned->contains( $cafeAction->company_id ) ){
        return true;
      }else{
        return false;
      }
    }

    /**
     * If a user is an admin or super admin they can deny all actions.
     * If the user owns the company that owns the cafe then they can deny actions.
     *
     * @param \App\Models\User $user
     * @param \App\Models\CafeAction $cafeAction
     * @return bool
     */
    public function deny( User $user, CafeAction $cafeAction ){
      if( $user->permission == 2 || $user->permission == 3 ){
        return true;
      }else if( $user->companiesOwned->contains( $cafeAction->company_id ) ){
        return true;
      }else{
        return false;
      }
    }
}
