<?php

class Database
{
    private static ?PDO $connection = null;

    public static function connection()
    {
       if (self::$connection === null) {
<<<<<<< HEAD
<<<<<<< HEAD
            $config = require __DIR__ . './../../config/dbConf.php';
=======
            $config = require __DIR__ . '/../../config/dbConf.php';
>>>>>>> 2cd2577 (add ProductType module)
=======
            $config = require __DIR__ . '/../../config/dbConf.php';
>>>>>>> cbc3ef86c29f0e36ad96e909a6ca7ad48579114e
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
