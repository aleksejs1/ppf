<?php

set_error_handler('errorHandler');
register_shutdown_function('shutdownHandler');
error_reporting(E_ALL);
ini_set('display_errors', 0);

include __DIR__ . '/../app/config/config.php';
include __DIR__.'/../app/appKernel.php';

loadComponents();


function loadComponents() : void
{
    foreach (getComponents() as $component)
    {
        $file = getComponentPath($component);
        if (!file_exists($file)) {
            trigger_error('Componen '.$component.' not found!' ,E_USER_ERROR);
        }
        include $file;
    }
}

function getComponentPath(string $component) : string
{
    if (!$component || $component === '') {
        trigger_error('Wrong component array configuration!' ,E_USER_ERROR);
    }

    return __DIR__.'/../components/'.$component.'.php';
}

function load(string $module) : void
{
    $file = getModulePath($module);
    if (!file_exists($file)) {
        trigger_error('Module '.$module.' not found!' ,E_USER_ERROR);
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


function errorHandler($error_level, $error_message, $error_file, $error_line, $error_context)
{
    $error = 'Level: '.$error_level.'; Message:'.$error_message.' (File:'.$error_file.' : '.$error_line.')';
    switch ($error_level) {
        case E_ERROR:
        case E_CORE_ERROR:
        case E_COMPILE_ERROR:
        case E_PARSE:
            ppfLog($error, 'fatal');
            break;
        case E_USER_ERROR:
        case E_RECOVERABLE_ERROR:
            ppfLog($error, 'error');
            break;
        case E_WARNING:
        case E_CORE_WARNING:
        case E_COMPILE_WARNING:
        case E_USER_WARNING:
            ppfLog($error, 'warn');
            break;
        case E_NOTICE:
        case E_USER_NOTICE:
            ppfLog($error, 'info');
            break;
        case E_STRICT:
            ppfLog($error, 'debug');
            break;
        default:
            ppfLog($error, 'warn');
    }
}

function shutdownHandler()
{
    $lasterror = error_get_last();
    switch ($lasterror['type'])
    {
        case E_ERROR:
        case E_CORE_ERROR:
        case E_COMPILE_ERROR:
        case E_USER_ERROR:
        case E_RECOVERABLE_ERROR:
        case E_CORE_WARNING:
        case E_COMPILE_WARNING:
        case E_PARSE:
            $error = '[FATAL] Level:'.$lasterror['type'].'; Message:'.$lasterror['message'].' (File:'.
                $lasterror['file'].' : '.$lasterror['line'].')';
            ppfLog($error, 'fatal');
    }
}

function ppfLog($error, $errorLevel)
{
    print('500! ' . $errorLevel . ': ' . $error);
    exit;
}