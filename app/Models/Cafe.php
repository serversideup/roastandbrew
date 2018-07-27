<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Cviebrock\EloquentSluggable\Sluggable;

use Request;

class Cafe extends Model
{
	protected $table = 'cafes';

	use Sluggable;

  public function sluggable()
  {
      return [
          'slug' => [
              'source' => ['company.name', 'location_name', 'address', 'city', 'state']
          ]
      ];
  }

	public function brewMethods(){
		return $this->belongsToMany( 'App\Models\BrewMethod', 'cafes_brew_methods', 'cafe_id', 'brew_method_id' );
	}

	public function company(){
		return $this->belongsTo( 'App\Models\Company', 'company_id', 'id' );
	}

	public function likes(){
		return $this->belongsToMany( 'App\Models\User', 'users_cafes_likes', 'cafe_id', 'user_id');
	}

	public function userLike(){
		$userID = Request::user('api') != '' ? Request::user('api')->id : null;

		return $this->belongsToMany( 'App\Models\User', 'users_cafes_likes', 'cafe_id', 'user_id')
								->where('user_id', $userID );
	}

	public function tags(){
		return $this->belongsToMany( 'App\Models\Tag', 'cafes_users_tags', 'cafe_id', 'tag_id');
	}

	public function photos(){
		return $this->hasMany( 'App\Models\CafePhoto', 'id', 'cafe_id' );
	}

	public function actions(){
		return $this->hasMany( 'App\Models\CafeAction', 'id', 'cafe_id' );
	}
}
