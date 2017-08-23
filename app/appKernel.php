<?php

$components = [
    'Ppf/Ppf',
    'HttpKernel/HttpKernel',
    'Response/Response',
    'TemplateEngine/TemplateEngine',
    'Profiler/Profiler',
    'EventDispatcher/Events',
];

function getComponents()
{
    global $components;

    return $components;
}