<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


class AlterUserProfileAddYoutubeInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement("ALTER TABLE `user_profiles` ADD `youtube_auth_info` TEXT NULL AFTER `is_sensitive`;");
        \DB::statement("ALTER TABLE `broadcasts` ADD `youtube_stream_info` TEXT NULL AFTER `broadcast_type`, ADD `youtube_stream_log` TEXT NULL AFTER `youtube_stream_info`;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
