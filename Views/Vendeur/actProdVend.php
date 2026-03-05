<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
require '../../Layout/header.php';
require '../../Controlleur/ProductController.php';

$successMessage = '';
$errorMessage = '';
$role = get_role($_SESSION['connectedUser']['id_user']);
if (!isset($_SESSION['connectedUser']) || $role !== "vendeur") {
    header('Location: ' . BASE_URL . '/index.php'); 
    exit();
}

if(isset($_POST['add_produit_produit'])){
    $file = $_FILES["pic"];
    $file_path = uploadPic($file);
    if(!$file_path){
        echo "Ko picture";
    }else{
        unset($_POST['add_produit_produit']);
        $_POST['pic'] = $file_path;
        if(add_produit($_POST)){
            header('Location: ' . BASE_URL . '/Views/Vendeur/actProdvend.php');
            exit();
        }else{
            echo "ajout prod erreur";
        }
    }
}
if(isset($_POST['supprimer_produit'])){
    unset($_POST['supprimer']); 
    if(del_produit($_POST)){
        header('Location: ' . BASE_URL . '/Views/Vendeur/actProdvend.php');
        exit();
    }else{
        echo "del prod error";
    }
}
if(isset($_POST['modifier_produit'])){
    var_dump($_POST);
    unset($_POST['modifier_produit']); 
    if(update_produit($_POST)){
        header('Location: ' . BASE_URL . '/Views/Vendeur/actProdvend.php');
        exit();
    }else{
        echo "del prod error";
    }
}
if(isset($_POST['create_prevente'])){
    array_pop($_POST);
    if(create_prevente($_POST)){
        $_SESSION['successMessage'] = "Prevente créée avec succès.";
        header('Location: ' . BASE_URL . '/Views/Vendeur/actProdvend.php');
        exit();
    }else{
        echo "publihed error";
    }
}

// message de succes
if (isset($_SESSION['successMessage'])) {
    $successMessage = $_SESSION['successMessage'];
    unset($_SESSION['successMessage']);
}

$idUser = $_SESSION['connectedUser']['id_user'];
$lst_produit = get_produits($idUser);
$categories = get_categories();
$preventes = get_prevente(); 

require 'modals/add_produit.php';
require 'modals/del_produit.php';
require 'modals/update_produit.php';
require 'modals/create_prevente.php';

$title = "Action - Produit - Vendeur - Groupy"; 
?>

<div class="container mt-5">

    <?php if ($successMessage): ?>
    <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
        <?= htmlspecialchars($successMessage) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php endif; ?>

    <h2 class="mb-4">Liste de vos Poduits</h2>
    <button class="btn btn-success mb-3 me-3" data-bs-toggle="modal" data-bs-target="#addProduitModal">
        <i class="bi bi-plus-square"></i> Produit
    </button>

    <div class="table-responsive">
        <table id="maTable" class="table table-striped table-bordered align-middle">
        <thead class="table-dark">
            <tr>
            <th>Id</th>
            <th>Nom</th>
            <th>Catégorie</th>
            <th>Description</th>
            <th>Prix</th>
            <th>Image</th>
            <th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($lst_produit as $produit): ?>
            <tr>
                <td><?= $produit['id_produit'] ?></td>
                <td><?= htmlspecialchars($produit['nom']) ?></td>
                <td><?= htmlspecialchars($produit['categorie']) ?></td>
                <td><?= htmlspecialchars($produit['description']) ?></td>
                <td><?= htmlspecialchars($produit['prix']) ?></td>
                <td>
                <img src="<?= htmlspecialchars($produit['image']) ?>" alt="Image du produit" style="width: 50px; height: 50px; object-fit: cover;">
                </td>
                <td>
                <?php verif_produit_prevente($produit['id_produit']); ?>
                <button
                    <?php if (verif_produit_prevente($produit['id_produit']) === true): ?>
                    disabled
                    <?php endif; ?>
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
                    <?php if (verif_produit_prevente($produit['id_produit']) === true): ?>
                    disabled
                    <?php endif; ?>
                    type="button" 
                    class="btn btn-danger btn-sm"
                    data-bs-toggle="modal"
                    data-bs-target="#deleteModal"
                    data-id="<?= htmlspecialchars($produit['id_produit']) ?>">
                    <i class="bi bi-trash"></i>
                </button>

                <button
                    <?php if (verif_produit_prevente($produit['id_produit']) === true): ?>
                    disabled
                    <?php endif; ?>
                    type="button" 
                    class="btn btn-success btn-sm"
                    data-bs-toggle="modal"
                    data-bs-target="#pulbishedProduitModal"
                    data-id="<?= htmlspecialchars($produit['id_produit']) ?>"
                    data-nom="<?= htmlspecialchars($produit['nom']) ?>">
                    <i class="bi bi-globe2"></i>
                </button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        </table>
    </div>


    <a href="<?= BASE_URL ?>Views/User/dashboard.php" class="btn btn-primary">Menu</a>
    <a href="<?= BASE_URL ?>Views/Vendeur/actPrevVend.php" class="btn btn-success">Gestion prevente</a>
</div>

<?php require '../../Layout/footer.php'; ?>

<script>
  $(document).ready(function () {
    $('#maTable').DataTable({
      pageLength: 5,
      language: {
        search: "Rechercher :",
        lengthMenu: "Afficher _MENU_ entrées",
        info: "Affichage de _START_ à _END_ sur _TOTAL_ entrées",
        paginate: { previous: "Précédent", next: "Suivant" },
        zeroRecords: "Aucune correspondance trouvée"
      }
    });
  });
</script>

