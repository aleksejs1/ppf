<?php

namespace Components\Profiler;

$timestamps = [];

function saveStart()
{
    global $timestamps;

    $timestamps['start'] = round(microtime(true) * 1000);
}

function saveEnd()
{
    global $timestamps;

    $timestamps['end'] = round(microtime(true) * 1000);

    $fullTime = $timestamps['end'] - $timestamps['start'];

    echo '<div style="position: fixed; bottom: 0px; width: 100%; background: beige;">Full execution time: '.$fullTime.' ms</div>';
}