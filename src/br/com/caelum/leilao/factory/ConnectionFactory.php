<?php
namespace src\br\com\caelum\leilao\factory;

use PDO;

class ConnectionFactory
{
    private static $host = DB_HOST;
    private static $user = DB_USER;
    private static $password = DB_PASSWORD;
    private static $dbname = DB_DBNAME;

    public static function getConnectionWithoutDb()
    {
        return new PDO("mysql:host=".static::$host, static::$user, static::$password);
    }

    public static function getConnection()
    {
        return new PDO("mysql:host=".static::$host.";dbname=".static::$dbname, static::$user, static::$password);
    }

    public static function getDatabase()
    {
        return static::$dbname;
    }
}
