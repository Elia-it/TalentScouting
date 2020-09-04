<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenamePornhubActorsTableToPornstars extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('pornhub_actors', 'pornstars');

        //Schema::table('Pornhub_actors', function (Blueprint $table) {
            //
       // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::rename('pornstars', 'pornhub_actors');
    }
}
