<div class="modal fade" id="updatePreventeModal" tabindex="-1" aria-labelledby="updatePreventeModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="updatePreventeModalLabel">Modifier un produit</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
      </div>
      <form method="post" action="#">
        <?= csrf_field() ?>
        <div class="modal-body text-start">
            <input type="hidden" name="id_prevente" id="update_id_prevente">

            <div class="mb-3">
                <label class="form-label">Prix</label>
                <input type="text" name="prix_prevente" id="update_prix_prevente" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">Date limite</label>
                <input type="date" name="date_limite" id="update_date_limite" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">Nombre minimum</label>
                <input type="number" name="nombre_min" id="update_nombre_min" class="form-control">
            </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
          <button type="submit" name="update_prevente" class="btn btn-warning">Modifier</button>
        </div>
      </form>
    </div>
  </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function () {
    const updatePreventeModal = document.getElementById('updatePreventeModal');
    updatePreventeModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;

        updatePreventeModal.querySelector('#update_id_prevente').value = button.getAttribute('data-id');
        updatePreventeModal.querySelector('#update_prix_prevente').value = button.getAttribute('data-prix_prevente');
        updatePreventeModal.querySelector('#update_date_limite').value = button.getAttribute('data-date_limite');
        updatePreventeModal.querySelector('#update_nombre_min').value = button.getAttribute('data-nombre_min');
    });
});
</script>
