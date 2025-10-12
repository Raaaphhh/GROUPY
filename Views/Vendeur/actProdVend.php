<?php 
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
require '../../Layout/header.php'; 
require '../../Controlleur/ProductController.php';

$role = get_role($_SESSION['connectedUser']['id_user']);
if (!isset($_SESSION['connectedUser']) || $role !== "vendeur") {
    header('Location: /groupy/index.php'); 
    exit();
}

if(isset($_POST['add_produit_produit'])){
    array_pop($_POST);
    if(add_produit($_POST)){
        header('Location: /groupy/Views/Vendeur/actProdvend.php');
        exit();
    }else{
        echo "ajout prod erreur";
    }
}
if(isset($_POST['supprimer_produit'])){
    unset($_POST['supprimer']); 
    if(del_produit($_POST)){
        header('Location: /groupy/Views/Vendeur/actProdvend.php');
        exit();
    }else{
        echo "del prod error";
    }
}
if(isset($_POST['modifier_produit'])){
    var_dump($_POST);
    unset($_POST['modifier_produit']); 
    if(update_produit($_POST)){
        header('Location: /groupy/Views/Vendeur/actProdvend.php');
        exit();
    }else{
        echo "del prod error";
    }
}
if(isset($_POST['create_prevente'])){
    array_pop($_POST);
    if(create_prevente($_POST)){
        header('Location: /groupy/Views/Vendeur/actProdvend.php');
        exit();
    }else{
        echo "publihed error";
    }
}
if(isset($_POST['published_prevente'])){
    unset($_POST['published_prevente']); 
    if(published_prevente($_POST)){
        header('Location: /groupy/Views/Vendeur/actProdvend.php');
        exit();
    }else{
        echo "publihed error";
    }
}
if(isset($_POST['update_prevente'])){
    unset($_POST['update_prevente']); 
    if(update_prevente($_POST)){
        header('Location: /groupy/Views/Vendeur/actProdvend.php');
        exit();
    }else{
        echo "del prevene error";
    }
}
if(isset($_POST['supprimer_prevente'])){
    unset($_POST['supprimer_prevente']); 
    if(del_prevente($_POST)){
        header('Location: /groupy/Views/Vendeur/actProdvend.php');
        exit();
    }else{
        echo "del prevene error";
    }
}

$idUser = $_SESSION['connectedUser']['id_user'];
$produits = get_produits($idUser);
$categories = get_categories();
$preventes = get_prevente();

require 'modals/add_produit.php';
require 'modals/del_produit.php';
require 'modals/update_produit.php';
require 'modals/create_prevente.php';
require 'modals/published_prevente.php'; 
require 'modals/update_prevente.php';
require 'modals/del_prevente.php';

$title = "Action - Produit - Vendeur - Groupy"; 
?>

