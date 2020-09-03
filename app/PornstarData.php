<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PornstarData extends Model
{
    //

    protected $table = 'pornstars_data';

    public $timestamps = false;

    protected $fillable = [
        'pornstar_id', 'weekly', 'last_month', 'monthly', 'yearly', 'rank_by_date', 'video', 'visual', 'subscriber'
    ];


    public function amateur_model()
    {
        return $this->hasManyThrough('App\PornhubActor', 'id', 'pornstar_id');
    }
}
