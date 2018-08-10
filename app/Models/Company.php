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
 * Defines the company model.
 */
class Company extends Model
{
	/**
	 * A company is stored in the companies table.
	 */
	protected $table = 'companies';

	/**
	 * You can fill the company name.
	 */
	protected $fillable = ['name'];

	/**
	 * A company has many cafes
	 */
  public function cafes(){
  	return $this->hasMany( 'App\Models\Cafe', 'company_id', 'id' );
  }

	/**
	 * A company is added by a single user.
	 */
  public function addedBy(){
    return $this->belongsTo('App\Models\User', 'added_by', 'id');
  }

	/**
	 * A company can be owned by many users.
	 */
	public function ownedBy(){
		return $this->belongsToMany( 'App\Models\User', 'companies_owners', 'company_id', 'user_id' );
	}

	/**
	 * A company has many actions.
	 */
	public function actions(){
		return $this->hasMany( 'App\Models\Action', 'company_id', 'id' );
	}
}
