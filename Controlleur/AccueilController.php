<?php

function pariticipation($data){
    $pdo = connect_bd();
    if(!$pdo) {
        echo "Erreur de connexion à la base de données.";
        return false;
    }else{
        try{
            $req = "INSERT INTO participation (id_client, id_prevente) VALUES (:idUser, :idPrevente);";
            $stmt = $pdo->prepare($req);
            $stmt->bindParam(':idUser', $_SESSION['connectedUser']['id_user'],PDO::PARAM_INT);
            $stmt->bindParam(':idPrevente', $data['idPrevente'],PDO::PARAM_INT);
            $result = $stmt->execute();
            if(!$result){
                echo "Erreur lors de l'insertion de la participation.";
                return false;
            }
            deconect_db($pdo);
            return true;
        }
        catch (PDOException $e) {
            echo "Erreur lors de l'insertion de la participation : " . $e->getMessage();
            return false;
        }
    }
}

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