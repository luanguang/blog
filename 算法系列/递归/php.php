<?php
ini_set('display_errors', true);
error_reporting(-1);

function fact($x)
{
    if ($x == 1) {
        return 1;
    }

    return $x * fact($x - 1);
}

echo fact(10);
