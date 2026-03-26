<?php 
require '../../Layout/header.php'; 

if (!isset($_SESSION['connectedUser'])) {
    header('Location: ' . BASE_URL . '/Views/User/formco.php'); 
    exit();
}

if(isset($_POST['submit'])) {
    csrf_verify();
    array_pop($_POST);
    if (changePassword($_POST)){
        header('Location: ' . BASE_URL . '/views/user/dashboard.php');
        exit();
    }
    else{
        echo "<div class='alert alert-danger text-center' role='alert'>Erreur lors du changement de mot de passe. Veuillez réessayer.</div>";
    }
}

$title = "Change mot de passe - Groupy"; 
?>

<h1 class="text-center my-4">Changez votre mot de passe</h1>
<p class="text-center">C'est votre première connexion, veuillez changer votre mot de passe.</p>

<div class="container">
    <form action="" method="post" class="w-50 mx-auto">
        <?= csrf_field() ?>
        <div class="mb-3">
            <label for="new_password" class="form-label">Nouveau mot de passe</label>
            <input type="password" class="form-control" id="new_password" name="new_password" placeholder="Entrez votre nouveau mot de passe" required>
        </div>
        <div class="mb-3">
            <label for="confirm_password" class="form-label">Confirmez le mot de passe</label>
            <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirmez votre mot de passe" required>
        </div>
        <div class="d-grid">
            <button type="submit" name="submit" class="btn btn-primary">Changer le mot de passe</button>
        </div>
    </form>
</div>


<?php require '../../Layout/footer.php'; ?>