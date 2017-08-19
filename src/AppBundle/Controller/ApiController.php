<?php

namespace AppBundle\Controller;

use Components\Response;

function index()
{
    Response\response(['response' => 'it is api'], 'json');
}