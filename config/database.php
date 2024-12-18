<?php

class Database {
    private static $pdo = null;

    public static function connect() {
        if (self::$pdo === null) {
            $dotenv = parse_ini_file(__DIR__ . '/../.env');
            $dsn = "mysql:host={$dotenv['DB_HOST']};dbname={$dotenv['DB_NAME']};charset=utf8mb4";
            self::$pdo = new PDO($dsn, $dotenv['DB_USER'], $dotenv['DB_PASS']);
            self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        return self::$pdo;
    }
}