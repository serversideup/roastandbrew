<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CafePhoto extends Model
{
	protected $table = 'cafes_photos';

  public function cafe(){
    return $this->belongsTo('App\Models\Cafe', 'cafe_id', 'id');
  }

  public function user(){
    return $this->belongsTo('App\Models\User', 'uploaded_by', 'id');
  }
}
