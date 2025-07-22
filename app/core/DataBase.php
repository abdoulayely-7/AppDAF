<?php

namespace app\core;

use PDO;
use PDOException;

class DataBase extends Singleton
{
    private $connection;

    protected function __construct()
    {
        $host = DB_HOST;
        $port = DB_PORT;
        $dbname = DB_NAME;
        $user = DB_USER;
        $password = DB_PASSWORD;

        try {
            $infoDB = "pgsql:host=$host;port=$port;dbname=$dbname;";
            $this->connection = new PDO(
                DSN,
                $user,
                $password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => true
                ]
            );
            

        } catch (PDOException $e) {
            die("Erreur de connexion à la base de données : " . $e->getMessage());
        }
    }

    public function getConnection(): PDO
    {
        return $this->connection;
    }
}
