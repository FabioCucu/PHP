<?php

class DataBaseConn
{
    private static PDO $db;

    public static function getDB(array $dbconfig):PDO
    {
        self::$db = new PDO( $dbconfig["dsn"], $dbconfig["username"], $dbconfig["password"], $dbconfig["options"]);
        return self::$db;
    }
}