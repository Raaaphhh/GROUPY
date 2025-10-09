<?php 
require '../../Layout/header.php'; 
require '../../Controlleur/ProductController.php';

$role = get_role($_SESSION['connectedUser']['id_user']);
if (!isset($_SESSION['connectedUser']) || $role !== "gestionnaire") {
    header('Location: /groupy/index.php'); 
    exit();
}
$idUser = $_SESSION['connectedUser']['id_user'];
$categories = get_categories();

// TODO: 
// faire une fonction de verification si la catégorie est utilisé par un produit

// faire une fonction pour verifier si la catégorie est utilisé par une prevente :
        // dans ce cas on grise la modification et supression du produit 

if(isset($_POST['add_categorie'])){
    array_pop($_POST);
    if(add_categorie($_POST)){
        header('Location: /groupy/Views/Produit/dashboardProduit.php');
        exit();
    }else{
        echo "ajout categorie erreur";
    }
}

$title = "Action - Produit - Gestionnaire - Groupy"; 
?>

<body class="bg-light text-center">

<h1 class="mb-4">Gestion Produits</h1>

<!-- liste des catégories -->
<?php if ($categories && $role == "gestionnaire") : ?>
<h3>Liste des catégories :</h3>
<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Nom</th>
        </tr>
    </thead>
    <tbody>
        <?php
            foreach ($categories as $categorie) {
                echo "<tr>";
                echo "<form method='post' action='/groupy/Views/Produit/handleProduit.php'>";
                echo "<td>" . htmlspecialchars($categorie['lib']) . "</td>";
                echo "<td>
                        <button type='submit' class='btn btn-warning btn-sm' name='submit_edit'>Modifier</button>
                        <button type='submit' class='btn btn-danger btn-sm' name='submit_del'>Supprimer</button>
                        </td>";
                echo "</form>";
                echo "</tr>";
            }
        ?>
    </tbody>
</table>
<?php elseif ($role == "gestionnaire"): ?>
    <p>Aucune produit publié pour le moment.</p>
<?php endif; ?>
    
</body>

<!-- modal ajout de categorie -->
<div class="modal fade" id="addCategorietModal" tabindex="-1" aria-labelledby="addCategorietModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 shadow-lg">
        <div class="modal-header bg-success text-dark">
            <h5 class="modal-title" id="addCategorietModalLabel">Ajouter une catégorie</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
        </div>
        <form action="#" method="post">
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

<?php require '../../Layout/footer.php'; ?>