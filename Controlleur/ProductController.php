<?php
session_start();
// include 'BddConnController.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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
                FROM produit p
                INNER JOIN categorie c ON p.id_categorie_ = c.id_categorie
                WHERE p.id_vendeur = $idUserCo
            ";
            $stmt = $pdo->prepare($req);
            $stmt->execute();
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
            $idUser = $_SESSION['connectedUser']['id_user'];
            $req = "
                SELECT prevente.*, produit.*, categorie.lib AS nom_categorie
                FROM prevente
                INNER JOIN produit 
                    ON prevente.id_produit = produit.id_produit
                INNER JOIN categorie 
                    ON produit.id_categorie_ = categorie.id_categorie
                WHERE produit.id_vendeur = $idUser;";
            $stmt = $pdo->prepare($req);
            $stmt->execute();
            $preventes = $stmt->fetchAll(PDO::FETCH_ASSOC);
            var_dump($preventes);
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
            $req = "SELECT id_categorie, lib FROM categorie";
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

// function get_cate_used(){
//     $req = "SELECT id_categorie, lib, FROM categorie
//             INNER JOIN produit ON categorie.id_categorie = produit.id_categorie_
//             WHERE produit.id_vendeur = ?";
// }




// ============= ADD =============
function add_categorie($data){
    $pdo = connect_bd();
    if(!$pdo) {
        echo "Erreur de connexion à la base de données.";
        return false;
    }else{
        try{
            $req = "INSERT INTO categorie (id_gestionnaire, lib) VALUES (?, ?)";
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
    var_dump($data);
    $pdo = connect_bd();
    if(!$pdo) {
        echo "Erreur de connexion à la base de données.";
        return false;
    }else{
        try{
            $req = "INSERT INTO produit (id_categorie_, nom, description, prix, image, id_vendeur) 
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
            $req = "INSERT INTO prevente (date_limite, nombre_min, statut, prix_prevente, id_produit) VALUES (?,?,?,?,?)";
            $stmt = $pdo->prepare($req);
            $params = [
                $data['date_limite'],
                $data['nombre_minimum'],
                "non plubié",
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
            // ajouter une verification de si le produit est deja en vente
            $req = "UPDATE prevente SET statut = ? WHERE id_prevente = ?";
            $stmt = $pdo->prepare($req);
            $params = [
                "publié",
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
            $req = "DELETE FROM produit WHERE id_produit = ?";
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
            $req = "DELETE FROM categorie WHERE id_categorie = ?";
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
// ==================================




// ============= UPDATE =============
function update_categorie($data){
    $pdo = connect_bd();
    if(!$pdo) {
        echo "Erreur de connexion à la base de données.";
        return false;
    }else{
        try{
            $req = "UPDATE categorie SET lib = ? WHERE id_categorie = ?";
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
            $req = "UPDATE produit 
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
// ==================================

?>