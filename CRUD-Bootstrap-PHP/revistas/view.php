<?php
include('function.php');
view($_GET['id'] ?? null);


include(HEADER_TEMPLATE);
?>

<?php if ($revista): ?>

<h2>Revista <?php echo $revista['id']; ?></h2>
<hr>

<?php if (!empty($_SESSION['message'])): ?>
    <div class="alert alert-<?php echo $_SESSION['type']; ?>"><?php echo $_SESSION['message']; ?></div>
<?php endif; ?>

<div class="row g-4">
    <div class="col-md-4 col-lg-3">
        <img src="<?php echo capa($revista['foto']); ?>" class="capa-detalhe"
            alt="Capa de <?php echo htmlspecialchars($revista['nome']); ?>">
    </div>

    <div class="col-md-8 col-lg-9">
        <dl class="dl-horizontal">
            <dt>Nome:</dt> <!-- titulo -->
            <dd><?php echo htmlspecialchars($revista['nome']); ?></dd>

            <dt>Ano:</dt>
            <dd><?php echo $revista['ano']; ?></dd>

            <dt>Edição:</dt>
            <dd><?php echo $revista['edicao']; ?></dd>

            <dt>Data de Cadastro:</dt>
            <dd><?php echo formatData($revista['datacadastro'], "d/m/Y - H:i:s"); ?></dd>
        </dl>

        <?php if (!empty($revista['descricao'])): ?>
        <dl class="dl-horizontal">
            <dt>Descrição:</dt>
            <dd><?php echo nl2br(htmlspecialchars($revista['descricao'])); ?></dd>
        </dl>
        <?php endif; ?>
    </div>
</div>

<div id="actions" class="row">
    <div class="col-md-12">
        <a href="edit.php?id=<?php echo $revista['id']; ?>" class="btn btn-primary"><i
                class="fa-solid fa-pen-to-square"></i> Editar</a>
        <a href="index.php" class="btn btn-outline-secondary"><i class="fa-solid fa-arrow-rotate-left"></i> Voltar</a>
    </div>

</div>

<?php else: ?>

<h2>Revista</h2>
<hr>
<div class="alert alert-danger" role="alert">Revista não encontrada!</div>
<a href="index.php" class="btn btn-outline-secondary"><i class="fa-solid fa-arrow-rotate-left"></i> Voltar</a>

<?php endif; ?>

<?php include FOOTER_TEMPLATE; ?>
