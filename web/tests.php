<?php

use Components\TEngine;

include(__DIR__.'/../components/TemplateEngine/Tests.php');

$results = [];

$results['templateEngine'] = TEngine\runTests();

foreach ($results as $key => $result) {
    echo '<h2>'.$key.'</h2>';
    $ok = 0;
    foreach ($result as $res) {
        if ($res['result'] === 'ok') {
            echo '<span style="background-color:green">ok</span> ';
            $ok++;
        } else {
            echo '<span style="background-color:red">bad</span> ';
        }
        echo $res['name'] . ': ' . $res['result'] . '<br>';
    }
    echo '<h3>Result: ' . $ok . '/' . count($result) . '</h3><hr>';
}

function assertEquals($a,$b) {
    if ($a === $b) {
        return 'ok';
    } else {
        return 'bad';
    }
}