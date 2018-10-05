<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddedCities extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cities', function( Blueprint $table ){
          $table->increments('id');
          $table->string('name');
          $table->string('state');
          $table->string('country');
          $table->string('slug');
          $table->decimal('latitude', 11, 8)->nullable();
          $table->decimal('longitude', 11, 8)->nullable();
          $table->decimal('radius', 4, 2)->nullable();
          $table->timestamps();
        });

        Schema::table('cafes', function( Blueprint $table ){
          $table->integer('city_id')->after('location_name')->unsigned()->nullable();
          $table->foreign('city_id')->references('id')->on('cities');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('cafes', function( Blueprint $table ){
        $table->dropForeign('cafes_city_id_foreign');
        $table->dropColumn('city_id');
      });

      Schema::drop('cities');
    }
}
