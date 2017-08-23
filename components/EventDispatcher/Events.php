<?php

namespace Components\Events;

$events = [];

foreach (getEventListeners() as $event => $listener) {
    addListener($event, $listener);
}

function addListener($event, $listener)
{
    global $events;

    $events[$event][] = $listener;
}

function doEvent($event, $data)
{
    global $events;

    if (!array_key_exists($event, $events)) {
        return;
    }
    foreach ($events[$event] as $listener) {
        call_user_func($listener, $data);
    }
}