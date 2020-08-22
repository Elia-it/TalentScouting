<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('models', function (Blueprint $table) {
            $table->id();
            $table->string('username');
            $table->string('first_name')->nullable();
            $table->enum('available',['yes', 'not'])->nullable();
            $table->tinyInteger('age')->nullable();
            $table->date('registered_at')->nullable();
            $table->bigInteger('n_videos')->nullable();
            $table->bigInteger('n_visual')->nullable();
            $table->bigInteger('subscribers')->nullable();
            $table->integer('last_month_rank')->nullable();
            $table->string('modelHub')->nullable();
            $table->string('official_site')->nullable();
            $table->string('twitter')->nullable();
            $table->string('fanCentro')->nullable();
            $table->string('snapchat')->nullable();
            $table->string('instagram')->nullable();
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
        Schema::dropIfExists('models');
    }
}
