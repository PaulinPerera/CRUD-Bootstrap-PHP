<?php
include "function.php";
edit();
include HEADER_TEMPLATE;
?>

<h2>Atualizar Revista</h2>

<?php if ($revista): ?>
<form action="edit.php?id=<?= (int) $revista['id']; ?>" method="post" enctype="multipart/form-data">
    <!-- area de campos do form -->
    <hr>
    <div class="row">
        <div class="col-md-4 col-lg-3">
            <div class="capa-preview">
                <img src="<?php echo capa($revista['foto']); ?>" id="preview" data-original="<?php echo capa($revista['foto']); ?>"
                    class="capa-detalhe" alt="Capa de <?php echo htmlspecialchars($revista['nome']); ?>">
            </div>
        </div>

        <div class="col-md-8 col-lg-9">
            <div class="row">
                <div class="form-group col-md-8">
                    <label for="nome">Nome</label>
                    <input type="text" class="form-control" id="nome" name="revista[nome]" maxlength="50" required
                        value="<?php echo htmlspecialchars($revista['nome']); ?>">
                </div>

                <div class="form-group col-md-4">
                    <label for="datacadastro">Data de Cadastro</label>
                    <input type="date" class="form-control" id="datacadastro" disabled
                        value="<?php echo formatData($revista['datacadastro'], "Y-m-d"); ?>">
                </div>
            </div>

            <div class="row">
                <div class="form-group col-md-6">
                    <label for="ano">Ano</label>
                    <input type="number" class="form-control" id="ano" name="revista[ano]" min="1" required
                        value="<?php echo $revista['ano']; ?>">
                </div>

                <div class="form-group col-md-6">
                    <label for="edicao">Edição</label>
                    <input type="number" class="form-control" id="edicao" name="revista[edicao]" min="1" required
                        value="<?php echo $revista['edicao']; ?>">
                </div>
            </div>

            <div class="row">
                <div class="form-group col-md-12">
                    <label for="descricao">Descrição</label>
                    <textarea class="form-control" id="descricao" name="revista[descricao]" rows="4"
                        maxlength="1000"><?php echo htmlspecialchars($revista['descricao']); ?></textarea>
                </div>
            </div>

            <div class="row">
                <div class="form-group col-md-12">
                    <label for="foto">Nova foto da capa (opcional)</label>
                    <input type="file" class="form-control" id="foto" name="foto" accept="image/*">
                </div>
            </div>
        </div>
    </div>

    <div id="actions" class="row">
        <div class="col-md-12">
            <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-floppy-disk"></i> Salvar
            </button>
            <a href="index.php" class="btn btn-outline-secondary">
                <i class="fa-solid fa-rotate-left"></i> Cancelar
            </a>
        </div>
    </div>
</form>
<?php else: ?>
    <hr>
    <div class="alert alert-danger" role="alert">Revista não encontrada!</div>
    <a href="index.php" class="btn btn-outline-secondary"><i class="fa-solid fa-arrow-rotate-left"></i> Voltar</a>
<?php endif; ?>

<?php include FOOTER_TEMPLATE; ?>
