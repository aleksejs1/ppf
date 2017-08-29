<?php

namespace Components\TEngine;

function render($template, $data, $blocks = [])
{
    $file = __DIR__.'/../../app/Resources/views/'.$template.'.html';
    if (!file_exists($file)) {
        trigger_error('Template '.$file.' not found!' ,E_USER_ERROR);
    }
    $result = file_get_contents($file);

    $result = doInclude($result);

    $result = parseTemplate($result, $data, $blocks);

    $result = cleanTags($result);

    return $result;
}

function parseTemplate($template, $data, $blocks = [])
{
    $parsedString = $template;

    $parsedString = replaceTemplateBlocksToDefined($parsedString, $blocks);

    if (substr(trim($template),0,10) === '{{extends ' ) {
        $parent = substr(trim($template),10,strpos($template, '}}') - 10);
        $parsedString = preg_replace_callback(
            '/{{block(\b(?:(?R)|(?:(?!{{\/?block).))*){{\/block}}/is',
            function ($matches) use  (&$blocks) {
                $keyEndPosition = strpos($matches[1], '}}');
                $key = trim(substr($matches[1], 0, $keyEndPosition));
                $templatePart = substr($matches[1], $keyEndPosition+2);
                $blocks[$key] = $templatePart;

                return '';
            },
            $parsedString);

        return render($parent, $data, $blocks);
    }


    $parsedString = preg_replace_callback(
        '/{{for(\b(?:(?R)|(?:(?!{{\/?for).))*){{\/for}}/is',
        function ($matches) use  ($data) {
            $parsedPart = '';
            $loopKeyEndPosition = strpos($matches[1], '}}');
            $loopKey = trim(substr($matches[1], 0, $loopKeyEndPosition));
            $templatePart = substr($matches[1], $loopKeyEndPosition+2);

            foreach ($data[$loopKey] as $loopPart) {
                $parsedPart .= parseTemplate($templatePart, $loopPart);
            }

            return $parsedPart;
        },
        $parsedString);


    $parsedString =  preg_replace_callback(
        '/\{{2}(.*?)\}{2}/is',
        function ($matches) use ($data) {
            $key = trim($matches[1]);

            if (array_key_exists($key, $data)) {
                return $data[$key];
            }

            return '{{'.$key.'}}';
        },
        $parsedString);

    return $parsedString;
}

function replaceTemplateBlocksToDefined($template, $blocks)
{
    return preg_replace_callback(
        '/{{block(\b(?:(?R)|(?:(?!{{\/?block).))*){{\/block}}/is',
        function ($matches) use  ($blocks) {
            $keyEndPosition = strpos($matches[1], '}}');
            $key = trim(substr($matches[1], 0, $keyEndPosition));
            $templatePart = substr($matches[1], $keyEndPosition+2);

            if (array_key_exists($key, $blocks)) {
                return '{{block '.$key.'}}'.insertIntoBlockParentBlocks($blocks[$key], $templatePart).'{{/block}}';
            }

            return '{{block '.$key.'}}'.$templatePart.'{{/block}}';
        },
        $template);
}

function insertIntoBlockParentBlocks($block, $parent)
{
    return strtr ($block, array ('{{parent()}}' => $parent));
}

function cleanTags($parsedString)
{
    $parsedString =  preg_replace_callback(
        '/\{{2}(.*?)\}{2}/is',
        function ($matches) {
            return '';
        },
        $parsedString);

    return $parsedString;
}

function doInclude($template)
{
    $parsedString = preg_replace_callback(
        '/({{include[^}}]*\/}})/',
        function ($matches) {
            $template = trim(rtrim(ltrim($matches[0], '{{include'),'/}}'));
            $file = __DIR__.'/../../app/Resources/views/'.$template.'.html';
            if (!file_exists($file)) {
                trigger_error('Template '.$file.' not found!' ,E_USER_ERROR);
            }
            $result = file_get_contents($file);
            $result = doInclude($result);

            return $result;
        },
        $template);

    return $parsedString;
}