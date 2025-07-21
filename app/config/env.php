<?php

$dotenv = Dotenv\Dotenv::createImmutable('../');
$dotenv->load();

define('WEB_URL', $_ENV['WEB_URL']);
define("DB_HOST", $_ENV["DB_HOST"]);
define("DB_PORT", $_ENV["DB_PORT"]);
define("DB_NAME", $_ENV["DB_NAME"]);
define("DB_USER", $_ENV["DB_USER"]);
define("DB_PASSWORD", $_ENV["DB_PASSWORD"]);
define("DSN", $_ENV["DSN"]);
define("UPLOAD_DIR", $_ENV["UPLOAD_DIR"]);

