<?php

namespace Components\Response;

use Components\Ppf;
use Components\Events;

function init()
{
}

function response($data, $format = null)
{
//    Events\doEvent('pre_response', 'test');

    if (!$format) {
        $format = Ppf\getConfig('format');
    }

    if ($format === 'json') {
        return [
            'json',
            $data
        ];
    }
    if ($format === 'text') {
        return [
            'html',
            $data
        ];
    }
}

function redirect($url)
{
    return [
        'redirect',
        $url
    ];
}

function responseWithCode($message, $status)
{
    header($message, true, $status);
}

function sendResponse($response)
{
    Events\doEvent('kernel_response', $response);
    $responseType = $response[0];
    $data = $response[1];
    if ($responseType === 'html') {
        if (is_array($data)) {
            $data = implode("|",$data);
        }
        if (is_string($data)) {
            print($data);
        }
    } else if ($responseType === 'json') {
        header("Content-Type: application/json");
        header("Cache-Control: no-cache, must-revalidate");
        print json_encode($data, JSON_PRETTY_PRINT);
    } else if ($responseType === 'redirect') {
        header('Location: '.$data);
    }

    exit;
}