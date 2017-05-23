<?php

class DB
{

    private static $host = 'localhost';
    private static $dbname = 'projektnoprogramiranje';
    private static $username = 'root';
    private static $password = '';

    private static function connect()
    {
        $pdo = new PDO('mysql:host='. self::$host .';dbname='. self::$dbname .';charset=utf8', self::$username, self::$password);
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    }

    public static function query($query, $params = array())
    {
        $statement = self::connect()->prepare($query);
        $statement->execute($params);

        if (explode(' ', $query)[0] == 'SELECT')
        {
            $data = $statement->fetchAll(PDO::FETCH_ASSOC);
            return $data;
        }
    }
}
