<?php
require '../../Layout/header.php';

$lst_all_vendeur = get_all_vendeur();

if (isset($_POST['bloquer'])) {
    csrf_verify();
    unset($_POST['bloquer']);
    if (block_vendeur($_POST['id_vendeur'])) {
        header('Location: ' . BASE_URL . '/Views/Gestionnaire/actBloc.php');
        exit();
    } else {
        echo "block error"; 
    }
}

if(isset($_POST['debloquer'])){
    csrf_verify();
    unset($_POST['debloquer']);
    if(debloquer($_POST['id_vendeur_a_debloquer'])){
        header('Location: ' . BASE_URL . '/Views/Gestionnaire/actBloc.php'); 
        exit(); 
    } else{
        echo "deblock error"; 
    }
}

require 'modals/block.php';
require 'modals/deblock.php';

$title = "Action - Blocage - Groupy"; 
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-0"><i class="bi bi-shield-exclamation text-danger"></i> Gestion des vendeurs</h2>
        <p class="text-muted small mb-0">Surveillez les signalements et gérez les accès vendeurs.</p>
    </div>
    <a href="<?= BASE_URL ?>/Views/User/dashboard.php" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left"></i> Retour
    </a>
</div>

<div class="table-responsive">
        <table id="bloc" class="table table-striped table-bordered align-middle">
        <thead class="table-dark">
            <tr>
            <th>Id</th>
            <th>Nom</th>
            <th>Entreprise</th>
            <th>Siret</th>
            <th>Adresse entreprise</th>
            <th>Email Pro</th>
            <th>Alerte signalements</th>
            <th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($lst_all_vendeur as $vendeur): ?>
                <?php $alerte = vendeur_a_alerte($vendeur['id_user']); ?>
                    <tr>
                        <td><?= $vendeur['id_user'] ?></td>
                        <td><?= htmlspecialchars($vendeur['nom']) ?></td>
                        <td><?= htmlspecialchars($vendeur['nom_entreprise']) ?></td>
                        <td><?= htmlspecialchars($vendeur['siret']) ?></td>
                        <td><?= htmlspecialchars($vendeur['adresse_entreprise']) ?></td>
                        <td><?= htmlspecialchars($vendeur['email_pro']) ?></td>
                        <td>
                            <?php if ($alerte): ?>
                                <span class="badge bg-danger">Produit(s) > 50 %</span>
                            <?php else: ?>
                                <span class="badge bg-success">RAS</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($vendeur['statut'] === "actif") : ?>
                                <button
                                    type="button"
                                    class="btn btn-danger btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#blockVendeurModal"
                                    data-id="<?= htmlspecialchars($vendeur['id_user']) ?>">
                                    Bloquer
                                </button>
                                <button type="button" class="btn btn-success btn-sm" disabled>
                                    Debloquer
                                </button>
                            <?php elseif ($vendeur['statut'] === "bloque") :  ?>
                                <button type="button" class="btn btn-danger btn-sm" disabled>
                                    Bloquer
                                </button>
                                <button
                                    type="button" 
                                    class="btn btn-success btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#deblockVendeurModal"
                                    data-id="<?= htmlspecialchars($vendeur['id_user']) ?>">
                                    Debloquer
                                </button>
                            <?php endif; ?>
                        </td>
                    </tr>
            <?php endforeach; ?>
        </tbody>
        </table>
    </div>

<div class="mt-3">
    <a href="<?= BASE_URL ?>/Views/User/dashboard.php" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left"></i> Retour au dashboard
    </a>
</div>

<?php require '../../Layout/footer.php'; ?>

<script>
  $(document).ready(function () {
    $('#bloc').DataTable({
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