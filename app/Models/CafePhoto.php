<?php
/*
	Defines the namespace for the model.
*/
namespace App\Models;

/*
	Defines the interface used by the model.
*/
use Illuminate\Database\Eloquent\Model;

/**
 * Defines the Cafe Photo model.
 */
class CafePhoto extends Model
{
	/**
	 * Defines the table that stores the cafe photo data.
	 */
	protected $table = 'cafes_photos';

	/**
	 * A photo belongs to an individual cafe.
	 */
  public function cafe(){
    return $this->belongsTo('App\Models\Cafe', 'cafe_id', 'id');
  }

	/**
	 * A photo belongs to an individual user.
	 */
  public function user(){
    return $this->belongsTo('App\Models\User', 'uploaded_by', 'id');
  }
}
