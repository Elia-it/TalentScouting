<?php

namespace App\Http\Controllers;

use App\Pornstars;
use Illuminate\Http\Request;
use App\simple_html_dom;
use Sunra\PhpSimple\HtmlDomParser;
use App\AmateurModel;
use App\Pornhub;

class Test extends Controller
{
    //
    public function models(){

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

//        $page = 2;
//
//        for ($counter = 1; $counter <= $page; $counter++) {

    $i = 0;
            $content = file_get_html('https://it.pornhub.com/pornstars?o=t&performerType=amateur&page=1', false, $context);

            preg_match_all('/<li class=\"modelLi\">(.*?)<\/li>/', $content, $models);

            foreach ($models[0] as $file_model) {
                $model = [];

//                <li class="modelLi">
//                    <div class="wrap">
//                        <div class="subscribe-to-pornstar-icon display-none">
//                            <button type="button" data-title="Iscriviti alla Pornostar" class="tooltipTrig" onclick="return false;"><span></span></button>
//                        </div>
//                        <a class="js-mxp" data-mxptype="Pornstar" data-mxptext="Eva Elfie" href="/model/eva-elfie">
//                        <span class="pornstar_label">
//                            <span class="title-album">
//                                <span class="rank_number"> 						1					</span>
//                                <hr class="noChange">
//                            </span>
//                        </span>
//                            <img data-thumb_url="https://di.phncdn.com/pics/users/683/394/291/avatar1551823518/(m=eidYGCjadqg)(mh=CfsySYPgm_-8kI8P)200x200.jpg" src="https://di.phncdn.com/pics/users/683/394/291/avatar1551823518/(m=eidYGCjadqg)(mh=CfsySYPgm_-8kI8P)200x200.jpg" alt="Eva Elfie">         </a>
//                        <div class="thumbnail-info-wrapper">
//                                <a href="/model/eva-elfie" class="title js-mxp" data-mxptype="Pornstar" data-mxptext="Eva Elfie">
//                                <span class="modelName">                     Eva <span class="lastName">Elfie<span class="modelBadges">
//                                <span class="modelBadges"><span class="verifiedPornstar tooltipTrig" data-title="Amatoriali Verificati"><i class="verifiedIcon"></i></span></span></span>                </span>             </span></a>
//                            <span class="videosNumber">55 Video                367M visualizzazioni </span>
//                        </div>
//                    </div>
//                </li>


                //Model name
                if (preg_match('/<span class=\"lastName\"/', $file_model)) {
                    preg_match('/<span class=\"modelName\">\s(.*?)<span/', $file_model, $modelName);
                    $model['model_name'] = trim($modelName[1]);

                    preg_match('/<span class=\"lastName\">(.*?)<span/', $file_model, $lastName);
                    $model['last_name'] = trim($lastName[1]);
                } else {
                    preg_match('/<span class=\"modelName\">\s(.*?)<span/', $file_model, $modelName);
                    $model['model_name'] = trim($modelName[1]);
                    $model['last_name'] = NULL;

                }

                //Image of model


                preg_match('/data-thumb_url=\W(.*?) src/', $file_model, $p);
                $link = str_replace('"', '', $p[1]);
                $model['link_img'] = trim($link);

                //personal pornhub site
//              preg_match('/<a\s+(?:[^>]*?\s+)?href=(["\'])(.*?)\1/', $file_model, $findHref);
//                preg_match('/href=(["])(.*?)\1/', $file_model, $username_model);
                preg_match('/<div class=\"thumbnail-info-wrapper\">(.*?)<span class=\"modelName\"/', $file_model, $x);
                preg_match('/href=(.*?) class=\"title js-mxp\"/', $x[1], $y);
                $link_profile = "https://www.pornhub.com" . trim(str_replace('"', '', $y[1]));
                $content_profile = file_get_html("$link_profile", false, $context);
//                var_dump($link_to_profile);
//                preg_match('/<a href=\"(.*?)\" class=\"title\"\1/', $file_model, $link_prova);
//                var_dump($link_prova);


//                $link_profile = "https://www.pornhub.com" . $username_model[2] . "";
//                $content_profile = file_get_html("$link_profile", false, $context);

//                var_dump($content_profile);
                //Check if model is available
                if (preg_match('/<div class=\"geoBlocked\">/', $content_profile)) {
                    //Not available
                    $model['available'] = 0;
                    $model['age'] = NULL;
                    $model['birth_date'] = NULL;
                    $model['joined'] = NULL;
                    $model['videos'] = NULL;
                    $model['visuals'] = NULL;
                    $model['subscribers'] = NULL;
                    $model['modelHub'] = NULL;
                    $model['official_site'] = NULL;
                    $model['twitter'] = NULL;
                    $model['fan_centro'] = NULL;
                    $model['instagram'] = NULL;
                    //Need Ranks
                    $model['weekly'] = NULL;

                    preg_match('/<span class=\"rank_number\">(.*?)<\/span>/', $file_model, $rank_monthly);
                    $model['monthly'] = trim($rank_monthly[1]);

                    $model['yearly'] = NULL;
                    $model['last_month'] = NULL;
                } else {
                    //Available
                    $model['available'] = 1;


                    // Another way to take img
//                    preg_match('/<img id=\"getAvatar\" src=\"(.*?)\"/', $content_profile, $getImg);
//                    echo $getImg[1];


                    //Birth Date
                    if (preg_match('/<span itemprop=\"birthDate\" class=\"smallInfo\">(.*?)<\/span/', $content_profile, $birthDate)) {
                        $model['birth_date'] = date('Y/m/d', strtotime(ucwords(strtolower(trim($birthDate[1])))));
                    } else {
                        $model['birth_date'] = NULL;
                    }

                    //age
                    preg_match_all('/<div class=\"infoPiece\">(.*?)<\/div>/', $content_profile, $info_piece);

                    foreach ($info_piece[1] as $item) {
                        if (preg_match('/Age/', $item)) {
                            preg_match('/class=\"smallInfo\">\s+(.*?)<\/span/', $item, $age);
                            $model['age'] = trim($age[1]);
                        }
                        if (empty($model['age'])) {
                            $model['age'] = NULL;
                        }
                    }


                    //Numbers of videos
                    preg_match('/<div class=\"showingCounter pornstarVideosCounter\">(.*?)<\/div/', $content_profile, $n_videos);
                    $n_videos_to_convert = trim($n_videos[1]);
                    $videos = substr($n_videos_to_convert, strpos($n_videos_to_convert, 'of') + 3);
                    $model['videos'] = intval($videos);
                    var_dump($model['videos']);

                    //Visual for all videos
                    preg_match('/<div class=\"tooltipTrig infoBox videoViews\"(.*?)<\/span>/', $content_profile, $n_video_visual);
                    //$str_n_video_visual = trim($n_video_visual[1]);
                  //  $model['visuals'] = trim(substr($str_n_video_visual, strpos($str_n_video_visual, '"big">') + 6));
                    preg_match('/data-title=\"Video\sviews:\s(.*?)\"/', $n_video_visual[1], $n_visual);
                    $model['visuals'] = intval(str_replace(',', '', $n_visual[1]));
                    var_dump($model['visuals']);

                    //Subscribers
                    preg_match('/<\/div>\W+<div class=\"infoBox\">\s+(.*?)<\/span/', $content_profile, $n_subscribers);
                    $str_subscribers = trim($n_subscribers[1]);
                    $subscriber_to_convert = trim(substr($str_subscribers, strpos($str_subscribers, '"big">') + 6));
                    //var_dump($subscriber_to_convert);

                    // preg_match('/data-title=\"Video\sviews:\s(.*?)\"/', $n_video_visual[1], $n_visual);
                    // var_dump($model['subscribers']);

                    $model['subscribers'] = intval(format_num_to_thousands($subscriber_to_convert));
                    var_dump($model['subscribers']);
                    echo '<br>';

                   // var_dump(thousandsCurrencyFormat($model['subscribers']));

                    //Joined (date from today)
                    preg_match('/<span>\s+Joined:+(.*?)\s+ago/', $content_profile, $joined);
                    $joined_date = trim(substr($joined[1], strpos($joined[1], 'Info">') + 6));
                    $model['joined'] = date('Y/m/d', strtotime("-" . $joined_date));


                    //Rank week/monthly/yearly      !Current Rank is like the monthly
                    preg_match('/<span class=\"rank_number\">(.*?)<\/span/', $file_model, $rank);
                    $model['monthly_ranking'] = trim($rank[1]);

                    preg_match_all('/<div class=\"infoBox rankDetails\">(.*?)<\/div>/', $content_profile, $current_rank);


                    //[1][0] Weekly rank
                    preg_match('/\"big\">\s(.*?)\s<\/span/', $current_rank[1][0], $weekly);
                    $model['weekly'] = trim($weekly[1]);


                    //[1][1] Monthly rank
                    preg_match('/\"big\">\s(.*?)\s<\/span/', $current_rank[1][1], $monthly);
                    $model['monthly'] = trim($monthly[1]);

                    //[1][2] Last Month rank
                    preg_match('/\"big\">\s(.*?)\s<\/span/', $current_rank[1][2], $last_month);
                    $model['last_month'] = trim($last_month[1]);

                    //[1][3] Yearly rank
                    preg_match('/\"big\">\s(.*?)\s<\/span/', $current_rank[1][3], $yearly);
                    $model['yearly'] = trim($yearly[1]);

                    //insert all model rank


                    //Esternal link
                    preg_match('/<ul class=\"clearfix socialList\">(.*?)<\/ul>/', $content_profile, $social_list);
                    preg_match_all('/<li>(.*?)<\/li>/', $social_list[1], $all_social);
                    foreach ($all_social[1] as $social) {

                        //ModelHUb
                        if (!empty($model['last_name'])) {
                            if (preg_match('/target=\"_blank\">\s+ ' . $model['model_name'] . ' ' . $model['last_name'] . ' Modelhub/i', $social)) {
                                preg_match('/<a href=\"(.*?)\"/', $social, $modelHub);
                                $model['modelHub'] = $modelHub[1];
                            }
                        }

                        if (empty($model['last_name'])) {
                            if (preg_match('/target=\"_blank\">\s+' . $model['model_name'] . ' Modelhub/i', $social, $test)) {
                                preg_match('/<a href=\"(.*?)\"/', $social, $modelHub);
                                $model['modelHub'] = $modelHub[1];
                            }
                        }
                        if (empty($model['modelHub'])) {
                            $model['modelHub'] = NULL;
                        }


                        //Official Site
                        if (preg_match('/Official Site/', $social)) {
                            preg_match('/<a href=\"(.*?)\"/', $social, $official_site);
                            if(strlen($official_site[1])> 8) {
                                $model['official_site'] = $official_site[1];
                            }
                        }
                        if (empty($model['official_site'])) {
                            $model['official_site'] = NULL;
                        }

                        //Twitter
                        if (preg_match('/Twitter/', $social)) {
                            preg_match('/<a href=\"(.*?)\"/', $social, $twitter);
                            $model['twitter'] = $twitter[1];
                        }
                        if (empty($model['twitter'])) {
                            $model['twitter'] = NULL;
                        }

                        //Instagram
                        if (preg_match('/Instagram/', $social)) {
                            preg_match('/<a href=\"(.*?)\"/', $social, $instagram);
                            $model['instagram'] = $instagram[1];
                        }
                        if (empty($model['instagram'])) {
                            $model['instagram'] = NULL;
                        }

                        //Fan Centro
                        if (preg_match('/FanCentro/', $social)) {
                            preg_match('/<a href=\"(.*?)\"/', $social, $fanCentro);
                            $model['fan_centro'] = $fanCentro[1];
                        }
                        if (empty($model['fan_centro'])) {
                            $model['fan_centro'] = NULL;
                        }


                    }

                }
                $all_models[] = $model;
                $i++;

                $test = format_num_to_thousands('120.4M');
                echo $test;



                if($i == 1){
                    break;
                }
            }


//        }

        return 'Done!';

//        return view('test', compact('all_models'));
    }

    public function test_mo(){

        $all_pornstars = [];
        $all_amateurs = [];
        $pornhub = new Pornhub();
        $page = 1;
        for ($counter = 1; $counter <= $page; $counter++){

            $pornstars = $pornhub->getPornstarsByPage('pornstar', $counter);

            foreach ($pornstars as $pornstar){
                $pornstar_data = $pornhub->getPornstarTest('pornstar', $pornstar['username']);
                var_dump($pornstar_data);
            }

//            $amateur_models = $pornhub->getPornstarsByPage('model', $counter);
//
//            foreach ($amateur_models as $model){
//                $model_data = $pornhub->getPornstarTest('model', $model['username']);
//
//                $all_amateurs[] = $model_data;
//            }

        }


        return view('test', compact('all_pornstars'));
    }
}
