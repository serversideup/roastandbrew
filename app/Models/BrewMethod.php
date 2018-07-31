<?php

/*
	Defines the namespace for the model.
*/
namespace App\Models;

/*
	Uses the model interface.
*/
use Illuminate\Database\Eloquent\Model;

/**
 * Defines the brew method model.
 */
class BrewMethod extends Model
{
	/**
	 * Defines the table that holds the brew method data.
	 */
	protected $table = 'brew_methods';

	/**
	 * Defines the cafes relationship. A brew method can belong
	 * to many cafes.
	 */
	public function cafes(){
		return $this->belongsToMany( 'App\Models\Cafe', 'cafes_brew_methods', 'brew_method_id', 'cafe_id' );
	}
}
