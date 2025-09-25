<?php
function connect_bd() {
    $host = "localhost";
    $dbname = "vente_groupe";
    $username = "root"; 
    $password = "mysql";
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
        // $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        // echo "Connexion réussie à la base de données.";
        return $pdo;
    } catch (PDOException $e) {
        die("Erreur de connexion : " . $e->getMessage());
        return null;
    }
}

function deconect_db() {
    $pdo = null;
}
?>