<?php
/*
  Define the namespace for the user model.
*/
namespace App\Models;

/*
  Defines the facades used by the model.
*/
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Defines the user model.
 */
class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'provider', 'provider_id'
    ];

    /**
     * A user can like many cafes
     */
    public function likes(){
      return $this->belongsToMany( 'App\Models\Cafe', 'users_cafes_likes', 'user_id', 'cafe_id');
    }

    /**
     * A user can upload many photos.
     */
    public function cafePhotos(){
      return $this->hasMany( 'App\Models\CafePhoto', 'id', 'cafe_id' );
    }

    /**
     * A user can perform many actions.
     */
    public function cafeActions(){
      return $this->hasMany( 'App\Models\CafeAction', 'id', 'user_id' );
    }

    /**
     * A user can process many actions.
     */
    public function cafeActionsProcessed(){
      return $this->hasMany( 'App\Models\CafeAction', 'id', 'processed_by' );
    }

    /**
     * A user can own many companies.
     */
    public function companiesOwned(){
      return $this->belongsToMany( 'App\Models\Company', 'companies_owners', 'user_id', 'company_id' );
    }
}
