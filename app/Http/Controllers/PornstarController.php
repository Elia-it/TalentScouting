<?php

namespace App\Http\Controllers;

use App\PornstarRank;
use App\Pornstar;
use Illuminate\Http\Request;
use test\Mockery\Fixtures\MethodWithVoidReturnType;
use Illuminate\Support\Facades\DB;
use function foo\func;

class PornstarController extends Controller
{
    //
    public function index(Request $request){

        $select = 'pornstars.full_name, pornstars.type, pornstars.link_img, pornstars.age, pornstars.joined_date, pornstars.modelhub, pornstars.website, pornstars.twitter, pornstars.instagram, pornstars.fan_centro, last_rec.weekly, last_rec.monthly, last_rec.last_month, last_rec.yearly, last_rec.videos, last_rec.visuals, last_rec.subscribers';
        $query = Pornstar::join(DB::raw('(
            SELECT
              pornstars_ranks.*
            FROM
              (SELECT
                 pornstar_id, MAX(rank_by_date) AS rank_by_date
               FROM
                 pornstars_ranks
               GROUP BY
                 pornstar_id ) AS latest_rank
            INNER JOIN
              pornstars_ranks
            ON
              pornstars_ranks.pornstar_id = latest_rank.pornstar_id AND
              pornstars_ranks.rank_by_date = latest_rank.rank_by_date
            )last_rec'), function($join){
                $join->on('pornstars.id', '=', 'last_rec.pornstar_id');
        })
        ->select(explode(', ', $select));


//      dd($test[8]);

        if($request->pornstar == NULL && $request->amateur_model == 'on'){
            $query->where('type', '=', 'model');


        }elseif ($request->amateur_model == NULL && $request->pornstar == 'on'){
            $query->where('type', '=', 'pornstar');

        }

        if($request->has('more_than_age') OR $request->has('less_than_age')){
            if($request->more_than_age >= 18 OR $request->less_than_age <= 90 && !empty($request->more_than_age) OR !empty($request->less_than_age) ){
                $query->whereBetween('birth_date', [date('Y-m-d',strtotime("-" . $request->less_than_age . " years")), date('Y-m-d',strtotime("-" . $request->more_than_age . " years"))]);
            }
        }


        if($request->has('type') && !empty($request->type)){
            $query->where('type', '=', $request->type);

        }

        if($request->has('verifiSELECT pornstars.id, pornstars_ranks.rank_by_date from pornstars_ranks join pornstars ON pornstars.id = pornstars_ranks.pornstar_id GROUP BY pornstars.id ed') && $request->verified == 'on'){
            $query->where('verified', '=', '1');
        }elseif($request->has('not_verified') && $request->not_verified == 'on'){
            $query->where('verified', '=', '0');
        }

        if($request->has('joined_date') && !empty($request->joined_date)){
            $query->whereBetween('joined_date', [$request->joined_date, date('Y-m-d')]);
        }

        if($request->has('modelhub') && $request->modelhub == 'on'){
            $query->where('modelhub', '!=', NULL);

        }
        if($request->has('website') && $request->website == 'on'){
            $query->where('website', '!=', NULL);

        }
        if($request->has('instagram') && $request->instagram == 'on'){
            $query->where('instagram', '!=', NULL);

        }
        if($request->has('twitter') && $request->twitter == 'on'){
            $query->where('twitter', '!=', NULL);

        }
        if($request->has('fan_centro') && $request->fan_centro == 'on'){
            $query->where('fan_centro', '!=', NULL);

        }


        if($request->has('more_than_video') && !empty($request->more_than_video)){
            $query->where('last_rec.videos', '>=', $request->more_than_video);
        }

        if($request->has('more_than_subscriber') && !empty($request->more_than_subscriber)){
            $query->where('last_rec.subscribers', '>=', $request->more_than_subscriber);
        }

        if($request->has('more_than_visual') && !empty($request->more_than_visual)){
            $query->where('last_rec.visuals', '>=', $request->more_than_visual);
        }



        if($request->has('order_by') && !empty($request->order_by)){

            if(preg_match('~rank~si', $request->order_by)){

                preg_match('~rank_(.*)_(.*)~si', $request->order_by, $orderby);

                $order_by_rank = 'last_rec.' . $orderby[1];
                $asc_desc = $orderby[2];
                $query->orderBy($order_by_rank, $asc_desc);

            }elseif(preg_match('~info~si', $request->order_by)){
                if(preg_match('~joined_date~si', $request->order_by)){
                    preg_match('~joined_date_(.*)~si', $request->order_by, $orderby);
                    $query->orderBy('joined_date', $orderby[1]);
                }elseif(preg_match('~age_(.*)~si', $request->order_by, $orderby)){

                    $query->orderBy('age', $orderby[1]);
                }
            }
        }else{
            $query->orderby('pornstar_id', 'ASC');
        }


        $count_pornstars = $query->get()->count();

        $limit = 20;

        $pages = ceil($count_pornstars / $limit);

        $page = min($pages, filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT, array(
            'options' => array(
                'default'   => 1,
                'min_range' => 1,
            ),
        )));

        $offset = ($page - 1) * $limit;


        $query->limit($limit);
        $query->offset($offset);


//        dd($count_pornstars);

        $all_pornstars = $query->get();


//        dd($all_pornstars);
//        var_dump($all_pornstars);


//        dd($all_pornstars[8]);

//        die;
        $date_increase = [];

        if($request->has('increase_from') && $request->increase_from != NULL){
            $date_increase['from'] = $request->increase_from;
        }else{
            $date_increase['from'] = date('2019-01-01');
        }
        if($request->has('increase_to') && $request->increase_to != NULL){
            $date_increase['to'] = $request->increase_to;
        }else{
            $date_increase['to'] = date('Y-m-d');
        }

        return view('pornstars', compact('all_pornstars', 'date_increase', 'pages'));

    }

    public function getTest($id, $date){
        if($date['from'] != NULL){
            $from = PornstarRank::where('pornstar_id', $id)->where('rank_by_date', '>=', $date['from'])->orderBy('rank_by_date', 'ASC')->first();
        }else{
            $from = PornstarRank::where('pornstar_id', $id)->orderBy('rank_by_date', 'ASC')->first();
        }


        if($date['to'] != NULL){
            if ($date['to'] != $date['from']){
                $to = PornstarRank::where('pornstar_id', $id)->where('rank_by_date', '<=', $date['to'])->orderBy('rank_by_date', 'DESC')->first();
            }else{
                $to = $from;
            }
        }else{
            $to = PornstarRank::where('pornstar_id', $id)->orderBy('rank_by_date', 'DESC')->first();
        }

        $date_increase = [];
        $date_increase['from'] = $from;
        $date_increase['to'] = $to;

        return $date_increase;
    }

}
