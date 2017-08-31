<?php

namespace Components\TemplateEngine;

include('TemplateEngine.php');

function runTests()
{
    $result = [];

    $d['name'] = 'getFilePath()';
    $d['result'] = assertEquals(
        strpos(getFilePath('test1'),'components\TemplateEngine/../../app/Resources/views/test1.html') !== false,
        true
    );
    $result[] = $d;

    $d['name'] = 'getTemplateNameFromTag()';
    $d['result'] = assertEquals(getTemplateNameFromTag('{{include testFile/}}'), 'testFile');
    $result[] = $d;

    $d['name'] = 'getKeyFromRawTag()';
    $d['result'] = assertEquals(getKeyFromRawTag(' content}} Trololol '), 'content');
    $result[] = $d;

    $d['name'] = 'getKeyEndPosition()';
    $d['result'] = assertEquals(getKeyEndPosition(' content}} Trololol '), 8);
    $result[] = $d;

    $d['name'] = 'getTemplatePartFromRawTag()';
    $d['result'] = assertEquals(getTemplatePartFromRawTag('content}} Trololol '), ' Trololol ');
    $result[] = $d;

    $d['name'] = 'hasBlockInBlocks() #1';
    $d['result'] = assertEquals(hasBlockInBlocks('content',[]), false);
    $result[] = $d;

    $d['name'] = 'hasBlockInBlocks() #2';
    $d['result'] = assertEquals(hasBlockInBlocks('content',['content' => 'Trololol']), true);
    $result[] = $d;

    $d['name'] = 'hasBlockInBlocks() #3';
    $d['result'] = assertEquals(hasBlockInBlocks('content',['title' => 'Trololol']), false);
    $result[] = $d;

    $d['name'] = 'makeRawBlock()';
    $d['result'] = assertEquals(makeRawBlock('block','content'), '{{block block}}content{{/block}}');
    $result[] = $d;

    $d['name'] = 'isTemplateExtension() #1';
    $d['result'] = assertEquals(isTemplateExtension('{{extends test}} {{block aa}}bb{{/block}}'), true);
    $result[] = $d;

    $d['name'] = 'isTemplateExtension() #2';
    $d['result'] = assertEquals(isTemplateExtension('{{block aa}}bb{{/block}}'), false);
    $result[] = $d;

    $d['name'] = 'isTemplateExtension()';
    $d['result'] = assertEquals(isTemplateExtension('{{block aa}}bb{{/block}}'), false);
    $result[] = $d;

    $d['name'] = 'replaceTemplateBlocksToDefined() #1';
    $d['result'] = assertEquals(
        replaceTemplateBlocksToDefined('{{block aa}}bb{{/block}}', ['aa' => 'cc']),
        '{{block aa}}cc{{/block}}'
    );
    $result[] = $d;

    $d['name'] = 'replaceTemplateBlocksToDefined() #2';
    $d['result'] = assertEquals(
        replaceTemplateBlocksToDefined('{{block aa}}bb{{/block}}', ['dd' => 'cc']),
        '{{block aa}}bb{{/block}}'
    );
    $result[] = $d;

    $d['name'] = 'insertIntoBlockParentBlocks()';
    $d['result'] = assertEquals(
        insertIntoBlockParentBlocks('{{block aa}}bb{{parent()}}{{/block}}','cc'),
        '{{block aa}}bbcc{{/block}}'
    );
    $result[] = $d;

    $d['name'] = 'getBlocks() #1';
    $d['result'] = assertEquals(getBlocks('{{block aa}}bb{{/block}}',[]), ['aa'=>'bb']);
    $result[] = $d;

    $d['name'] = 'getBlocks() #2';
    $d['result'] = assertEquals(getBlocks('{{block aa}}bb{{/block}}',['aa'=>'cc']), ['aa'=>'bb']);
    $result[] = $d;

    $d['name'] = 'getBlocks() #3';
    $d['result'] = assertEquals(getBlocks('{{block aa}}bb{{/block}}',['dd'=>'ee']), ['dd'=>'ee','aa'=>'bb']);
    $result[] = $d;

    $d['name'] = 'getBlocksData() #1';
    $d['result'] = assertEquals(getBlocksData(['aa'=>'bb']),[]);
    $result[] = $d;

    $d['name'] = 'getBlocksData() #2';
    $d['result'] = assertEquals(getBlocksData(['aa'=>'bb','__blocks'=>['cc'=>'dd']]),['cc'=>'dd']);
    $result[] = $d;

    $d['name'] = 'setBlocksData() #1';
    $d['result'] = assertEquals(setBlocksData(['aa'=>'bb'],['cc'=>'dd']),['aa'=>'bb','__blocks'=>['cc'=>'dd']]);
    $result[] = $d;

    $d['name'] = 'setBlocksData() #2';
    $d['result'] = assertEquals(
        setBlocksData(['aa'=>'bb','__blocks'=>['cc'=>'dd']],['ee'=>'ff']),
        ['aa'=>'bb','__blocks'=>['ee'=>'ff']]
    );
    $result[] = $d;

    $d['name'] = 'getExtensionName()';
    $d['result'] = assertEquals(getExtensionName('{{extends test}} {{block aa}}bb{{/block}}'),'test');
    $result[] = $d;

    $d['name'] = 'cleanUnparsedTags()';
    $d['result'] = assertEquals(cleanUnparsedTags('{{test}} {{block aa}}bb{{/block}}'),' bb');
    $result[] = $d;

    $d['name'] = 'parseVariables()';
    $d['result'] = assertEquals(parseVariables('{{test}}bb{{ cc}}',['test'=>'aa','cc'=>'cc']),'aabbcc');
    $result[] = $d;

    $d['name'] = 'parseLoops()';
    $d['result'] = assertEquals(parseLoops('{{for aa}}{{num}},{{/for}}',['aa'=>[['num'=>'1'],['num'=>'2']]]),'1,2,');
    $result[] = $d;

    $d['name'] = 'parseTemplate() #1';
    $d['result'] = assertEquals(parseTemplate('abc',[]),'abc');
    $result[] = $d;

    $d['name'] = 'parseIncludes()';
    $d['result'] = assertEquals(parseIncludes('cc{{include test1/}}'),'ccaabb');
    $result[] = $d;

    $d['name'] = 'render() #1';
    $d['result'] = assertEquals(render('test1',[]),'aabb');
    $result[] = $d;

    return $result;
}

