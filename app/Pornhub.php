<?php


namespace App;

use App\AmateurModel;

class Pornhub
{
    private $model_name;
    private $last_name;
    private $x = [];
    private $t = [];
    private $all_models = [];
    private $model = [];


    public function getContext(){
        $context = stream_context_create(
            array (
                'http' => array (
                    'header' => "User-Agent:MyAgent/1.0\r\n",
                    'follow_location' => false,
                    'max_redirects' => 20
                )
            )
        );

        return $context;
    }

    public function get_all_models($page){
        $context = $this->getContext();

        $all_models = [];


        $content = file_get_html('https://it.pornhub.com/pornstars?o=t&performerType=amateur&page='. $page . '', false, $context);

        preg_match_all('/<li class=\"modelLi\">(.*?)<\/li>/', $content, $models);
        $i = 0;
        $counter = 0;
        foreach ($models[0] as $model){
            if($counter < 39){
                $counter++;
                continue;
            }
            $this->get_info_model($model);
            $all_models[] = $this->model;
            $this->model = [];
            if($i == 4){
                break;
            }
            $i++;
        }

        return $all_models;
    }

    public function get_info_model($file_model){
        $context = $this->getContext();
        $model = [];

        if (preg_match('/<span class=\"lastName\"/', $file_model)) {
            preg_match('/<span class=\"modelName\">\s(.*?)<span/', $file_model, $modelName);
            $model['model_name'] = trim($modelName[1]);
            $this->model_name = $model['model_name'];
            $this->model['model_name'] = $model['model_name'];


            preg_match('/<span class=\"lastName\">(.*?)<span/', $file_model, $lastName);
            $model['last_name'] = $lastName[1];
            $this->last_name = $model['last_name'];
            $this->model['last_name'] = $model['last_name'];
        } else {
            preg_match('/<span class=\"modelName\">\s(.*?)<span/', $file_model, $modelName);
            $model['model_name'] = trim($modelName[1]);
            $this->model_name = $model['model_name'];
            $this->model['model_name'] = $model['model_name'];

            $model['last_name'] = NULL;
            $this->last_name = $model['last_name'];
            $this->model['last_name'] = $model['last_name'];
        }

        //Image of model
        preg_match('/data-thumb_url=(.*?) src/', $file_model, $link_img_to_convert);
        $link_img = trim(str_replace('"', '', $link_img_to_convert[1]));
        $model['link_img'] = $link_img;
        $this->model['link_img'] = $model['link_img'];

        //Rank Monthly
        preg_match('/<span class=\"rank_number\">(.*?)<\/span>/', $file_model, $rank_monthly);
        $model['monthly'] = trim($rank_monthly[1]);
        $this->model['monthly'] = $model['monthly'];

        // preg_match('/<a\s+(?:[^>]*?\s+)?href=(["\'])(.*?)\1/', $file_model, $findHref);
        preg_match('/href=\"(.*?)\"/', $file_model, $username_model);

        $link_profile = "https://www.pornhub.com" . $username_model[1] . "";
        $content_profile = file_get_html("$link_profile", false, $context);

        $this->check_if_available($content_profile);

        return;
    }

    public function check_if_available($content_profile){
        if (preg_match('/<div class=\"geoBlocked\">/', $content_profile)) {
            $this->model['available'] = 0;
            $this->model['age'] = NULL;
            $this->model['birth_date'] = NULL;
            $this->model['joined'] = NULL;
            $this->model['videos'] = NULL;
            $this->model['visuals'] = NULL;
            $this->model['subscribers'] = NULL;
            $this->model['modelHub'] = NULL;
            $this->model['official_site'] = NULL;
            $this->model['twitter'] = NULL;
            $this->model['fan_centro'] = NULL;
            $this->model['instagram'] = NULL;
            //Ranks
            $this->model['weekly'] = NULL;
            $this->model['yearly'] = NULL;
            $this->model['last_month'] = NULL;
        }else{
            $this->get_personal_info($content_profile);
        }
        return ;
    }

