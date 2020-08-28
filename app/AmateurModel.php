<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\ModelRank;

class AmateurModel extends Model
{
    //
    protected $table = 'amateur_models';

    protected $fillable = [
        'full_name', 'last_name', 'link_img', 'available', 'age', 'birth_date', 'joined', 'video', 'visual', 'subscriber', 'modelhub', 'website', 'twitter', 'fan_centro', 'instagram'
    ];

//    protected $casts = [
//        'birth_date' => 'date:d:m:Y'
//    ];

    public function rank(){
        return $this->hasMany('App\ModelRank', 'model_id', 'id');
    }

}
