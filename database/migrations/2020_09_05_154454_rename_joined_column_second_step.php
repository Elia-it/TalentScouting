<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Pornstar;

class RenameJoinedColumnSecondStep extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pornstars', function (Blueprint $table) {
            //
            $pornstars = Pornstar::all();
            foreach ($pornstars as $pornstar){
                $pornstar->update(['joined_date' => $pornstar->joined]);
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pornstars', function (Blueprint $table) {
            //
            $pornstars = Pornstar::all();
            foreach ($pornstars as $pornstar){
                $pornstar->update(['joined_date' => NULL]);
            }
        });
    }
}
