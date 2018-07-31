<?php
/*
	Defines the namespace for the model.
*/
namespace App\Models;

/*
	Includes the eloquent model interface.
*/
use Illuminate\Database\Eloquent\Model;

/**
 * Defines the cafe model.
 */
class CafeAction extends Model
{
	/**
	 * Defines the table that stores the cafe actions.
	 */
	protected $table = 'cafes_actions';

	/**
	 * An action belongs to a cafe.
	 */
  public function cafe(){
  	return $this->belongsTo( 'App\Models\Cafe', 'cafe_id', 'id' );
  }

	/**
	 * An action is performed by a user
	 */
  public function by(){
    return $this->belongsTo( 'App\Models\User', 'user_id', 'id' );
  }

	/**
	 * An action is processed by a user.
	 */
  public function processedBy(){
    return $this->belongsTo( 'App\Models\User', 'processed_by', 'id' );
  }
}
