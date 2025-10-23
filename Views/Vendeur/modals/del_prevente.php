<div class="modal fade" id="deletePreventeModal" tabindex="-1" aria-labelledby="deletePreventeModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deletePreventeModalLabel">Confirmer la suppression</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
      </div>
      <div class="modal-body">
        Êtes-vous sûr de vouloir supprimer cette prevente ?
      </div>
      <div class="modal-footer">
        <form method="post" action="#">
          <input type="hidden" name="id_prevente" id="idPreventeASupprimer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
          <button type="submit" name="supprimer_prevente" class="btn btn-danger">Supprimer</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const deleteModal = document.getElementById('deletePreventeModal');
    deleteModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget; 
        const idPrevente = button.getAttribute('data-id'); 
        const inputHidden = deleteModal.querySelector('#idPreventeASupprimer');
        inputHidden.value = idPrevente;
    });
});
</script>