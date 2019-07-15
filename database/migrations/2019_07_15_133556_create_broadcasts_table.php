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
            $table->longText('broadcast_image');
            $table->string('geo_location', 512);
            $table->string('allow_user_messages', 50)->default('yes');
            $table->bigInteger('user_id');
            $table->longText('stream_url');
            $table->enum('status', ['online', 'offline'])->default('online');
            $table->longText('share_url');
            $table->dateTime('timestamp');
            $table->longText('video_name');
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
