<?php
function double_search(array $list, $item)
{
    $high = count($list) - 1;
    $low = 0;
    $i = 1;
    while ($low <= $high) {
        $mid = ceil(($high + $low) / 2);
        if ($list[$mid] == $item) {
            return '查询了' . $i . '次，位置是' . $mid;
        }
        if ($list[$mid] < $item) {
            $low = $mid + 1;
        }
        if ($list[$mid] > $item) {
            $high = $mid - 1;
        }
        $i++;
    }
    return '不存在';
}
