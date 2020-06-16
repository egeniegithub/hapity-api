<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateBroadcastsTableAddStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('broadcasts', function (Blueprint $table) {
            $table->string('error_log')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('broadcasts', 'error_log'))

        {

            Schema::table('broadcasts', function (Blueprint $table)
            {

                $table->dropColumn('error_log');

            });

        }
    }
}
