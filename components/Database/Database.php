<?php

namespace Components\Database;

use Components\Ppf;

componentLoad('Database/MySqlDriver');
componentLoad('Database/QueryBuilder');
componentLoad('Database/Repository');
componentLoad('Database/StructureReader');

function init()
{
    initDatabase();
}

function initDatabase()
{
    $databaseConnectionParams = Ppf\getConfig('database');

    if ($databaseConnectionParams) {
        prepareDbStructureConfig();
    }
}

function prepareDbStructureConfig()
{
    $dbStructureFile = getDbStructureFilePath();
    if (!file_exists($dbStructureFile)) {
        createDbStructureFile($dbStructureFile);
    }

    include $dbStructureFile;
}

function getDbStructureFilePath()
{
    return __DIR__ . '/../../var/cache/db_structure.php';
}

function createDbStructureFile($dbStructureFile)
{
    $var_str = var_export(getDbStructure(), true);
    $var = "<?php\n\nfunction getStructure()\n{\n return $var_str;\n}\n\n?>";
    file_put_contents($dbStructureFile, $var);
}

function closeDbConnection()
{
    $dbConnection = getDbConnection(true);
}

function getDbConnection($close = false)
{
    static $dbConnection = false;

    if ($close) {
        if ($dbConnection) {
            dbClose($dbConnection);
            $dbConnection = false;
        }

        return true;
    }

    if ($dbConnection) {
        return $dbConnection;
    }

    $databaseConnectionParams = Ppf\getConfig('database');
    if ($databaseConnectionParams) {
        $dbConnection = dbConnect(
            $databaseConnectionParams['host'],
            $databaseConnectionParams['user'],
            $databaseConnectionParams['pass'],
            $databaseConnectionParams['name']
        );
    }

    return $dbConnection;
}

function escape($variable)
{
    return dbRealEscapeString(getDbConnection(), $variable);
}