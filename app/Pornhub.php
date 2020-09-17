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

  public function testScrape($url){

      $content = $this->getContent($url);
      $pornstar = [];

      if(preg_match_all('~class="infoBox rankDetails">(.*?)</span>~si', $content, $current_rank)){
          //weekly
          preg_match('~class="big">(.*)~si', $current_rank[1][0], $week);
          if(trim($week[1]) == 'N/A'){
              $pornstar['weekly'] = NULL;
          }else {
              $pornstar['weekly'] = intval(trim($week[1]));
          }

          //monthly
          preg_match('~class="big">(.*)~si', $current_rank[1][1], $month);
          if (trim($month[1]) == 'N/A') {
              $pornstar['monthly'] = NULL;
          } else {
              $pornstar['monthly'] = intval(trim($month[1]));
          }

          //last month
          preg_match('~class="big">(.*)~si', $current_rank[1][2], $last_month);
          if(trim($last_month[1]) == 'N/A'){
              $pornstar['last_month'] = NULL;
          }else {
              $pornstar['last_month'] = intval(trim($last_month[1]));
          }

          //yearly
          preg_match('~class="big">(.*)~si', $current_rank[1][3], $year);
          if(trim($year[1]) == 'N/A'){
              $pornstar['yearly'] = NULL;
          }else {
              $pornstar['yearly'] = intval(trim($year[1]));
          }
          echo '<hr>';
      }else{
          $pornstar['weekly'] = NULL;
          $pornstar['monthly'] = NULL;
          $pornstar['last_month'] = NULL;
          $pornstar['yearly'] = NULL;
      }


    return $pornstar;
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
          if(!preg_match('~href="javascript:void\(0\);"~si', $link[1])) {


              preg_match('~href="/' . $model . '/(.*?)"~si', $link[1], $username);

              $pornstar['username'] = $username[1];

              if (preg_match('~<i class="verifiedIcon"></i>~', $pornstar_info)) {
                  $pornstar['verified'] = 1;
              } else {
                  $pornstar['verified'] = 0;
              }

              $pornstar['type'] = $model;

              $all_pornstars[] = $pornstar;
          }
      }

        return $all_pornstars;

  }

  public function getPornstarByTypeAndUsername($type, $username){
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
          if(preg_match_all('~class="infoBox rankDetails">(.*?)</span>~si', $content, $current_rank)){

              //weekly
              preg_match('~class="big">(.*)~si', $current_rank[1][0], $week);
              if(trim($week[1]) == 'N/A'){
                  $pornstar['weekly'] = NULL;
              }else {
                  $pornstar['weekly'] = intval(trim($week[1]));
              }

              //monthly
              preg_match('~class="big">(.*)~si', $current_rank[1][1], $month);
              if (trim($month[1]) == 'N/A') {
                  $pornstar['monthly'] = NULL;
              } else {
                  $pornstar['monthly'] = intval(trim($month[1]));
              }

              //last month
              preg_match('~class="big">(.*)~si', $current_rank[1][2], $last_month);
              if(trim($last_month[1]) == 'N/A'){
                  $pornstar['last_month'] = NULL;
              }else {
                  $pornstar['last_month'] = intval(trim($last_month[1]));
              }

              //yearly
              preg_match('~class="big">(.*)~si', $current_rank[1][3], $year);
              if(trim($year[1]) == 'N/A'){
                  $pornstar['yearly'] = NULL;
              }else {
                  $pornstar['yearly'] = intval(trim($year[1]));
              }

          }else{
              $pornstar['weekly'] = NULL;
              $pornstar['monthly'] = NULL;
              $pornstar['last_month'] = NULL;
              $pornstar['yearly'] = NULL;
          }

          //How Many Videos
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

          //video views
          if(preg_match('~data-title="Video views:\s+(.*?)">~', $content, $video_views)){
              $pornstar['visuals'] = intval(str_replace(',', '', trim($video_views[1])));
          }else{
              $pornstar['visuals'] = NULL;
          }

          //Subscribers
          if(preg_match('~<div class="infoBox"><div class="title">Subscribers</div><span>(.*?)</span></div>~si', $content, $subs)){
              $pornstar['subscribers'] = intval(str_replace(',', '', $subs[1]));
          }elseif(preg_match('~<div class="infoBox">[\w\W]+<span class="big">\s{2,}+(.*?)\s{2,}</span>[\w\W]+<div class="title">\s{2,}Subscribers\s{2,}</div>~si', $content, $subs)){
              $pornstar['subscribers'] = intval(format_num_to_thousands($subs[1]));
          }else{
              $pornstar['subscribers'] = NULL;
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

      }

      return $pornstar;
  }


}
