<?php

include __DIR__ . '/../../app/config/params.php';

$config = [
    'env' => 'dev',
    'format' => $params['format'],
    'database' => [
        'host' => $params['database_host'],
        'name' => $params['database_name'],
        'user' => $params['database_user'],
        'pass' => $params['database_pass'],
    ],
];

include __DIR__ . '/../../app/config/route.php';
include __DIR__ . '/../../app/config/services.php';