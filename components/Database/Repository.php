<?php

namespace Components\Database;

function fetchOne($table, $id)
{
    $tableStructure = getTableStructure($table);
    $sql =
        renderSelectSql('*') .
        renderFromSql($tableStructure['table']) .
        renderWhereSql($tableStructure, ['id' => $id])
    ;
    if ($result = dbQuery($sql)) {
        return dbFetchRow($result);
    }

    return false;
}

function getTableStructure($table)
{
    $structure = getStructure();
    if (!array_key_exists($table, $structure)) {
        trigger_error('Table '.$table.' not exist in config!' ,E_USER_ERROR);
    }

    return $structure[$table];
}



function save($table, $data)
{
    $tableStructure = getTableStructure($table);

    if (isUpdate($tableStructure, $data)) {
        $sql =
            renderUpdateSql($tableStructure['table']) .
            renderSetSql($tableStructure, $data) .
            renderWhereSql($tableStructure, $data)
        ;
    } else {
        $sql =
            renderInsertSql($tableStructure['table']) .
            renderSetSql($tableStructure, $data)
        ;
    }
    dbQuery($sql);
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
