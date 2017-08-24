<?php

include __DIR__ . '/../../app/config/params.php';

$config = [
    'env' => 'dev',
    'format' => $params['format'],
    'database_host' => $params['database_host'],
    'database_name' => $params['database_name'],
    'database_user' => $params['database_user'],
    'database_pass' => $params['database_pass'],
];

include __DIR__ . '/../../app/config/route.php';
include __DIR__ . '/../../app/config/services.php';