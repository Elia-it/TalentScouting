<?php
use App\PornstarRank;

function number_of_pagination($n_rows, $n_rows_for_page){
        $n_pagination = ceil($n_rows, $n_rows_for_page);
}


function format_num_to_thousands($num) {

    $check = $num;
    $convert_num = $num;

    if (strpos(strtoupper($check), "K") != false) {
        $s = rtrim($check, "kK");
        $convert_num = floatval($check) * 1000;
    } else if (strpos(strtoupper($check), "M") != false) {
        $s = rtrim($check, "mM");
        $convert_num =  floatval($check) * 1000000;
    } else {
        $convert_num = floatval($check);
    }

    return $convert_num;
}

function xlp($num) {

    $check = $num;
    $real_num = $num;

    if (strpos(strtoupper($check), "K") != false) {
        $check = rtrim($check, "kK");
        $real_num = floatval($check) * 1000;
    } else if (strpos(strtoupper($check), "M") != false) {
        $check = rtrim($check, "mM");
        $real_num=  floatval($check) * 1000000;
    } else {
        $check = floatval($check);
    }

    return $check;
}

function getAverageVideo_Visuals($video, $visual){
    if($video != 0 && $visual != 0){
        $average = $visual / $video;
        $average_fixed = number_format($average, 2, '.', ' \'');
    }else{
        $average_fixed = 0;
    }


    return $average_fixed;
}

function getIncreaseByPercentual($original_field, $new_field){

        $increase_tot = intval($new_field) - intval($original_field);
        if($original_field == 0){
            $original_field = 1;
        }
        $percentual_increase = ($increase_tot/$original_field) * 100;

        $res = floatval(number_format($percentual_increase, 3, '.', ''));

        return $res;
}

function getData($date){

//    dd($date['to'], $date['from']);

    if(!empty($date['from']) AND !empty($date['to'])){

        //    SUBSCRIBER INCREASE BY VISUAL
        $increase_video = getIncreaseByPercentual($date['from']->videos, $date['to']->videos);
        $increase['videos'] = $increase_video;

//    SUBSCRIBER INCREASE BY VISUAL
        $increase_visual = getIncreaseByPercentual($date['from']->visuals, $date['to']->visuals);
        $increase['visuals'] = $increase_visual;


//    SUBSCRIBER INCREASE BY PERCENTUAL
        $increase_subscribers = getIncreaseByPercentual($date['from']->subscribers, $date['to']->subscribers);
        $increase['subscribers'] = $increase_subscribers;
    }else{
        $increase['videos'] = 0;
        $increase['visuals'] = 0;
        $increase['subscribers'] = 0;
    }



    return $increase;
}
