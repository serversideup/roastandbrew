<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CafeAction extends Model
{
	protected $table = 'cafes_actions';

  public function cafes(){
  	return $this->belongsTo( 'App\Models\Cafe', 'cafe_id', 'id' );
  }

  public function by(){
    return $this->belongsTo( 'App\Models\User', 'user_id', 'id' );
  }

  public function processedBy(){
    return $this->belongsTo( 'App\Models\User', 'processed_by', 'id' );
  }
}
