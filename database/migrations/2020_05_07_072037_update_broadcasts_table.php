<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateBroadcastsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('broadcasts', function (Blueprint $table) {
            $table->tinyInteger('is_antmedia')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('broadcasts', 'is_antmedia'))

        {

            Schema::table('broadcasts', function (Blueprint $table)

            {

                $table->dropColumn('is_antmedia');

            });

        }
    }
}
