<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameColumnsToPornstarsRanksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pornstars_ranks', function (Blueprint $table) {
            //
            $table->renameColumn('video', 'videos');
            $table->renameColumn('visual', 'visuals');
            $table->renameColumn('subscriber', 'subscribers');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pornstars_ranks', function (Blueprint $table) {
            //
            $table->renameColumn('videos', 'video');
            $table->renameColumn('visuals', 'visual');
            $table->renameColumn('subscribers', 'subscriber');
        });
    }
}
