<?php 
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
require '../../Layout/header.php'; 
require '../../Controlleur/ProductController.php';

$role = get_role($_SESSION['connectedUser']['id_user']);
if (!isset($_SESSION['connectedUser']) || $role !== "vendeur") {
    header('Location: ' . BASE_URL . '/index.php'); 
    exit();
}

if(isset($_POST['published_prevente'])){
    csrf_verify();
    unset($_POST['published_prevente']);
    if(published_prevente($_POST)){
        header('Location: ' . BASE_URL . '/Views/Vendeur/actPrevVend.php');
        exit();
    }else{
        echo "publihed error";
    }
}
if(isset($_POST['update_prevente'])){
    csrf_verify();
    unset($_POST['update_prevente']);
    if(update_prevente($_POST)){
        header('Location: ' . BASE_URL . '/Views/Vendeur/actPrevVend.php');
        exit();
    }else{
        echo "del prevene error";
    }
}
if(isset($_POST['supprimer_prevente'])){
    csrf_verify();
    unset($_POST['supprimer_prevente']);
    if(del_prevente($_POST)){
        header('Location: ' . BASE_URL . '/Views/Vendeur/actPrevVend.php');
        exit();
    }else{
        echo "del prevene error";
    }
}

$idUser = $_SESSION['connectedUser']['id_user'];
$produits = get_produits($idUser);
$categories = get_categories();
$preventes = get_prevente();

require 'modals/create_prevente.php';
require 'modals/published_prevente.php'; 
require 'modals/update_prevente.php';
require 'modals/del_prevente.php';

$title = "Action - Produit - Vendeur - Groupy"; 
?>

<body class="">
    <h2 class="mb-4">Liste de vos Preventes</h2>
    <br><br>
    <div class="table-responsive">
        <table id="maTable2" class="table table-striped table-bordered align-middle">
        <thead class="table-dark">
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
            <tr>
                <td><?= htmlspecialchars($prevente ['id_prevente'])?></td>
                <td><?= htmlspecialchars($prevente['nom']) ?></td>
                <td><?= htmlspecialchars($prevente['nom_categorie']) ?></td>
                <td><?= htmlspecialchars($prevente['prix_prevente']) ?> €</td>
                <td><img src="<?= htmlspecialchars($prevente['image']) ?>" alt="Image" width="50"></td>
                <td><?= htmlspecialchars($prevente['date_limite'])?></td>
                <td><?= htmlspecialchars($prevente['nombre_min'])?></td>
                <td style="background-color: 
                    <?php 
                        if ($prevente['statut'] === 'Annule') {
                            echo '#ffcccc';
                        } elseif ($prevente['statut'] === 'Valide') {
                            echo '#ccffcc';
                        } elseif ($prevente['statut'] === 'Active') {
                            echo '#cce5ff';
                        } else {
                            echo '#e0e0e0';
                        }
                    ?>;">
                    <?= htmlspecialchars($prevente['statut']) ?>
                </td>
                <td>
                <button 
                    <?php if ($prevente['statut'] !== 'non publié'): ?> 
                    disabled
                    data-bs-toggle="tooltip"
                    data-bs-placement="top"
                    title = "Déjà publié"
                    <?php endif; ?>
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
                    <?php if ($prevente['statut'] !== 'non publié'): ?> 
                    disabled
                    data-bs-toggle="tooltip"
                    data-bs-placement="top"
                    title = "Déjà publié"
                    <?php endif; ?> 
                    type="button" 
                    class="btn btn-danger btn-sm"
                    data-bs-toggle="modal"
                    data-bs-target="#deletePreventeModal"
                    data-id="<?= htmlspecialchars($prevente['id_prevente']) ?>">
                    <i class="bi bi-trash"></i>
                </button>

                <button
                    <?php if ($prevente['statut'] !== 'non publié'): ?> 
                    disabled
                    data-bs-toggle="tooltip"
                    data-bs-placement="top"
                    title = "Déjà publié"
                    <?php endif; ?>
                    type="button" 
                    class="btn btn-primary btn-sm"
                    data-bs-toggle="modal"
                    data-bs-target="#publishedModal"
                    data-id="<?= htmlspecialchars($prevente['id_prevente']) ?>">
                    <i class="bi bi-cloud-upload"></i>
                </button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        </table>
    </div>
    <a href="<?= BASE_URL ?>/Views/User/dashboard.php" class="btn btn-primary">Menu</a>
    <a href="<?= BASE_URL ?>/Views/Vendeur/actProdVend.php" class="btn btn-success">Gestion prorduits</a>
</body>

<?php require '../../Layout/footer.php'; ?>

<script>
  $(document).ready(function () {
    $('#maTable2').DataTable({
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


