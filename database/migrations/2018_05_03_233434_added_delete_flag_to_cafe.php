<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddedDeleteFlagToCafe extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cafes', function( Blueprint $table ){
          $table->integer('deleted')->default(0)->after('added_by');
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
        $table->dropColumn('deleted');
      });
    }
}
