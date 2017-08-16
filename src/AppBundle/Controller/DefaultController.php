<?php

namespace AppBundle\Controller;

use AppBundle\Service\Menu;

load('AppBundle/Service/Menu');

function index()
{
    $menu = Menu\getMenu();

    $r = render('default', [
        'content' => 'it works',
        'menu' => $menu,
        'title' => 'Main'
    ]);
    response($r);
}

function testRedirect()
{
    redirect('api');
}