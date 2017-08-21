<?php

$components = [
    'Ppf/Ppf',
    'Response/Response',
    'TemplateEngine/TemplateEngine',
    'EventDispatcher/Events',
];

function getComponents()
{
    global $components;

    return $components;
}