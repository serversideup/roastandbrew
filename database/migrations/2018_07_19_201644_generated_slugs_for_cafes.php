<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Models\Cafe;

class GeneratedSlugsForCafes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      $cafes = Cafe::with('company')->get();

      foreach( $cafes as $cafe ){
        if( $cafe->slug == '' ){
          $cafe->slug = Cviebrock\EloquentSluggable\Services\SlugService::createSlug( App\Models\Cafe::class, 'slug', $cafe->company->name.' '.$cafe->location_name.' '.$cafe->address.' '.$cafe->city.' '.$cafe->state);
          $cafe->save();
        }
      }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      $cafes = Cafe::with('company')->get();

      foreach( $cafes as $cafe ){
        $cafe->slug = '';
        $cafe->save();
      }
    }
}
