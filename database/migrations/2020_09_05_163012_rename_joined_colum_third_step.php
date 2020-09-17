<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Pornstar;

class RenameJoinedColumThirdStep extends Migration
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
            $table->dropColumn('joined');
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
            $table->date('joined')->nullable();

            $pornstars = Pornstar::all();

            foreach ($pornstars as $pornstar){
                $pornstar->update(['joined' => $pornstar->joined_date]);
            }
        });
    }
}
