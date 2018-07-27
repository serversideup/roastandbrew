<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddedCompaniesOwnersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies_owners', function( Blueprint $table ){
          $table->integer('user_id')->unsigned();
          $table->foreign('user_id')->references('id')->on('users');
          $table->integer('company_id')->unsigned();
          $table->foreign('company_id')->references('id')->on('companies');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('companies_owners');
    }
}
