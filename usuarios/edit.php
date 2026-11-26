<?php
include "function.php";
exige_admin();
edit();
$titulo_pagina = 'Editar Usuário';
include HEADER_TEMPLATE;
?>

<h2>Atualizar Usuário</h2>

<?php if ($usuario): ?>
<form action="edit.php?id=<?= (int) $usuario['id']; ?>" method="post">
    <?php echo csrf_field(); ?>
    <hr>
    <div class="row">
        <div class="form-group col-md-6">
            <label for="nome">Nome</label>
            <input type="text" class="form-control" id="nome" name="usuario[nome]" maxlength="50" required
                value="<?php echo htmlspecialchars($usuario['nome']); ?>">
        </div>

        <div class="form-group col-md-6">
            <label for="user">Login</label>
            <?php if ($usuario['user'] === 'admin'): ?>
                <input type="text" class="form-control" id="user" name="usuario[user]" value="admin" readonly>
            <?php else: ?>
                <input type="text" class="form-control" id="user" name="usuario[user]" maxlength="30"
                    pattern="[A-Za-z0-9_.\-]{3,30}" autocomplete="off" required
                    value="<?php echo htmlspecialchars($usuario['user']); ?>">
            <?php endif; ?>
        </div>
    </div>

    <div class="row">
        <div class="form-group col-md-6">
            <label for="senha">Nova senha (deixe em branco para manter)</label>
            <input type="password" class="form-control" id="senha" name="senha" minlength="6" autocomplete="new-password">
        </div>

        <div class="form-group col-md-6">
            <label for="confirma">Confirmar nova senha</label>
            <input type="password" class="form-control" id="confirma" name="confirma" minlength="6" autocomplete="new-password">
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
    <div class="alert alert-danger" role="alert">Usuário não encontrado!</div>
    <a href="index.php" class="btn btn-outline-secondary"><i class="fa-solid fa-arrow-rotate-left"></i> Voltar</a>
<?php endif; ?>

<?php include FOOTER_TEMPLATE; ?>
