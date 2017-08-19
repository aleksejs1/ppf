<?php

namespace Components\TEngine;

function render($template, $data)
{
    $result = file_get_contents(__DIR__.'/../../app/Resources/views/'.$template.'.html');

    $result = preg_replace_callback(
        '/\{{2}(.*?)\}{2}/is',
        function ($matches) use ($data) {
            $key = trim($matches[1]);
            if (array_key_exists($key, $data)) {
                return $data[$key];
            }

            return '';
        },
        $result);

    return $result;
}
