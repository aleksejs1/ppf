<?php

namespace Components\Database;

function dbConnect($host, $user, $pass, $name)
{
    return mysqli_connect($host, $user, $pass, $name);
}

function dbClose($connection)
{
    return mysqli_close($connection);
}

function dbRealEscapeString($connection, $variable)
{
    return mysqli_real_escape_string($connection, $variable);
}

function dbQuery($sql)
{
    $connection = getDbConnection();
    return mysqli_query($connection, $sql);
}

function dbFetchRow($stmt)
{
    return mysqli_fetch_row($stmt);
}

function dbFetchAll($stmt)
{
    return mysqli_fetch_all($stmt);
}

function dbFreeResult($result)
{
    return mysqli_free_result($result);
}