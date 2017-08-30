<?php

namespace Components\Database;


function renderSelectSql($select)
{
    return ' SELECT '.$select.' ';
}

function renderFromSql($table)
{
    return ' FROM `' . escape($table) . '` ';
}

function renderWhereSql($tableStructure, $data)
{
    $where = '';
    foreach ($tableStructure['id'] as $key => $field) {
        if (array_key_exists($key, $data)) {
            $where .= ' `' . $key . '` = \'' . escape($data[$key]) . '\' AND ';
        }
    }
    if ($where !== '') {
        $where = ' WHERE ' . rtrim($where,' AND ');
    }

    return $where;
}


function renderSetSql($tableStructure, $data)
{
    $set = '';
    foreach ($tableStructure['fields'] as $key => $field) {
        if (array_key_exists($key, $data)) {
            $set .= ' `' . $key . '` = \'' . escape($data[$key]) . '\' AND ';
        }
    }
    if ($set !== '') {
        $set = ' SET ' . rtrim($set,' AND ');
    }

    return $set;
}

function renderInsertSql($table)
{
    return 'INSERT INTO `' . $table . '` ';
}

function renderUpdateSql($table)
{
    return ' UPDATE `' . $table . '` ';
}