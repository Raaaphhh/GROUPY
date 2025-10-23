<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require '../../Layout/headerAdmin.php';
require '../Controlleur/AdminController.php';

if (isset($_POST['submit_update'])) {
    array_pop($_POST);
    if (add_gestionnaire($_POST)) {
        header("Location: /groupy/Admin/ViewsAdmin/dashboardAdmin.php");
        exit;
    } else {
        echo "erreur";
    }
}
?>


<body class="text-center">
    <h1>Admin dashboard</h1>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Ajouter gestionnaire</h5>
                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addModal">
                            +
                        </button>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Suivi de gestionnaire</h5>
                        <a href="gestionGestionnaire.php" class="btn btn-success">Voir</a>
                    </div>
                </div>
            </div>
        </div>
    </div> 


    <!-- Modal Ajout gestionnaire-->
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 shadow-lg">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="addModalLabel">Ajouter un gestionnaire</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <form action="#" method="post">
                <div class="modal-body text-start">

                <div class="mb-3">
                    <label class="form-label">Nom</label>
                    <input type="text" name="nom" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Prenom</label>
                    <input type="text" name="prenom" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Adresse</label>
                    <input type="text" name="adresse" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Telephone</label>
                    <input type="text" name="phone" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">email</label>
                    <input type="email" name="email" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Mot de passe</label>
                    <input type="password" name="motdepasse" class="form-control">
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" name="submit_update" class="btn btn-success">Enregistrer</button>
                </div>
            </form>
            </div>
        </div>
    </div>

</body>

<?php require '../../Layout/footer.php';?>