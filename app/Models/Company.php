<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
	protected $table = 'companies';

	protected $fillable = ['name'];

  public function cafes(){
  	return $this->hasMany( 'App\Models\Cafe', 'company_id', 'id' );
  }

  public function addedBy(){
    return $this->belongsTo('App\Models\User', 'added_by', 'id');
  }
}
