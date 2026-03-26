<div class="modal fade" id="blockVendeurModal" tabindex="-1" aria-labelledby="blockVendeurModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="blockVendeurModalLabel">Confirmer le blocage</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                Êtes-vous sûr de vouloir bloquer ce vendeur ?
            </div>
            <div class="modal-footer">
                <form method="post" action="#">
                        <?= csrf_field() ?>
                        <input type="hidden" name="id_vendeur" id="idVendeurABloquer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" name="bloquer" class="btn btn-danger">Bloquer</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const blockModal = document.getElementById('blockVendeurModal');
    blockModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget; 
        const idVendeur = button.getAttribute('data-id'); 
        const inputHiddenVendeur = blockModal.querySelector('#idVendeurABloquer');
        inputHiddenVendeur.value = idVendeur;
    });
});
</script>
