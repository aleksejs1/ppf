<?php

//require __DIR__.'/../app/loader.php';
require __DIR__.'/../app/kernel.php';

$current_route = str_replace($_SERVER['REDIRECT_BASE'].'/', '', $_SERVER['REDIRECT_URL']);

if (!array_key_exists($current_route, $routes)) {
    response('404');
}

load($routes[$current_route][0].'/Controller/'.$routes[$current_route][1].'Controller');
call_user_func($routes[$current_route][0].'\\'.'Controller\\'.$routes[$current_route][2]);

