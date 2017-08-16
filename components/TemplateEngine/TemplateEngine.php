<?php

function render($template, $data) {
    $result = file_get_contents(__DIR__.'/../../app/Resources/views/'.$template.'.html');

    foreach ($data as $key => $value) {
        $result = str_replace('{{'.$key.'}}',$value,$result);
    }
    $result = str_replace('{{','',$result);
    $result = str_replace('}}','',$result);

    return $result;
}