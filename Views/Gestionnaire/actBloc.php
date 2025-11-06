<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require '../../Layout/header.php';

$lst_all_vendeur = get_all_vendeur();
// var_dump($lst_all_vendeur);

$title = "Action - Blocage - Groupy"; 
?>

<body>
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
            <th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($lst_all_vendeur as $vendeur): ?>
            <tr>
                <td><?= $vendeur['id_user'] ?></td>
                <td><?= htmlspecialchars($vendeur['nom']) ?></td>
                <td><?= htmlspecialchars($vendeur['nom_entreprise']) ?></td>
                <td><?= htmlspecialchars($vendeur['siret']) ?></td>
                <td><?= htmlspecialchars($vendeur['adresse_entreprise']) ?></td>
                <td><?= htmlspecialchars($vendeur['email_pro']) ?></td>
                <td>
                    <?php if (verif_vendeur_blocked($vendeur['id_user']) === false) : ?>
                        <button
                            type="button" 
                            class="btn btn-danger btn-sm"
                            data-bs-toggle="modal"
                            data-bs-target="#"
                            data-id="<?= htmlspecialchars($vendeur['id_user']) ?>">
                            Bloquer
                        </button>
                        <button type="button" class="btn btn-success btn-sm" disabled>
                            Debloquer
                        </button>
                    <?php elseif (verif_vendeur_blocked($vendeur['id_user']) === true) :  ?>
                        <button type="button" class="btn btn-danger btn-sm"disabled>
                            Bloquer
                        </button>
                        <button
                            type="button" 
                            class="btn btn-success btn-sm"
                            data-bs-toggle="modal"
                            data-bs-target="#"
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
</body>

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