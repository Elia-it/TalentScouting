<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModelsRanksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('models_ranks', function (Blueprint $table) {
            $table->id();
            $table->integer('model_id');
            $table->integer('weekly')->nullable();
            $table->integer('monthly');
            $table->integer('last_month')->nullable();
            $table->integer('yearly')->nullable();
            $table->timestamp('rank_by_date');
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('models_ranks');
    }
}
