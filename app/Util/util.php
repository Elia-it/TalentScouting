<?php
function test_parsing(){
    $models = [];
    $m =[];
    for ($i = 0; $i<3; $i++){
        $m['first_name'] = 'Marta';
        $m['last_name'] = 'Giri';

        $models[] = $m;
    }

    return $models;
}
