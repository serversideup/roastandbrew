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
 * Defines the action model.
 */
class Action extends Model
{
	/**
	 * Defines the table that stores the actions.
	 */
	protected $table = 'actions';

	/**
	 * An action belongs to a cafe.
	 */
  public function cafe(){
  	return $this->belongsTo( 'App\Models\Cafe', 'cafe_id', 'id' );
  }

	/**
	 * An action belongs to a company.
	 */
	 public function company(){
		 return $this->belongsTo( 'App\Models\Company', 'company_id', 'id' );
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
