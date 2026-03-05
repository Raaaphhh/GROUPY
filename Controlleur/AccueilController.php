<?php

function pariticipation($data){
    $pdo = connect_bd();
    if(!$pdo) {
        echo "Erreur de connexion à la base de données.";
        return false;
    }else{
        if(verif_participation($data['idUser'], $data['idPrevente'])){
            return false;
        }
        else{
            try{
                // var_dump($data);
                $req = "INSERT INTO Participation (id_client, id_prevente) VALUES (:idUser, :idPrevente);";
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
}

function verif_participation($idUser, $idPrevente){
    $pdo = connect_bd();
    if(!$pdo) {
        echo "Erreur de connexion à la base de données.";
        return false;
    }else{
        try{
            $req = "SELECT * FROM Participation WHERE id_client = :idUser AND id_prevente = :idPrevente;";
            $stmt = $pdo->prepare($req);
            $stmt->bindParam(':idUser', $idUser, PDO::PARAM_INT);
            $stmt->bindParam(':idPrevente', $idPrevente, PDO::PARAM_INT);
            $stmt->execute();
            $participation = $stmt->fetch(PDO::FETCH_ASSOC);
            deconect_db($pdo);
            if($participation){
                return true;
            } else {
                return false;
            }
        }
        catch (PDOException $e) {
            echo "Erreur lors de la vérification de la participation : " . $e->getMessage();
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
            $idUser = isset($_SESSION['connectedUser']) ? $_SESSION['connectedUser']['id_user'] : null;
            $req = "
                SELECT Prevente.*, Produit.*, Categorie.lib AS nom_categorie
                FROM Prevente
                INNER JOIN Produit 
                    ON Prevente.id_produit = Produit.id_produit
                INNER JOIN Categorie 
                    ON Produit.id_categorie_ = Categorie.id_categorie";

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
            $req = "SELECT COUNT(*) AS total FROM Participation WHERE id_prevente = :idPrevente;";
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