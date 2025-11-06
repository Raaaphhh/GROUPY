<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require '../../Layout/header.php'; 

if (!isset($_SESSION['connectedUser']) || $_SESSION['connectedUser']['motdepasse_change'] === 0) {
    header('Location: /groupy/Views/User/formco.php'); 
    exit();
}

$lst_prev_client = get_prevente_client($_SESSION['connectedUser']['id_user']);

if (isset($_POST['gene_facture'])) {
    unset($_POST['gene_facture']);
    generate_facture_pdf($_POST);
    exit; 
}

if (isset($_POST['signaler_produit'])) {
    unset($_POST['signaler_produit']);
    if (signalement($_POST)) {
        header('Location: /groupy/Views/Client/factures.php');
        exit();
    } else {
        echo "signalement error";
    }
}

require 'modals/signal.php';

$title = "Factures - Client - Groupy"; 
?>

<body class="text-center">

<h1>Mes Factures</h1>

<div class="table-responsive">
        <table id="maTable3" class="table table-striped table-bordered align-middle">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Date</th>
                <th>Nom</th>
                <th>Categorie</th>
                <th>Prix</th>
                <th>Image</th>
                <th>Statut</th>
                <th>Télécharger</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($lst_prev_client as $prevente) : ?>
            <tr>
                <td><?= htmlspecialchars($prevente['id_prevente']) ?></td>
                <td><?= htmlspecialchars($prevente['date_limite']) ?></td>
                <td><?= htmlspecialchars($prevente['nom']) ?></td>
                <td><?= htmlspecialchars($prevente['nom_categorie']) ?></td>
                <td><?= htmlspecialchars($prevente['prix_prevente']) ?> €</td>
                <td><img src="<?= htmlspecialchars($prevente['image']) ?>" alt="Image" width="50"></td>
                <td><?= htmlspecialchars($prevente['statut']) ?></td>
                <td>
                    <form action="" method="POST">
                        <input type="hidden" name="id_prevente" value="<?= htmlspecialchars($prevente['id_prevente']) ?>">
                        <input type="hidden" name="date_limite" value="<?= htmlspecialchars($prevente['date_limite']) ?>">
                        <input type="hidden" name="nom" value="<?= htmlspecialchars($prevente['nom']) ?>">
                        <input type="hidden" name="nom_categorie" value="<?= htmlspecialchars($prevente['nom_categorie']) ?>">
                        <input type="hidden" name="prix_prevente" value="<?= htmlspecialchars($prevente['prix_prevente']) ?>">
                        <input type="hidden" name="image" value="<?= htmlspecialchars($prevente['image']) ?>">
                        <input type="hidden" name="statut" value="<?= htmlspecialchars($prevente['statut']) ?>">
                        <?php if ($prevente['statut'] !== 'Valide') : ?>
                            <button class="btn btn-secondary" type="button" disabled>
                                <i class="bi bi-file-earmark-arrow-down"></i>
                            </button>
                            <button class="btn btn-secondary" type="button" disabled>
                                <i class="bi bi-exclamation-circle"></i>
                            </button>
                        <?php elseif ($prevente['statut'] === 'Valide') : ?>
                            <button class="btn btn-primary" name="gene_facture" type="submit">
                                <i class="bi bi-file-earmark-arrow-down"></i>
                            </button>
                            <?php if (get_prod_signal($prevente['id_produit']) === false ) : ?>
                            <button 
                                type="button" 
                                class="btn btn-danger"
                                data-bs-toggle="modal"
                                data-bs-target="#signalProduitModal"
                                data-idProduit="<?= htmlspecialchars($prevente['id_produit']) ?>"
                                data-idUser="<?= htmlspecialchars($_SESSION['connectedUser']['id_user']) ?>">
                                <i class="bi bi-exclamation-circle"></i>
                            </button>
                            <?php else: ?>
                                <button class="btn btn-secondary" type="button" disabled>
                                    <i class="bi bi-exclamation-circle"></i>
                                </button>
                            <?php endif; ?>
                        <?php endif; ?>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        </table>
    </div>
</body>

<?php require '../../Layout/footer.php'; ?>

<script>
  $(document).ready(function () {
    $('#maTable3').DataTable({
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