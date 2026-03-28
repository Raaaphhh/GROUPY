<?php
require '../../Layout/header.php';

if(get_vendeur_blocked($_SESSION['connectedUser']['id_user']) === true){
    header('Location: ' . BASE_URL . '/Views/User/blocked.php');
    exit();
}

if (!isset($_SESSION['connectedUser']) || $_SESSION['connectedUser']['motdepasse_change'] === 0) {
    header('Location: ' . BASE_URL . '/Views/User/formco.php');
    exit();
}

$role = get_role($_SESSION['connectedUser']['id_user']);
$prenom = htmlspecialchars($_SESSION['connectedUser']['prenom']);
$title = "Dashboard - Groupy";
?>

<div class="py-4 mb-4 border-bottom">
    <h2 class="mb-1">Bonjour, <?= $prenom ?> !</h2>
    <span class="badge bg-secondary text-uppercase"><?= htmlspecialchars($role) ?></span>
</div>

<div class="row justify-content-center g-4">

    <div class="col-md-4 col-sm-6">
        <div class="card h-100 shadow-sm border-0">
            <div class="card-body text-center py-4">
                <i class="bi bi-person-circle fs-1 text-primary mb-3 d-block"></i>
                <h5 class="card-title">Mon profil</h5>
                <p class="card-text text-muted small">Consulter et modifier vos informations personnelles.</p>
                <a href="<?= BASE_URL ?>/Views/User/profil.php" class="btn btn-primary">Accéder</a>
            </div>
        </div>
    </div>

    <?php if ($role === "client"): ?>
    <div class="col-md-4 col-sm-6">
        <div class="card h-100 shadow-sm border-0">
            <div class="card-body text-center py-4">
                <i class="bi bi-receipt fs-1 text-warning mb-3 d-block"></i>
                <h5 class="card-title">Mes participations</h5>
                <p class="card-text text-muted small">Suivez vos préventes et téléchargez vos factures.</p>
                <a href="<?= BASE_URL ?>/Views/Client/factures.php" class="btn btn-warning">Accéder</a>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if ($role === "vendeur"): ?>
    <div class="col-md-4 col-sm-6">
        <div class="card h-100 shadow-sm border-0">
            <div class="card-body text-center py-4">
                <i class="bi bi-box-seam fs-1 text-success mb-3 d-block"></i>
                <h5 class="card-title">Mes produits</h5>
                <p class="card-text text-muted small">Gérez votre catalogue de produits.</p>
                <a href="<?= BASE_URL ?>/Views/Vendeur/actProdVend.php" class="btn btn-success">Accéder</a>
            </div>
        </div>
    </div>

    <div class="col-md-4 col-sm-6">
        <div class="card h-100 shadow-sm border-0">
            <div class="card-body text-center py-4">
                <i class="bi bi-tags fs-1 text-success mb-3 d-block"></i>
                <h5 class="card-title">Mes préventes</h5>
                <p class="card-text text-muted small">Publiez et suivez vos campagnes de prévente.</p>
                <a href="<?= BASE_URL ?>/Views/Vendeur/actPrevVend.php" class="btn btn-success">Accéder</a>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if ($role === "gestionnaire"): ?>
    <div class="col-md-4 col-sm-6">
        <div class="card h-100 shadow-sm border-0">
            <div class="card-body text-center py-4">
                <i class="bi bi-list-ul fs-1 text-success mb-3 d-block"></i>
                <h5 class="card-title">Catégories & clients</h5>
                <p class="card-text text-muted small">Gérez les catégories de produits et consultez les clients.</p>
                <a href="<?= BASE_URL ?>/Views/Gestionnaire/actProdGest.php" class="btn btn-success">Accéder</a>
            </div>
        </div>
    </div>

    <div class="col-md-4 col-sm-6">
        <div class="card h-100 shadow-sm border-0">
            <div class="card-body text-center py-4">
                <i class="bi bi-shield-exclamation fs-1 text-danger mb-3 d-block"></i>
                <h5 class="card-title">Gestion des vendeurs</h5>
                <p class="card-text text-muted small">Surveillez les signalements et bloquez les vendeurs.</p>
                <a href="<?= BASE_URL ?>/Views/Gestionnaire/actBloc.php" class="btn btn-danger">Accéder</a>
            </div>
        </div>
    </div>
    <?php endif; ?>

</div>

<?php require '../../Layout/footer.php'; ?>
