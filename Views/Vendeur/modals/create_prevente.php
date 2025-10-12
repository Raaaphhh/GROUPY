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
                    <label class="form-label">Prix Prévente</label>
                    <input type="number" name="prix_prevente" class="form-control" min="0">
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" name="create_prevente" class="btn btn-success">Publier</button>
                </div>
            </div>
        </form>
        </div>
    </div>
</div>