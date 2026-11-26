<!-- Modal de Delete-->
<div class="modal fade" id="delete-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="modalLabel">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modalLabel">Excluir Revista</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
                <p>Deseja realmente excluir <strong class="modal-nome">esta revista</strong>?</p>
                <p class="text-body-secondary">Esta ação não pode ser desfeita.</p>
            </div>
            <div class="modal-footer">
                <a id="confirm" class="btn btn-danger" href="#">
                    <i class="fa-solid fa-circle-check"></i> Sim
                </a>
                <a id="cancel" class="btn btn-outline-secondary" data-bs-dismiss="modal" role="button">
                    <i class="fa-solid fa-circle-xmark"></i> Não
                </a>
            </div>
        </div>
    </div>
</div> <!-- /.modal -->

