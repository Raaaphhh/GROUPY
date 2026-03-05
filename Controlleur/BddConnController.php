<?php

function loadEnv($path) {
    if (!file_exists($path)) return;

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        list($name, $value) = explode('=', $line, 2);
        $_ENV[trim($name)] = trim($value);
    }
}

loadEnv(dirname(__DIR__) . '/.env');

function getEnvVar($key) {
    return $_ENV[$key] ?? getenv($key) ?: null;
}

function connect_bd() {
    $host = getEnvVar('DB_HOST');
    $dbname = getEnvVar('DB_NAME');
    $username = getEnvVar('DB_USER');
    $password = getEnvVar('DB_PASS');
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
        return $pdo;
    } catch (PDOException $e) {
        die("Erreur de connexion : " . $e->getMessage());
        return null;
    }
}

function connect_bdd_test() {
    $host = "127.0.0.1";
    $port = 3306; 
    $dbname = "vente_groupe_test";
    $username = "root";
    $password = "mysql";

    try {
        $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8", $username, $password);
        return $pdo;
    } catch (PDOException $e) {
        die("Erreur de connexion : " . $e->getMessage());
    }
}

function deconect_db() {
    $pdo = null;
}
?>