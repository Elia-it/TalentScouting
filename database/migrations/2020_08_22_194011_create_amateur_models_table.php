<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAmateurModelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('amateur_models', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('link_img')->nullable();
            $table->boolean('available')->default(0);
            $table->tinyInteger('age')->nullable();
            $table->date('birth_date')->nullable();
            $table->date('joined')->nullable();
            $table->string('modelhub')->nullable();
            $table->string('website')->nullable();
            $table->string('twitter')->nullable();
            $table->string('fan_centro')->nullable();
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
        Schema::dropIfExists('amateur_models');
    }
}
