<?php

function get_published_prevente(){
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
                WHERE prevente.statut = 'publie';";

            $stmt = $pdo->prepare($req);
            $stmt->execute();
            $preventesPublished = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if($preventesPublished){
                deconect_db($pdo);
                return $preventesPublished;
            }
        }
        catch (PDOException $e) {
            echo "Erreur lors de la récupération des produits : " . $e->getMessage();
            return false;
        }
    }
}

function get_count_participants($idPrevente){
    $pdo = connect_bd();
    if(!$pdo) {
        echo "Erreur de connexion à la base de données.";
        return false;
    }
    else{
        try{
            $req = "SELECT COUNT(*) AS total FROM participation WHERE id_prevente = :idPrevente;";
            $stmt = $pdo->prepare($req);
            $stmt->bindParam(':idPrevente', $idPrevente, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if($result){
                deconect_db($pdo);
                return $result['total'];
            }
        }
        catch (PDOException $e) {
            echo "Erreur lors de la récupération du nombre de participants : " . $e->getMessage();
            return false;
        }
    }
}

?>