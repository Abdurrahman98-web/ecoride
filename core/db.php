<?php

class Database
{
    private static $instance = null;
    private $pdo;

    /* ============================================================
       PRIVATE CONSTRUCTOR (Singleton)
       ============================================================ */
    private function __construct()
    {
        $host = "localhost";
        $db   = "ecoride";
        $user = "root";
        $pass = "";
        $charset = "utf8mb4";

        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $this->pdo = new PDO($dsn, $user, $pass, $options);
        } catch (PDOException $e) {
            die("Database connection error.");
        }
    }

    /* ============================================================
       GET INSTANCE (Singleton Access)
       ============================================================ */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new Database();
        }

        return self::$instance;
    }

    /* ============================================================
       GET PDO CONNECTION
       ============================================================ */
    public function getConnection()
    {
        return $this->pdo;
    }
}

