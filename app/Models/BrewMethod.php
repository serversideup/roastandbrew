<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BrewMethod extends Model
{
	protected $table = 'brew_methods';

	public function cafes(){
		return $this->belongsToMany( 'App\Models\Cafe', 'cafes_brew_methods', 'brew_method_id', 'cafe_id' );
	}
}
