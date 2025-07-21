<?php

use Dotenv\Dotenv;

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$dsn = $_ENV['DSN'] ?? '';
$user = $_ENV['DB_USER'] ?? '';
$pass = $_ENV['DB_PASSWORD'] ?? '';

$driver = '';
if (stripos($dsn, 'pgsql:host') === 0) {
    $driver = 'pgsql';
} elseif (stripos($dsn, 'mysql:host') === 0) {
    $driver = 'mysql';
}

preg_match('/dbname=([^;]+)/', $dsn, $matches);
$dbName = $matches[1] ?? null;

// Création de la base
if ($driver === 'pgsql' && $dbName) {
    $dsnDefault = preg_replace('/dbname=([^;]+)/', 'dbname=postgres', $dsn);
    try {
        $pdo = new PDO($dsnDefault, $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->exec("CREATE DATABASE \"$dbName\"");
        echo "Base de données \"$dbName\" créée ou déjà existante.\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'already exists') === false) {
            die("Erreur création base: " . $e->getMessage());
        }
    }
}
if ($driver === 'mysql' && $dbName) {
    $dsnDefault = preg_replace('/dbname=([^;]+)/', '', $dsn);
    try {
        $pdo = new PDO($dsnDefault, $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbName` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        echo "Base de données `$dbName` créée ou déjà existante.\n";
    } catch (PDOException $e) {
        die("Erreur création base: " . $e->getMessage());
    }
}

class Migration
{
    private static ?\PDO $pdo = null;
    private static string $driver = '';

    private static function connect()
    {
        global $dsn, $user, $pass, $driver;
        if (self::$pdo === null) {
            self::$pdo = new \PDO($dsn, $user, $pass);
            self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$driver = $driver;
        }
    }

    private static function type($type)
    {
        $map = [
            'id' => [
                'pgsql' => 'SERIAL PRIMARY KEY',
                'mysql' => 'INT AUTO_INCREMENT PRIMARY KEY'
            ],
            'date' => [
                'pgsql' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
                'mysql' => 'DATETIME DEFAULT CURRENT_TIMESTAMP'
            ]
        ];
        return $map[$type][self::$driver] ?? $type;
    }

    public static function up()
    {
        self::connect();
        // Enums PostgreSQL uniquement
        if (self::$driver === 'pgsql') {
            self::$pdo->exec("DO $$ BEGIN
                IF NOT EXISTS (SELECT 1 FROM pg_type WHERE typname = 'statut') THEN
                    CREATE TYPE statut AS ENUM ('success', 'echec');
                END IF;
            END $$;");
        }
        $queries = [

            // Table client
            "CREATE TABLE IF NOT EXISTS client (
                id " . self::type('id') . ",
                nom VARCHAR(100),
                prenom VARCHAR(100),
                cni VARCHAR(150) UNIQUE NOT NULL,
                copie_cni_recto TEXT,
                copie_cni_verso TEXT
            )",

            // Table journalisation
            "CREATE TABLE IF NOT EXISTS journalisation (
                id " . self::type('id') . ",
                date date DEFAULT CURRENT_TIMESTAMP,
                heure time DEFAULT CURRENT_TIME,
                localisation VARCHAR(20) UNIQUE NOT NULL,
                adresse varchar(100) NOT NULL,
                statut VARCHAR(20) NOT NULL CHECK (statut IN ('success', 'echec')),
                client_id INTEGER REFERENCES client (id))",
        ];

        foreach ($queries as $sql) {
            self::$pdo->exec($sql);
        }

        echo "Migration terminée avec succès pour le SGBD : " . self::$driver . "\n";
    }
}

Migration::up();
