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

/**
 * Defines the City Model.
 */
class City extends Model
{
  use Sluggable;

  /**
   * The table used to stor city data.
   */
  protected $table = 'cities';

  /**
   * Defines the sluggable implementation.
   */
  public function sluggable(){
    return [
      'slug' => [
        'source' => ['name']
      ]
    ];
  }

  /**
   * A city has many cafes.
   */
  public function cafes(){
    return $this->hasMany( 'App\Models\Cafe', 'city_id', 'id' );
  }
}
