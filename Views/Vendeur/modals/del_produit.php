<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteModalLabel">Confirmer la suppression</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
      </div>
      <div class="modal-body">
        Êtes-vous sûr de vouloir supprimer ce produit ?
      </div>
      <div class="modal-footer">
        <form method="post" action="#">
          <?= csrf_field() ?>
          <input type="hidden" name="id_produit" id="idProduitASupprimer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
          <button type="submit" name="supprimer_produit" class="btn btn-danger">Supprimer</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const deleteModal = document.getElementById('deleteModal');
    deleteModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget; 
        const idProduit = button.getAttribute('data-id'); 
        const inputHidden = deleteModal.querySelector('#idProduitASupprimer');
        inputHidden.value = idProduit;
    });
});
</script>