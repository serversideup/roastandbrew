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
 * Defines the tag model.
 */
class Tag extends Model
{
  /**
   * Defines the fillable tables.
   */
  protected $fillable = [
      'name'
  ];

  /**
   * A tag can belong to many cafes
   */
  public function cafes(){
    return $this->belongsToMany( 'App\Models\Cafe', 'cafes_users_tags', 'tag_id', 'user_id');
  }
}
?>
