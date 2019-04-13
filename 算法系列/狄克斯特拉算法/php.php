<?php
$processed = [];
$graph['start']['a'] = 6;
$graph['start']['b'] = 2;
$graph['a']['fin'] = 1;
$graph['b']['a'] = 3;
$graph['b']['fin'] = 5;
$graph['fin'] = [];
$costs['a'] = 6;
$costs['b'] = 2;
$costs['fin'] = 99999;
$parents['a'] = 'start';
$parents['b'] = 'start';
$parents['fin'] = null;

function find_lowest_cost_node($costs)
{
    global $processed;
    $lowest_cost = 99999;
    $lowest_cost_node = null;
    foreach ($costs as $key => $cost) {
        if (($cost < $lowest_cost) && !in_array($key, $processed)) {
            $lowest_cost = $cost;
            $lowest_cost_node = $key;
        }
    }
    return $lowest_cost_node;
}

$node = find_lowest_cost_node($costs);
while (!is_null($node)) {
    $cost = $costs[$node];
    $neighbors = $graph[$node];
    foreach (array_keys($neighbors) as $value) {
        $new_cost = $cost + $neighbors[$value];
        if ($costs[$value] > $new_cost) {
            $costs[$value] = $new_cost;
            $parents[$value] = $node;
        }
    }
    $processed[] = $node;
    $node = find_lowest_cost_node($costs);
}