<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
  protected $fillable = [
      'name'
  ];

  public function cafes(){
    return $this->belongsToMany( 'App\Models\Cafe', 'cafes_users_tags', 'tag_id', 'user_id');
  }
}
?>
