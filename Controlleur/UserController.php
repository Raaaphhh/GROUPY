<?php
session_start();
require 'BddConnController.php';

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

function register($data){
    $pdo = connect_bd();
    if(!$pdo) {
        echo "Erreur de connexion à la base de données.";
        return false;
    }
    else{
        // Vérifier si l'utilisateur existe déjà
        $user = get_user($data['email'], $data['motdepasse']);
        if($user){
            echo "L'utilisateur existe déjà.";
            return false;
        }
        else{
            if($data && count($data) > 7){
                $motdepasse_non_hash = $data['motdepasse'];

                $data['motdepasse'] = password_hash($data['motdepasse'], PASSWORD_BCRYPT);
                $data_part1 = array_slice($data, 0, 6);
                $data_part2 = array_slice($data, 6); 

                $req1 = "INSERT INTO utilisateur (nom, prenom, adresse, phone, email, motdepasse) VALUES (?, ?, ?, ?, ?, ?)";
                $stmt1 = $pdo->prepare($req1);
                $result1 = $stmt1->execute(array_values($data_part1));

                $idVenduer = $pdo->lastInsertId();
                $req2 = "INSERT INTO vendeur (id_user, nom_entreprise, siret, adresse_entreprise, email_pro) VALUES (?, ?, ?, ?, ?)";
                $fusion_data_id = array_merge([$idVenduer], array_values($data_part2));
                $stmt2 = $pdo->prepare($req2);
                $result2 = $stmt2->execute($fusion_data_id);
                if (!$result1 || !$result2) {
                    echo "Erreur lors de l'insertion de l'utilisateur.";
                    return false;
                }
                else {
                    $existUser = get_user($data['email'], $motdepasse_non_hash);
                    $_SESSION['connectedUser'] = $existUser;
                    deconect_db($pdo);
                    header("Location: /groupy/index.php");
                    exit;
                }
            }
            else {
                $motdepasse_non_hash = $data['motdepasse'];

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
                    $existUser = get_user($data['email'], $motdepasse_non_hash);
                    $_SESSION['connectedUser'] = $existUser;
                    deconect_db($pdo);
                    header("Location: /groupy/index.php");
                    exit;
                }
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

// A changer
// voir selon le role
function updateUser($data){
    $pdo = connect_bd();
    if(!$pdo) {
        echo "Erreur de connexion à la base de données.";
        return false;
    }
    else{
        $data['id'] = $_SESSION['connectedUser']['id_user'];
        $req = "UPDATE user SET  nom = ?, prenom = ?, date_naissance = ?, email = ?, telephone = ?, password = ?, adresse = ?, photo = ? WHERE id = ?";
        $stmt = $pdo->prepare($req);
        $params = [
            $data['nom'],
            $data['prenom'],
            $data['date_naissance'],
            $data['email'],
            $data['telephone'],
            $data['password'],
            $data['adresse'],
            $data['id']
        ];
        $result = $stmt->execute($params);
        
        if (!$result) {
            echo "Erreur lors de la mise à jour de l'utilisateur.";
            return false;
        } else {
            $_SESSION['connectedUser'] = $data;
            deconect_db($pdo);
            echo "Modification réussie.";
        }
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