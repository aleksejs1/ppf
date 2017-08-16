<?php

require __DIR__ . '/../app/config/config.php';
require __DIR__.'/../app/appKernel.php';

foreach ($components as $component) {
    require __DIR__.'/../components/'.$component.'.php';
}