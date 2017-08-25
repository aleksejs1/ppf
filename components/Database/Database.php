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
    $db_connection = getDbConnection(true);
}

function getDbConnection($close = false)
{
    static $db_connection = false;

    if ($close) {
        if ($db_connection) {
            mysqli_close($db_connection);
            $db_connection = false;
        }

        return true;
    }

    if ($db_connection) {
        return $db_connection;
    }

    $databaseConnectionParams = Ppf\getConfig('database');

    if ($databaseConnectionParams) {
        $db_connection = mysqli_connect(
            $databaseConnectionParams['host'],
            $databaseConnectionParams['user'],
            $databaseConnectionParams['pass'],
            $databaseConnectionParams['name']
        );
    }

    return $db_connection;
}
