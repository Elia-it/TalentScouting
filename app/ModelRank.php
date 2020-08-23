<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\AmateurModel;

class ModelRank extends Model
{
    //
    protected $table = 'models_ranks';

    protected $fillable = [
        'model_id', 'weekly', 'last_month', 'monthly', 'yearly', 'rank_by_date'
    ];


    public function amateur_model()
    {
        return $this->hasManyThrough('App\AmateurModel', 'id', 'model_id');
    }
}