<body class="bg-light text-center">
    <h1 class="mb-4">Gestion Produits</h1>
    <button class="btn btn-success mb-3 me-3" data-bs-toggle="modal" data-bs-target="#addProduitModal">
        <i class="bi bi-plus-square"></i> Produit
    </button>
    <button class="btn btn-success mb-3 ms-3" data-bs-toggle="modal" data-bs-target="#pulbishedProduitModal">
        <i class="bi bi-plus-square"></i> Prévente
    </button>

    <!-- liste des produits -->
    <h3 class="mt-3">Liste des produits :</h3>
    <?php if ($produits) : ?>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Categorie</th>
                <th>Description</th>
                <th>Prix</th>
                <th>Image</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($produits as $produit) : ?>
                <tr>
                    <td><?= htmlspecialchars($produit['id_produit'])?></td>
                    <td><?= htmlspecialchars($produit['nom']) ?></td>
                    <td><?= htmlspecialchars($produit['categorie']) ?></td>
                    <td><?= htmlspecialchars($produit['description']) ?></td>
                    <td><?= htmlspecialchars($produit['prix']) ?> €</td>
                    <td><img src="<?= htmlspecialchars($produit['image']) ?>" alt="Image" width="50"></td>
                    <td>
                        <button 
                            type="button" 
                            class="btn btn-warning btn-sm"
                            data-bs-toggle="modal"
                            data-bs-target="#updateProdModal"
                            data-id="<?= htmlspecialchars($produit['id_produit']) ?>"
                            data-nom="<?= htmlspecialchars($produit['nom']) ?>"
                            data-description="<?= htmlspecialchars($produit['description']) ?>"
                            data-prix="<?= htmlspecialchars($produit['prix']) ?>"
                            data-categorie="<?= htmlspecialchars($produit['categorie']) ?>">
                            <i class="bi bi-pencil"></i>
                        </button>

                        <button 
                            type="button" 
                            class="btn btn-danger btn-sm"
                            data-bs-toggle="modal"
                            data-bs-target="#deleteModal"
                            data-id="<?= htmlspecialchars($produit['id_produit']) ?>">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php else: ?>
        <p>Aucun produit ajouté pour le moment.</p>
    <?php endif; ?>

    <br><br>
    
    <!-- liste des preventes non publiés-->
    <?php if ($preventes && $role == "vendeur") :?>
    <h3 class="mt-3">Liste des préventes non publiés:</h3>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Categorie</th>
                <th>Prix</th>
                <th>Image</th>
                <th>Date limite</th>
                <th>Nombre minimum</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($preventes as $prevente) : ?>
                <?php if ($prevente['statut'] === "non publié") : ?>
                <tr>
                    <td><?= htmlspecialchars($prevente ['id_prevente'])?></td>
                    <td><?= htmlspecialchars($prevente['nom']) ?></td>
                    <td><?= htmlspecialchars($prevente['nom_categorie']) ?></td>
                    <td><?= htmlspecialchars($prevente['prix_prevente']) ?> €</td>
                    <td><img src="<?= htmlspecialchars($prevente['image']) ?>" alt="Image" width="50"></td>
                    <td><?= htmlspecialchars($prevente['date_limite'])?></td>
                    <td><?= htmlspecialchars($prevente['nombre_min'])?></td>
                    <td><?= htmlspecialchars($prevente['statut'])?></td>
                    <td>
                        <button 
                            type="button" 
                            class="btn btn-warning btn-sm"
                            data-bs-toggle="modal"
                            data-bs-target="#updatePreventeModal"
                            data-id="<?= htmlspecialchars($prevente['id_prevente']) ?>"
                            data-prix_prevente="<?= htmlspecialchars($prevente['prix_prevente']) ?>"
                            data-date_limite="<?= htmlspecialchars($prevente['date_limite']) ?>"
                            data-nombre_min="<?= htmlspecialchars($prevente['nombre_min']) ?>">
                            <i class="bi bi-pencil"></i>
                        </button>

                        <button 
                            type="button" 
                            class="btn btn-primary btn-sm"
                            data-bs-toggle="modal"
                            data-bs-target="#publishedModal"
                            data-id="<?= htmlspecialchars($prevente['id_prevente']) ?>">
                            <i class="bi bi-cloud-upload"></i>
                        </button>

                        <button 
                            type="button" 
                            class="btn btn-danger btn-sm"
                            data-bs-toggle="modal"
                            data-bs-target="#deletePreventeModal"
                            data-id="<?= htmlspecialchars($prevente['id_prevente']) ?>">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                </tr>
                <?php endif; ?>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>
        
    <br><br>

    <!-- liste des preventes publiés-->
    <?php if ($preventes && $role == "vendeur") :?>
    <h3 class="mt-3">Liste des préventes en ligne :</h3>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Categorie</th>
                <th>Prix</th>
                <th>Image</th>
                <th>Date limite</th>
                <th>Nombre minimum</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($preventes as $prevente) : ?>
                <?php if ($prevente['statut'] === "publié") : ?>
                <tr>
                    <td><?= htmlspecialchars($prevente ['id_prevente'])?></td>
                    <td><?= htmlspecialchars($prevente['nom']) ?></td>
                    <td><?= htmlspecialchars($prevente['nom_categorie']) ?></td>
                    <td><?= htmlspecialchars($prevente['prix_prevente']) ?> €</td>
                    <td><img src="<?= htmlspecialchars($prevente['image']) ?>" alt="Image" width="50"></td>
                    <td><?= htmlspecialchars($prevente['date_limite'])?></td>
                    <td><?= htmlspecialchars($prevente['nombre_min'])?></td>
                    <td><?= htmlspecialchars($prevente['statut'])?></td>
                    <td>
                        <!-- seulement si il y a aucun participant -->
                        <button 
                            type="button" 
                            class="btn btn-danger btn-sm"
                            data-bs-toggle="modal"
                            data-bs-target="#"
                            data-id="<?= htmlspecialchars($prevente['id_prevente']) ?>">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                </tr>
                <?php endif; ?>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>

</body>

<?php require '../../Layout/footer.php'; ?>