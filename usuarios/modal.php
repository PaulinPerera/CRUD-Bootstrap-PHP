<!-- Modal de Delete (formulário POST com token CSRF: excluir nunca é feito por link) -->
<div class="modal fade" id="delete-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="modalLabel">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form class="modal-content" action="delete.php" method="post">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="id" id="delete-id" value="">
            <div class="modal-header">
                <h4 class="modal-title" id="modalLabel">Excluir Usuário</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
                <p>Deseja realmente excluir <strong class="modal-nome">este usuário</strong>?</p>
                <p class="text-body-secondary">Esta ação não pode ser desfeita.</p>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-danger">
                    <i class="fa-solid fa-circle-check"></i> Sim
                </button>
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="fa-solid fa-circle-xmark"></i> Não
                </button>
            </div>
        </form>
    </div>
</div> <!-- /.modal -->
