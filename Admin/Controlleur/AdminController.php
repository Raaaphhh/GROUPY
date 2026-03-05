<?php 
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

require 'BddConnControllerAdmin.php';

function verif_role_admin($iduserConnected) {
    $pdo = connect_bd_Admin();
    if (!$pdo) {
        return false;
    }
    try {
        $idUser = $iduserConnected;
        $stmt = $pdo->prepare("SELECT * FROM vendeur WHERE id_user = :id");
        $stmt->execute(['id' => $idUser]);
        $vendeur = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($vendeur) {
            $_SESSION['vendeurInfo'] = $vendeur; 
            deconnect_db_Admin($pdo);
            return "vendeur";
        }

        $stmt = $pdo->prepare("SELECT * FROM client WHERE id_user = :id");
        $stmt->execute(['id' => $idUser]);
        $client = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($client) {
            deconnect_db_Admin($pdo);
            return "client";
        }

        if (!$vendeur && !$client) {
            deconnect_db_Admin($pdo);
            return "admin";
        }
        deconnect_db_Admin($pdo);
        return false;

    } catch (PDOException $e) {
        echo "Erreur lors de la récupération du rôle : " . $e->getMessage();
        deconnect_db_Admin($pdo);
        return false;
    }
}

function login_admin($data){
    $pdo = connect_bd_Admin();
    if(!$pdo) {
        echo "Erreur de connexion à la base de données.";
        return false;
    }
    else{
        $existUser = get_user($data['email'], $data['motdepasse']);
        if($existUser){
            $_SESSION['connectedUser'] = $existUser;
            $role = verif_role_admin($existUser['id_user']);
            if($role !== "admin"){
                echo "Accès refusé. Vous n'êtes pas un administrateur.";
                session_unset();
                session_destroy();
                return false;
            }
            deconnect_db_Admin($pdo);
            header("Location: " . BASE_URL . "/Admin/ViewsAdmin/dashboardAdmin.php");
            exit;
        }
        else{
            echo "Utilisateur ou mot de passe incorrect.";
            deconnect_db_Admin($pdo);
            return false;
        }
    }
}

// -------------------------------------- SEPRATION FONCTION ----------------------------------- //

function add_gestionnaire($data) {
    $pdo = connect_bd_Admin();
    if (!$pdo) {
        echo "Erreur de connexion à la base de données.";
        return false;
    }
    else{
        try{
            if (add_user_admin($data) == true) {
                $idUser = $_SESSION['connectedUser']['id_user'];
                $req = "INSERT INTO gestionnaire (id_user, est_actif) VALUES (?, ?)";
                $stmt = $pdo->prepare($req);
                $params = [$idUser, 0];
                $result = $stmt->execute($params);
                if (!$result) {
                    echo "Erreur lors de l'insertion du gestionnaire.";
                    deconnect_db_Admin($pdo);
                    return false;
                }
                else {
                    deconnect_db_Admin($pdo);
                    return true;
                }
            }
            else{
                deconnect_db_Admin($pdo);
                return false;
            }
        }
        catch (PDOException $e) {
            echo "Erreur lors de l'insertion du gestionnaire : " . $e->getMessage();
            deconnect_db_Admin($pdo);
            return false;
        }
    }
}

function add_user_admin($data) {
    $pdo = connect_bd_Admin();
    if (!$pdo) {
        echo "Erreur de connexion à la base de données.";
        return false;
    }
    else{
        $user = get_user_admin($data['email'], $data['motdepasse']);
        if($user){
            echo "L'utilisateur existe déjà.";
            return false;
        }
        else{
            $motdepasse_non_hash = $data['motdepasse'];
            $data['motdepasse'] = password_hash($data['motdepasse'], PASSWORD_BCRYPT);
            $req = "INSERT INTO utilisateur (nom, prenom, adresse, phone, email, motdepasse, motdepasse_change) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($req);
            $params = [
                $data['nom'],
                $data['prenom'],
                $data['adresse'],
                $data['phone'],
                $data['email'],
                $data['motdepasse'],
                0
            ];
            $result = $stmt->execute($params);
            if (!$result) {
                echo "Erreur lors de l'insertion de l'utilisateur.";
                return false;
            }
            else {
                $existUser = get_user_admin($data['email'], $motdepasse_non_hash);
                $_SESSION['connectedUser'] = $existUser;
                deconect_db($pdo);
                return true;
            }
        }
    }
}

function get_user_admin($email, $password){
    $pdo = connect_bd();
    if(!$pdo) {
        echo "Erreur de connexion à la base de données.";
        deconect_db($pdo);
        return false;
    }
    else{
        try{
            $req = "SELECT * FROM utilisateur WHERE email = ?";
            $stmt = $pdo->prepare($req);
            $result = $stmt->execute([$email]);
            if (!$result) {
                echo "user n'existe pas donc peut etre crée";
                deconect_db($pdo);
                return false;
            }
            else{
                if ($stmt->rowCount() > 0) {
                    $user = $stmt->fetch(PDO::FETCH_ASSOC);
                    if(password_verify($password, $user['motdepasse'])) {
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

function get_gestionnaires() {
    $pdo = connect_bd_Admin();
    if (!$pdo) {
        echo "Erreur de connexion à la base de données.";
        return false;
    }
    try {
        $req = "SELECT utilisateur.*, gestionnaire.est_actif 
                FROM utilisateur 
                INNER JOIN gestionnaire 
                ON utilisateur.id_user = gestionnaire.id_user";

        $stmt = $pdo->prepare($req);
        $stmt->execute();
        $gestionnaires = $stmt->fetchAll(PDO::FETCH_ASSOC);
        deconnect_db_Admin($pdo);
        return $gestionnaires;
    } catch (PDOException $e) {
        echo "Erreur lors de la récupération des gestionnaires : " . $e->getMessage();
        deconnect_db_Admin($pdo);
        return false;
    }
}

?> 