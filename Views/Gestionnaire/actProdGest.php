<?php
require '../../Layout/header.php';
require '../../Controlleur/ProductController.php';

$role = get_role($_SESSION['connectedUser']['id_user']);
if (!isset($_SESSION['connectedUser']) || $role !== "gestionnaire") {
    header('Location: ' . BASE_URL . '/index.php');
    exit();
}

if(isset($_POST['add_categorie'])){
    csrf_verify();
    array_pop($_POST);
    if(add_categorie($_POST)){
        header('Location: ' . BASE_URL . '/Views/Gestionnaire/actProdGest.php');
        exit();
    }else{
        echo "ajout categorie erreur";
    }
}
if(isset($_POST['supprimer'])){
    csrf_verify();
    unset($_POST['supprimer']);
    if(del_categorie($_POST)){
        header('Location: ' . BASE_URL . '/Views/Gestionnaire/actProdGest.php');
        exit();
    }else{
        echo "ajout categorie erreur";
    }
}
if(isset($_POST['modifier'])){
    csrf_verify();
    array_pop($_POST);
    if(update_categorie($_POST)){
        header('Location: ' . BASE_URL . '/Views/Gestionnaire/actProdGest.php');
        exit();
    }else{
        echo "modification categorie erreur";
    }
}

$idUser = $_SESSION['connectedUser']['id_user'];
$categories = get_categories();
$utilisateurs = get_all_users(); //a coder

$title = "Action - Gestionnaire - Groupy"; 
?>

<body class="bg-light text-center">

    <h1 class="mb-4">Gestion Produits</h1>
    
    <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addCategorietModal">
        <i class="bi bi-plus-square"></i> Catégorie
    </button>
    
    <br><br>

    <!-- liste des catégories -->
    <?php if ($categories && $role == "gestionnaire") : ?>
    <h3>Liste des catégories :</h3>
    <table class="table table-bordered table-striped w-50 mx-auto">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($categories as $categorie) : ?>
                <tr>
                    <td><?= htmlspecialchars($categorie['lib'])?></td>
                    <td>
                        <button 
                            type="button" 
                            class="btn btn-warning btn-sm"
                            data-bs-toggle="modal"
                            data-bs-target="#updateProdModal"
                            data-id="<?= htmlspecialchars($categorie['id_categorie']) ?>"
                            data-nom="<?= htmlspecialchars($categorie['lib']) ?>">
                            <i class="bi bi-pencil"></i>
                        </button>

                        <button 
                            type="button" 
                            class="btn btn-danger btn-sm"
                            data-bs-toggle="modal"
                            data-bs-target="#deleteModal"
                            data-id="<?= htmlspecialchars($categorie['id_categorie']) ?>">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php elseif ($role == "gestionnaire"): ?>
        <p>Aucune produit publié pour le moment.</p>
    <?php endif; ?>

    <br><br>

    <!-- liste des utilisateurs -->
    <?php if ($utilisateurs && $role == "gestionnaire") : ?>
    <h3 class="">Liste des Utilisateurs :</h3>
    <table class="table table-bordered table-striped w-50 mx-auto">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Prenom</th>
                <th>adresse</th>
                <th>Telephone</th>
                <th>Email</th>
                <th>Participation</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($utilisateurs as $utilisateur) : ?>
                <tr>
                    <td><?= htmlspecialchars($utilisateur['nom'])?></td>
                    <td><?= htmlspecialchars($utilisateur['prenom'])?></td>
                    <td><?= htmlspecialchars($utilisateur['adresse'])?></td>
                    <td><?= htmlspecialchars($utilisateur['phone'])?></td>
                    <td><?= htmlspecialchars($utilisateur['email'])?></td>
                    <td><?= htmlspecialchars($utilisateur['nombre_participations'])?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php elseif ($role == "gestionnaire"): ?>
        <p>Aucun client pour le moment.</p>
    <?php endif; ?>
    
</body>


<!-- modal validation supression CATEGORIE -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteModalLabel">Confirmer la suppression</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
      </div>
      <div class="modal-body">
        Êtes-vous sûr de vouloir supprimer cette categorie ?
      </div>
      <div class="modal-footer">
        <form method="post" action="#">
            <?= csrf_field() ?>
            <input type="hidden" name="id_categorie" id="idCategorieASupprimer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
            <button type="submit" name="supprimer" class="btn btn-danger">Supprimer</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- modal ajout de CATEGORIE -->
<div class="modal fade" id="addCategorietModal" tabindex="-1" aria-labelledby="addCategorietModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 shadow-lg">
        <div class="modal-header bg-success text-dark">
            <h5 class="modal-title" id="addCategorietModalLabel">Ajouter une catégorie</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
        </div>
        <form action="#" method="post">
            <?= csrf_field() ?>
            <div class="modal-body text-start">
                <div class="mb-3">
                    <label class="form-label">Nom</label>
                    <input type="text" name="nom" class="form-control">
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" name="add_categorie" class="btn btn-success">Ajouter</button>
                </div>
            </div>
        </form>
        </div>
    </div>
</div>

<!-- modal modification CATEGORIE -->
<div class="modal fade" id="updateProdModal" tabindex="-1" aria-labelledby="updateProdModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="updateProdModalLabel">Modifier une catégorie</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
      </div>
      <form method="post" action="#">
        <?= csrf_field() ?>
        <div class="modal-body text-start">
            <input type="hidden" name="id_categorie" id="idCategorieAModifier">
            <div class="mb-3">
                <label class="form-label">Nom</label>
                <input type="text" name="nom" id="nomCategorie" class="form-control">
            </div>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
            <button type="submit" name="modifier" class="btn btn-warning">Modifier</button>
        </div>
      </form>
    </div>
  </div>
</div>
        

<?php require '../../Layout/footer.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // suppression
    const deleteModal = document.getElementById('deleteModal');
    deleteModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget; 
        const idCategorie = button.getAttribute('data-id'); 
        const inputHidden = deleteModal.querySelector('#idCategorieASupprimer');
        inputHidden.value = idCategorie;
    });

    // modification
    const updateModal = document.getElementById('updateProdModal');
    updateModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget; 
        const idCategorie = button.getAttribute('data-id'); 
        const nomCategorie = button.getAttribute('data-nom'); 
        updateModal.querySelector('#idCategorieAModifier').value = idCategorie;
        updateModal.querySelector('#nomCategorie').value = nomCategorie;
    });
});
</script>
