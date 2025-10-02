<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>

<h1>Admin dashboard</h1>

<div class="card" style="width: 18rem;">
    <div class="card-body">
        <h5 class="card-title">Gestion des données</h5>
        <p class="card-text">Ajoutez, modifiez ou supprimez des données facilement à partir de ce tableau de bord.</p>
        <a href="add.php" class="btn btn-success">Ajouter</a>
        <a href="update.php" class="btn btn-warning">Modifier</a>
        <a href="delete.php" class="btn btn-danger">Supprimer</a>
    </div>
</div>

<?php require '../Layout/footerAdmin.php'; ?>