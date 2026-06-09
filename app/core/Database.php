<?php

class Database
{
    private static ?PDO $connection = null;

    public static function connection()
    {
       if (self::$connection === null) {
            $config = require __DIR__ . './../../config/dbConf.php';
            $dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['dbname']};charset=utf8mb4";
            try {
                self::$connection = new PDO($dsn, $config['user'], $config['pass']);
                self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Database connection failed: " . $e->getMessage());
            }
        }
        return self::$connection;
    }
}
