<?php


namespace App;

use App\AmateurModel;

class Pornhub
{

    private function getContent($url){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, Array("User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.15) Gecko/20080623 Firefox/2.0.0.15") );
        curl_setopt($ch, CURLOPT_NOBODY, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $content= curl_exec ($ch);
        curl_close ($ch);

        return $content;
    }

    public function getModelsByPage($page){

        $all_models = [];

        $url = 'https://it.pornhub.com/pornstars?o=t&performerType=amateur&page=' . $page . '';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.15) Gecko/20080623 Firefox/2.0.0.15"));
        curl_setopt($ch, CURLOPT_NOBODY, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $content = curl_exec($ch);
        curl_close($ch);


//            preg_match_all('~<a class="js-mxp"(.*?)<span~si', $content, $models);
//
//            foreach ($models[0] as $model) {
//                preg_match('~href="/model/(.*?)"~si', $model, $username);
//                $all_models[] = $username[1];
//            }

        preg_match_all('~<li class="modelLi">(.*?)</li>~si', $content, $modelLi);
        foreach ($modelLi[0] as $model_info){
            $model = [];
            preg_match('~<a class="js-mxp"(.*?)<span~si', $model_info, $link);
            preg_match('~href="/model/(.*?)"~si', $link[1], $username);
            $model['username'] = $username[1];

            if(preg_match('~<i class="verifiedIcon"></i>~', $model_info)){
                $model['verified'] = 1;
            }else{
                $model['verified'] = 0;
            }

            $all_models[] = $model;
        }
        return $all_models;
    }


