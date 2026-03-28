<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// include 'BddConnController.php';
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

function get_produits($idUserCo) {
    $pdo = connect_bd();
    if(!$pdo) {
        echo "Erreur de connexion à la base de données.";
        return false;
    }
    else{
        try{
            $req = "
                SELECT p.id_produit, p.nom, p.description, p.prix, p.image,
                    p.id_vendeur, p.created_at, p.updated_at,
                    c.lib AS categorie
                FROM Produit p
                INNER JOIN Categorie c ON p.id_categorie_ = c.id_categorie
                WHERE p.id_vendeur = ?";
            $stmt = $pdo->prepare($req);
            $stmt->execute([$idUserCo]);
            $produits = $stmt->fetchAll(PDO::FETCH_ASSOC);
            deconect_db($pdo);
            return $produits;
        }
        catch (PDOException $e) {
            echo "Erreur lors de la récupération des produits : " . $e->getMessage();
            return false;
        }
    }
}

function get_prevente(){
    $pdo = connect_bd();
    if(!$pdo) {
        echo "Erreur de connexion à la base de données.";
        return false;
    }
    else{
        try{
            $pdo->exec("UPDATE Prevente SET statut = 'Fermée' WHERE date_limite < CURDATE() AND statut = 'Active'");
            $idUser = $_SESSION['connectedUser']['id_user'];
            $req = "
                SELECT Prevente.*, Produit.*, Categorie.lib AS nom_categorie
                FROM Prevente
                INNER JOIN Produit
                    ON Prevente.id_produit = Produit.id_produit
                INNER JOIN Categorie
                    ON Produit.id_categorie_ = Categorie.id_categorie
                WHERE Produit.id_vendeur = ?";
            $stmt = $pdo->prepare($req);
            $stmt->execute([$idUser]);
            $preventes = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if($preventes){
                deconect_db($pdo);
                return $preventes;
            }
        }
        catch (PDOException $e) {
            echo "Erreur lors de la récupération des produits : " . $e->getMessage();
            return false;
        }
    }
}

function get_categories(){
    $pdo = connect_bd();
    if(!$pdo) {
        echo "Erreur de connexion à la base de données.";
        return false;
    }else{
        try{
            $req = "SELECT id_categorie, lib FROM Categorie";
            $stmt = $pdo->prepare($req);
            $stmt->execute();
            $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
            deconect_db($pdo);
            return $categories;
        }
        catch (PDOException $e) {
            echo "Erreur lors de la récupération des catégories : " . $e->getMessage();
            return false;
        }
    }
}

function get_all_users(){
    $pdo = connect_bd();
    if(!$pdo) {
        echo "Erreur de connexion à la base de données.";
        return false;
    }else{
        try{
            $req = "SELECT u.id_user,u.nom,u.prenom, u.adresse,u.phone, u.email,
                        COUNT(p.id_particiption) AS nombre_participations FROM Utilisateur u
                    INNER JOIN Client c ON u.id_user = c.id_user
                    LEFT JOIN Participation p ON c.id_user = p.id_client
                    GROUP BY u.id_user, u.nom, u.prenom, u.email
                    ORDER BY nombre_participations DESC;";
            $stmt = $pdo->prepare($req);
            $stmt->execute();
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
            deconect_db($pdo);
            return $users;
        }
        catch (PDOException $e) {
            echo "Erreur lors de la récupération des utilisateurs : " . $e->getMessage();
            return false;
        }
    }
}

function verif_produit_prevente($idProduit){
    $pdo = connect_bd();
    if(!$pdo) {
        echo "Erreur de connexion à la base de données.";
        return false;
    }else{
        try{
            $req = "SELECT * FROM Prevente WHERE id_produit = ?";
            $stmt = $pdo->prepare($req);
            $params = [$idProduit];
            $stmt->execute($params);
            $produit = $stmt->fetch(PDO::FETCH_ASSOC);
            deconect_db($pdo);
            if($produit){
                return true;
            }
            return false;
        }
        catch (PDOException $e) {
            echo "Erreur lors de la récupération des produits : " . $e->getMessage();
            return false;
        }
    }
}

