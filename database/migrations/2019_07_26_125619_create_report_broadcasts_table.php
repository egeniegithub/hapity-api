<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReportBroadcastsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('report_broadcasts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('reporter_user_id')->nullable();
            $table->bigInteger('broadcast_id')->nullable();
            $table->enum('status', ['pending', 'active', 'inActive'])->default('pending');
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
        Schema::dropIfExists('report_broadcasts');
    }
}
