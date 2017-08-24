<?php

namespace Components\Ppf;

function getConfig($key)
{
    global $config;

    if (array_key_exists($key, $config)) {
        return $config[$key];
    }

    return false;
}

function preResp($data)
{
    error_log($data);
}