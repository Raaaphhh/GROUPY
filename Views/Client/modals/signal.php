<div class="modal fade" id="signalProduitModal" tabindex="-1" aria-labelledby="signalProduitModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="signalProduitModalLabel">Confirmer le signalement</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
      </div>
      <div class="modal-body">
        Êtes-vous sûr de vouloir signaler cette prevente ?
      </div>
        <form method="post" action="#">
          <?= csrf_field() ?>
          <input type="hidden" name="id_produit" id="idProduitASignaler">
          <input type="hidden" name="id_user" id="idUserSignal">

          <select name="motif" class="form-select w-75 mx-auto d-block" required>
            <option value="" disabled selected>Choisissez un motif</option>
            <option value="Contenu inapproprié">Contenu inapproprié</option>
            <option value="Produit non conforme">Produit non conforme</option>
            <option value="Fraude suspectée">Fraude suspectée</option>
            <option value="Autre">Autre</option>
          </select>

          <div class="modal-footer mt-3">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
            <button type="submit" name="signaler_produit" class="btn btn-danger">Signaler</button>
          </div>
        </form>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const deleteModal = document.getElementById('signalProduitModal');
    deleteModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget; 
        const idProduit = button.getAttribute('data-idProduit'); 
        const idUser = button.getAttribute('data-idUser'); 
        const inputHiddenProduit = deleteModal.querySelector('#idProduitASignaler');
        const inputHiddenUser = deleteModal.querySelector('#idUserSignal');
        inputHiddenProduit.value = idProduit;
        inputHiddenUser.value = idUser;
    });
});
</script>