<?php

namespace Components\TEngine;

function render($templateName, $data)
{
    $filePath = getFilePath($templateName);
    $template = file_get_contents($filePath);
    $parsedTemplate = parseTemplate($template, $data);

    return cleanUnparsedTags($parsedTemplate);
}

function getFilePath($templateName)
{
    $path = __DIR__.'/../../app/Resources/views/'.$templateName.'.html';
    if (!file_exists($path)) {
        trigger_error('Template '.$path.' not found!' ,E_USER_ERROR);
    }

    return $path;
}

function parseTemplate($template, $data)
{
    $template = parseIncludes($template);
    $template = replaceTemplateBlocksToDefined($template, getBlocksData($data));

    if (isTemplateExtension($template)) {
        $blocks = getBlocks($template, getBlocksData($data));
        $template = getExtensionName($template);
        $data = setBlocksData($data, $blocks);

        return render($template, $data);
    }

    $template = parseLoops($template, $data);
    $template = parseVariables($template, $data);

    return $template;
}


function parseIncludes($template)
{
    return preg_replace_callback(
        '/({{include[^}}]*\/}})/',
        function ($matches) {
            $templateName = getTemplateNameFromTag($matches[0]);
            $filePath = getFilePath($templateName);
            $includedTemplate = file_get_contents($filePath);

            return parseIncludes($includedTemplate);
        },
        $template);
}

function getTemplateNameFromTag($rawTag)
{
    return trim(rtrim(ltrim($rawTag, '{{include'),'/}}'));
}

function replaceTemplateBlocksToDefined($template, $blocks)
{
    return preg_replace_callback(
        '/{{block(\b(?:(?R)|(?:(?!{{\/?block).))*){{\/block}}/is',
        function ($matches) use  ($blocks) {
            $key = getKeyFromRawTag($matches[1]);
            $templatePart = getTemplatePartFromRawTag($matches[1]);

            if (hasBlockInBlocks($key, $blocks)) {
                $block = insertIntoBlockParentBlocks($blocks[$key], $templatePart);

                return makeRawBlock($key, $block);
            }

            return makeRawBlock($key,$templatePart);
        },
        $template);
}

function getKeyFromRawTag($rawTag)
{
    return trim(substr($rawTag, 0, getKeyEndPosition($rawTag)));
}

function getKeyEndPosition($rawTag)
{
    return strpos($rawTag, '}}');
}

function getTemplatePartFromRawTag($rawTag)
{
    return substr($rawTag, getKeyEndPosition($rawTag) + 2);
}

function hasBlockInBlocks($key, $blocks)
{
    return array_key_exists($key, $blocks);
}

function makeRawBlock($blockName, $blockBody)
{
    return '{{block '.$blockName.'}}'.$blockBody.'{{/block}}';
}

function insertIntoBlockParentBlocks($block, $parent)
{
    return strtr ($block, array ('{{parent()}}' => $parent));
}

function isTemplateExtension($template)
{
    return substr(trim($template),0,10) === '{{extends ';
}

function getBlocks($template, $blocks)
{
    preg_replace_callback(
        '/{{block(\b(?:(?R)|(?:(?!{{\/?block).))*){{\/block}}/is',
        function ($matches) use  (&$blocks) {
            $key = getKeyFromRawTag($matches[1]);
            $templatePart = getTemplatePartFromRawTag($matches[1]);
            $blocks[$key] = $templatePart;

            return '';
        },
        $template);

    return $blocks;
}

function getBlocksData($data)
{
    if (!array_key_exists('__blocks', $data)) {
        $data['__blocks'] = [];
    }

    return $data['__blocks'];
}

function getExtensionName($template)
{
    $template = trim($template);

    return substr($template,10,strpos($template, '}}') - 10);
}

function setBlocksData($data, $blocks)
{
    $data['__blocks'] = $blocks;

    return $data;
}

function parseLoops($template, $data)
{
    return preg_replace_callback(
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
        $template);
}

function parseVariables($template, $data)
{
    return preg_replace_callback(
        '/\{{2}(.*?)\}{2}/is',
        function ($matches) use ($data) {
            $key = trim($matches[1]);

            if (array_key_exists($key, $data)) {
                return $data[$key];
            }

            return '{{'.$key.'}}';
        },
        $template);
}

function cleanUnparsedTags($parsedString)
{
    $parsedString =  preg_replace_callback(
        '/\{{2}(.*?)\}{2}/is',
        function ($matches) {
            return '';
        },
        $parsedString);

    return $parsedString;
}
