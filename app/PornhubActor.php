<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PornhubActor extends Model
{
    //
    protected $table = 'pornhub_actors';

    protected $fillable = [
        'full_name', 'type', 'verified' ,'last_name', 'link_img', 'available', 'age', 'birth_date', 'joined', 'video', 'visual', 'subscriber', 'modelhub', 'website', 'twitter', 'fan_centro', 'instagram'
    ];

    public function model_rank(){
        return $this->hasMany('App\ModelData', 'model_id', 'id');
    }

    public function pornstar_rank(){
        return $this->hasMany('App\PornstarData', 'pornstar_id', 'id');
    }
}
