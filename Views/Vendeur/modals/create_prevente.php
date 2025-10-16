<div class="modal fade" id="pulbishedProduitModal" tabindex="-1" aria-labelledby="pulbishedProduitModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 shadow-lg">
        <div class="modal-header bg-success text-dark">
            <h5 class="modal-title" id="addProduitModalLabel">Creer une prévente</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
        </div>
        <form action="#" method="post">
            <div class="modal-body text-start">
                <div class="mb-3">
                    <label class="form-label">Produit</label>
                    <input type="hidden" name="id_produit" id="published_id_produit" class="form-control">
                    <input type="text" name="nom_produit" id="published_nom_produit" class="form-control" disabled>
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

<script>
document.addEventListener('DOMContentLoaded', function () {
    const createPreventeModal = document.getElementById('pulbishedProduitModal');
    pulbishedProduitModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;

        pulbishedProduitModal.querySelector('#published_id_produit').value = button.getAttribute('data-id');
        pulbishedProduitModal.querySelector('#published_nom_produit').value = button.getAttribute('data-nom');
    });
});
</script>
