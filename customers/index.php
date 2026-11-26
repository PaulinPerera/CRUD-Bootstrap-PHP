<?php
    include("function.php");
    exige_login();   // dados pessoais (CPF, e-mail, telefone): só para quem está conectado
    index();
    $titulo_pagina = 'Clientes';
    include HEADER_TEMPLATE;
?>

<header class="page-header">
    <h2>Clientes</h2>
    <div class="page-actions">
        <a class="btn btn-primary" href="add.php"><i class="fa-solid fa-user-plus"></i> Novo Cliente</a>
        <a class="btn btn-outline-secondary" href="index.php"><i class="fa-solid fa-refresh"></i> Atualizar</a>
    </div>
</header>

<hr>

<!-- Pesquisa pelo nome do cliente -->
<div class="search-bar">
    <form action="index.php" method="get" role="search">
        <div class="input-group">
            <input type="search" class="form-control" name="filtro" maxlength="50" placeholder="Pesquisar por nome..."
                aria-label="Pesquisar cliente por nome" value="<?php echo htmlspecialchars($filtro); ?>">
            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-magnifying-glass"></i> Pesquisar</button>
        </div>
    </form>

    <?php if ($filtro !== ''): ?>
        <p class="search-info">
            <?php echo $clientes ? count($clientes) : 0; ?> resultado(s) para
            <strong>“<?php echo htmlspecialchars($filtro); ?>”</strong> &middot;
            <a href="index.php">Limpar pesquisa</a>
        </p>
    <?php endif; ?>
</div>

<div class="table-responsive table-card">
<table class="table table-hover">
    <thead>
        <tr>
            <th>ID</th>
            <th width="26%">Nome</th>
            <th>CPF</th>
            <th>E-mail</th>
            <th>Telefone</th>
            <th>Foto</th>
            <th>Opções</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($clientes): ?>
            <?php foreach ($clientes as $cliente): ?>
                <tr>
                    <td><?php echo $cliente['id']; ?></td>
                    <td><?php echo htmlspecialchars($cliente['nome']); ?></td>
                    <td><?php echo htmlspecialchars($cliente['cpf']); ?></td>
                    <td><?php echo htmlspecialchars($cliente['email']); ?></td>
                    <td><?php echo htmlspecialchars($cliente['telefone']); ?></td>
                    <td>
                        <a href="view.php?id=<?php echo $cliente['id']; ?>">
                            <img src="<?php echo foto($cliente['foto']); ?>" class="foto-cliente"
                                alt="Foto de <?php echo htmlspecialchars($cliente['nome']); ?>">
                        </a>
                    </td>
                    <td class="actions text-end">
                        <a href="view.php?id=<?php echo $cliente['id']; ?>" class="btn btn-sm btn-outline-secondary"><i
                                class="fa fa-eye"></i> Visualizar</a>
                        <a href="edit.php?id=<?php echo $cliente['id']; ?>" class="btn btn-sm btn-outline-primary"><i
                                class="fa fa-pencil"></i> Editar</a>
                        <a href="#" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#delete-modal"
                            data-id="<?= $cliente['id']; ?>" data-nome="<?= htmlspecialchars($cliente['nome']); ?>" data-tipo="Cliente">
                            <i class="fa fa-trash"></i> Excluir
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="7" class="empty-state">Nenhum cliente encontrado.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
</div>

<?php include "modal.php"; ?>
<?php include FOOTER_TEMPLATE; ?>
