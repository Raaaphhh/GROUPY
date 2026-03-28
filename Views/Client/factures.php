<?php
require '../../Layout/header.php';

if (!isset($_SESSION['connectedUser']) || $_SESSION['connectedUser']['motdepasse_change'] === 0) {
    header('Location: ' . BASE_URL . '/Views/User/formco.php');
    exit();
}

$lst_prev_client = get_prevente_client($_SESSION['connectedUser']['id_user']);

if (isset($_POST['gene_facture'])) {
    csrf_verify();
    unset($_POST['gene_facture']);
    generate_facture_pdf($_POST);
    exit;
}

if (isset($_POST['signaler_produit'])) {
    csrf_verify();
    unset($_POST['signaler_produit']);
    if (signalement($_POST)) {
        header('Location: ' . BASE_URL . '/Views/Client/factures.php');
        exit();
    } else {
        echo "signalement error";
    }
}

require 'modals/signal.php';

$title = "Mes participations - Groupy";
?>

<body class="text-center">

<h1>Mes participations</h1>

<?php if (empty($lst_prev_client)): ?>
    <div class="container mt-5">
        <div class="alert alert-info d-inline-block px-5 py-4">
            <i class="bi bi-cart-x fs-3 d-block mb-2"></i>
            Vous ne participez à aucune prévente pour le moment.
            <div class="mt-3">
                <a href="<?= BASE_URL ?>/index.php" class="btn btn-primary">Découvrir les préventes</a>
            </div>
        </div>
    </div>
<?php else: ?>

<div class="table-responsive">
    <table id="maTable3" class="table table-striped table-bordered align-middle">
        <thead class="table-dark">
            <tr>
                <th>Produit</th>
                <th>Catégorie</th>
                <th>Prix prévente</th>
                <th>Image</th>
                <th>Date limite</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($lst_prev_client as $prevente) : ?>
            <tr>
                <td><?= htmlspecialchars($prevente['nom']) ?></td>
                <td><?= htmlspecialchars($prevente['nom_categorie']) ?></td>
                <td><?= htmlspecialchars($prevente['prix_prevente']) ?> €</td>
                <td><img src="<?= htmlspecialchars($prevente['image']) ?>" alt="Image" width="50" class="rounded"></td>
                <td><?= htmlspecialchars($prevente['date_limite']) ?></td>
                <td>
                    <?php
                        $statut = $prevente['statut'];
                        $badgeClass = 'bg-secondary';
                        $badgeTooltip = '';
                        if ($statut === 'Valide') {
                            $badgeClass   = 'bg-success';
                            $badgeTooltip = 'Objectif atteint — votre facture est disponible';
                        } elseif ($statut === 'Active') {
                            $badgeClass   = 'bg-primary';
                            $badgeTooltip = 'Prévente en cours — en attente du minimum de participants';
                        } elseif ($statut === 'Fermée') {
                            $badgeClass   = 'bg-warning text-dark';
                            $badgeTooltip = 'Date limite dépassée sans atteindre le minimum';
                        } elseif ($statut === 'Annule') {
                            $badgeClass   = 'bg-danger';
                            $badgeTooltip = 'Prévente annulée';
                        } else {
                            $badgeTooltip = 'Statut inconnu';
                        }
                    ?>
                    <span class="badge <?= $badgeClass ?>"
                          data-bs-toggle="tooltip"
                          data-bs-placement="top"
                          title="<?= htmlspecialchars($badgeTooltip) ?>">
                        <?= htmlspecialchars($statut) ?>
                    </span>
                </td>
                <td>
                    <form action="" method="POST">
                        <?= csrf_field() ?>
                        <input type="hidden" name="id_prevente"   value="<?= htmlspecialchars($prevente['id_prevente']) ?>">
                        <input type="hidden" name="date_limite"   value="<?= htmlspecialchars($prevente['date_limite']) ?>">
                        <input type="hidden" name="nom"           value="<?= htmlspecialchars($prevente['nom']) ?>">
                        <input type="hidden" name="nom_categorie" value="<?= htmlspecialchars($prevente['nom_categorie']) ?>">
                        <input type="hidden" name="prix_prevente" value="<?= htmlspecialchars($prevente['prix_prevente']) ?>">
                        <input type="hidden" name="image"         value="<?= htmlspecialchars($prevente['image']) ?>">
                        <input type="hidden" name="statut"        value="<?= htmlspecialchars($prevente['statut']) ?>">

                        <?php if ($prevente['statut'] !== 'Valide') : ?>
                            <button class="btn btn-secondary btn-sm" type="button" disabled
                                data-bs-toggle="tooltip"
                                data-bs-placement="top"
                                title="Disponible uniquement quand la prévente est confirmée">
                                <i class="bi bi-file-earmark-arrow-down"></i>
                            </button>
                            <button class="btn btn-secondary btn-sm" type="button" disabled
                                data-bs-toggle="tooltip"
                                data-bs-placement="top"
                                title="Disponible uniquement quand la prévente est confirmée">
                                <i class="bi bi-exclamation-circle"></i>
                            </button>
                        <?php else : ?>
                            <button class="btn btn-primary btn-sm" name="gene_facture" type="submit"
                                data-bs-toggle="tooltip"
                                data-bs-placement="top"
                                title="Télécharger la facture PDF">
                                <i class="bi bi-file-earmark-arrow-down"></i>
                            </button>
                            <?php if (get_prod_signal($prevente['id_produit']) === false) : ?>
                                <button
                                    type="button"
                                    class="btn btn-danger btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#signalProduitModal"
                                    data-idProduit="<?= htmlspecialchars($prevente['id_produit']) ?>"
                                    data-idUser="<?= htmlspecialchars($_SESSION['connectedUser']['id_user']) ?>"
                                    title="Signaler ce produit">
                                    <i class="bi bi-exclamation-circle"></i>
                                </button>
                            <?php else: ?>
                                <span class="badge bg-danger">
                                    <i class="bi bi-check-circle"></i> Signalé
                                </span>
                            <?php endif; ?>
                        <?php endif; ?>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php endif; ?>

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

    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    tooltipTriggerList.forEach(function (el) {
        new bootstrap.Tooltip(el);
    });
  });
</script>
