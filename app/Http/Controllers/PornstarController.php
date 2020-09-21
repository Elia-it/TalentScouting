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
        $query = Pornstar::select('porns.*', 'ranks1.*')->from('pornstars as porns')->join('pornstars_ranks as ranks1', 'porns.id', '=', 'ranks1.pornstar_id')
            ->leftJoin('pornstars_ranks as ranks2', function ($join){
                    $join->on(function($ranks2){
                        $ranks2->where('porns.id', '=', DB::raw('ranks2.pornstar_id'))
                            ->where(function($where){
                                $where->where('ranks1.rank_by_date', '<', DB::raw('ranks2.rank_by_date'))
                                    ->orWhere(function($where2){
                                        $where2->where('ranks1.rank_by_date', '=', DB::raw('ranks2.rank_by_date'))
                                            ->where('ranks1.id', '<', DB::raw('ranks2.id'));
                                    });
                            });
                    });
        })->where('ranks2.id', '=', NULL)
            ->toSql();

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

        if($request->has('verified') && $request->verified == 'on'){
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
            $query->where('ranks1.videos', '>=', $request->more_than_video);
        }

        if($request->has('more_than_subscriber') && !empty($request->more_than_subscriber)){
            $query->where('ranks1.subscribers', '>=', $request->more_than_subscriber);
        }

        if($request->has('more_than_visual') && !empty($request->more_than_visual)){
            $query->where('ranks1.visuals', '>=', $request->more_than_visual);
        }



        if($request->has('order_by') && !empty($request->order_by)){

            if(preg_match('~rank~si', $request->order_by)){

                preg_match('~rank_(.*)_(.*)~si', $request->order_by, $orderby);

                $order_by_rank = 'ranks1.' . $orderby[1];
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
            $query->orderBy('pornstar_id', 'ASC');
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
            $date_increase['from'] = date('1940-01-01');
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
