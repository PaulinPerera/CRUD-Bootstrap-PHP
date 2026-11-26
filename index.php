<?php
include 'config.php';
include DBAPI;

$erro = null;
try {
	$db = open_database();
	close_database($db);
} catch (Exception $e) {
	$erro = $e->getMessage();
}

$titulo_pagina = 'Dashboard';
include HEADER_TEMPLATE;
?>

<h1>Dashboard</h1>
<hr>

<?php if (!$erro): ?>

	<div class="row g-3">
		<?php if (logado()): ?>
			<div class="col-12 col-md-6 col-lg-4">
				<a href="revistas/add.php" class="shortcut shortcut-primary">
					<span class="shortcut-icon"><i class="fa-solid fa-plus"></i></span>
					<span>
						<span class="shortcut-title">Nova Revista</span>
						<span class="shortcut-text">Cadastrar uma revista</span>
					</span>
				</a>
			</div>
		<?php endif; ?>

		<div class="col-12 col-md-6 col-lg-4">
			<a href="revistas" class="shortcut">
				<span class="shortcut-icon"><i class="fa-solid fa-book"></i></span>
				<span>
					<span class="shortcut-title">Revistas</span>
					<span class="shortcut-text"><?php echo logado() ? 'Consultar, editar e excluir' : 'Consultar o catálogo'; ?></span>
				</span>
			</a>
		</div>

		<?php if (logado()): ?>
			<div class="col-12 col-md-6 col-lg-4">
				<a href="customers/" class="shortcut">
					<span class="shortcut-icon"><i class="fa-solid fa-users"></i></span>
					<span>
						<span class="shortcut-title">Clientes</span>
						<span class="shortcut-text">Consultar, editar e excluir</span>
					</span>
				</a>
			</div>
		<?php endif; ?>

		<?php if (eh_admin()): ?>
			<div class="col-12 col-md-6 col-lg-4">
				<a href="usuarios/" class="shortcut">
					<span class="shortcut-icon"><i class="fa-solid fa-user-lock"></i></span>
					<span>
						<span class="shortcut-title">Usuários</span>
						<span class="shortcut-text">Quem pode acessar o sistema</span>
					</span>
				</a>
			</div>
		<?php endif; ?>
	</div>

<?php else: ?>
	<div class="alert alert-danger" role="alert">
		<p><b>ERRO:</b> Não foi possível Conectar ao Banco de Dados!<br>
			<?= htmlspecialchars($erro) ?>
		</p>
	</div>

<?php endif; ?>

<?php include FOOTER_TEMPLATE; ?>
