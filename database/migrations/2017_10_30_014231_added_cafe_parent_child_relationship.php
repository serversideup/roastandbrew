<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddedCafeParentChildRelationship extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('cafes', function( Blueprint $table ){
        $table->integer('parent')->unsigned()->nullable()->after('id');
        $table->foreign('parent')->references('id')->on('cafes');
        $table->string('location_name')->after('name');
        $table->integer('roaster')->after('longitude');
        $table->text('website')->after('roaster');
        $table->text('description')->after('website');
        $table->integer('added_by')->after('description')->unsigned()->nullable();
        $table->foreign('added_by')->references('id')->on('users');
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
        $table->dropColumn('parent');
        $table->dropColumn('location_name');
        $table->dropColumn('roaster');
        $table->dropColumn('website');
        $table->dropColumn('description');
        $table->dropColumn('added_by');
      });
    }
}
