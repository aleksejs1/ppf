<?php

namespace Components\Ppf;

function getConfig($key)
{
    global $config;

    return $config[$key];
}