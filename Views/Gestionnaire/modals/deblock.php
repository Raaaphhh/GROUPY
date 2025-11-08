<div class="modal fade" id="deblockVendeurModal" tabindex="-1" aria-labelledby="deblockVendeurModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deblockVendeurModalLabel">Confirmer le deblocage</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                Êtes-vous sûr de vouloir debloquer ce vendeur ?
            </div>
            <div class="modal-footer">
                <form method="post" action="#">
                        <input type="hidden" name="id_vendeur_a_debloquer" id="idVendeurADebloquer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" name="debloquer" class="btn btn-success">Debloquer</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const blockModal = document.getElementById('deblockVendeurModal');
    blockModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget; 
        const idVendeur = button.getAttribute('data-id'); 
        const inputHiddenVendeur = blockModal.querySelector('#idVendeurADebloquer');
        inputHiddenVendeur.value = idVendeur;
    });
});
</script>
