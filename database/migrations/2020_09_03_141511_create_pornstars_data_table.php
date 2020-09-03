<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePornstarsDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pornstars_data', function (Blueprint $table) {
            $table->id();
            $table->integer('pornstar_id');
            $table->integer('weekly')->nullable();
            $table->integer('monthly')->nullable();
            $table->integer('last_month')->nullable();
            $table->integer('yearly')->nullable();
            $table->integer('video')->nullable();
            $table->bigInteger('visual')->nullable();
            $table->bigInteger('subscriber')->nullable();
            $table->timestamp('rank_by_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pornstars_data');
    }
}
