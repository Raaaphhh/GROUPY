<?php 
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
include 'Layout/header.php'; 
require 'Controlleur/AccueilController.php';

if(isset($_POST['participer'])){
    array_pop($_POST);
    if(pariticipation($_POST)){
        header("Location: index.php");
    } else {
        echo "";
    }
}

$role_user = get_role($_SESSION['connectedUser']['id_user']);
$allpreventepublisheds = get_published_prevente();
$title = "Accueil - Groupy"; 
?>

<?php if (isset($_SESSION['connectedUser'])): ?>
    
    <h1>Preventes en cours : </h1>
    <div class="container mt-4">
        <div class="row">
            <?php foreach ($allpreventepublisheds as $prevente): ?>
            <?php if ($prevente['statut'] === 'Active') : ?>
                <?php $nb_participations = get_count_participants($prevente['id_prevente']); ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm position-relative">

                        <span class="badge 
                            <?php if ($prevente['statut'] === 'Valide'): ?>
                                bg-success
                            <?php else: ?>
                                bg-secondary
                            <?php endif; ?>
                            position-absolute top-0 end-0 m-2 p-2 rounded-pill">
                            <?= htmlspecialchars($prevente['statut']); ?>
                        </span>

                        <?php if (!empty($prevente['image'])): ?>
                            <img src="<?= htmlspecialchars($prevente['image']); ?>" 
                                class="card-img-top img-fluid" 
                                style="max-height: 200px; object-fit: cover;" 
                                alt="Image produit">
                        <?php else: ?>
                            <img src="/assets/images/placeholder.jpg" 
                                class="card-img-top img-fluid" 
                                style="max-height: 200px; object-fit: cover;" 
                                alt="Image par défaut">
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

                            <?php if($role_user === 'client'): ?>
                                <?php if (verif_participation($_SESSION['connectedUser']['id_user'], $prevente['id_prevente'])) : ?>
                                    <p class="text-success">Vous participez déjà à cette prévente.</p>
                                <?php else : ?>
                                    <form action="" method="POST">
                                        <input type="hidden" name="idUser" value="<?= htmlspecialchars($_SESSION['connectedUser']['id_user']); ?>">
                                        <input type="hidden" name="idPrevente" value="<?= $prevente['id_prevente']; ?>">
                                        <button type="submit" name="participer" class="btn btn-dark">Participer</button>
                                    </form>
                                <?php endif; ?>
                            <?php endif; ?>

                        </div>
                    </div>
                </div>
            <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>

<?php else: ?>
    <h1>Preventes en cours : </h1>
    <div class="container mt-4">
        <div class="row">
            <?php foreach ($allpreventepublisheds as $prevente): ?>
            <?php if ($prevente['statut'] === 'Active') : ?>
                <?php $nb_participations = get_count_participants($prevente['id_prevente']); ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm position-relative">

                        <?php if (!empty($prevente['image'])): ?>
                            <img src="<?= htmlspecialchars($prevente['image']); ?>" 
                                class="card-img-top img-fluid" 
                                style="max-height: 200px; object-fit: cover;" 
                                alt="Image produit">
                        <?php else: ?>
                            <img src="/assets/images/placeholder.jpg" 
                                class="card-img-top img-fluid" 
                                style="max-height: 200px; object-fit: cover;" 
                                alt="Image par défaut">
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

                            <a href="Views/User/formco.php">
                                <button type="button" class="btn btn-primary">
                                    En savoir plus
                                </button>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
    
<?php endif; ?>    

<?php include 'Layout/footer.php'; ?>