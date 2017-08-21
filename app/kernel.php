<?php

use Components\Response;

require __DIR__ . '/../app/config/config.php';
require __DIR__.'/../app/appKernel.php';

set_error_handler("errorHandler");
register_shutdown_function("shutdownHandler");

error_reporting(E_ALL);
ini_set("display_errors", 0);

foreach ($components as $component)
{
    require __DIR__.'/../components/'.$component.'.php';
}

function load($what)
{
    include __DIR__.'/../src/'.$what.'.php';
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
            $error = '[SHUTDOWN] Level:'.$lasterror['type'].'; Message:'.$lasterror['message'].' (File:'.
                $lasterror['file'].' : '.$lasterror['line'].')';
            ppfLog($error, 'fatal');
    }
}

function ppfLog($error, $errlvl)
{
    Response\responseWithCode($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', 500);
    print('500! ' . $error);
    exit;
}