  public function getModel($username){

        $url= 'https://www.pornhub.com/model/'. $username . '';
        $content = $this->getContent($url);

        $model = [];

        $model['type'] = 'model';
        $model['username'] = $username;

      if(preg_match('/<div class=\"geoBlocked\">/', $content)){
          //not available
          $model['model_name'] = str_replace('-', ' ', $username);
          $model['available'] = 0;
      }else {
          $model['available'] = 1;
          if(preg_match('/itemprop=\"name\">\s+(.*?)\s{2,}<\/h1>/', $content, $model_name)){
              $model['model_name'] = $model_name[1];
          }else{
              $model['model_name'] = $username;
          }


          //Link img
          if(preg_match('~<img id="getAvatar" src="(.*?)"~', $content, $link_img)){
              $model['link_img'] = $link_img[1];
          }else{
              $model['link_img'] = NULL;
          }

          //Age
          if(preg_match_all('~<div class="infoPiece">(.*?)</div>~si', $content, $info_piece)){
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
          }else{
              $model['age'] = NULL;
          }

          //birth date
          if (preg_match('~itemprop="birthDate" class="smallInfo">(.*?)</span~si', $content, $birthDate)) {
              $model['birth_date'] = date('Y/m/d', strtotime(ucwords(strtolower(trim($birthDate[1])))));
          } else {
              $model['birth_date'] = NULL;
          }


          //Ranks
          if(preg_match_all('~<div class="infoBox rankDetails">(.*?)</div>~si', $content, $current_rank)) {


              foreach ($current_rank[1] as $rank) {

                  if (preg_match('~Weekly rank~si', $rank)) {
                      preg_match('~<span class="big">\s{1,}+(.*?)\s{1,}</span>~si', $rank, $week);
                      if ($week[1] == 'N/A') {
                          $model['weekly_rank'] = 0;
                      } else {
                          $model['weekly_rank'] = $week[1];
                      }
                  }
                  if (preg_match('~Monthly rank~si', $rank)) {
                      preg_match('~<span class="big">\s{1,}+(.*?)\s{1,}</span>~si', $rank, $month);
                      if ($month[1] == 'N/A') {
                          $model['monthly_rank'] = 0;
                      } else {
                          $model['monthly_rank'] = $month[1];
                      }
                  }
                  if (preg_match('~Last month~si', $rank)) {
                      preg_match('~<span class="big">\s{1,}+(.*?)\s{1,}</span>~si', $rank, $last_month);
                      if ($last_month[1] == 'N/A') {
                          $model['last_month_rank'] = 0;
                      } else {
                          $model['last_month_rank'] = $last_month[1];
                      }
                  }
                  if (preg_match('~Yearly rank~si', $rank)) {
                      preg_match('~<span class="big">\s{1,}+(.*?)\s{1,}</span>~si', $rank, $year);
                      if ($year[1] == 'N/A') {
                          $model['yearly_rank'] = 0;
                      } else {
                          $model['yearly_rank'] = $year[1];
                      }
                  }
              }
          }else{
              $model['weekly_rank'] = 0;
              $model['monthly_rank'] = 0;
              $model['last_month_rank'] = 0;
              $model['yearly_rank'] = 0;
          }

          //How many videos
          if(preg_match('~<div class="showingCounter pornstarVideosCounter">(.*?)</div~si', $content, $n_videos)) {

              $n_videos_to_convert = trim($n_videos[1]);
              $videos = substr($n_videos_to_convert, strpos($n_videos_to_convert, 'of') + 3);
              $model['videos'] = intval($videos);
          }else{
              $model['videos'] = NULL;
          }

          //video views
          if(preg_match('~<div class="tooltipTrig infoBox videoViews" data-title="Video views:\s+(.*?)">~', $content, $video_views)){
              $model['visuals'] = str_replace(',', '', trim($video_views[1]));
          }else{
              $model['visuals'] = NULL;
          }

          //subscribers
          if(preg_match('~<div class="infoBox">(.*?)[\W\w]Subscribers\s{1,}</div>~si', $content, $for_subs)){
              preg_match_all('~<div class="infoBox">(.*?)</div>~si', $for_subs[0], $infoBox_for_subs);
              foreach ($infoBox_for_subs[0] as $infoBox){
                  if(preg_match('~Subscribers~si', $infoBox)){
                      preg_match('~<span class="big">\s{1,}+(.*?)\s{1,}</span>~si', $infoBox, $subs);
                      $model['subscribers'] = intval(format_num_to_thousands($subs[1]));
                  }
              }
          }else{
              $model['subscribers'] = NULL;
          }


          //Joined
          if(preg_match('~<span>\s+Joined:+(.*?)\s+ago~si', $content, $joined)){
              $joined_date = trim(substr($joined[1], strpos($joined[1], 'Info">') + 6));
              $model['joined'] = date('Y/m/d', strtotime("-" . $joined_date));
          }else{
              $model['joined'] = NULL;
          }


          //All socials
          if(preg_match('~<ul class="clearfix socialList">(.*?)</ul>~si', $content, $social_list)) {


              preg_match_all('~<li>(.*?)</li>~si', $social_list[1], $all_social);
              foreach ($all_social[1] as $social) {

                  //ModelHUb
                  if (preg_match('/target=\"_blank\">\s+ ' . $model['model_name'] . ' Modelhub/i', $social)) {
                      preg_match('/<a href=\"(.*?)\"/', $social, $model_hub);
                      $model['modelhub'] = $model_hub[1];

                  }
                  if (empty($model['modelhub'])) {
                      $model['modelhub'] = NULL;
                  }

                  //Official Site
                  if (preg_match('/Official Site/', $social)) {
                      preg_match('/<a href=\"(.*?)\"/', $social, $official_site_link);
                      if (strlen($official_site_link[1]) > 8) {
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
          }else{
              $model['modelhub'] = NULL;
              $model['website'] = NULL;
              $model['twitter'] = NULL;
              $model['instagram'] = NULL;
              $model['fan_centro'] = NULL;
          }
      }
      return $model;
  }



  public function getPornstarByPage($page){

      $all_pornstars = [];

      $url = 'https://www.pornhub.com/pornstars?o=t&performerType=pornstar&page=' . $page . '';
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_HTTPHEADER, array("User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.15) Gecko/20080623 Firefox/2.0.0.15"));
      curl_setopt($ch, CURLOPT_NOBODY, false);
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      $content = curl_exec($ch);
      curl_close($ch);



      preg_match_all('~<li class="pornstarLi">(.*?)</li>~si', $content, $pornstarLi);
      foreach ($pornstarLi[0] as $pornstar_info){
          $pornstar = [];
          preg_match('~<a class="js-mxp"(.*?)<span~si', $pornstar_info, $link);
          preg_match('~href="/pornstar/(.*?)"~si', $link[1], $username);
          $pornstar['username'] = $username[1];

          if(preg_match('~<i class="verifiedIcon"></i>~', $pornstar_info)){
              $pornstar['verified'] = 1;
          }else{
              $pornstar['verified'] = 0;
          }

          $all_pornstars[] = $pornstar;
      }


      return $all_pornstars;
  }

  public function getPornstar($username){
      $url= 'https://www.pornhub.com/pornstar/'. $username . '';
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL,$url);
      curl_setopt($ch, CURLOPT_HTTPHEADER, Array("User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.15) Gecko/20080623 Firefox/2.0.0.15") );
      curl_setopt($ch, CURLOPT_NOBODY, false);
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      $content= curl_exec ($ch);
      curl_close ($ch);

      $pornstar = [];

      $pornstar['type'] = 'pornstar';
      $pornstar['username'] = $username;


      if(preg_match('/<div class=\"geoBlocked\">/', $content)){
          //not available
          $pornstar['pornstar_name'] = str_replace('-', ' ', $username);
          $pornstar['available'] = 0;
      }else {
          $pornstar['available'] = 1;
          if(preg_match('/<h1 itemprop=\"name\">\s+(.*?)\s{2,}<\/h1>/', $content, $pornstar_name)){
              $pornstar['pornstar_name'] = trim($pornstar_name[1]);
          }elseif(preg_match('~div class="name">[\W\w]+<h1>(.*?)</h1>~si', $content, $pornstar_name)){
              $pornstar['pornstar_name'] = trim($pornstar_name[1]);
          }elseif(preg_match('~<div class="name">[\w\W]+<h1>\s{2,}?(.*?)\s{2,}</h1>~si', $content, $pornstar_name)){
              $pornstar['pornstar_name'] = trim($pornstar_name[1]);
          }else{
              $pornstar['pornstar_name'] = $username;
          }


          //Link img
          if(preg_match('~id="getAvatar"~', $content)){
              preg_match('~<img id="getAvatar" src="(.*?)"~', $content, $link_img);
              $pornstar['link_img'] = $link_img[1];
          }else{
              $pornstar['link_img'] = NULL;
          }

          //Age
          if(preg_match_all('~<div class="infoPiece">(.*?)</div>~si', $content, $info_piece)){
              foreach ($info_piece[1] as $item) {


                  if (preg_match('~Age~', $item)) {
                      preg_match('~class="smallInfo">\s?+(.*?)</span~si', $item, $age);

                      $pornstar['age'] = trim($age[1]);
                  }
                  if (empty($pornstar['age'])) {
                      $pornstar['age'] = NULL;
                  }
                  if (preg_match('~Birth date~', $item)) {
                      preg_match('~class="smallInfo">\s+(.*?)</span~', $item, $age);

                  }

              }
          }else{
              $pornstar['age'] = NULL;
          }

          //birth date
          if (preg_match('~itemprop="birthDate" class="smallInfo">(.*?)</span~si', $content, $birthDate)) {
              $pornstar['birth_date'] = date('Y/m/d', strtotime(ucwords(strtolower(trim($birthDate[1])))));
          } else {
              $pornstar['birth_date'] = NULL;
          }


          //Ranks
          if(preg_match_all('~class="infoBox rankDetails">(.*?)</span></div>~si', $content, $current_rank)){
              foreach ($current_rank[0] as $rank){

                  if(preg_match('~Weekly rank~si', $rank)){
                      preg_match('~<span class="big">(.*?)</span>~si', $rank, $week);
                      //<div class="infoBox rankDetails"><span class="big">10</span><div class="title">Weekly Rank</div></div>
                      if($week[1] == 'N/A'){
                          $pornstar['weekly_rank'] = 0;
                      }else {
                          $pornstar['weekly_rank'] = $week[1];
                      }
                  }
                  if(preg_match('~Monthly rank~si', $rank)) {
                      preg_match('~<span class="big">(.*?)</span>~si', $rank, $month);
                      if ($month[1] == 'N/A') {
                          $pornstar['monthly_rank'] = 0;
                      } else {
                          $pornstar['monthly_rank'] = $month[1];
                      }
                  }
                  if(preg_match('~Last month~si', $rank)){
                      preg_match('~<span class="big">(.*?)</span>~si', $rank, $last_month);
                      if($last_month[1] == 'N/A'){
                          $pornstar['last_month_rank'] = 0;
                      }else {
                          $pornstar['last_month_rank'] = $last_month[1];
                      }
                  }
                  if(preg_match('~Yearly rank~si', $rank)){
                      preg_match('~<span class="big">(.*?)</span>~si', $rank, $year);
                      if($year[1] == 'N/A'){
                          $pornstar['yearly_rank'] = 0;
                      }else {
                          $pornstar['yearly_rank'] = $year[1];
                      }
                  }
              }
          }else{
              $pornstar['weekly_rank'] = 0;
              $pornstar['monthly_rank'] = 0;
              $pornstar['last_month_rank'] = 0;
              $pornstar['yearly_rank'] = 0;
          }


          //How Many Videos
          if(preg_match('~<div class="showingCounter pornstarVideosCounter">(.*?)</div~si', $content, $n_videos)){
              $n_videos_to_convert = trim($n_videos[1]);
              $videos = substr($n_videos_to_convert, strpos($n_videos_to_convert, 'of') + 3);
              $pornstar['videos'] = intval($videos);
          }else{
              $pornstar['videos'] = NULL;
          }

          //video views
          if(preg_match('~data-title="Video views:\s+(.*?)">~', $content, $video_views)){
              $pornstar['visuals'] = str_replace(',', '', trim($video_views[1]));
          }else{
              $pornstar['visuals'] = NULL;
          }

          //subscribers
//          preg_match('~<div class="infoBox">(.*?)[\W\w]Subscribers\s{1,}</div>~si', $content, $for_subs);
//          preg_match_all('~<div class="infoBox">(.*?)</div>~si', $for_subs[0], $infoBox_for_subs);
//          foreach ($infoBox_for_subs[0] as $infoBox){
//              if(preg_match('~Subscribers~si', $infoBox)){
//                  preg_match('~<span class="big">\s{1,}+(.*?)\s{1,}</span>~si', $infoBox, $subs);
//                  $model['subscribers'] = intval(format_num_to_thousands($subs[1]));
//              }
//          }

//         Subscribers
          if(preg_match('~class="title">Subscribers</div><span>(.*?)</span>~si', $content, $subs)){
              $pornstar['subscribers'] = intval(str_replace(',', '', $subs[1]));

          }elseif(preg_match('~<div class="infoBox">(.*?)[\W\w]Subscribers\s{1,}</div>~si', $content, $for_subs)){

              preg_match('~div class="infoBox subscribers">[\W\w]+<span class="big">(.*?)</span~si', $for_subs[0], $subs);
              $pornstar['subscribers'] = intval(format_num_to_thousands($subs[1]));
          }else{
              $pornstar['subscribers'] = NULL;
          }

          //Joined
          if(preg_match('~<span>\s?+Joined:+(.*?)\s+ago~si', $content, $joined)) {
              $joined_date = trim(substr($joined[1], strpos($joined[1], 'Info">') + 6));
              $pornstar['joined'] = date('Y/m/d', strtotime("-" . $joined_date));
          }else{
              $pornstar['joined'] = NULL;
          }


          //All socials
          if(preg_match('~<ul class="socialList"~', $content)) {

              preg_match('~<ul class="socialList">(.*?)</ul>~si', $content, $social_list);
              preg_match_all('~<li>(.*?)</li>~si', $social_list[1], $all_social);
              foreach ($all_social[1] as $social) {

                  //ModelHUb
                  if (preg_match('/target=\"_blank\">\s+ ' . $pornstar['pornstar_name'] . ' Modelhub/i', $social)) {
                      preg_match('/href=\"(.*?)\"/', $social, $model_hub);
                      $pornstar['modelhub'] = $model_hub[1];

                  }
                  if (empty($pornstar['modelhub'])) {
                      $pornstar['modelhub'] = NULL;
                  }

                  //Official Site
                  if (preg_match('/Official Site/', $social)) {
                      preg_match('/href=\"(.*?)\"/', $social, $official_site_link);

                      if (strlen($official_site_link[1]) > 8) {
                          $pornstar['website'] = $official_site_link[1];

                      }
                  }
                  if (empty($pornstar['website'])) {
                      $pornstar['website'] = NULL;
                  }

                  //Twitter
                  if (preg_match('/Twitter/', $social)) {
                      preg_match('/href=\"(.*?)\"/', $social, $twitter_link);
                      $pornstar['twitter'] = $twitter_link[1];

                  }
                  if (empty($pornstar['twitter'])) {
                      $pornstar['twitter'] = NULL;
                  }

                  //Instagram
                  if (preg_match('/Instagram/', $social)) {
                      preg_match('/href=\"(.*?)\"/', $social, $instagram_link);
                      $pornstar['instagram'] = $instagram_link[1];

                  }
                  if (empty($pornstar['instagram'])) {
                      $pornstar['instagram'] = NULL;
                  }

                  //Fan Centro
                  if (preg_match('/FanCentro/', $social)) {
                      preg_match('/href=\"(.*?)\"/', $social, $fanCentro);
                      $pornstar['fan_centro'] = $fanCentro[1];

                  }
                  if (empty($pornstar['fan_centro'])) {
                      $pornstar['fan_centro'] = NULL;
                  }
              }
          }else{
              // No Socials
              $pornstar['modelhub'] = NULL;
              $pornstar['website'] = NULL;
              $pornstar['twitter'] = NULL;
              $pornstar['instagram'] = NULL;
              $pornstar['fan_centro'] = NULL;
          }
      }
      return $pornstar;
  }

  public function testScrape(){
      $url= 'https://www.pornhub.com/model/eva-elfie';
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL,$url);
      curl_setopt($ch, CURLOPT_HTTPHEADER, Array("User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.15) Gecko/20080623 Firefox/2.0.0.15") );
      curl_setopt($ch, CURLOPT_NOBODY, false);
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      $content= curl_exec ($ch);
      curl_close ($ch);

      if(preg_match_all('~<div class="showingCounter pornstarVideosCounter">(.*?)</div~si', $content, $n_videos)){
          $videos = 0;
           foreach ($n_videos[0] as $n_video){

               $n_videos_to_convert = trim($n_video);
               $videos = $videos + intval(substr($n_videos_to_convert, strpos($n_videos_to_convert, 'of') + 3));

           }

          $pornstar['videos'] = $videos;
      }else{
          $pornstar['videos'] = NULL;
      }
      return ;

  }



