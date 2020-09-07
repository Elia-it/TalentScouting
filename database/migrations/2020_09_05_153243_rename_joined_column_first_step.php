<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameJoinedColumnFirstStep extends Migration
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
            $table->date('joined_date')->nullable()->after('birth_date');
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
            $table->dropColumn('joined_date');
        });
    }
}
