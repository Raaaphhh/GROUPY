<?php

function register($data){
    $pdo = connect_bd();
    if(!$pdo) {
        echo "Erreur de connexion à la base de données.";
        return false;
    }
    else{
        $user = get_user($data['email'], $data['password']);
        if($user){
            echo "L'utilisateur existe déjà.";
            return false;
        }
        else{
            $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
            $req = "INSERT INTO user (nom, prenom, date_naissance, email, telephone, password, adresse, photo) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($req);
            $result = $stmt->execute(array_values($data));
            if (!$result) {
                echo "Erreur lors de l'insertion de l'utilisateur.";
                return false;
            } else {
                $_SESSION['connectedUser'] = $existUser;
                deconect_db($pdo);
                header("Location: dashboard.php");
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