<?php 
require '../../Layout/header.php'; 
// require ''; 

if (!isset($_SESSION['connectedUser']) || $_SESSION['connectedUser']['motdepasse_change'] === 0) {
    header('Location: /groupy/Views/User/formco.php'); 
    exit();
}

$role = get_role($_SESSION['connectedUser']['id_user']);
$title = "Dashboard - Groupy"; 
?>

<body class="bg-light text-center">
<h1>Espace <?php echo $role ?> </h1>

<div class="container mt-4">
    <div class="row">
        <!-- Profile Card -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="card-title">Profil</h5>
                    <a href="/groupy/Views/User/profil.php" class="btn btn-primary">Voir</a>
                </div>
            </div>
        </div>

        <?php if ($role === "vendeur" || $role === "gestionnaire" ): ?>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="card-title">Gestion de produits</h5>
                    <a href="/groupy/Views/Produit/dashboardProduit.php" class="btn btn-success">Voir</a>
                </div>
            </div>
        </div>    
        <?php endif; ?>

    </div>
</div>

<!-- Modal pour ajouter un produit -->
<!-- <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 shadow-lg">
        <div class="modal-header bg-warning text-dark">
            <h5 class="modal-title" id="addModalLabel">Ajouter un produit</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
        </div>
        <form action="#" method="post">
            <div class="modal-body text-start">

            <div class="mb-3">
                <label class="form-label">Photo</label>
                <input type="pic" name="pic" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">Nom</label>
                <input type="text" name="nom" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">Description</label>
                <input type="text" name="description" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">prix</label>
                <input type="number" name="prix" class="form-control">
            </div>


            <div class="mb-3">
                <label class="form-label">Catégorie</label>
                <select name="categorie" class="form-select">
                    
                </select>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Annuler</button>
                <button type="submit" name="submit_update" class="btn btn-success">ajouter</button>
            </div>
        </form>
        </div>
    </div>
</div> -->

</body>

<?php require '../../Layout/footer.php'; ?>
