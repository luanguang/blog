<?php
$states_needed = ['mt', 'wa', 'or', 'id', 'nv', 'ut', 'ca', 'az'];
$stations['kone'] = ['id', 'nv', 'ut'];
$stations['ktwo'] = ['wa', 'id', 'mt'];
$stations['kthree'] = ['or', 'nv', 'ca'];
$stations['kfour'] = ['nv', 'ut'];
$stations['kfive'] = ['ca', 'az'];

$final = [];
while (!empty($states_needed)) {
    $best = [];
    $states_cover = [];
    foreach ($stations as $key => $value) {
        $covered = array_intersect($states_needed, $value);
        if (count($covered) > count($states_cover)) {
            $best = $key;
            $states_cover = $value;
        }
    }
    $states_needed = array_diff($states_needed, $states_cover);
    $final[] = $best;
}

print_r($final);
