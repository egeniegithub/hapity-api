<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UserProfile extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id');
            $table->string('first_name', 512);
            $table->string('last_name', 512);
            $table->string('email', 512);
            $table->string('profile_picture', 512)->nullable();
            $table->string('gender', 512)->nullable();
            $table->date('date_of_birth')->nullable();
            $table->bigInteger('age')->nullable();
            $table->string('auth_key', 512)->nullable();
            $table->string('full_name', 512);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_profiles');
    }
}
