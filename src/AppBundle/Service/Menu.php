<?php

namespace AppBundle\Service\Menu;

function getMenu()
{
    return getLink('','Main').getLink('redirect','Redirect to API').getLink('api','Api');
}

function getLink($link, $title)
{
    return sprintf('<a href="%s" class="btn btn-light">%s</a>', $link, $title);
}