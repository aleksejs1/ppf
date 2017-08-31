<?php

namespace Components\Profiler;

function init()
{
}

function timestamps($action = 'get', $data = [])
{
    static $timestamps = [];

    switch ($action) {
        case 'get':
            return $timestamps;
            break;
        case 'set':
            $timestamps = $data;
            break;
    }
}

function saveStart()
{
    $timestamps = timestamps('get');
    $timestamps['start'] = round(microtime(true) * 1000);
    timestamps('set', $timestamps);
}

function saveEnd(&$data)
{
    $timestamps = timestamps('get');
    $timestamps['end'] = round(microtime(true) * 1000);
    timestamps('set', $timestamps);

    $fullTime = $timestamps['end'] - $timestamps['start'];

    if($data[0] === 'html') {
        $data[1] .= '<div style="position: fixed; bottom: 0px; width: 100%; background: beige;">Full execution time: '.$fullTime.' ms</div>';
    }
}