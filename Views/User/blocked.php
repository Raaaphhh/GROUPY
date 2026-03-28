<?php
require '../../Layout/header.php';
$title = "Compte suspendu - Groupy";
?>

<div class="row justify-content-center mt-5">
    <div class="col-md-6">
        <div class="card border-danger shadow">
            <div class="card-body text-center py-5">
                <i class="bi bi-shield-x text-danger" style="font-size: 4rem;"></i>
                <h3 class="mt-3 text-danger">Compte suspendu</h3>
                <p class="text-muted mt-3">
                    Votre compte vendeur a été suspendu suite à des signalements.<br>
                    Veuillez contacter un gestionnaire pour plus d'informations.
                </p>
                <hr>
                <a href="<?= BASE_URL ?>/index.php" class="btn btn-outline-secondary">
                    <i class="bi bi-house"></i> Retour à l'accueil
                </a>
            </div>
        </div>
    </div>
</div>

<?php require '../../Layout/footer.php'; ?>
