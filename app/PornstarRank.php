<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PornstarRank extends Model
{
    //
    protected $table = 'pornstars_ranks';

    public $timestamps = false;

    protected $fillable = [
        'pornstar_id', 'weekly', 'last_month', 'monthly', 'yearly', 'rank_by_date', 'video', 'visual', 'subscriber'
    ];


    public function amateur_model()
    {
        return $this->hasManyThrough('App\Pornstars', 'id', 'pornstar_id');
    }
}