  public function getPornstarsByPage($type, $page){
      // $type = amateur/pornstar $page = number page

        $url = 'https://www.pornhub.com/pornstars?o=t&performerType='. $type .'&page=' . $page . '';
        $content = $this->getContent($url);

        $all_pornstars = [];

        if($type == 'amateur'){
            $model = 'model';
            preg_match_all('~<li class="modelLi">(.*?)</li>~si', $content, $pornstarLi);
        }elseif($type == 'pornstar'){
            $model = 'pornstar';
            preg_match_all('~<li class="pornstarLi">(.*?)</li>~si', $content, $pornstarLi);
        }else{
            die;
        }

        foreach ($pornstarLi[0] as $pornstar_info){
            $pornstar = [];
            preg_match('~<a class="js-mxp"(.*?)<span~si', $pornstar_info, $link);

            preg_match('~href="/'. $model .'/(.*?)"~si', $link[1], $username);
            $pornstar['username'] = $username[1];

            if(preg_match('~<i class="verifiedIcon"></i>~', $pornstar_info)){
                $pornstar['verified'] = 1;
            }else{
                $pornstar['verified'] = 0;
            }

            $pornstar['type'] = $model;

            $all_pornstars[] = $pornstar;
        }

        return $all_pornstars;

  }

  public function getPornstarTest($type, $username){
        // $type = model/pornstar $username = Pornstar's username

        $url = 'https://www.pornhub.com/' . $type . '/'. $username . '';
        $content = $this->getContent($url);


      if(preg_match('/<div class=\"geoBlocked\">/', $content)){
          //not available
          $pornstar['available'] = 0;
          //username
          $pornstar['username'] = $username;
          //full_name
          $pornstar['full_name'] = str_replace('-', ' ', $username);


      }else{
          //available
          $pornstar['available'] = 1;
          //username
          $pornstar['username'] = $username;

          //full_name
          if(preg_match('~itemprop="name">\s{2,}?(.*?)\s{2,}?</h1>~si', $content, $pornstar_name)){
              $pornstar['full_name'] = trim($pornstar_name[1]);
          } elseif(preg_match('~<div class="name">[\W\w]+<h1>\s{2,}?(.*?)\s{2,}?</h1>~si', $content, $pornstar_name)) {
              $pornstar['full_name'] = trim($pornstar_name[1]);
          } else {
              $pornstar['full_name'] = str_replace('-', ' ', $username);
          }

          //link_img
          if(preg_match('~id="getAvatar"~', $content)){
              preg_match('~<img id="getAvatar" src="(.*?)"~', $content, $link_img);
              $pornstar['link_img'] = $link_img[1];
          }elseif(preg_match('~class="thumbImage">(.*?)</div~si', $content, $image)) {
              preg_match('~src="(.*?)"~si', $image[1], $link_img);
              $pornstar['link_img'] = $link_img[1];
          }else{

              $pornstar['link_img'] = NULL;
          }

          //age
          if(preg_match_all('~<div class="infoPiece">(.*?)</div>~si', $content, $info_piece)){
              foreach ($info_piece[1] as $item) {


                  if (preg_match('~Age~', $item)) {
                      preg_match('~class="smallInfo">\s?+(.*?)</span~si', $item, $age);

                      $pornstar['age'] = intval(trim($age[1]));
                  }
                  if (empty($pornstar['age'])) {
                      $pornstar['age'] = NULL;
                  }
                  if (preg_match('~Birth date~', $item)) {
                      preg_match('~class="smallInfo">\s+(.*?)</span~', $item, $age);

                  }

              }
          }else{
              $pornstar['age'] = NULL;
          }

          //birth_date
          if (preg_match('~itemprop="birthDate" class="smallInfo">(.*?)</span~si', $content, $birthDate)) {
              $pornstar['birth_date'] = date('Y/m/d', strtotime(ucwords(strtolower(trim($birthDate[1])))));
          } else {
              $pornstar['birth_date'] = NULL;
          }

          //Ranks
          if(preg_match_all('~class="infoBox rankDetails">(.*?)</span></div>~si', $content, $current_rank)){
              foreach ($current_rank[0] as $rank){

                  if(preg_match('~Weekly rank~si', $rank)){
                      preg_match('~<span class="big">(.*?)</span>~si', $rank, $week);
                      //<div class="infoBox rankDetails"><span class="big">10</span><div class="title">Weekly Rank</div></div>
                      if($week[1] == 'N/A'){
                          $pornstar['weekly'] = 0;
                      }else {
                          $pornstar['weekly'] = intval($week[1]);
                      }
                  }
                  if(preg_match('~Monthly rank~si', $rank)) {
                      preg_match('~<span class="big">(.*?)</span>~si', $rank, $month);
                      if ($month[1] == 'N/A') {
                          $pornstar['monthly'] = 0;
                      } else {
                          $pornstar['monthly'] = intval($month[1]);
                      }
                  }
                  if(preg_match('~Last month~si', $rank)){
                      preg_match('~<span class="big">(.*?)</span>~si', $rank, $last_month);
                      if($last_month[1] == 'N/A'){
                          $pornstar['last_month'] = 0;
                      }else {
                          $pornstar['last_month'] = intval($last_month[1]);
                      }
                  }
                  if(preg_match('~Yearly rank~si', $rank)){
                      preg_match('~<span class="big">(.*?)</span>~si', $rank, $year);
                      if($year[1] == 'N/A'){
                          $pornstar['yearly'] = 0;
                      }else {
                          $pornstar['yearly'] = intval($year[1]);
                      }
                  }
              }
          }else{
              $pornstar['weekly'] = 0;
              $pornstar['monthly'] = 0;
              $pornstar['last_month'] = 0;
              $pornstar['yearly'] = 0;
          }

          //How Many Videos
          if(preg_match_all('~<div class="showingCounter pornstarVideosCounter">(.*?)</div~si', $content, $n_videos)){
              $videos = 0;
              foreach ($n_videos[0] as $n_video){

                  $n_videos_to_convert = trim($n_video);
                  $videos = $videos + intval(substr($n_videos_to_convert, strpos($n_videos_to_convert, 'of') + 3));

              }

              $pornstar['video'] = $videos;
          }else{
              $pornstar['video'] = NULL;
          }

          //video views
          if(preg_match('~data-title="Video views:\s+(.*?)">~', $content, $video_views)){
              $pornstar['visual'] = intval(str_replace(',', '', trim($video_views[1])));
          }else{
              $pornstar['visual'] = NULL;
          }

          //Subscribers
          if(preg_match('~<div class="infoBox"><div class="title">Subscribers</div><span>(.*?)</span></div>~si', $content, $subs)){
              $pornstar['subscriber'] = intval(str_replace(',', '', $subs[1]));
          }elseif(preg_match('~<div class="infoBox">[\w\W]+<span class="big">\s{2,}+(.*?)\s{2,}</span>[\w\W]+<div class="title">\s{2,}Subscribers\s{2,}</div>~si', $content, $subs)){
              $pornstar['subscriber'] = intval(format_num_to_thousands($subs[1]));
          }else{
              $pornstar['subscriber'] = NULL;
          }

          //Joined
          if(preg_match('~<span>\s{1,}?Joined:+(.*?)\s+ago~si', $content, $joined)) {
              //model
              $joined_date = trim(substr($joined[1], strpos($joined[1], 'Info">') + 6));
              $pornstar['joined_date'] = date('Y/m/d', strtotime("-" . $joined_date));
          }elseif(preg_match('~<span>\s?+Joined:+(.*?)\s+ago~si', $content, $joined)) {
              //pornstar
              $joined_date = trim(substr($joined[1], strpos($joined[1], 'Info">') + 6));
              $pornstar['joined_date'] = date('Y/m/d', strtotime("-" . $joined_date));
          }else{
              $pornstar['joined_date'] = NULL;
          }

          //All socials
//          if(preg_match('~<ul class="socialList"~', $content)) {
//
//              preg_match('~<ul class="socialList">(.*?)</ul>~si', $content, $social_list);
//              preg_match_all('~<li>(.*?)</li>~si', $social_list[1], $all_social);
//              foreach ($all_social[1] as $social) {
//
//                  //ModelHUb
//                  if (preg_match('/target=\"_blank\">\s+ ' . $pornstar['full_name'] . ' Modelhub/i', $social)) {
//                      preg_match('/href=\"(.*?)\"/', $social, $model_hub);
//                      $pornstar['modelhub'] = $model_hub[1];
//
//                  }
//                  if (empty($pornstar['modelhub'])) {
//                      $pornstar['modelhub'] = NULL;
//                  }
//
//                  //Official Site
//                  if (preg_match('/Official Site/', $social)) {
//                      preg_match('/href=\"(.*?)\"/', $social, $official_site_link);
//
//                      if (strlen($official_site_link[1]) > 8) {
//                          $pornstar['website'] = $official_site_link[1];
//
//                      }
//                  }
//                  if (empty($pornstar['website'])) {
//                      $pornstar['website'] = NULL;
//                  }
//
//                  //Twitter
//                  if (preg_match('/Twitter/', $social)) {
//                      preg_match('/href=\"(.*?)\"/', $social, $twitter_link);
//                      $pornstar['twitter'] = $twitter_link[1];
//
//                  }
//                  if (empty($pornstar['twitter'])) {
//                      $pornstar['twitter'] = NULL;
//                  }
//
//                  //Instagram
//                  if (preg_match('/Instagram/', $social)) {
//                      preg_match('/href=\"(.*?)\"/', $social, $instagram_link);
//                      $pornstar['instagram'] = $instagram_link[1];
//
//                  }
//                  if (empty($pornstar['instagram'])) {
//                      $pornstar['instagram'] = NULL;
//                  }
//
//                  //Fan Centro
//                  if (preg_match('/FanCentro/', $social)) {
//                      preg_match('/href=\"(.*?)\"/', $social, $fanCentro);
//                      $pornstar['fan_centro'] = $fanCentro[1];
//
//                  }
//                  if (empty($pornstar['fan_centro'])) {
//                      $pornstar['fan_centro'] = NULL;
//                  }
//              }
//          }else{
//              // No Socials
//              $pornstar['modelhub'] = NULL;
//              $pornstar['website'] = NULL;
//              $pornstar['twitter'] = NULL;
//              $pornstar['instagram'] = NULL;
//              $pornstar['fan_centro'] = NULL;
//          }

          //All socials
          if(preg_match('~<ul class="(clearfix\s)?+socialList">(.*?)</ul>~si', $content, $social_list)) {


              preg_match_all('~<li>(.*?)</li>~si', $social_list[2], $all_social);
              foreach ($all_social[1] as $social) {

                  //ModelHUb
                  if (preg_match('/target=\"_blank\">\s+ ' . $pornstar['full_name'] . ' Modelhub/i', $social)) {
                      preg_match('~href="(.*?)"~si', $social, $model_hub);
                      $pornstar['modelhub'] = $model_hub[1];

                  }
                  if (empty($pornstar['modelhub'])) {
                      $pornstar['modelhub'] = NULL;
                  }

                  //Official Site
                  if (preg_match('/Official Site/', $social)) {
                      preg_match('/href=\"(.*?)\"/', $social, $official_site_link);
                      if (strlen($official_site_link[1]) > 8) {
                          $pornstar['website'] = $official_site_link[1];

                      }
                  }
                  if (empty($pornstar['website'])) {
                      $pornstar['website'] = NULL;
                  }

                  //Twitter
                  if (preg_match('/Twitter/', $social)) {
                      preg_match('/href=\"(.*?)\"/', $social, $twitter_link);
                      $pornstar['twitter'] = $twitter_link[1];

                  }
                  if (empty($pornstar['twitter'])) {
                      $pornstar['twitter'] = NULL;
                  }

                  //Instagram
                  if (preg_match('/Instagram/i', $social)) {
                      preg_match('~href="(.*?)"~si', $social, $instagram_link);
                      $pornstar['instagram'] = $instagram_link[1];

                  }
                  if (empty($pornstar['instagram'])) {
                      $pornstar['instagram'] = NULL;
                  }

                  //Fan Centro
                  if (preg_match('/FanCentro/', $social)) {
                      preg_match('/href=\"(.*?)\"/', $social, $fanCentro);
                      $pornstar['fan_centro'] = $fanCentro[1];

                  }
                  if (empty($pornstar['fan_centro'])) {
                      $pornstar['fan_centro'] = NULL;
                  }
              }
          }else{
              $pornstar['modelhub'] = NULL;
              $pornstar['website'] = NULL;
              $pornstar['twitter'] = NULL;
              $pornstar['instagram'] = NULL;
              $pornstar['fan_centro'] = NULL;
          }


//          if(preg_match('~class="title">Subscribers</div><span>(.*?)</span>~si', $content, $subs)){
//              $pornstar['subscribers'] = intval(str_replace(',', '', $subs[1]));
//
//          }elseif(preg_match('~<div class="infoBox">(.*?)[\W\w]Subscribers\s{1,}</div>~si', $content, $for_subs)){
//              preg_match_all('~<div class="infoBox">(.*?)</div>~si', $for_subs[0], $infoBox_for_subs);
//              foreach ($infoBox_for_subs[0] as $infoBox){
//                  if(preg_match('~Subscribers~si', $infoBox)){
//                      preg_match('~<span class="big">\s{1,}+(.*?)\s{1,}</span>~si', $infoBox, $subs);
//                      $model['subscribers'] = intval(format_num_to_thousands($subs[1]));
//                  }
//              }
//          }elseif(preg_match('~<div class="infoBox">(.*?)[\W\w]Subscribers\s{1,}</div>~si', $content, $for_subs)){
//              preg_match('~div class="infoBox subscribers">[\W\w]+<span class="big">(.*?)</span~si', $for_subs[0], $subs);
//              $pornstar['subscribers'] = intval(format_num_to_thousands($subs[1]));
//          }else{
//              $pornstar['subscribers'] = NULL;
//          }
      }

      return $pornstar;
  }

}
