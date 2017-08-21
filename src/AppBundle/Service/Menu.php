<?php

namespace AppBundle\Service\Menu;

function getMenu()
{
    return [
        [
            'link' => '',
            'title' => 'Main',
        ],
        [
            'link' => 'redirect',
            'title' => 'Redirect to API',
        ],
        [
            'link' => 'api',
            'title' => 'Api',
        ],
        [
            'link' => 'err',
            'title' => '500 Error',
        ],
    ];
}
