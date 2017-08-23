<?php

use Components\Response;
use Components\HttpKernel;

require __DIR__.'/../app/loader.php';
require __DIR__.'/../components/HttpKernel/Kernel.php';

$request = HttpKernel\getFromGlobals();
$response = HttpKernel\handle($request);
Response\sendResponse($response);
