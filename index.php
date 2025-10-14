<?php 
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
include 'Layout/header.php'; 
require 'Controlleur/AccueilController.php';
$allpreventepublisheds = get_published_prevente();
$title = "Accueil - Groupy"; 
?>

<?php if (!isset($_SESSION['connectedUser'])): ?>
    
<h1>Bienvenue sur Groupy</h1>
<p>Ceci est ma page d’accueil.</p>

<?php else: ?>
    <h1>Preventes en cours : </h1>
    <div class="container mt-4">
        <div class="row">
            <?php foreach ($allpreventepublisheds as $prevente): ?>
                <?php $nb_participations = get_count_participants($prevente['id_prevente']); ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <?php if (!empty($prevente['image'])): ?>
                            <img src="<?= htmlspecialchars($prevente['image']); ?>" class="card-img-top" alt="Image produit">
                        <?php else: ?>
                            <img src="/assets/images/placeholder.jpg" class="card-img-top" alt="Image par défaut">
                        <?php endif; ?>

                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><?= htmlspecialchars($prevente['nom']); ?></h5>
                            <p class="card-text text-muted mb-1">
                                <strong>Catégorie :</strong> <?= htmlspecialchars($prevente['nom_categorie']); ?>
                            </p>
                            <p class="card-text"><?= htmlspecialchars($prevente['description']); ?></p>

                            <p class="card-text mb-2">
                                <strong>Prix prévente :</strong> <?= htmlspecialchars($prevente['prix_prevente']); ?> €
                            </p>
                            <p class="card-text mb-2">
                                <strong>Date limite :</strong> <?= htmlspecialchars($prevente['date_limite']); ?>
                            </p>
                            <p class="card-text mb-2">
                                <strong>Objectif :</strong> <?= $nb_participations ?>/<?= htmlspecialchars($prevente['nombre_min']); ?> participants
                            </p>

                            <a href="" class="btn btn-success mt-auto">Participer</a>

                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>    





<?php include 'Layout/footer.php'; ?>