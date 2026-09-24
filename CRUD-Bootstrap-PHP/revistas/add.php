<?php
include "function.php";
add();
include HEADER_TEMPLATE;
?>

<h2>Nova Revista</h2>

<form action="add.php" method="post" enctype="multipart/form-data">
    <!-- area de campos do form -->
    <hr>
    <div class="row">
        <div class="col-md-4 col-lg-3">
            <div class="capa-preview">
                <img src="<?php echo capa(''); ?>" id="preview" data-original="<?php echo capa(''); ?>"
                    class="capa-detalhe" alt="Prévia da capa">
            </div>
        </div>

        <div class="col-md-8 col-lg-9">
            <div class="row">
                <div class="form-group col-md-12">
                    <label for="nome">Nome</label>
                    <input type="text" class="form-control" id="nome" name="revista[nome]" maxlength="50" required>
                </div>
            </div>

            <div class="row">
                <div class="form-group col-md-6">
                    <label for="ano">Ano</label>
                    <input type="number" class="form-control" id="ano" name="revista[ano]" min="1" required>
                </div>

                <div class="form-group col-md-6">
                    <label for="edicao">Edição</label>
                    <input type="number" class="form-control" id="edicao" name="revista[edicao]" min="1" required>
                </div>
            </div>

            <div class="row">
                <div class="form-group col-md-12">
                    <label for="descricao">Descrição</label>
                    <textarea class="form-control" id="descricao" name="revista[descricao]" rows="4"
                        maxlength="1000"></textarea>
                </div>
            </div>

            <div class="row">
                <div class="form-group col-md-12">
                    <label for="foto">Foto da capa</label>
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

<?php include FOOTER_TEMPLATE; ?>
