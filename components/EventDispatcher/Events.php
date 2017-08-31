<?php

namespace Components\Events;

function init()
{
    foreach (getEventListeners() as $event => $listener) {
        addListener($event, $listener);
    }
}

function events($action = 'get', $data = [])
{
    static $events = [];

    switch ($action) {
        case 'get':
            return $events;
        break;
        case 'set':
            $events = $data;
        break;
    }
}

function addListener($event, $listener)
{
    $events = events('get');
    $events[$event][] = $listener;
    events('set', $events);
}

function doEvent($event, &$data)
{
    $events = events('get');
    if (!array_key_exists($event, $events)) {
        return;
    }
    foreach ($events[$event] as $listener) {
        call_user_func_array($listener, [&$data]);
    }
}