<?php
include "function.php";
exige_admin();
add();
$titulo_pagina = 'Novo Usuário';
include HEADER_TEMPLATE;
?>

<h2>Novo Usuário</h2>

<form action="add.php" method="post">
    <?php echo csrf_field(); ?>
    <hr>
    <div class="row">
        <div class="form-group col-md-6">
            <label for="nome">Nome</label>
            <input type="text" class="form-control" id="nome" name="usuario[nome]" maxlength="50" required>
        </div>

        <div class="form-group col-md-6">
            <label for="user">Login</label>
            <input type="text" class="form-control" id="user" name="usuario[user]" maxlength="30"
                pattern="[A-Za-z0-9_.\-]{3,30}" autocomplete="off" required>
        </div>
    </div>

    <div class="row">
        <div class="form-group col-md-6">
            <label for="senha">Senha</label>
            <input type="password" class="form-control" id="senha" name="senha" minlength="6" autocomplete="new-password" required>
        </div>

        <div class="form-group col-md-6">
            <label for="confirma">Confirmar senha</label>
            <input type="password" class="form-control" id="confirma" name="confirma" minlength="6" autocomplete="new-password" required>
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
