<?php

namespace Components\Database;

use Components\Ppf;

function getDbStructure() {
    $dbConnection = getDbConnection();
    if ($result = dbQuery($dbConnection, 'SHOW TABLES;')) {
        $tables = dbFetchAll($result);
        dbFreeResult($result);
    }
    foreach ($tables as $table) {
        if ($result = dbQuery($dbConnection, 'SHOW COLUMNS FROM ' . $table[0])) {
            $fields = dbFetchAll($result);
        }
        $tableStructure = [
            'type' => 'table',
            'table' => $table[0],
            'id' => parseIds($fields),
            'fields' => parseFields($fields),
        ];

        $dbStructure[$table[0]] = $tableStructure;
    }

    setRelationData($dbConnection, $dbStructure);

    return $dbStructure;
}

function setRelationData($dbConnection, &$dbStructure)
{
    foreach (getForeignKeysData($dbConnection) as $keyData) {
        $dbStructure[$keyData[5]]['manyToOne'][] = [
            $keyData[6] => [
                'targerTable' => $keyData[10],
                'inversedBy' => $keyData[11],
                'joinColumn' => [
                    'name' => $keyData[11],
                    'referencedColumnName' => $keyData[6]
                ]
            ]
        ];
        $dbStructure[$keyData[10]]['oneToMany'][] = [
            $keyData[11] => [
                'targerTable' => $keyData[5],
                'mappedBy' => $keyData[6]
            ]
        ];
    }
}

function getForeignKeysData($dbConnection)
{
//    $data = [];
    foreach (getForeignKeys($dbConnection) as $key) {
        $sql = 'SELECT * FROM information_schema.key_column_usage 
                WHERE CONSTRAINT_NAME = \''.$key[2].'\' AND 
                TABLE_NAME = \''.$key[4].'\' 
                AND TABLE_SCHEMA = \'' . Ppf\getConfig('database')['name'] . '\'';
        if ($result = dbQuery($dbConnection, $sql)) {
            $data = dbFetchAll($result);
        }
    }

    return $data;
}

function getForeignKeys($dbConnection)
{
    $keys = [];
    $sql = 'SELECT * FROM information_schema.table_constraints 
            WHERE CONSTRAINT_TYPE = \'FOREIGN KEY\' AND TABLE_SCHEMA = \'' . Ppf\getConfig('database')['name'] . '\'';
    if ($result = dbQuery($dbConnection, $sql)) {
        $keys = dbFetchAll($result);
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

