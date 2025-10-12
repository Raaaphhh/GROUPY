<!-- modal ajout PRODUIT -->
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
                    <input type="text" name="nom" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" required></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Prix</label>
                    <input type="number" name="prix" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Image</label>
                    <input type="file" name="pic" class="form-control">
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" name="add_produit_produit" class="btn btn-success">Ajouter</button>
                </div>
            </div>
        </form>
        </div>
    </div>
</div>