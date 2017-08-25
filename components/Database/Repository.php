<?php

namespace Components\Database;

function fetchOne($table, $id)
{
    $db_connection = getDbConnection();
    $structure = getStructure();
    $tableStructure = $structure[$table];
    $sql = 'SELECT * FROM `'.$tableStructure['table'] . '` ' . renderWhereString($tableStructure, ['id' => $id]);
    if ($result = mysqli_query($db_connection, $sql)) {
        return mysqli_fetch_row($result);
    }

    return false;
}

function save($table, $data)
{
    $db_connection = getDbConnection();
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
    mysqli_query($db_connection, $sql);
}

function renderWhereString($tableStructure, $data)
{
    $where = '';
    foreach ($tableStructure['id'] as $key => $field) {
        if (array_key_exists($key, $data)) {
            $where .= ' `' . $key . '` = \'' . $data[$key] . '\' AND ';
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
            $set .= ' `' . $key . '` = \'' . $data[$key] . '\' AND ';
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
