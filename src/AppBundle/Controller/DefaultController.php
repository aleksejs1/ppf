<?php

namespace AppBundle\Controller;

use Components\Response;
use Components\TEngine;
use Components\Database;
use AppBundle\Service\Menu;

load('AppBundle/Service/Menu');

function index()
{
    $menu = Menu\getMenu();
    $r = TEngine\render('default', [
        'content' => 'it works',
        'menu' => $menu,
        'menu2' => [
            [
                'link' => 'aaa',
                'title' => 'bbb',
                'menu2in' => [
                    [
                        'link' => 'aaa2',
                        'title' => 'bbb2',
                    ]
                ]
            ]
        ],
        'title' => 'Main'
    ]);

//    db test
//    print_r(Database\fetchOne('user', 2));
//    print_r(Database\fetchOne('user', 4));
//    Database\save('user', ['id' => 1, 'name' => rand(1,10)]);

    return Response\response($r);
}

function testError()
{
    trigger_error('Error',E_USER_ERROR);
    return Response\response('');
}

function testRedirect()
{
    return Response\redirect('api');
}