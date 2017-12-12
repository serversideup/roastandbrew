<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddedCafesFiles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cafes_photos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cafe_id')->unsigned();
            $table->foreign('cafe_id')->references('id')->on('cafes');
            $table->integer('uploaded_by')->unsigned();
            $table->foreign('uploaded_by')->references('id')->on('users');
            $table->text('file_url');
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
        Schema::dropIfExists('cafes_photos');
    }
}
