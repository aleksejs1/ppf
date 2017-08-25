<?php

function load(string $module) : void
{
    $file = getModulePath($module);
    if (!file_exists($file)) {
        trigger_error('Module '.$module.' not found!' ,E_USER_ERROR);
    }
    include $file;
}

function componentLoad(string $component) : void
{
    $file = \Components\HttpKernel\getComponentPath($component);
    if (!file_exists($file)) {
        trigger_error('Module '.$component.' not found!' ,E_USER_ERROR);
    }
    include $file;
}

function getModulePath(string $module) : string
{
    if (!$module || $module === '') {
        trigger_error('Attempt to load empty module!' ,E_USER_ERROR);
    }

    return __DIR__.'/../src/'.$module.'.php';
}
