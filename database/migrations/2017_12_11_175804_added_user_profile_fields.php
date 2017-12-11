<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddedUserProfileFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->text('favorite_coffee')->after('avatar');
            $table->text('flavor_notes')->after('favorite_coffee');
            $table->boolean('profile_visibility')->default('1')->after('flavor_notes');
            $table->string('city')->after('profile_visibility');
            $table->string('state')->after('city');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('favorite_coffee');
            $table->dropColumn('flavor_notes');
            $table->dropColumn('profile_visibility');
            $table->dropColumn('city');
            $table->dropColumn('state');
        });
    }
}
