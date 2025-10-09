<?php 
require '../../Layout/header.php'; 
require '../../Controlleur/ProductController.php';

$role = get_role($_SESSION['connectedUser']['id_user']);
if (!isset($_SESSION['connectedUser']) || $role !== "vendeur") {
    header('Location: /groupy/index.php'); 
    exit();
}
$idUser = $_SESSION['connectedUser']['id_user'];
$produits = get_produits($idUser);
$categories = get_categories();
$preventes = get_prevente();

if(isset($_POST['add_produit'])){
    array_pop($_POST);
    if(add_produit($_POST)){
        header('Location: /groupy/Views/Produit/dashboardProduit.php');
        exit();
    }else{
        echo "ajout prod erreur";
    }
}
if(isset($_POST['published'])){
    array_pop($_POST);
    if(published_produit($_POST)){
        header('Location: /groupy/Views/Produit/dashboardProduit.php');
        exit();
    }else{
        echo "publihed error";
    }
}

$title = "Action - Produit - Vendeur - Groupy"; 
?>

<body class="bg-light text-center">
    <h1 class="mb-4">Gestion Produits</h1>
    <button class="btn btn-success mb-3 me-3" data-bs-toggle="modal" data-bs-target="#addProduitModal">Ajouter un produit</button>
    <button class="btn btn-success mb-3 ms-3" data-bs-toggle="modal" data-bs-target="#pulbishedProduitModal">Publier un produit</button>

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
            <?php
                foreach ($produits as $produit) {
                    echo "<tr>";
                    echo "<form method='post' action='/groupy/Views/Produit/handleProduit.php'>";
                    echo "<td>" . htmlspecialchars($produit['id_produit']) . "</td>";
                    echo "<td>" . htmlspecialchars($produit['nom']) . "</td>";
                    echo "<td>" . htmlspecialchars($produit['categorie']) . "</td>";
                    echo "<td>" . htmlspecialchars($produit['description']) . "</td>";
                    echo "<td>" . htmlspecialchars($produit['prix']) . " €</td>";
                    echo "<td><img src='" . htmlspecialchars($produit['image']) . "' alt='Image' width='50'></td>";
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
    <?php else: ?>
        <p>Aucun produit ajouté pour le moment.</p>
    <?php endif; ?>

    <br><br>
    
    <!-- liste des preventes -->
    <?php if ($preventes && $role == "vendeur") :?>
    <h3 class="mt-3">Liste des préventes :</h3>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Date limite</th>
                <th>Nombre minimum</th>
                <th>statut</th>
                <th>Prix</th>
            </tr>
        </thead>
        <tbody>
            <?php
                foreach ($preventes as $prevente) {
                    echo "<tr>";
                    echo "<form method='post' action=''>";
                    echo "<td>" . htmlspecialchars($prevente['nom']) . "</td>";
                    echo "<td>" . htmlspecialchars($prevente['date_limite']) . "</td>";
                    echo "<td>" . htmlspecialchars($prevente['nombre_min']) . "</td>";
                    echo "<td>" . htmlspecialchars($prevente['statut']) . "</td>";
                    echo "<td>" . htmlspecialchars($prevente['prix_prevente']) . "€</td>";
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
    <?php elseif($role == "vendeur"): ?>
        <p>Aucun produit ajouté pour le moment.</p>
    <?php endif; ?>

</body>


<!-- modal ajout de produit -->
<div class="modal fade" id="addProduitModal" tabindex="-1" aria-labelledby="addProduitModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 shadow-lg">
        <div class="modal-header bg-success text-dark">
            <h5 class="modal-title" id="addProduitModalLabel">Ajouter un produit</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
        </div>
        <form action="#" method="post">
            <div class="modal-body text-start">
                <div class="mb-3">
                    <label class="form-label">Catégorie</label>
                    <select name="categorie" class="form-select">
                        <?php foreach ($categories as $categorie): ?>
                            <option value="<?= htmlspecialchars($categorie['id_categorie']) ?>">
                                <?= htmlspecialchars($categorie['lib']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Nom</label>
                    <input type="text" name="nom" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control"></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Prix</label>
                    <input type="number" name="prix" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label">Image</label>
                    <input type="file" name="pic" class="form-control">
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" name="add_produit" class="btn btn-success">Ajouter</button>
                </div>
            </div>
        </form>
        </div>
    </div>
</div>

<!-- modal publication de produit -->
<div class="modal fade" id="pulbishedProduitModal" tabindex="-1" aria-labelledby="pulbishedProduitModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 shadow-lg">
        <div class="modal-header bg-success text-dark">
            <h5 class="modal-title" id="addProduitModalLabel">Publier un produit</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
        </div>
        <form action="#" method="post">
            <div class="modal-body text-start">
                <div class="mb-3">
                    <label class="form-label">Produit</label>
                    <select name="id_produit" class="form-select">
                            <option value="" disabled selected>-- Sélectionnez un produit --</option>
                        <?php foreach ($produits as $produit): ?>
                            <option value="<?= htmlspecialchars($produit['id_produit']) ?>">
                                <?= htmlspecialchars($produit['nom']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Date Limite</label>
                    <input type="date" name="date_limite" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Nombre Minimum</label>
                    <input type="number" name="nombre_minimum" class="form-control" min="1">
                </div>

                <div class="mb-3">
                    <label class="form-label">Statut</label>
                    <select name="statut" class="form-select">
                        <option value="" disabled selected>-- Sélectionnez un statut --</option>
                        <option value="publie">En vente</option>
                        <option value="archive">Vendu</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Prix Prévente</label>
                    <input type="number" name="prix_prevente" class="form-control" min="0">
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" name="published" class="btn btn-success">Publier</button>
                </div>
            </div>
        </form>
        </div>
    </div>
</div>

<?php require '../../Layout/footer.php'; ?>