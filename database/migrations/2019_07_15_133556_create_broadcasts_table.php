<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBroadcastsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('broadcasts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title', 512);
            $table->longText('description')->nullable();
            $table->longText('broadcast_image')->nullable();
            $table->string('geo_location', 512)->nullable();
            $table->string('allow_user_messages', 50)->default('yes');
            $table->bigInteger('user_id');
            $table->longText('stream_url');
            $table->enum('status', ['online', 'offline'])->default('online');
            $table->longText('share_url');
            $table->longText('video_name');
            $table->bigInteger('is_deleted')->default(0);
            $table->longText('filename')->nullable();
            $table->string('is_sensitive', 10)->nullable();
            $table->bigInteger('post_id')->nullable();
            $table->bigInteger('post_id_joomla')->nullable();
            $table->bigInteger('post_id_drupal')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('broadcasts');
    }
}
