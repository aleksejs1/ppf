<?php

$routes = [
    '' =>  ['AppBundle','Default','index'],
    'ext' =>  ['AppBundle','Default','ext'],
    'redirect' =>  ['AppBundle','Default','testRedirect'],
    'api' => ['AppBundle','Api','index'],
    'err' => ['AppBundle','Default','testError'],
];

function getRoutes()
{
    global $routes;

    return $routes;
}