    public function get_personal_info($content_profile){

        $this->model['available'] = 1;

        //Birth Date
        if (preg_match('/<span itemprop=\"birthDate\" class=\"smallInfo\">(.*?)<\/span/', $content_profile, $birthDate)) {
            $this->model['birth_date'] = date('Y/m/d', strtotime(ucwords(strtolower(trim($birthDate[1])))));
        } else {
            $this->model['birth_date'] = NULL;
        }

        //age
        preg_match_all('/<div class=\"infoPiece\">(.*?)<\/div>/', $content_profile, $info_piece);
        foreach ($info_piece[1] as $item) {
            if (preg_match('/Age/', $item)) {
                preg_match('/class=\"smallInfo\">\s+(.*?)<\/span/', $item, $age);
                $this->model['age'] = trim($age[1]);
            }
            if (empty($this->model['age'])) {
                $this->model['age'] = NULL;
            }

        }

        //Numbers of videos
        preg_match('/<div class=\"showingCounter pornstarVideosCounter\">(.*?)<\/div/', $content_profile, $n_videos);
        $n_videos_to_convert = trim($n_videos[1]);
        $videos = substr($n_videos_to_convert, strpos($n_videos_to_convert, 'of') + 3);
        $this->model['videos'] = intval($videos);

        //Visual for all videos
        preg_match('/<div class=\"tooltipTrig infoBox videoViews\"(.*?)<\/span>/', $content_profile, $n_video_visual);
        preg_match('/data-title=\"Video\sviews:\s(.*?)\"/', $n_video_visual[1], $n_visual);
        $this->model['visuals'] = intval(str_replace(',', '', $n_visual[1]));

        //Subscribers
        preg_match('/<\/div>\W+<div class=\"infoBox\">\s+(.*?)<\/span/', $content_profile, $n_subscribers);
        $str_subscribers = trim($n_subscribers[1]);
        $subscriber_to_convert  = trim(substr($str_subscribers, strpos($str_subscribers, '"big">') + 6));
        $this->model['subscribers'] = intval(format_num_to_thousands($subscriber_to_convert));

        //Joined (date from today)
        preg_match('/<span>\s+Joined:+(.*?)\s+ago/', $content_profile, $joined);
        $joined_date = trim(substr($joined[1], strpos($joined[1], 'Info">') + 6));
        $this->model['joined'] = date('Y/m/d', strtotime("-" . $joined_date));


        preg_match_all('/<div class=\"infoBox rankDetails\">(.*?)<\/div>/', $content_profile, $current_rank);


        //[1][0] Weekly rank
        preg_match('/\"big\">\s(.*?)\s<\/span/', $current_rank[1][0], $weekly);
        $this->model['weekly'] = trim($weekly[1]);

        //[1][1] Monthly rank not showing code because it's already taken at 75 line


        //[1][2] Last Month rank
        preg_match('/\"big\">\s(.*?)\s<\/span/', $current_rank[1][2], $last_month);
        $this->model['last_month'] = trim($last_month[1]);

        //[1][3] Yearly rank
        preg_match('/\"big\">\s(.*?)\s<\/span/', $current_rank[1][3], $yearly);
        $this->model['yearly'] = trim($yearly[1]);




        preg_match('/<ul class=\"clearfix socialList\">(.*?)<\/ul>/', $content_profile, $social_list);
        preg_match_all('/<li>(.*?)<\/li>/', $social_list[1], $all_social);

        foreach ($all_social[1] as $social){
            $this->get_external_links($social);
        }

        return ;
    }


