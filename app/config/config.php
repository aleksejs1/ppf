<?php

include __DIR__ . '/../../app/config/params.php';

$config = [
    'env' => 'dev',
    'format' => $params['format'],
];

include __DIR__ . '/../../app/config/route.php';