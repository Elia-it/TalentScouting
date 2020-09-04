<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\ModelData;
use App\PornstarRank;

class PassDataToPornstarsRanks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $all_models_ranks = ModelData::all();

        foreach ($all_models_ranks as $model_rank){
            PornstarRank::create([
                'pornstar_id' => $model_rank->model_id,
                'weekly' => $model_rank->weekly,
                'monthly' => $model_rank->monthly,
                'last_month' => $model_rank->last_month,
                'yearly' => $model_rank->yearly,
                'video' => $model_rank->video,
                'visual' => $model_rank->visual,
                'subscriber' => $model_rank->subscriber,
                'rank_by_date' => $model_rank->rank_by_date
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $all_models_ranks = ModelData::all();

        foreach ($all_models_ranks as $model_rank){
            PornstarRank::where('pornstar_id', $model_rank->model_id)->delete();
        }
    }
}
