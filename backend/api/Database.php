<?php

namespace App;

use PDO;

class Database
{
    public static function connect(): PDO
    {
        $host = $_ENV['DB_HOST'] ?? 'db';
        $db   = $_ENV['DB_NAME'] ?? 'movies';
        $user = $_ENV['DB_USER'] ?? 'user';
        $pass = $_ENV['DB_PASS'] ?? 'password';
        $dsn  = "mysql:host=$host;dbname=$db;charset=utf8mb4";

        return new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
    }
}
