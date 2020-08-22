<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class ModelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        //


        $context = stream_context_create(
            array (
                'http' => array (
                    'header' => "User-Agent:MyAgent/1.0\r\n",
                    'follow_location' => false,
                    'max_redirects' => 20
                )
            )
        );
        $all_models = [];
        $all_ranks = [];

       for ($counter = 1; $counter <= 50; $counter++) {


            $content = file_get_html('https://it.pornhub.com/pornstars?o=t&performerType=amateur&page=' . $counter. '', false, $context);

            preg_match_all('/<li class=\"modelLi\">(.*?)<\/li>/', $content, $models);

            foreach ($models[0] as $file_model) {
                $model = [];


                //Model name
                if (preg_match('/<span class=\"lastName\"/', $file_model)) {
                    preg_match('/<span class=\"modelName\">\s(.*?)<span/', $file_model, $modelName);
                    $model['modelName'] = trim($modelName[1]);

                    preg_match('/<span class=\"lastName\">(.*?)<span/', $file_model, $lastName);
                    $model['last_name'] = $lastName[1];
                } else {
                    preg_match('/<span class=\"modelName\">\s(.*?)<span/', $file_model, $modelName);
                    $model['modelName'] = $modelName[1];

                }

                //Image of model
                preg_match('/data-thumb_url=(.*?) src/', $file_model, $p);
                $link = trim(str_replace('"', '', $p[1]));
                $model['link_img'] = $link;

                //personal pornhub site
                preg_match('/<a\s+(?:[^>]*?\s+)?href=(["\'])(.*?)\1/', $file_model, $findHref);
                preg_match('/href=(["])(.*?)\1/', $file_model, $username_model);

                $link_profile = "https://www.pornhub.com" . $username_model[2] . "";
                $content_profile = file_get_html("$link_profile", false, $context);


                //Check if model is available
                if (preg_match('/<div class=\"geoBlocked\">/', $content_profile)) {
                    //Not available
                    $model['available'] = 'not';
                } else {
                    //Available
                    $model['available'] = 'yes';



                    //Birth Date
                    if(preg_match('/<span itemprop=\"birthDate\" class=\"smallInfo\">(.*?)<\/span/', $content_profile, $birthDate)){
                        $model['birth_date'] = date('m/d/Y', strtotime(ucwords(strtolower(trim($birthDate[1])))));
                    }else{
                        $model['birth_date'] = "N/D";
                    }


                    //Numbers of videos
                    preg_match('/<div class=\"showingCounter pornstarVideosCounter\">(.*?)<\/div/', $content_profile, $n_videos);
                    $n_videos_to_convert = trim($n_videos[1]);
                    $model['n_videos'] = substr($n_videos_to_convert, strpos($n_videos_to_convert, 'of') + 3);


                    //Visual for all videos
                    preg_match('/<div class=\"tooltipTrig infoBox videoViews\"(.*?)<\/span>/', $content_profile, $n_video_visual);
                    $str_n_video_visual = trim($n_video_visual[1]);
                    $model['n_video_visual'] = trim(substr($str_n_video_visual, strpos($str_n_video_visual, '"big">') + 6));


                    //Subscribers
                    preg_match('/<\/div>\W+<div class=\"infoBox\">\s+(.*?)<\/span/', $content_profile, $n_subscribers);
                    $str_subscribers = trim($n_subscribers[1]);
                    $model['subscribers'] = trim(substr($str_subscribers, strpos($str_subscribers, '"big">') + 6));


                    //Joined (date from today)
                    preg_match('/<span>\s+Joined:+(.*?)\s+ago/', $content_profile, $joined);
                    $joined_date = trim(substr($joined[1], strpos($joined[1], 'Info">') + 6));
                    $model['joined'] = date('m/Y', strtotime("-" . $joined_date));


                    $ranks = [];
                    $rank['id'] = $model['modelName'];

                    //Rank week/monthly/yearly      !Current Rank is like the monthly
                    preg_match('/<span class=\"rank_number\">(.*?)<\/span/', $file_model, $rank);
                    $model['monthly_ranking'] = trim($rank[1]);

                    preg_match_all('/<div class=\"infoBox rankDetails\">(.*?)<\/div>/', $content_profile, $current_rank);


                    //[1][0] Weekly rank
                    preg_match('/\"big\">\s(.*?)\s<\/span/', $current_rank[1][0], $weekly);
                    $rank['weekly'] = trim($weekly[1]);

                    //[1][1] Monthly rank
                    preg_match('/\"big\">\s(.*?)\s<\/span/', $current_rank[1][1], $monthly);
                    $rank['monthly'] = trim($monthly[1]);

                    //[1][2] Last Month rank
                    preg_match('/\"big\">\s(.*?)\s<\/span/', $current_rank[1][2], $last_month);
                    $rank['last_month'] = trim($last_month[1]);

                    //[1][3] Yearly rank
                    preg_match('/\"big\">\s(.*?)\s<\/span/', $current_rank[1][3], $yearly);
                    $rank['yearly'] = trim($yearly[1]);

                    //insert all model rank
                    $all_ranks[] = $rank;


                    //Esternal link
                    preg_match('/<ul class=\"clearfix socialList\">(.*?)<\/ul>/', $content_profile, $social_list);
                    preg_match_all('/<li>(.*?)<\/li>/', $social_list[1], $all_social);
                    foreach ($all_social[1] as $social) {

                        //ModelHUb
                        if (!empty($model['last_name'])) {
                            if (preg_match('/target=\"_blank\">\s+ ' . $model['modelName'] . ' ' . $model['last_name'] . ' Modelhub/i', $social)) {
                                preg_match('/<a href=\"(.*?)\"/', $social, $modelHub);
                                $model['modelHub'] = $modelHub[1];
                            }
                        }

                        if(empty($model['last_name'])){
                            if (preg_match('/target=\"_blank\">\s+' . $model['modelName'] . ' Modelhub/i', $social, $test)){
                                preg_match('/<a href=\"(.*?)\"/', $social, $modelHub);
                                $model['modelHub'] = $modelHub[1];
                            }
                        }
                        if(empty($model['modelHub'])){
                            $model['modelHub'] = "N/D";
                        }



                        //Official Site
                        if (preg_match('/Official Site/', $social)) {
                            preg_match('/<a href=\"(.*?)\"/', $social, $official_site);
                            $model['official_site'] = $official_site[1];
                        }
                        if(empty($model['official_site'])){
                            $model['official_site'] = "N/D";
                        }

                        //Twitter
                        if (preg_match('/Twitter/', $social)) {
                            preg_match('/<a href=\"(.*?)\"/', $social, $twitter);
                            $model['twitter'] = $twitter[1];
                        }
                        if(empty($model['twitter'])){
                            $model['twitter'] = "N/D";
                        }

                        //Instagram
                        if (preg_match('/Instagram/', $social)) {
                            preg_match('/<a href=\"(.*?)\"/', $social, $instagram);
                            $model['instagram'] = $instagram[1];
                        }
                        if(empty($model['instagram'])){
                            $model['instagram'] = "N/D";
                        }

                        //Fan Centro
                        if (preg_match('/FanCentro/', $social)) {
                            preg_match('/<a href=\"(.*?)\"/', $social, $fanCentro);
                            $model['fan_centro'] = $fanCentro[1];
                        }
                        if(empty($model['fan_centro'])){
                            $model['fan_centro'] = "N/D";
                        }


                        preg_match_all('/<div class=\"infoPiece\">(.*?)<\/div>/', $content_profile, $info_piece);

                        foreach ($info_piece[1] as $item) {
                            if(preg_match('/Age/', $item)){
                                preg_match('/class=\"smallInfo\">\s+(.*?)<\/span/', $item, $age);
                                $model['age'] = trim($age[1]);
                            }
                            if (empty($model['age'])){
                                $model['age'] = "N/D";
                            }
                        }
//                        }

                    }

                }
                $all_models[] = $model;


            }


        }
        return view('test', compact('all_models', 'all_ranks'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}