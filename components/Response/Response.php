<?php

namespace Components\Response;

use Components\Ppf;

function response($data, $format = null)
{
    if (!$format) {
        $format = Ppf\getConfig('format');
    }

    if ($format === 'json') {
        header("Content-Type: application/json");
        header("Cache-Control: no-cache, must-revalidate");
        print json_encode($data, JSON_PRETTY_PRINT);
        exit;
    }
    if ($format === 'text') {
        if (is_array($data)) {
            $data = implode("|",$data);
        }
        if (is_string($data)) {
            print($data);
        }
        exit;
    }
    exit;
}

function redirect($url)
{
    header('Location: '.$url);
    exit;
}

function responseWithCode($message, $status)
{
    header($message, true, $status);
}