<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddedSocialLinks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('companies', function( Blueprint $table ){
          $table->string('instagram_url')->after('description')->nullable();
          $table->string('twitter_url')->after('description')->nullable();
          $table->string('facebook_url')->after('description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('companies', function( Blueprint $table ){
        $table->dropColumn('instagram_url');
        $table->dropColumn('twitter_url');
        $table->dropColumn('facebook_url');
      });
    }
}