function uploadPic($file) {
    // Dossier de stockage physique
    // $target_dir = $_SERVER['DOCUMENT_ROOT'] . "/groupy/photo/";
    $target_dir = ROOT_PATH . "/photo/";
    // Nom unique
    $file_name = uniqid() . "_" . basename($file["name"]);
    $target_file = $target_dir . $file_name;

    // Chemin public enregistré en BDD
    // $public_path = "/groupy/photo/" . $file_name;
    $public_path = BASE_URL . "/photo/" . $file_name;

    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $allowed = ["jpg", "jpeg", "png", "gif"];

    // Vérification image
    $check = getimagesize($file["tmp_name"]);
    if ($check === false) return false;

    if (!in_array($imageFileType, $allowed)) return false;
    if ($file["size"] > 5000000) return false; 

    // Upload
    if (move_uploaded_file($file["tmp_name"], $target_file)) {
        return $public_path;
    } else {
        return false;
    }
}



// ============= ADD =============
function add_categorie($data){
    $pdo = connect_bd();
    if(!$pdo) {
        echo "Erreur de connexion à la base de données.";
        return false;
    }else{
        try{
            $req = "INSERT INTO Categorie (id_gestionnaire, lib) VALUES (?, ?)";
            $stmt = $pdo->prepare($req);
            $params = [
                $_SESSION['connectedUser']['id_user'],
                $data['nom']
            ];
            $result = $stmt->execute($params);
            if (!$result) {
                echo "Erreur lors de l'exécution de la requête.";
                return false;
            }else{
                deconect_db($pdo);
                return true; 
            }
        }
        catch (PDOException $e) {
            echo "Erreur lors de l'ajout de la catégorie : " . $e->getMessage();
            return false;
        }
    }
}

function add_produit($data){
    $pdo = connect_bd();
    if(!$pdo) {
        echo "Erreur de connexion à la base de données.";
        return false;
    }else{
        try{
            $req = "INSERT INTO Produit (id_categorie_, nom, description, prix, image, id_vendeur) 
                    VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($req);
            $params = [
                $data['categorie'],
                $data['nom'],
                $data['description'],
                $data['prix'],
                $data['pic'],
                $_SESSION['connectedUser']['id_user'],
            ];
            $result = $stmt->execute($params);
            if (!$result) {
                echo "Erreur lors de l'exécution de la requête.";
                return false;
            }else{
                deconect_db($pdo);
                return true; 
            }
        }
        catch (PDOException $e) {
            echo "Erreur lors de l'ajout du produit : " . $e->getMessage();
            return false;
        }
    }
}

function create_prevente($data){
    $pdo = connect_bd();
    if(!$pdo) {
        echo "Erreur de connexion à la base de données.";
        return false;
    }else{
        try{
            // ajouter une verification de si le produit est deja en vente
            $req = "INSERT INTO Prevente (date_limite, nombre_min, statut, prix_prevente, id_produit) VALUES (?,?,?,?,?)";
            $stmt = $pdo->prepare($req);
            $params = [
                $data['date_limite'],
                $data['nombre_minimum'],
                "non publié",
                $data['prix_prevente'],
                $data['id_produit']
            ];
            $result = $stmt->execute($params);
            if (!$result) {
                echo "Erreur lors de l'exécution de la requête.";
                return false;
            }else{
                deconect_db($pdo);
                return true; 
            }
        }
        catch (PDOException $e) {
            echo "Erreur lors de la publication du produit : " . $e->getMessage();
            return false;
        }
    }
}

function published_prevente($data){
    $pdo = connect_bd();
    if(!$pdo) {
        echo "Erreur de connexion à la base de données.";
        return false;
    }else{
        try{
            $req = "UPDATE Prevente SET statut = ? WHERE id_prevente = ?";
            $stmt = $pdo->prepare($req);
            $params = [
                "Active",
                $data['id_prevente']
            ];
            $result = $stmt->execute($params);
            if (!$result) {
                echo "Erreur lors de l'exécution de la requête.";
                return false;
            }else{
                deconect_db($pdo);
                return true; 
            }
        }
        catch (PDOException $e) {
            echo "Erreur lors de la publication du produit : " . $e->getMessage();
            return false;
        }
    }
}
// ==================================




