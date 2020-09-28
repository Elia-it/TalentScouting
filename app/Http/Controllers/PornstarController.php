<?php

namespace App\Http\Controllers;

use App\PornstarRank;
use App\Pornstar;
use http\Env\Response;
use Illuminate\Http\Request;
use test\Mockery\Fixtures\MethodWithVoidReturnType;
use Illuminate\Support\Facades\DB;
use function foo\func;

class PornstarController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('catch_error');

    }

    public function update_filters(Request $request){

        $page   = $request->input( 'page', 1 );
        $x_page = 50;

        $offset = ($page - 1) * $x_page;


        $select = ['pornstars.id', 'pornstars.full_name','pornstars.verified','pornstars.age','pornstars.type','pornstars.link_img','pornstars.modelhub','pornstars.website','pornstars.twitter','pornstars.instagram','pornstars.fan_centro','pornstars.joined_date','last_rec.visuals','last_rec.videos','last_rec.subscribers','last_rec.monthly'];
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
            ->select($select);

        $count_pornstars = $query->get()->count();

//        TYPE
        if($request->has('type') AND $request->type != NULL){
            if($request->type == 'pornstar'){
                $query->where('type', '=', 'pornstar');
            } elseif ( $request->type == 'model') {
                $query->where('type', '=', 'model');
            }
        }

//        AGE
        if($request->has('more_than_age') OR $request->has('less_than_age')){
            if($request->has('more_than_age') AND $request->more_than_age != NULL){
                if($request->has('less_than_age') AND $request->less_than_age != NULL){
                    $query->whereBetween('birth_date', [date('Y-m-d',strtotime("-" . $request->less_than_age . " years")), date('Y-m-d',strtotime("-" . $request->more_than_age . " years"))]);
                }else{
                    $query->where('birth_date', '<', date('Y-m-d',strtotime("-" . $request->more_than_age . " years")));
                }
            }elseif($request->has('less_than_age') AND $request->less_than_age != NULL){
                $query->where('birth_date', '>', date('Y-m-d',strtotime("-" . $request->less_than_age . " years")));
            }
        }

//        VIDEOS

        if($request->has('more_than_videos') OR $request->has('less_than_videos')){
            if($request->has('more_than_videos') AND $request->more_than_videos != NULL){
                if($request->has('less_than_videos') AND $request->less_than_videos != NULL){
                    $query->whereBetween('videos', [$request->more_than_videos, $request->less_than_videos]);
                }else{
                    $query->where('videos', '>=', $request->more_than_videos);
                }
            }elseif($request->has('less_than_videos') AND $request->less_than_videos != NULL){
                $query->where('videos', '<=', $request->less_than_videos);
            }
        }

//        VIEWS

        if($request->has('more_than_video_views') OR $request->has('less_than_video_views')){
            if($request->has('more_than_video_views') AND $request->more_than_video_views != NULL){
                if($request->has('less_than_video_views') AND $request->less_than_video_views != NULL){
                    $query->whereBetween('visual', [$request->more_than_video_views, $request->less_than_video_views]);
                }else{
                    $query->where('visual', '>=', $request->more_than_video_views);
                }
            }elseif($request->has('less_than_video_views') AND $request->less_than_video_views != NULL){
                $query->where('visual', '<=', $request->less_than_video_views);
            }
        }

//        SUBSCRIBERS

        if($request->has('more_than_subscribers') OR $request->has('less_than_subscribers')){
            if($request->has('more_than_subscribers') AND $request->more_than_subscribers != NULL){
                if($request->has('less_than_subscribers') AND $request->less_than_subscribers != NULL){
                    $query->whereBetween('subscribers', [$request->more_than_subscribers, $request->less_than_subscribers]);
                }else{
                    $query->where('subscribers', '>=', $request->more_than_subscribers);
                }
            }elseif($request->has('less_than_subscribers') AND $request->less_than_subscribers != NULL){
                $query->where('subscribers', '<=', $request->less_than_subscribers);
            }
        }

//        JOINED DATE

        if($request->has('from_joined_date') OR $request->has('to_joined_date')){
            if($request->has('from_joined_date') AND $request->from_joined_date != NULL){
                if($request->has('to_joined_date') AND $request->to_joined_date != NULL){
                    $query->whereBetween('joined_date', [$request->from_joined_date, $request->to_joined_date]);
                }else{
                    $query->whereBetween('joined_date', [$request->from_joined_date, date('Y-m-d')]);
                }
            }elseif($request->has('to_joined_date') AND $request->to_joined_date != NULL){
                $query->where('joined_date', '<=', $request->to_joined_date);
            }
        }

        //        VERIFIED

        //! check it

        if($request->has('verified') && $request->verified == 1) {
            $query->where('verified', '=', '1');
        }

        //        SOCIALS
        if($request->has('modelhub') && $request->modelhub == 1){
            $query->where('modelhub', '!=', NULL);

        }
        if($request->has('website') && $request->website == 1){
            $query->where('website', '!=', NULL);

        }
        if($request->has('instagram') && $request->instagram == 1){
            $query->where('instagram', '!=', NULL);

        }
        if($request->has('twitter') && $request->twitter == 1){
            $query->where('twitter', '!=', NULL);

        }
        if($request->has('fan_centro') && $request->fan_centro == 1){
            $query->where('fan_centro', '!=', NULL);

        }


        // TRACK NINJA
        if($request->has('is_ninja_tracked') && $request->is_ninja_tracked == 1){
            $query->where('is_ninja_tracked', '=', '1');
        }


        $query->offset( $offset )->limit( $x_page );

        //ORDER BY
        if($request->has('orderby') AND $request->orderby){
            if(preg_match('~rank~si', $request->orderby)){
                preg_match('~rank_(.*)~si', $request->orderby, $asc_desc);
                $query->orderby('last_rec.monthly', $asc_desc[1]);
            }else{
                preg_match('~(.*)_(.*)~si', $request->orderby, $order);
                $query->orderby($order[1], $order[2]);
            }
        }else{
            $query->orderby('last_rec.monthly', 'ASC');
            $query->where('last_rec.monthly', '!=', NULL);
        }




        $all_pornstars = $query->get();



        foreach ($all_pornstars as $pornstar){
            $pornstar->is_ninja_tracked = 0;

            if ($pornstar['is_ninja_tracked'] == 1){
                $pornstar->ig_er = rand(1,70)/10;
                if ($pornstar->videos < 10 AND $pornstar->visuals > 1000000){
                    $pornstar['color_ig_er'] = 'green';
                } elseif($pornstar->videos < 50 AND $pornstar->visuals > 1000000){
                    $pornstar['color_ig_er'] = 'yellow';
                }elseif($pornstar->videos < 100 AND $pornstar->visuals > 1000000){
                    $pornstar['color_ig_er'] = 'orange';
                }else{
                    $pornstar['color_ig_er'] = 'red';
                }
            }else{
                $pornstar['ig_er'] = NULL;
                $pornstar['color_ig_er'] = NULL;
            }
            if($pornstar->instagram != NULL){
                $pornstar['ninja_button'] = TRUE;
            }else{
                $pornstar['ninja_button'] = FALSE;
            }

            $pornstar['slut_advisor_rank'] = rand(1,100)/10;
            if ($pornstar['slut_advisor_rank'] >= 8){
                $pornstar['color_slut_ad'] = 'green';
            } elseif($pornstar['slut_advisor_rank'] < 8){
                $pornstar['color_slut_ad'] = 'yellow';
            }elseif($pornstar['slut_advisor_rank'] < 5 AND $pornstar['slut_advisor_rank'] > 3){
                $pornstar['color_slut_ad'] = 'orange';
            }else{
                $pornstar['color_slut_ad'] = 'red';
            }

            $pornstar['printable_video_views'] = thousandsCurrencyFormat($pornstar->visuals);
            $pornstar['printable_videos'] = thousandsCurrencyFormat($pornstar->videos);
            $pornstar['printable_subscribers'] = thousandsCurrencyFormat($pornstar->subscribers);


        }

        $counter_tracked = 0;
        for ($x = 0; $x < 100; $x++){
            $rand_id = rand(0, $count_pornstars);
            $rand_track = rand(0,1);
            if($rand_track == 1){
                $counter_tracked++;
            }
            if($all_pornstars->contains('id', $rand_id))
            {
                $all_pornstars[$rand_id-1]->is_ninja_tracked = $rand_track;
            }

        }

        // filter is_ninja_tracked missed
//        if($request->has('is_ninja_tracked') && $request->is_ninja_tracked != NULL){
//
//        }


        return response()->json([
            'results'     => $all_pornstars,
            'max_page'    => ceil($count_pornstars/$x_page),
            'page'        => $page,
            'total_pornstars' => $count_pornstars,
            'n_porstars_tracked' => $counter_tracked,
        ]);


    }



    public function getDataForIncreases($id, $date){
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


    public function track_model(Request $request){

        if ($request->has('id') AND $request->id != NULL){
            $pornstar = Pornstar::find($request->id);
            if(empty($pornstar)){
                return response()->json(['message' => 'Pornstar not found'], 400);
            }

            if($pornstar->instagram != NULL){
                return response()->json(TRUE);
            }else{
                return response()->json(FALSE);
            }
        }

        return response()->json([
            'message' => 'id not valid'
        ]);
    }

//    public function untrack_model(Request $request){
//        if($request->has('id')  AND $request->id != NULL){
//            $pornstar = Pornstar::find($request->id);
//            if(empty($pornstar)){
//                return response()->json(['message' => 'Pornstar not found'], 400);
//            }
//
//            if($pornstar->is_ninja_tracked)
//        }
//    }

}
