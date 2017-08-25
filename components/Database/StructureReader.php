<?php

namespace Components\Database;

use Components\Ppf;

function getDbStructure() {
    $db_connection = getDbConnection();
    if ($result = mysqli_query($db_connection, 'SHOW TABLES;')) {
        $tables = mysqli_fetch_all($result);
        mysqli_free_result($result);
    }
    foreach ($tables as $table) {
        if ($result = mysqli_query($db_connection, 'SHOW COLUMNS FROM ' . $table[0])) {
            $fields = mysqli_fetch_all($result);
        }
        $tableStructure = [
            'type' => 'table',
            'table' => $table[0],
            'id' => parseIds($fields),
            'fields' => parseFields($fields),
        ];

        $dbStructure[$table[0]] = $tableStructure;
    }

    setRelationData($db_connection, $dbStructure);

    return $dbStructure;
}

function setRelationData($db_connection, &$db_structure)
{
    foreach (getForeignKeysData($db_connection) as $keyData) {
        $db_structure[$keyData[5]]['manyToOne'][] = [
            $keyData[6] => [
                'targerTable' => $keyData[10],
                'inversedBy' => $keyData[11],
                'joinColumn' => [
                    'name' => $keyData[11],
                    'referencedColumnName' => $keyData[6]
                ]
            ]
        ];
        $db_structure[$keyData[10]]['oneToMany'][] = [
            $keyData[11] => [
                'targerTable' => $keyData[5],
                'mappedBy' => $keyData[6]
            ]
        ];
    }
}

function getForeignKeysData($db_connection)
{
//    $data = [];
    foreach (getForeignKeys($db_connection) as $key) {
        $sql = 'SELECT * FROM information_schema.key_column_usage 
                WHERE CONSTRAINT_NAME = \''.$key[2].'\' AND 
                TABLE_NAME = \''.$key[4].'\' 
                AND TABLE_SCHEMA = \'' . Ppf\getConfig('database')['name'] . '\'';
        if ($result = mysqli_query($db_connection, $sql)) {
            $data = mysqli_fetch_all($result);
        }
    }

    return $data;
}

function getForeignKeys($db_connection)
{
    $keys = [];
    $sql = 'SELECT * FROM information_schema.table_constraints 
            WHERE CONSTRAINT_TYPE = \'FOREIGN KEY\' AND TABLE_SCHEMA = \'' . Ppf\getConfig('database')['name'] . '\'';
    if ($result = mysqli_query($db_connection, $sql)) {
        $keys = mysqli_fetch_all($result);
    }

    return $keys;
}

function parseIds($fields)
{
    $result = [];
    foreach ($fields as $field) {
        $name = $field[0];
        $type = $field[1];
        $nullable = $field[2];
        $key = $field[3];
        $def = $field[4];
        $ext = $field[5];
        if ($key === 'PRI') {
            $result[$name] = [
                'type' => $type,
                'generator' => $ext
            ];
        }
    }

    return $result;
}

function parseFields($fields)
{
    $result = [];
    foreach ($fields as $field) {
        $name = $field[0];
        $type = $field[1];
        $nullable = $field[2];
        $key = $field[3];
        $def = $field[4];
        $ext = $field[5];
        if ($key !== 'PRI') {
            $result[$name] = [
                'type' => $type,
                'nullable' => $nullable==='YES'?true:false
            ];
        }
    }

    return $result;
}