// ============= DELETE =============
function del_produit($idProduit){
    $pdo = connect_bd();
    if(!$pdo) {
        echo "Erreur de connexion à la base de données.";
        return false;
    }else{
        try{
            $idProd = $idProduit['id_produit'];
            $req = "DELETE FROM Produit WHERE id_produit = ?";
            $stmt = $pdo->prepare($req);
            $params = [$idProd];
            $result = $stmt->execute($params);
            if (!$result) {
                echo "Erreur lors de l'exécution de la requête.";
                return false;
            }else{
                deconect_db($pdo);
                return true; 
            }
        }
        catch (PDOException $e) {
            echo "Erreur lors de la suppression du produit : " . $e->getMessage();
            return false;
        }
    }
}

function del_categorie($idCategorie){
    $pdo = connect_bd();
    if(!$pdo) {
        echo "Erreur de connexion à la base de données.";
        return false;
    }else{
        try{
            $idCate = $idCategorie['id_categorie'];
            $req = "DELETE FROM Categorie WHERE id_categorie = ?";
            $stmt = $pdo->prepare($req);
            $params = [$idCate];
            $result = $stmt->execute($params);
            if (!$result) {
                echo "Erreur lors de l'exécution de la requête.";
                return false;
            }else{
                deconect_db($pdo);
                return true; 
            }
        }
        catch (PDOException $e) {
            echo "Erreur lors de la suppression du produit : " . $e->getMessage();
            return false;
        }
    }
}

function del_prevente($idPrevente){
    $pdo = connect_bd();
    if(!$pdo) {
        echo "Erreur de connexion à la base de données.";
        return false;
    }else{
        try{
            $idPrev = $idPrevente['id_prevente'];
            $req = "DELETE FROM Prevente WHERE id_prevente = ?";
            $stmt = $pdo->prepare($req);
            $params = [$idPrev];
            $result = $stmt->execute($params);
            if (!$result) {
                echo "Erreur lors de l'exécution de la requête.";
                return false;
            }else{
                deconect_db($pdo);
                return true; 
            }
        }
        catch (PDOException $e) {
            echo "Erreur lors de la suppression du produit : " . $e->getMessage();
            return false;
        }
    }
}
// ==================================




// ============= UPDATE =============
function update_categorie($data){
    $pdo = connect_bd();
    if(!$pdo) {
        echo "Erreur de connexion à la base de données.";
        return false;
    }else{
        try{
            $req = "UPDATE Categorie SET lib = ? WHERE id_categorie = ?";
            $stmt = $pdo->prepare($req);
            $params = [
                $data['nom'],
                $data['id_categorie']
            ];
            $result = $stmt->execute($params);
            if (!$result) {
                echo "Erreur lors de l'exécution de la requête.";
                return false;
            }else{
                deconect_db($pdo);
                return true; 
            }
        }
        catch (PDOException $e) {
            echo "Erreur lors de la modification de la catégorie : " . $e->getMessage();
            return false;
        }
    }
}

function update_produit($data){
    $pdo = connect_bd();
    if(!$pdo) {
        echo "Erreur de connexion à la base de données.";
        return false;
    }else{
        try{
            $req = "UPDATE Produit 
                    SET id_categorie_ = ?, nom = ?, description = ?, prix = ?
                    WHERE id_produit = ?";
            $stmt = $pdo->prepare($req);
            $params = [
                $data['id_categorie'],
                $data['nom'],
                $data['description'],
                $data['prix'],
                $data['id_produit']
            ];
            $result = $stmt->execute($params);
            if (!$result) {
                echo "Erreur lors de l'exécution de la requête.";
                return false;
            }else{
                deconect_db($pdo);
                return true; 
            }
        }
        catch (PDOException $e) {
            echo "Erreur lors de la modification du produit : " . $e->getMessage();
            return false;
        }
    }
}

function update_prevente($data){
    $pdo = connect_bd();
    if(!$pdo) {
        echo "Erreur de connexion à la base de données.";
        return false;
    }else{
        try{
            $req = "UPDATE Prevente 
                    SET prix_prevente = ?, date_limite = ?, nombre_min = ?
                    WHERE id_prevente = ?";
            $stmt = $pdo->prepare($req);
            $params = [
                $data['prix_prevente'],
                $data['date_limite'],
                $data['nombre_min'],
                $data['id_prevente']
            ];
            $result = $stmt->execute($params);
            if (!$result) {
                echo "Erreur lors de l'exécution de la requête.";
                return false;
            }else{
                deconect_db($pdo);
                return true; 
            }
        }
        catch (PDOException $e) {
            echo "Erreur lors de la modification du produit : " . $e->getMessage();
            return false;
        }
    }
}