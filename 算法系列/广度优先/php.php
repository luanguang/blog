<?php
function person_is_seller($name)
{
    return $name[0] == 'm';
}

function search_seller($name)
{
    $search = [];
    $search = array_merge($search, $graph[$name]);
    $searched = [];
    while (!empty($search)) {
        $person = array_shift($search);
        if (!in_array($person, $searched)) {
            if (person_is_seller($person)) {
                return $person . 'is a mango seller';
            }
            $search = array_merge($search, $person);
            $searched[] = $person;
        }
    }
    return false;
}