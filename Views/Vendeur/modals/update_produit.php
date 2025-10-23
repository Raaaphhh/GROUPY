<div class="modal fade" id="updateProdModal" tabindex="-1" aria-labelledby="updateProdModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="updateProdModalLabel">Modifier un produit</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
      </div>
      <form method="post" action="#">
        <div class="modal-body text-start">
            <input type="hidden" name="id_produit" id="update_id_produit">

            <div class="mb-3">
                <label class="form-label">Nom</label>
                <input type="text" name="nom" id="update_nom" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" id="update_description" class="form-control"></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Prix</label>
                <input type="number" name="prix" id="update_prix" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">Catégorie</label>
                <select name="id_categorie" id="update_categorie" class="form-select">
                    <?php foreach ($categories as $categorie): ?>
                        <option value="<?= htmlspecialchars($categorie['id_categorie']) ?>">
                            <?= htmlspecialchars($categorie['lib']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
          <button type="submit" name="modifier_produit" class="btn btn-warning">Modifier</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const updateProdModal = document.getElementById('updateProdModal');
    updateProdModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        updateProdModal.querySelector('#update_id_produit').value = button.getAttribute('data-id');
        updateProdModal.querySelector('#update_nom').value = button.getAttribute('data-nom');
        updateProdModal.querySelector('#update_description').value = button.getAttribute('data-description');
        updateProdModal.querySelector('#update_prix').value = button.getAttribute('data-prix');

        const categorieSelect = updateProdModal.querySelector('#update_categorie');
        const catValue = button.getAttribute('data-categorie');
        for (let option of categorieSelect.options) {
            option.selected = (option.textContent.trim() === catValue);
        }
    });
});
</script>