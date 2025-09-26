<?php

require 'BddConnController.php';

function register($data){
    session_start();
    $pdo = connect_bd();
    if(!$pdo) {
        echo "Erreur de connexion à la base de données.";
        return false;
    }
    else{
        // Vérifier si l'utilisateur existe déjà
        $user = get_user($data['email'], $data['password']);
        if($user){
            echo "L'utilisateur existe déjà.";
            return false;
        }
        else{
            if($data && count($data) > 7){
                // faut diviser le tableau en deux parties
                // premiere partie pour utilisateur
                // deuxieme partie pour vendeur
                // a finir
                // $userData = array_slice($data, 0, 7); exemple 
                $req1 = "INSERT INTO utilisateur (nom, prenom, adresse, phone, email, motdepasse) VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = $pdo->prepare($req1);
                $result = $stmt->execute(array_values($data));
                // N'es pas fini
                $idVenduer = $pdo->lastInsertId();
                $req2 = "INSERT INTO vendeur (idUtilisateur) VALUES (?)"; // a finir
            }
            else {
                $data['motdepasse'] = password_hash($data['motdepasse'], PASSWORD_BCRYPT);
                $req1 = "INSERT INTO utilisateur (nom, prenom, adresse, phone, email, motdepasse) VALUES (?, ?, ?, ?, ?, ?)";
                $stmt1 = $pdo->prepare($req1);
                $result1 = $stmt1->execute(array_values($data));
                
                $idClient = $pdo->lastInsertId();
                $req2 = "INSERT INTO client (id_user) VALUES ($idClient)";
                $stmt2 = $pdo->prepare($req2);
                $result2 = $stmt2->execute();
                if (!$result1 || !$result2) {
                    echo "Erreur lors de l'insertion de l'utilisateur.";
                    return false;
                }
                else {
                    $existUser = get_user($data['email'], $data['password']);
                    $_SESSION['connectedUser'] = $existUser;
                    deconect_db($pdo);
                    header("Location: ../formCo.php");
                    exit;
                }
            }
        }
    }
}

function get_user($email, $password){
    $pdo = connect_bd();
    if(!$pdo) {
        echo "Erreur de connexion à la base de données.";
        deconect_db($pdo);
        return false;
    }
    else{
        try{
            $req = "SELECT * FROM user WHERE email = ?";
            $stmt = $pdo->prepare($req);
            $result = $stmt->execute([$email]);
            if (!$result) {
                echo "Erreur lors de la récupération de l'utilisateur.";
                deconect_db($pdo);
                return false;
            }
            else{
                if ($stmt->rowCount() > 0) {
                    $user = $stmt->fetch(PDO::FETCH_ASSOC);
                    if(password_verify($password, $user['password'])) {
                        deconect_db($pdo);
                        return $user;
                    } else {
                        echo "Mot de passe incorrect."; 
                        deconect_db($pdo);
                        return false;
                    }
                }
            }
        }
        catch (PDOException $e) {
            echo "Erreur lors de la récupération de l'utilisateur : ";
            deconect_db($pdo);
            return false;
        }
    }
}

function login($data){
    $pdo = connect_bd();
    if(!$pdo) {
        echo "Erreur de connexion à la base de données.";
        return false;
    }
    else{
        $existUser = get_user($data['email'], $data['password']);
        if($existUser){
            $_SESSION['connectedUser'] = $existUser;
            deconect_db($pdo);
            header("Location: dashboard.php");
            exit;
        }
        else{
            echo "Utilisateur ou mot de passe incorrect.";
            deconect_db($pdo);
            return false;
        }
    }
}

function logout(){
    session_unset();
    session_destroy();
    header("Location: form_co.php");
}

?> 