<?php

use Dotenv\Dotenv;

$envPath = dirname(__DIR__, 2); // Remonte à la racine

// En local, charge le fichier .env
if (file_exists($envPath . '/.env')) {
    $dotenv = Dotenv::createImmutable($envPath);
    $dotenv->load();
}

// Fonction pour obtenir la variable d’environnement avec fallback
function env(string $key, string $default = null): ?string
{
    return $_ENV[$key] ?? getenv($key) ?? $default;
}

define('WEB_URL', env('WEB_URL', 'http://localhost:8080'));
define("DB_HOST", env("DB_HOST", "localhost"));
define("DB_PORT", env("DB_PORT", "5432"));
define("DB_NAME", env("DB_NAME", "default_db"));
define("DB_USER", env("DB_USER", "root"));
define("DB_PASSWORD", env("DB_PASSWORD", ""));
define("DSN", env("DSN", "pgsql:host=localhost;port=5432;dbname=default_db;"));
define("UPLOAD_DIR", env("UPLOAD_DIR", "public/images/uploads"));
