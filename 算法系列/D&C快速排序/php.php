<?php
function quicksort($arr)
{
    if (count($arr) < 2) {
        return $arr;
    } else {
        $pivot = $arr[0];
        $less = [];
        $newArr = array_slice($arr, 1);
        foreach ($newArr as $value) {
            if ($value <= $pivot) {
                $less[] = $value;
            }
        }
        $greater = [];
        foreach ($newArr as $val) {
            if ($val > $pivot) {
                $greater[] = $val;
            }
        }
        return array_merge(quicksort($less), [$pivot], quicksort($greater));
    }
}

$arr = [5, 4, 7, 9];
print_r(quicksort($arr));
