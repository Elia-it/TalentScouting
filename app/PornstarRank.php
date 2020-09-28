<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PornstarRank extends Model
{
    //
    protected $table = 'pornstars_ranks';

    public $timestamps = false;


    protected $fillable = [
        'pornstar_id', 'weekly', 'last_month', 'monthly', 'yearly', 'rank_by_date', 'videos', 'visuals', 'subscribers'
    ];


    public function amateur_model()
    {
        return $this->hasManyThrough('App\Pornstar', 'id', 'pornstar_id');
    }

}
