<?php
    // Esse é o login.php
    include("../config.php");

    // quem já está conectado não precisa ver a tela de login
    if (logado()) {
        redireciona(BASEURL . "index.php");
    }

    $titulo_pagina = 'Login';
    include(HEADER_TEMPLATE);
?>

<h2>Entrar</h2>

<form class="login-form" action="valida.php" method="post">
    <?php echo csrf_field(); ?>
    <hr>

    <div class="form-group">
        <label for="log">Usuário</label>
        <input type="text" class="form-control" id="log" name="login" maxlength="30" autocomplete="username" autofocus required>
    </div>

    <div class="form-group">
        <label for="pass">Senha</label>
        <input type="password" class="form-control" id="pass" name="senha" autocomplete="current-password" required>
    </div>

    <div id="actions" class="row">
        <div class="col-md-12">
            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-user-check"></i> Conectar</button>
            <a href="<?php echo BASEURL; ?>index.php" class="btn btn-outline-secondary"><i class="fa-solid fa-rotate-left"></i> Cancelar</a>
        </div>
    </div>
</form>

<?php include(FOOTER_TEMPLATE); ?>
