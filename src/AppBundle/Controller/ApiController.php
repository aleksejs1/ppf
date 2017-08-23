<?php

namespace AppBundle\Controller;

use Components\Response;

function index()
{
    return Response\response(['response' => 'it is api'], 'json');
}