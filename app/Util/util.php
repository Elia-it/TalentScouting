<?php
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
