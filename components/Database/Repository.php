<?php

namespace Components\Database;

function fetchOne($table, $id)
{
    $dbConnection = getDbConnection();
    $structure = getStructure();
    $tableStructure = $structure[$table];
    $sql = 'SELECT * FROM `'.$tableStructure['table'] . '` ' . renderWhereString($tableStructure, ['id' => $id]);
    if ($result = dbQuery($dbConnection, $sql)) {
        return dbFetchRow($result);
    }

    return false;
}

function save($table, $data)
{
    $dbConnection = getDbConnection();
    $structure = getStructure();
    $tableStructure = $structure[$table];

    if (isUpdate($tableStructure, $data)) {
        $sql =
            'UPDATE `'.$tableStructure['table'] . '` ' .
            renderSetString($tableStructure, $data) .
            renderWhereString($tableStructure, $data) ;
    } else {
        $sql = 'INSERT INTO `'.$tableStructure['table'] . '` ' . renderSetString($tableStructure, $data);
    }
    dbQuery($dbConnection, $sql);
}

function renderWhereString($tableStructure, $data)
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

function renderSetString($tableStructure, $data)
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

function isUpdate($tableStructure, $data)
{
    foreach ($tableStructure['id'] as $key => $id) {
        if (array_key_exists($key, $data)) {
            return true;
        }
    }

    return false;
}
