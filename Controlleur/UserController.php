<?php
session_start();
require 'BddConnController.php';

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);


function register_utilisateur($data){
    $pdo = connect_bd();
    if(!$pdo) {
        echo "Erreur de connexion à la base de données.";
        return false;
    }
    else{
        $user = get_user($data['email'], $data['motdepasse']);
        if($user){
            echo "L'utilisateur existe déjà.";
            return false;
        }
        else{
            $motdepasse_non_hash = $data['motdepasse'];
            $data['motdepasse'] = password_hash($data['motdepasse'], PASSWORD_BCRYPT);
            $req = "INSERT INTO utilisateur (nom, prenom, adresse, phone, email, motdepasse) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($req);
            $params = [
                $data['nom'],
                $data['prenom'],
                $data['adresse'],
                $data['phone'],
                $data['email'],
                $data['motdepasse']
            ];
            $result = $stmt->execute($params);
            if (!$result) {
                echo "Erreur lors de l'insertion de l'utilisateur.";
                return false;
            }
            else {
                $existUser = get_user($data['email'], $motdepasse_non_hash);
                $_SESSION['connectedUser'] = $existUser;
                deconect_db($pdo);
                return true;
            }
        }
    }
}

function register_Vendeur($data){
    $pdo = connect_bd();
    if(!$pdo) {
        echo "Erreur de connexion à la base de données.";
        return false;
    }
    else{
        echo "test";
        $insc_user = register_utilisateur($data);
        echo "test";
        $idVendeur = $_SESSION['connectedUser']['id_user'];
        if ($insc_user === false) {
            return false;
        }
        else{
            $req = "INSERT INTO vendeur (id_user, nom_entreprise, siret, adresse_entreprise, email_pro) VALUES (?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($req);
            $params = [$idVendeur, $data['nom_entreprise'], $data['siret'], $data['adresse_entreprise'], $data['email_pro']];
            $result = $stmt->execute($params);
            if (!$result) {
                echo "Erreur lors de l'insertion du vendeur.";
                return false;
            }
            else {
                $_SESSION['vendeurInfo'] = [
                    'id_user' => $idVendeur,
                    'nom_entreprise' => $data['nom_entreprise'],
                    'siret' => $data['siret'],
                    'adresse_entreprise' => $data['adresse_entreprise'],
                    'email_pro' => $data['email_pro']
                ];
                deconect_db($pdo);
                header("Location: /groupy/index.php");
                exit;
            }
        }
    }
}

function register_Client($data){
    $pdo = connect_bd();
    if(!$pdo) {
        echo "Erreur de connexion à la base de données.";
        return false;
    }
    else{
        $insc_user = register_utilisateur($data);
        $idClient = $_SESSION['connectedUser']['id_user'];
        if ($insc_user === false) {
            return false; 
        }
        else{
            $req = "INSERT INTO client (id_user) VALUES (?)";
            $stmt = $pdo->prepare($req);
            $params = [$idClient];
            $result = $stmt->execute($params);
            echo "test";
            if (!$result) {
                echo "Erreur lors de l'insertion du client.";
                return false;
            }
            else {
                deconect_db($pdo);
                header("Location: /groupy/index.php");
                exit;
            }
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
        $existUser = get_user($data['email'], $data['motdepasse']);
        if($existUser){
            $_SESSION['connectedUser'] = $existUser;
            deconect_db($pdo);
            header("Location: /groupy/index.php");
            exit;
        }
        else{
            echo "Utilisateur ou mot de passe incorrect.";
            deconect_db($pdo);
            return false;
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
            $req = "SELECT * FROM utilisateur WHERE email = ?";
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

function updateUser($data, $role){
    $pdo = connect_bd();
    if(!$pdo) {
        echo "Erreur de connexion à la base de données.";
        return false;
    }
    else{
        $data['id'] = $_SESSION['connectedUser']['id_user'];
        $reqUser = "UPDATE utilisateur 
            SET nom = ?, prenom = ?, adresse = ?, phone = ?, email = ? 
            WHERE id_user = ?";
        $stmtUser = $pdo->prepare($reqUser);
        $paramsUser = [
        $data['nom'],
        $data['prenom'],
        $data['adresse'],
        $data['phone'],
        $data['email'],
        $data['id']
        ];
        $resultUser = $stmtUser->execute($paramsUser);
        if (!$resultUser) {
            echo "Erreur lors de la mise à jour des informations du vendeur.";
            return false;
        }

        if($role == "vendeur") {
            $reqVendeur = "UPDATE vendeur 
                SET nom_entreprise = ?, siret = ?, adresse_entreprise = ?, email_pro = ? 
                WHERE id_user = ?";
            $stmtVendeur = $pdo->prepare($reqVendeur);
            $paramsVendeur = [
                $data['nom_entreprise'],
                $data['siret'],
                $data['adresse_entreprise'],
                $data['email_pro'],
                $data['id']
            ];
            $resultVendeur = $stmtVendeur->execute($paramsVendeur);
            if(!$resultVendeur){
                echo "Erreur lors de la mise à jour des informations vendeur.";
                return false;
            }
            $_SESSION['vendeurInfo']['nom_entreprise']     = $data['nom_entreprise'];
            $_SESSION['vendeurInfo']['siret']              = $data['siret'];
            $_SESSION['vendeurInfo']['adresse_entreprise'] = $data['adresse_entreprise'];
            $_SESSION['vendeurInfo']['email_pro']          = $data['email_pro'];
            
        }
        $_SESSION['connectedUser']['nom']     = $data['nom'];
        $_SESSION['connectedUser']['prenom']  = $data['prenom'];
        $_SESSION['connectedUser']['adresse'] = $data['adresse'];
        $_SESSION['connectedUser']['phone']   = $data['phone'];
        $_SESSION['connectedUser']['email']   = $data['email'];
        deconect_db($pdo);
        echo "Modification réussie.";
        return true;
    }
}

function get_role($iduserConnected) {
    $pdo = connect_bd();
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
            deconect_db($pdo);
            return "vendeur";
        }

        $stmt = $pdo->prepare("SELECT * FROM client WHERE id_user = :id");
        $stmt->execute(['id' => $idUser]);
        $client = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($client) {
            deconect_db($pdo);
            return "client";
        }

        if (!$vendeur && !$client) {
            deconect_db($pdo);
            return "admin";
        }
        deconect_db($pdo);
        return false;

    } catch (PDOException $e) {
        echo "Erreur lors de la récupération du rôle : " . $e->getMessage();
        deconect_db($pdo);
        return false;
    }
}

function logout(){
    session_unset();
    session_destroy();
}

?> 