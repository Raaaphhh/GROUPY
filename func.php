<?php
// peut etre supprimé
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();

function connect_bd() {
    $host = "localhost";
    $dbname = "auth_secure";
    $username = "root"; 
    $password = "mysql";
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
        // $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        // echo "Connexion réussie à la base de données.";
        return $pdo;
    } catch (PDOException $e) {
        die("Erreur de connexion : " . $e->getMessage());
        return null;
    }
}

function deconect_db() {
    $pdo = null;
}

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

function uploadPic($file) {
    $target_dir = "photo/";
    $target_file = $target_dir . basename($file["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Vérifier si c’est bien une image
    $check = getimagesize($file["tmp_name"]);
    if ($check !== false) {
        echo "File is an image - " . $check["mime"] . ".<br>";
    } else {
        echo "File is not an image.<br>";
        $uploadOk = 0;
    }

    // Vérifier si le fichier existe déjà
    if (file_exists($target_file)) {
        echo "Sorry, file already exists.<br>";
        $uploadOk = 0;
    }

    // Vérifier la taille du fichier
    if ($file["size"] > 500000) {
        echo "Sorry, your file is too large.<br>";
        $uploadOk = 0;
    }

    // Autoriser uniquement certains formats
    $allowed = ["jpg", "jpeg", "png", "gif"];
    if (!in_array($imageFileType, $allowed)) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.<br>";
        $uploadOk = 0;
    }

    // Vérification finale
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.<br>";
        return false;
    } else {
        if (move_uploaded_file($file["tmp_name"], $target_file)) {
            echo "The file ". htmlspecialchars(basename($file["name"])) . " has been uploaded.<br>";
            return $target_file;
        } else {
            echo "Sorry, there was an error uploading your file.<br>";
            return false;
        }
    }
}

// test test mail 
function sendMail($desti, $objet, $message){
    require 'vendor/autoload.php';

    $mail = new PHPMailer(true);

    try {
        // config SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'dcpraphael@gmail.com';
        $mail->Password = 'mxkw sjyt drrd scgd'; 
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        // Infos mail
        $mail->setFrom('dcpraphael@gmail.com', 'Raphael');
        $mail->addAddress($desti); // adresse desti
        $mail->Subject = $objet;
        $mail->Body    = $message;

        $mail->send();
        echo "Mail envoyé avec succès";
    } catch (Exception $e) {
        echo "Erreur : {$mail->ErrorInfo}";
    }
}

function updateUser($data){
    $pdo = connect_bd();
    if(!$pdo) {
        echo "Erreur de connexion à la base de données.";
        return false;
    }
    else{
        $data['photo'] = uploadPic($_FILES['photo']);
        $data['id'] = $_SESSION['connectedUser']['id'];
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
            $data['photo'],
            $data['id']
        ];
        // var_dump($params);
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

?>