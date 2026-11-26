<?php
include('function.php');
exige_login();
view($_GET['id'] ?? null);

$titulo_pagina = 'Cliente';
include(HEADER_TEMPLATE);
?>

<?php if ($cliente): ?>

<h2>Cliente <?php echo $cliente['id']; ?></h2>
<hr>

<div class="row g-4">
    <div class="col-md-4 col-lg-3">
        <img src="<?php echo foto($cliente['foto']); ?>" class="foto-detalhe"
            alt="Foto de <?php echo htmlspecialchars($cliente['nome']); ?>">
    </div>

    <div class="col-md-8 col-lg-9">
        <dl class="dl-horizontal">
            <dt>Nome:</dt>
            <dd><?php echo htmlspecialchars($cliente['nome']); ?></dd>

            <dt>CPF:</dt>
            <dd><?php echo htmlspecialchars($cliente['cpf']); ?></dd>

            <dt>E-mail:</dt>
            <dd><?php echo htmlspecialchars($cliente['email']); ?></dd>

            <dt>Telefone:</dt>
            <dd><?php echo htmlspecialchars($cliente['telefone']); ?></dd>

            <dt>Data de Cadastro:</dt>
            <dd><?php echo formatData($cliente['datacadastro'], "d/m/Y - H:i:s"); ?></dd>
        </dl>
    </div>
</div>

<div id="actions" class="row">
    <div class="col-md-12">
        <a href="edit.php?id=<?php echo $cliente['id']; ?>" class="btn btn-primary"><i
                class="fa-solid fa-pen-to-square"></i> Editar</a>
        <a href="index.php" class="btn btn-outline-secondary"><i class="fa-solid fa-arrow-rotate-left"></i> Voltar</a>
    </div>
</div>

<?php else: ?>

<h2>Cliente</h2>
<hr>
<div class="alert alert-danger" role="alert">Cliente não encontrado!</div>
<a href="index.php" class="btn btn-outline-secondary"><i class="fa-solid fa-arrow-rotate-left"></i> Voltar</a>

<?php endif; ?>

<?php include FOOTER_TEMPLATE; ?>
