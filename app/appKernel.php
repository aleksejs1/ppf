<?php

$components = [
    'Ppf/Ppf',
    'HttpKernel/HttpKernel',
    'Response/Response',
    'TemplateEngine/TemplateEngine',
    'EventDispatcher/Events',
];

function getComponents()
{
    global $components;

    return $components;
}