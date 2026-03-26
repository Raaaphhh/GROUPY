<div class="modal fade" id="publishedModal" tabindex="-1" aria-labelledby="publishedModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="publishedModalLabel">Confirmer la mise en ligne ?</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
      </div>
      <div class="modal-body">
        Êtes-vous sûr de vouloir mettre en ligne cette prevente ?
      </div>
      <div class="modal-footer">
        <form method="post" action="#">
          <?= csrf_field() ?>
          <input type="hidden" name="id_prevente" id="idPreventeAPublier">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
          <button type="submit" name="published_prevente" class="btn btn-primary">Publier</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const publishedModal = document.getElementById('publishedModal');
    publishedModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget; 
        const idPrevente = button.getAttribute('data-id'); 
        const inputHidden = publishedModal.querySelector('#idPreventeAPublier');
        inputHidden.value = idPrevente;
    });
});
</script>