<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pornstar extends Model
{
    //

    protected $table = 'pornstars';


    protected $fillable = [
        'full_name', 'username', 'type', 'verified', 'last_name', 'link_img', 'available', 'age', 'birth_date', 'joined_date', 'joined', 'modelhub', 'website', 'twitter', 'fan_centro', 'instagram', 'is_ninja_tracked'
    ];



//    public function pornstar_rank(){
//        return $this->hasMany('App\PornstarRank', 'pornstar_id', 'id');
//    }
}

