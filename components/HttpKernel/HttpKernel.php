<?php

namespace Components\HttpKernel;

use Components\Events;

function httpHandle ($request)
{
    Events\doEvent('kernel_request', $request);

    $current_route = str_replace($request['server']['REDIRECT_BASE'].'/', '', $request['server']['REDIRECT_URL']);
    $routes = getRoutes();
    if (!array_key_exists($current_route, $routes)) {
        return ['html','404'];
    }
    load($routes[$current_route][0] . '/Controller/' . $routes[$current_route][1] . 'Controller');

    return call_user_func($routes[$current_route][0] . '\\' . 'Controller\\' . $routes[$current_route][2]);
}