    public function get_external_links($social){

        //ModelHUb
        if (!empty($this->last_name)) {
            if (preg_match('/target=\"_blank\">\s+ ' . $this->model_name . ' ' . $this->last_name. ' Modelhub/i', $social)) {
                preg_match('/<a href=\"(.*?)\"/', $social, $modelHub);
                $this->model['modelHub'] = $modelHub[1];
            }
        }

        if (empty($this->last_name)) {
            if (preg_match('/target=\"_blank\">\s+' . $this->model_name . ' Modelhub/i', $social, $test)) {
                preg_match('/<a href=\"(.*?)\"/', $social, $modelHub);
                $this->model['modelHub'] = $modelHub[1];
            }
        }
        if (empty($model['modelHub'])) {
            $this->model['modelHub'] = NULL;
        }


        //Official Site
        if (preg_match('/Official Site/', $social)) {
            preg_match('/<a href=\"(.*?)\"/', $social, $official_site);
            if(strlen($official_site[1])> 8) {
                $this->model['official_site'] = $official_site[1];
            }
        }
        if (empty($model['official_site'])) {
            $this->model['official_site'] = NULL;
        }

        //Twitter
        if (preg_match('/Twitter/', $social)) {
            preg_match('/<a href=\"(.*?)\"/', $social, $twitter);
            $this->model['twitter'] = $twitter[1];
        }
        if (empty($this->model['twitter'])) {
            $this->model['twitter'] = NULL;
        }

        //Instagram
        if (preg_match('/Instagram/', $social)) {
            preg_match('/<a href=\"(.*?)\"/', $social, $instagram);
            $this->model['instagram'] = $instagram[1];
        }
        if (empty($this->model['instagram'])) {
            $this->model['instagram'] = NULL;
        }

        //Fan Centro
        if (preg_match('/FanCentro/', $social)) {
            preg_match('/<a href=\"(.*?)\"/', $social, $fanCentro);
            $this->model['fan_centro'] = $fanCentro[1];
        }
        if (empty($this->model['fan_centro'])) {
            $this->model['fan_centro'] = NULL;
        }

        return ;
    }

