<?php

namespace Components\Events;

$events = [];

function addListener($event, $listener)
{
    global $events;

    $events[$event][] = $listener;
}

function doEvent($event, $data)
{
    global $events;

    foreach ($events[$event] as $listener) {
        call_user_func($listener, $data);
    }
}