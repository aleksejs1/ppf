<?php

namespace AppBundle\Controller;

use Components\Response;
use Components\TEngine;
use AppBundle\Service\Menu;

load('AppBundle/Service/Menu');

function index()
{
    $menu = Menu\getMenu();
    $r = TEngine\render('default', [
        'content' => 'it works',
        'menu' => $menu,
        'title' => 'Main'
    ]);
    Response\response($r);
}

function testError()
{
    trigger_error('Error',E_USER_ERROR);
    Response\response('');
}

function testRedirect()
{
    Response\redirect('api');
}