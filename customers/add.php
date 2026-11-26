<?php
include "function.php";
exige_login();
add();
$titulo_pagina = 'Novo Cliente';
include HEADER_TEMPLATE;
?>

<h2>Novo Cliente</h2>

<form action="add.php" method="post" enctype="multipart/form-data">
    <?php echo csrf_field(); ?>
    <!-- area de campos do form -->
    <hr>
    <div class="row">
        <div class="col-md-4 col-lg-3">
            <div class="capa-preview">
                <img src="<?php echo foto(''); ?>" id="preview" data-original="<?php echo foto(''); ?>"
                    class="foto-detalhe" alt="Prévia da foto">
            </div>
        </div>

        <div class="col-md-8 col-lg-9">
            <div class="row">
                <div class="form-group col-md-12">
                    <label for="nome">Nome</label>
                    <input type="text" class="form-control" id="nome" name="cliente[nome]" maxlength="50" required>
                </div>
            </div>

            <div class="row">
                <div class="form-group col-md-6">
                    <label for="cpf">CPF</label>
                    <input type="text" class="form-control" id="cpf" name="cliente[cpf]" maxlength="14"
                        inputmode="numeric" placeholder="000.000.000-00" required>
                </div>

                <div class="form-group col-md-6">
                    <label for="telefone">Telefone</label>
                    <input type="tel" class="form-control" id="telefone" name="cliente[telefone]" maxlength="15"
                        placeholder="(00) 00000-0000" required>
                </div>
            </div>

            <div class="row">
                <div class="form-group col-md-12">
                    <label for="email">E-mail</label>
                    <input type="email" class="form-control" id="email" name="cliente[email]" maxlength="100" required>
                </div>
            </div>

            <div class="row">
                <div class="form-group col-md-12">
                    <label for="foto">Foto</label>
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
