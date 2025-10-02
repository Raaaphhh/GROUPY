<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../../Layout/header.php'; 

if (!isset($_SESSION['connectedUser'])) {
    header('Location: /groupy/Views/User/formco.php'); 
    exit();
}

if (isset($_POST['submit_logout'])) {
    logout();
    header('Location: /groupy/index.php'); 
    exit();
}

$role = get_role($_SESSION['connectedUser']['id_user']);


if (isset($_POST['submit_update'])) {
    if (updateUser($_POST, $role)) {
        header("Location: profil.php");
        exit();
    } else {
        echo "Erreur lors de la mise à jour.";
    }
}


$title = "Inscription Pro - Groupy"; 
?>

<body class="bg-light text-center">

    <h1>Bienvenue <?php echo htmlspecialchars($_SESSION['connectedUser']['prenom']); ?></h1>

    <div class="card shadow-lg border-0 rounded-4 mx-auto mt-5" style="max-width: 28rem;">
        <div class="card-header bg-primary text-white text-center rounded-top-4">
            <h5 class="mb-0">Informations Utilisateur</h5>
        </div>
        <div class="card-body">
            <ul class="list-group list-group-flush">
                <li class="list-group-item"><strong>Nom:</strong> <?= htmlspecialchars($_SESSION['connectedUser']['nom']); ?></li>
                <li class="list-group-item"><strong>Prénom:</strong> <?= htmlspecialchars($_SESSION['connectedUser']['prenom']); ?></li>
                <li class="list-group-item"><strong>Email:</strong> <?= htmlspecialchars($_SESSION['connectedUser']['email']); ?></li>
                <li class="list-group-item"><strong>Adresse:</strong> <?= htmlspecialchars($_SESSION['connectedUser']['adresse']); ?></li>
                <li class="list-group-item"><strong>Téléphone:</strong> <?= htmlspecialchars($_SESSION['connectedUser']['phone']); ?></li>
            </ul>

            <?php if ($role == "vendeur"): ?>
                <div class="mt-4">
                    <h6 class="text-primary fw-bold mb-3">Informations entreprise</h6>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><strong>Nom entreprise:</strong> <?= htmlspecialchars($_SESSION['vendeurInfo']['nom_entreprise']); ?></li>
                        <li class="list-group-item"><strong>Siret:</strong> <?= htmlspecialchars($_SESSION['vendeurInfo']['siret']); ?></li>
                        <li class="list-group-item"><strong>Adresse entreprise:</strong> <?= htmlspecialchars($_SESSION['vendeurInfo']['adresse_entreprise']); ?></li>
                        <li class="list-group-item"><strong>Email professionnel:</strong> <?= htmlspecialchars($_SESSION['vendeurInfo']['email_pro']); ?></li>
                    </ul>
                </div>
            <?php endif; ?>
        </div>
    </div>


    <div class="d-flex justify-content-center gap-3 mt-4">
        <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editModal">
            Modifier mes infos
        </button>
        <form action="#" method="post">
            <button class="btn btn-danger" type="submit" name="submit_logout">
                Déconnexion
            </button>
        </form>
    </div>


    <!-- Modal EDIT -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 shadow-lg">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title" id="editModalLabel">Modifier mes informations</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <form action="#" method="post">
                <div class="modal-body text-start">
                <input type="hidden" name="id_user" value="<?= $_SESSION['connectedUser']['id_user'] ?>">

                <div class="mb-3">
                    <label class="form-label">Nom</label>
                    <input type="text" name="nom" class="form-control" value="<?= htmlspecialchars($_SESSION['connectedUser']['nom']); ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Prénom</label>
                    <input type="text" name="prenom" class="form-control" value="<?= htmlspecialchars($_SESSION['connectedUser']['prenom']); ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($_SESSION['connectedUser']['email']); ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Adresse</label>
                    <input type="text" name="adresse" class="form-control" value="<?= htmlspecialchars($_SESSION['connectedUser']['adresse']); ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Téléphone</label>
                    <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($_SESSION['connectedUser']['phone']); ?>">
                </div>

                <?php if ($role == "vendeur"): ?>
                    <hr>
                    <h6 class="text-primary fw-bold mb-3">Informations entreprise</h6>
                    <div class="mb-3">
                    <label class="form-label">Nom entreprise</label>
                    <input type="text" name="nom_entreprise" class="form-control" value="<?= htmlspecialchars($_SESSION['vendeurInfo']['nom_entreprise']); ?>">
                    </div>
                    <div class="mb-3">
                    <label class="form-label">Siret</label>
                    <input type="text" name="siret" class="form-control" value="<?= htmlspecialchars($_SESSION['vendeurInfo']['siret']); ?>">
                    </div>
                    <div class="mb-3">
                    <label class="form-label">Adresse entreprise</label>
                    <input type="text" name="adresse_entreprise" class="form-control" value="<?= htmlspecialchars($_SESSION['vendeurInfo']['adresse_entreprise']); ?>">
                    </div>
                    <div class="mb-3">
                    <label class="form-label">Email professionnel</label>
                    <input type="email" name="email_pro" class="form-control" value="<?= htmlspecialchars($_SESSION['vendeurInfo']['email_pro']); ?>">
                    </div>
                <?php endif; ?>
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


<?php require '../../Layout/footer.php'; ?>