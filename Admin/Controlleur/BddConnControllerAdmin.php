<?php
function connect_bd_Admin() {
    $envPath = dirname(__DIR__, 2) . '/.env';
    if (file_exists($envPath)) {
        foreach (file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
            if (strpos(trim($line), '#') === 0) continue;
            [$name, $value] = explode('=', $line, 2);
            $_ENV[trim($name)] = trim($value);
        }
    }
    $host     = $_ENV['DB_HOST'] ?? getenv('DB_HOST') ?: 'localhost';
    $dbname   = $_ENV['DB_NAME'] ?? getenv('DB_NAME') ?: '';
    $username = $_ENV['DB_USER'] ?? getenv('DB_USER') ?: '';
    $password = $_ENV['DB_PASS'] ?? getenv('DB_PASS') ?: '';
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
        return $pdo;
    } catch (PDOException $e) {
        die("Erreur de connexion.");
    }
}

function deconnect_db_Admin() {
    $pdo = null;
}
?>