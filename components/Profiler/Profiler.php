<?php

namespace Components\Profiler;

$timestamps = [];

function saveStart()
{
    global $timestamps;

    $timestamps['start'] = round(microtime(true) * 1000);
}

function saveEnd(&$data)
{
    global $timestamps;

    $timestamps['end'] = round(microtime(true) * 1000);

    $fullTime = $timestamps['end'] - $timestamps['start'];

    if($data[0] === 'html') {
        $data[1] .= '<div style="position: fixed; bottom: 0px; width: 100%; background: beige;">Full execution time: '.$fullTime.' ms</div>';
    }
}