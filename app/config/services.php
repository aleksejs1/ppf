<?php

$eventListener = [
    'kernel_request' => '\Components\Profiler\saveStart',
    'kernel_response' => '\Components\Profiler\saveEnd',
];


function getEventListeners()
{
    global $eventListener;

    return $eventListener;
}