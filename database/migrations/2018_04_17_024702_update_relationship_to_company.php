<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateRelationshipToCompany extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cafes', function( Blueprint $table ){
          $table->dropForeign('cafes_parent_foreign');
          $table->dropColumn('parent');
          $table->integer('company')->unsigned()->after('name');
          $table->foreign('company')->references('id')->on('companies');
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
          $table->dropForeign('cafes_company_foreign');
          $table->dropColumn('company');
          $table->integer('parent')->unsigned();
          $table->foreign('parent')->references('id')->on('cafes');
        });
    }
}
