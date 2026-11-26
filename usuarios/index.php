<?php
    include("function.php");
    exige_admin();
    index();
    $titulo_pagina = 'Usuários';
    include HEADER_TEMPLATE;
?>

<header class="page-header">
    <h2>Usuários</h2>
    <div class="page-actions">
        <a class="btn btn-primary" href="add.php"><i class="fa-solid fa-user-plus"></i> Novo Usuário</a>
        <a class="btn btn-outline-secondary" href="index.php"><i class="fa-solid fa-refresh"></i> Atualizar</a>
    </div>
</header>

<hr>

<!-- Pesquisa pelo nome do usuário -->
<div class="search-bar">
    <form action="index.php" method="get" role="search">
        <div class="input-group">
            <input type="search" class="form-control" name="filtro" maxlength="50" placeholder="Pesquisar por nome..."
                aria-label="Pesquisar usuário por nome" value="<?php echo htmlspecialchars($filtro); ?>">
            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-magnifying-glass"></i> Pesquisar</button>
        </div>
    </form>

    <?php if ($filtro !== ''): ?>
        <p class="search-info">
            <?php echo $usuarios ? count($usuarios) : 0; ?> resultado(s) para
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
            <th width="36%">Nome</th>
            <th>Login</th>
            <th>Cadastro</th>
            <th>Opções</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($usuarios): ?>
            <?php foreach ($usuarios as $usuario): ?>
                <?php $pode_excluir = ($usuario['user'] !== 'admin' && (int) $usuario['id'] !== (int) $_SESSION['id']); ?>
                <tr>
                    <td><?php echo $usuario['id']; ?></td>
                    <td><?php echo htmlspecialchars($usuario['nome']); ?></td>
                    <td><?php echo htmlspecialchars($usuario['user']); ?></td>
                    <td><?php echo formatData($usuario['datacadastro'], "d/m/Y"); ?></td>
                    <td class="actions text-end">
                        <a href="edit.php?id=<?php echo $usuario['id']; ?>" class="btn btn-sm btn-outline-primary"><i
                                class="fa fa-pencil"></i> Editar</a>
                        <?php if ($pode_excluir): ?>
                        <a href="#" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#delete-modal"
                            data-id="<?= $usuario['id']; ?>" data-nome="<?= htmlspecialchars($usuario['nome']); ?>" data-tipo="Usuário">
                            <i class="fa fa-trash"></i> Excluir
                        </a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="5" class="empty-state">Nenhum usuário encontrado.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
</div>

<?php include "modal.php"; ?>
<?php include FOOTER_TEMPLATE; ?>
