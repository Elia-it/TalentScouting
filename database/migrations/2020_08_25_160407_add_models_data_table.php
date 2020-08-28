<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddModelsDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('models_data', function (Blueprint $table) {
            $table->id();
            $table->integer('model_id');
            $table->integer('weekly')->nullable();
            $table->integer('monthly')->nullable();
            $table->integer('last_month')->nullable();
            $table->integer('yearly')->nullable();
            $table->integer('video')->nullable();
            $table->integer('visual')->nullable();
            $table->integer('subscriber')->nullable();
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
        Schema::dropIfExists('models_data');
    }
}
