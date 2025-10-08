<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require '../../Layout/headerAdmin.php';
require '../Controlleur/AdminController.php';

$lst_gestionnaire = get_gestionnaires();
var_dump($lst_gestionnaire); // a supprimer 
?>

<body>
    <div class="container mt-4">
        <table class="table table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($lst_gestionnaire as $gestionnaire): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($gestionnaire['id_user']); ?></td>
                        <td><?php echo htmlspecialchars($gestionnaire['nom']); ?></td>
                        <td><?php echo htmlspecialchars($gestionnaire['email']); ?></td>
                        <td><?php if($gestionnaire['est_actif'] == 1) { echo "Activé"; } else { echo "Inactivé"; } ?></td>
                        <td>
                            <!-- a developer -->
                            <a href="#" class="btn btn-primary btn-sm">Modifier</a>
                            <a href="#" class="btn btn-danger btn-sm">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        <a href="/groupy/Admin/ViewsAdmin/dashboardAdmin.php" class="btn btn-success">Retour</a>
    </div>
</body>

<?php require '../../Layout/footer.php';?>
