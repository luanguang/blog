<?php
ini_set('display_errors', true);
error_reporting(-1);
function findMin($arr)
{
    $min = $arr[0];
    $min_index = 0;
    foreach ($arr as $key => $value) {
        if ($arr[$key] < $min) {
            $min = $arr[$key];
            $min_index = $key;
        }
    }
    return $min_index;
}

function selectSort($arr)
{
    $new = [];
    $len = count($arr);
    for ($i = 0; $i < $len; $i++) {
        $min = findMin($arr);
        $new[] = $arr[$min];
        unset($arr[$min]);
        $arr = array_values($arr);
    }
    return $new;
}

// function selectSort($arrs)
// {
//     $len = count($arrs);
//     for ($i = 0; $i < $len - 1; $i++) {
//         for ($j = $i + 1; $j < $len; $j++) {
//             if ($arrs[$i] > $arrs[$j]) {
//                 $temp = $arrs[$j];
//                 $arrs[$j] = $arrs[$i];
//                 $arrs[$i] = $temp;
//             }
//         }
//     }

//     return $arrs;
// }

$arrs = [2, 5, 3, 7, 1, 4];
print_r(selectSort($arrs));
