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
            $req = "INSERT INTO vendeur (id_user, nom_entreprise, siret, adresse_entreprise, email_pro, statut) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($req);
            $params = [$idVendeur, $data['nom_entreprise'], $data['siret'], $data['adresse_entreprise'], $data['email_pro'], "actif"];
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
            if(get_vendeur_blocked($existUser['id_user'])){
                deconect_db($pdo);
                return "bloque";
            }
            $_SESSION['connectedUser'] = $existUser;
            check_motdepasse_change();
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

        $stmt = $pdo->prepare("SELECT * FROM gestionnaire WHERE id_user = :id");
        $stmt->execute(['id' => $idUser]);
        $gestionnaire = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($gestionnaire) {
            deconect_db($pdo);
            return "gestionnaire";
        }

        if (!$vendeur && !$client && !$gestionnaire) {
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

function check_motdepasse_change() {
    if (isset($_SESSION['connectedUser'])) {
        $motdepasse_change = $_SESSION['connectedUser']['motdepasse_change'];
        if ($motdepasse_change === null) {
            return;
        } elseif ($motdepasse_change == 0) {
            header("Location: /groupy/Views/User/changePassword.php");
            exit;
        }
    }
}

function changePassword($data){
    $pdo = connect_bd();
    if(!$pdo) {
        echo "Erreur de connexion à la base de données.";
        return false;
    }
    if ($data['new_password'] !== $data['confirm_password']) {
        echo "Les mots de passe ne correspondent pas.";
        return false;
    }
    $idUser = $_SESSION['connectedUser']['id_user'];
    $role = get_role($idUser);
    $hashedPassword = password_hash($data['new_password'], PASSWORD_BCRYPT);
    $req = "UPDATE utilisateur SET motdepasse = ?, motdepasse_change = 1 WHERE id_user = ?";
    $stmt   = $pdo->prepare($req);
    $result = $stmt->execute([$hashedPassword, $idUser]);
    if (!$result) {
        echo "Erreur lors de la mise à jour du mot de passe.";
        return false;
    }
    if ($role === "gestionnaire") {
        $req2   = "UPDATE gestionnaire SET est_actif = 1 WHERE id_user = ?";
        $stmt2  = $pdo->prepare($req2);
        $result = $stmt2->execute([$idUser]);
    }
    $_SESSION['connectedUser']['motdepasse_change'] = 1;
    deconect_db($pdo);
    return true;
}

function logout(){
    session_unset();
    session_destroy();
}


// ===============FONCTION CLIENT PDF FACTURE / Signalement==================

function generate_facture_pdf($factureData) {
    require_once __DIR__ . '/../vendor/autoload.php'; 

    $mpdf = new \Mpdf\Mpdf();

    $html = "
    <h1>Facture Groupy</h1>
    <p><strong>Nom :</strong> {$factureData['nom']}</p>
    <p><strong>Catégorie :</strong> {$factureData['nom_categorie']}</p>
    <p><strong>Prix :</strong> {$factureData['prix_prevente']} €</p>
    <p><strong>Date limite :</strong> {$factureData['date_limite']}</p>
    <p><strong>Statut :</strong> {$factureData['statut']}</p>
    <img src='{$factureData['image']}' width='100'>
    ";

    $mpdf->WriteHTML($html);
    $mpdf->Output('facture_' . $factureData['id_prevente'] . '.pdf', 'D');
}

function get_prevente_client($idUser){
    $pdo = connect_bd();
    if(!$pdo) {
        echo "Erreur de connexion à la base de données.";
        return false;
    }
    try {
        // $req = "SELECT prevente.*, produit.*, categorie.lib AS nom_categorie
        //         FROM prevente
        //         INNER JOIN produit 
        //             ON prevente.id_produit = produit.id_produit
        //         INNER JOIN categorie 
        //             ON produit.id_categorie_ = categorie.id_categorie
        //         INNER JOIN participation
        //             ON participation.id_prevente = prevente.id_prevente
        //         WHERE participation.id_client = ?
        //         AND prevente.statut = 'Valide'";
        $req = "SELECT prevente.*, produit.*, categorie.lib AS nom_categorie
                FROM prevente
                INNER JOIN produit 
                    ON prevente.id_produit = produit.id_produit
                INNER JOIN categorie 
                    ON produit.id_categorie_ = categorie.id_categorie
                INNER JOIN participation
                    ON participation.id_prevente = prevente.id_prevente
                WHERE participation.id_client = ?";
        $stmt = $pdo->prepare($req);
        $result = $stmt->execute([$idUser]);
        if (!$result) {
            echo "Erreur lors de la récupération des préventes.";
            deconect_db($pdo);
            return false;
        }
        $preventeData = $stmt->fetchAll(PDO::FETCH_ASSOC);
        deconect_db($pdo);
        return $preventeData;
    } catch (PDOException $e) {
        echo "Erreur lors de la récupération des préventes : " . $e->getMessage();
        deconect_db($pdo);
        return false;
    }
}

function signalement($data){
    var_dump($data);
    $pdo = connect_bd();
    if(!$pdo) {
        echo "Erreur de connexion à la base de données.";
        return false;
    }
    else{
        $date_now = date('Y-m-d H:i:s');
        $req = "INSERT INTO  signaler (id_user, id_produit, motif, date_signal) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($req);
        $params = [
            $data['id_user'],
            $data['id_produit'],
            $data['motif'],
            $date_now
        ];
        $result = $stmt->execute($params);
        if (!$result) { 
            echo "Erreur lors du signalement de la prevente.";
            return false;
        }
        else {
            deconect_db($pdo);
            return true;
        }
    }
}

function get_prod_signal($id_produit){
    $pdo = connect_bd();
    if(!$pdo) {
        echo "Erreur de connexion à la base de données.";
        return false;
    }
    try {
        $req = "SELECT * FROM signaler WHERE id_produit = ?";
        $stmt = $pdo->prepare($req);
        $result = $stmt->execute([$id_produit]);
        if (!$result) {
            echo "Erreur lors de la récupération des signalements.";
            deconect_db($pdo);
            return false;
        }
        $signalData = $stmt->fetchAll(PDO::FETCH_ASSOC);
        deconect_db($pdo);
        return !empty($signalData); 
    } catch (PDOException $e) {
        echo "Erreur lors de la récupération des signalements : " . $e->getMessage();
        deconect_db($pdo);
        return false;
    }
}


// ===============BLOCAGE / LITIGE==================

function auto_block_50pc($id_produit, $id_vendeur){
    $pdo = connect_bd();
    if(!$pdo) {
        echo "Erreur de connexion à la base de données.";
        return false;
    }
    try {
        $req = "SELECT COUNT(*) AS total_signalements
                FROM signaler
                WHERE id_produit = ?";
        $stmt = $pdo->prepare($req);
        $result = $stmt->execute([$id_produit]);
        if (!$result) {
            echo "Erreur lors de la récupération des signalements.";
            deconect_db($pdo);  
            return false;
        }
        $total_signalements = $stmt->fetch(PDO::FETCH_ASSOC)['total_signalements'];

        $req2 = "SELECT COUNT(*) AS total_acheteurs
                 FROM participation
                 WHERE id_prevente IN (
                     SELECT id_prevente
                     FROM prevente
                     WHERE id_produit = ? AND id_vendeur = ?
                 )";
        $stmt2 = $pdo->prepare($req2);
        $result2 = $stmt2->execute([$id_produit, $id_vendeur]);
        if (!$result2) {
            echo "Erreur lors de la récupération du nombre d'acheteurs.";
            deconect_db($pdo);
            return false;
        }
        $total_acheteurs = $stmt2->fetch(PDO::FETCH_ASSOC)['total_acheteurs'];

        if ($total_acheteurs > 0 && ($total_signalements / $total_acheteurs) >= 0.5) {
            $req3 = "UPDATE vendeur SET est_bloque = 1 WHERE id_user = ?";
            $stmt3 = $pdo->prepare($req3);
            $result3 = $stmt3->execute([$id_vendeur]);
            if (!$result3) {
                echo "Erreur lors du blocage du vendeur.";
                deconect_db($pdo);
                return false;
            }
            echo "Le vendeur a été bloqué en raison d'un trop grand nombre de signalements.";
        }
        deconect_db($pdo);
        return true;
    }
    catch (PDOException $e) {
        echo "Erreur lors de la vérification des signalements : " . $e->getMessage();
        deconect_db($pdo);
        return false;
    }
}

function get_all_vendeur(){
    $pdo = connect_bd();
    if(!$pdo) {
        echo "Erreur de connexion à la base de données.";
        return false;
    }
    try {
        $req = "SELECT * FROM vendeur
                JOIN utilisateur ON vendeur.id_user = utilisateur.id_user";
        $stmt = $pdo->prepare($req);
        $result = $stmt->execute();
        if (!$result) {
            echo "Erreur lors de la récupération des vendeurs.";
            deconect_db($pdo);
            return false;
        }
        $allVendeur = $stmt->fetchAll(PDO::FETCH_ASSOC);
        deconect_db($pdo);
        return $allVendeur;
    } catch (PDOException $e) {
        echo "Erreur lors de la récupération des vendeurs : " . $e->getMessage();
        deconect_db($pdo);
        return false;
    }
}

function block_vendeur($idVendeur){
    $pdo = connect_bd();
    if(!$pdo) {
        echo "Erreur de connexion à la base de données.";
        return false;
    }
    else{
        try{
            $req = "INSERT INTO bloquer (id_vendeur, id_gestionnaire, date_blocage) VALUES (?, ?, ?)";
            $stmt = $pdo->prepare($req);
            $date_now = date('Y-m-d H:i:s');
            $idGestionnaire = $_SESSION['connectedUser']['id_user'];
            $params = [$idVendeur, $idGestionnaire, $date_now];
            $result = $stmt->execute($params);
            if (!$result) {
                echo "Erreur lors du blocage du vendeur.";
                return false;
            }

            $req2 = "UPDATE VENDEUR SET statut = ? WHERE id_user = ? ";
            $stmt2 = $pdo->prepare($req2);
            $params = ["bloque", $idVendeur];
            $result2 = $stmt2->execute($params);
            if (!$result2) {
                echo "Erreur lors du blocage du vendeur.";
                return false;
            }
            else {
                deconect_db($pdo);
                return true;
            }
        }catch (PDOException $e) {
            echo "Erreur lors du bloquage du vendeur : " . $e->getMessage();
            deconect_db($pdo);
            return false;
        }
        
    }
}

function debloquer($idVendeur){
    $pdo = connect_bd();
    if(!$pdo) {
        echo "Erreur de connexion à la base de données.";
        return false;
    }
    else{
        $req = "INSERT INTO debloquer (id_gestionnaire, id_vendeur, date_deblocage) VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($req); 
        $datenow = date('Y-m-d H:i:s');
        $params = [$_SESSION['connectedUser']['id_user'], $idVendeur, $datenow];
        $result = $stmt->execute($params);
        if (!$result) {
            echo "Erreur lors du déblocage du vendeur.";
            return false;
        }

        $req2 = "UPDATE VENDEUR SET statut = ? WHERE id_user = ?";
        $stmt2 = $pdo->prepare($req2);
        $params = ["actif", $idVendeur];
        $result2 = $stmt2->execute($params);
        if(!$result2){

        }
        else {
            deconect_db($pdo);
            return true;
        }
    }
}

function get_vendeur_blocked($idUser){
    $pdo = connect_bd();
    if(!$pdo) {
        echo "Erreur de connexion à la base de données.";
        return false;
    }
    else{
        try {
            $req = "SELECT * FROM vendeur WHERE id_user = ? AND statut = 'bloque'";
            $stmt = $pdo->prepare($req);
            $result = $stmt->execute([$idUser]);
            if (!$result) {
                echo "Erreur lors de la récupération du vendeur bloqué.";
                deconect_db($pdo);
                return false;
            }
            $vendeurData = $stmt->fetch(PDO::FETCH_ASSOC);
            deconect_db($pdo);
            return $vendeurData ? true : false;
        } catch (PDOException $e) {
            echo "Erreur lors de la récupération du vendeur bloqué : " . $e->getMessage();
            deconect_db($pdo);
            return false;
        }
    }
}

function supprimer_vendeur(){
    
}

?> 