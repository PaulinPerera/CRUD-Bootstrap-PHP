<?php
    include("function.php");
    index();
    $titulo_pagina = 'Revistas';
    include HEADER_TEMPLATE;
?>

<header class="page-header">
    <h2>Revistas</h2>
    <div class="page-actions">
        <?php if (logado()): ?>
        <a class="btn btn-primary" href="add.php"><i class="fa-solid fa-plus"></i> Nova Revista</a>
        <?php endif; ?>
        <a class="btn btn-outline-secondary" href="index.php"><i class="fa-solid fa-refresh"></i> Atualizar</a>
    </div>
</header>



<hr>

<!-- Pesquisa pelo nome da revista -->
<div class="search-bar">
    <form action="index.php" method="get" role="search">
        <div class="input-group">
            <input type="search" class="form-control" name="filtro" maxlength="50" placeholder="Pesquisar por nome..."
                aria-label="Pesquisar revista por nome" value="<?php echo htmlspecialchars($filtro); ?>">
            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-magnifying-glass"></i> Pesquisar</button>
        </div>
    </form>

    <?php if ($filtro !== ''): ?>
        <p class="search-info">
            <?php echo $revistas ? count($revistas) : 0; ?> resultado(s) para
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
            <th width="32%">Nome</th>
            <th>Ano</th>
            <th>Edição</th>
            <th>Cadastro</th>
            <th>Capa</th>
            <th>Opções</th>
        </tr>
    </thead>
    <tbody>
        <!-- Os dois pontos ":" indica se vai mostrar o que vira depois-->
        <?php if ($revistas): ?>
            <?php foreach ($revistas as $revista): ?> 
                <tr>
                    <td><?php echo $revista['id']; ?></td>
                    <td><?php echo htmlspecialchars($revista['nome']); ?></td>
                    <td><?php echo $revista['ano']; ?></td>
                    <td><?php echo $revista['edicao']; ?></td>
                    <td><?php echo formatData($revista['datacadastro'], "d/m/Y"); ?></td>
                    <td>
                        <a href="view.php?id=<?php echo $revista['id']; ?>">
                            <img src="<?php echo capa($revista['foto']); ?>" class="capa-thumb"
                                alt="Capa de <?php echo htmlspecialchars($revista['nome']); ?>">
                        </a>
                    </td>
                    <td class="actions text-end">
                        <a href="view.php?id=<?php echo $revista['id']; ?>" class="btn btn-sm btn-outline-secondary"><i
                                class="fa fa-eye"></i> Visualizar</a>
                        <?php if (logado()): ?>
                        <a href="edit.php?id=<?php echo $revista['id']; ?>" class="btn btn-sm btn-outline-primary"><i
                                class="fa fa-pencil"></i> Editar</a>
                        <a href="#" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#delete-modal"
                            data-id="<?= $revista['id']; ?>" data-nome="<?= htmlspecialchars($revista['nome']); ?>" data-tipo="Revista">
                            <i class="fa fa-trash"></i> Excluir
                        </a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="7" class="empty-state">Nenhuma revista encontrada.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
</div>

<?php if (logado()) include "modal.php"; ?>
<?php include FOOTER_TEMPLATE; ?>
