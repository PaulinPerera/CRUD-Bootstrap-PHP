<?php
include 'config.php';
include DBAPI;

include HEADER_TEMPLATE;
$erro = null;
try {
	$db = open_database();
} catch (Exception $e) {
	$erro = $e->getMessage();
}
?>

<h1>Dashboard</h1>
<hr>

<?php if (!$erro): ?>

	<div class="row g-3">
		<div class="col-12 col-md-6 col-lg-4">
			<a href="revistas/add.php" class="shortcut shortcut-primary">
				<span class="shortcut-icon"><i class="fa-solid fa-plus"></i></span>
				<span>
					<span class="shortcut-title">Nova Revista</span>
					<span class="shortcut-text">Cadastrar uma revista</span>
				</span>
			</a>
		</div>

		<div class="col-12 col-md-6 col-lg-4">
			<a href="revistas" class="shortcut">
				<span class="shortcut-icon"><i class="fa-solid fa-book"></i></span>
				<span>
					<span class="shortcut-title">Revistas</span>
					<span class="shortcut-text">Consultar, editar e excluir</span>
				</span>
			</a>
		</div>
	</div>

<?php else: ?>
	<div class="alert alert-danger" role="alert">
		<p><b>ERRO:</b> Não foi possível Conectar ao Banco de Dados!<br>
			<?= $erro ?>
		</p>
	</div>

<?php endif; ?>

<?php include FOOTER_TEMPLATE; ?>