    public function getModelsByPage($page){
        $context = $this->getContext();

        $all_models = [];
        for ($counter = 1; $counter <= $page; $counter++) {
            $url = 'https://it.pornhub.com/pornstars?o=t&performerType=amateur&page=' . $counter . '';
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.15) Gecko/20080623 Firefox/2.0.0.15"));
            curl_setopt($ch, CURLOPT_NOBODY, false);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $content = curl_exec($ch);
            curl_close($ch);


            preg_match_all('~<a class="js-mxp"(.*?)<span~si', $content, $models);

            foreach ($models[0] as $model) {
                preg_match('~href="/model/(.*?)"~si', $model, $username);
                $all_models[] = $username[1];
            }

        }
        return $all_models;
    }


  public function getModel($username){

        $url= 'https://www.pornhub.com/model/'. $username . '';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, Array("User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.15) Gecko/20080623 Firefox/2.0.0.15") );
        curl_setopt($ch, CURLOPT_NOBODY, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $content= curl_exec ($ch);
        curl_close ($ch);

        $model = [];

      if(preg_match('/<div class=\"geoBlocked\">/', $content)){
          //not available
          $model['model_name'] = str_replace('-', ' ', $username);
          $model['available'] = 0;
      }else {
          preg_match('/itemprop=\"name\">\s+(.*?)\s{2,}<\/h1>/', $content, $model_name);
          $model['available'] = 1;

          $model['model_name'] = $model_name[1];

          //Link img
          preg_match('~<img id="getAvatar" src="(.*?)"~', $content, $link_img);
          $model['link_img'] = $link_img[1];

          //Age
          preg_match_all('~<div class="infoPiece">(.*?)</div>~si', $content, $info_piece);

          foreach ($info_piece[1] as $item) {
              if (preg_match('~Age~', $item)) {
                  preg_match('~class="smallInfo">\s+(.*?)</span~', $item, $age);
                  $model['age'] = trim($age[1]);
              }
              if (empty($model['age'])) {
                  $model['age'] = NULL;
              }
              if (preg_match('~Birth date~', $item)) {
                  preg_match('~class="smallInfo">\s+(.*?)</span~', $item, $age);

              }

          }

          //birth date
          if (preg_match('~itemprop="birthDate" class="smallInfo">(.*?)</span~si', $content, $birthDate)) {
              $model['birth_date'] = date('Y/m/d', strtotime(ucwords(strtolower(trim($birthDate[1])))));
          } else {
              $model['birth_date'] = NULL;
          }


          //Ranks
          preg_match_all('~<div class="infoBox rankDetails">(.*?)</div>~si', $content, $current_rank);
          foreach ($current_rank[1] as $rank){

                if(preg_match('~Weekly rank~si', $rank)){
                    preg_match('~<span class="big">\s{1,}+(.*?)\s{1,}</span>~si', $rank, $week);
                    if($week[1] == 'N/A'){
                        $model['weekly_rank'] = 0;
                    }else {
                        $model['weekly_rank'] = $week[1];
                    }
                }
                if(preg_match('~Monthly rank~si', $rank)) {
                    preg_match('~<span class="big">\s{1,}+(.*?)\s{1,}</span>~si', $rank, $month);
                    if ($month[1] == 'N/A') {
                        $model['monthly_rank'] = 0;
                    } else {
                        $model['monthly_rank'] = $month[1];
                    }
                }
                if(preg_match('~Last month~si', $rank)){
                    preg_match('~<span class="big">\s{1,}+(.*?)\s{1,}</span>~si', $rank, $last_month);
                    if($last_month[1] == 'N/A'){
                        $model['last_month_rank'] = 0;
                    }else {
                        $model['last_month_rank'] = $last_month[1];
                    }
                }
                if(preg_match('~Yearly rank~si', $rank)){
                    preg_match('~<span class="big">\s{1,}+(.*?)\s{1,}</span>~si', $rank, $year);
                    if($year[1] == 'N/A'){
                        $model['yearly_rank'] = 0;
                    }else {
                        $model['yearly_rank'] = $year[1];
                    }
                }
          }


          preg_match('~<div class="showingCounter pornstarVideosCounter">(.*?)</div~si', $content, $n_videos);
          $n_videos_to_convert = trim($n_videos[1]);
          $videos = substr($n_videos_to_convert, strpos($n_videos_to_convert, 'of') + 3);
          $model['videos'] = intval($videos);

          //video views
          preg_match('~<div class="tooltipTrig infoBox videoViews" data-title="Video views:\s+(.*?)">~', $content, $video_views);
          $model['visuals'] = str_replace(',', '', trim($video_views[1]));

          //subscribers
          preg_match('~<div class="infoBox">(.*?)[\W\w]Subscribers\s{1,}</div>~si', $content, $for_subs);
          preg_match_all('~<div class="infoBox">(.*?)</div>~si', $for_subs[0], $infoBox_for_subs);
          foreach ($infoBox_for_subs[0] as $infoBox){
              if(preg_match('~Subscribers~si', $infoBox)){
                  preg_match('~<span class="big">\s{1,}+(.*?)\s{1,}</span>~si', $infoBox, $subs);
                  $model['subscribers'] = intval(format_num_to_thousands($subs[1]));
              }
          }

          //Joined
          preg_match('~<span>\s+Joined:+(.*?)\s+ago~si', $content, $joined);
          $joined_date = trim(substr($joined[1], strpos($joined[1], 'Info">') + 6));
          $model['joined'] = date('Y/m/d', strtotime("-" . $joined_date));


          //All socials
          preg_match('~<ul class="clearfix socialList">(.*?)</ul>~si', $content, $social_list);
          preg_match_all('~<li>(.*?)</li>~si', $social_list[1], $all_social);
          foreach ($all_social[1] as $social) {

              //ModelHUb
              if (preg_match('/target=\"_blank\">\s+ ' . $model['model_name'] . ' Modelhub/i', $social)) {
                  preg_match('/<a href=\"(.*?)\"/', $social, $model_hub);
                  $model['modelhub'] = $model_hub[1];

              }
              if(empty($model['modelhub'])){
                  $model['modelhub'] = NULL;
              }

              //Official Site
              if (preg_match('/Official Site/', $social)) {
                  preg_match('/<a href=\"(.*?)\"/', $social, $official_site_link);
                  if(strlen($official_site_link[1])> 8) {
                      $model['website'] = $official_site_link[1];

                  }
              }
              if (empty($model['website'])) {
                  $model['website'] = NULL;
              }

              //Twitter
              if (preg_match('/Twitter/', $social)) {
                  preg_match('/<a href=\"(.*?)\"/', $social, $twitter_link);
                  $model['twitter'] = $twitter_link[1];

              }
              if (empty($model['twitter'])) {
                  $model['twitter'] = NULL;
              }

              //Instagram
              if (preg_match('/Instagram/', $social)) {
                  preg_match('/<a href=\"(.*?)\"/', $social, $instagram_link);
                  $model['instagram'] = $instagram_link[1];

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
      return $model;
  }


}
