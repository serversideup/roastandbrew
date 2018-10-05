<?php

/*
	Defines the namespace for the model.
*/
namespace App\Models;

/*
	Defines the model interface
*/
use Illuminate\Database\Eloquent\Model;

/*
	Uses sluggable to generate slugs
*/
use Cviebrock\EloquentSluggable\Sluggable;

/*
	Defines the facades used by the model.
*/
use Request;

/**
 * Defines the Cafe Model.
 */
class Cafe extends Model
{
	use Sluggable;

	/**
	 * The table used to store the cafe data.
	 */
	protected $table = 'cafes';

	/**
	 * Defines the sluggable implementation.
	 */
  public function sluggable()
  {
      return [
          'slug' => [
              'source' => ['company.name', 'location_name', 'address', 'city', 'state']
          ]
      ];
  }

	/**
	 * A cafe can have many brew methods.
	 */
	public function brewMethods(){
		return $this->belongsToMany( 'App\Models\BrewMethod', 'cafes_brew_methods', 'cafe_id', 'brew_method_id' );
	}

	/**
	 * A cafe belongs to one company.
	 */
	public function company(){
		return $this->belongsTo( 'App\Models\Company', 'company_id', 'id' );
	}

	/**
	 * A cafe can have many user likes.
	 */
	public function likes(){
		return $this->belongsToMany( 'App\Models\User', 'users_cafes_likes', 'cafe_id', 'user_id');
	}

	/**
	 * A cafe can have one like from a specific user.
	 */
	public function userLike(){
		$userID = Request::user('api') != '' ? Request::user('api')->id : null;

		return $this->belongsToMany( 'App\Models\User', 'users_cafes_likes', 'cafe_id', 'user_id')
								->where('user_id', $userID );
	}

	/**
	 * A cafe can have many tags.
	 */
	public function tags(){
		return $this->belongsToMany( 'App\Models\Tag', 'cafes_users_tags', 'cafe_id', 'tag_id');
	}

	/**
	 * A cafe can have many photos.
	 */
	public function photos(){
		return $this->hasMany( 'App\Models\CafePhoto', 'id', 'cafe_id' );
	}

	/**
	 * A cafe can have many actions
	 */
	public function actions(){
		return $this->hasMany( 'App\Models\Action', 'cafe_id', 'id' );
	}

	/**
	 * A cafe belongs to a city
	 */
	public function city(){
		return $this->belongsTo( 'App\Models\City', 'city_id', 'id' );
	}
}
