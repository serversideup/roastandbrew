<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddedCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function( Blueprint $table ){
          $table->increments('id');
          $table->string('name');
          $table->integer('roaster');
          $table->text('website');
          $table->text('logo');
          $table->text('description');
          $table->integer('added_by')->unsigned()->nullable();
          $table->foreign('added_by')->references('id')->on('users');
          $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('companies');
    }
}
