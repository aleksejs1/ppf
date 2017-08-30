<?php

namespace Components\Database;

use Components\Ppf;

componentLoad('Database/Repository');
componentLoad('Database/StructureReader');

$databaseConnectionParams = Ppf\getConfig('database');

if ($databaseConnectionParams) {

    $dbStructureFile = __DIR__ . '/../../var/cache/db_structure.php';
    if (!file_exists($dbStructureFile)) {
        $var_str = var_export(getDbStructure(), true);
        $var = "<?php\n\nfunction getStructure()\n{\n return $var_str;\n}\n\n?>";
        file_put_contents($dbStructureFile, $var);
    }

    include $dbStructureFile;
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