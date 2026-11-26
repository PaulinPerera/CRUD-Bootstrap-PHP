<?php
/**
 * Sessão, login, mensagens (flash) e proteção CSRF.
 * Carregado automaticamente pelo config.php, então toda página já tem sessão iniciada.
 */

if (session_status() === PHP_SESSION_NONE) {
	session_set_cookie_params([
		'lifetime' => 0,
		'path'     => BASEURL,
		'httponly' => true,      // o JavaScript não enxerga o cookie da sessão
		'samesite' => 'Lax',     // o navegador não envia o cookie em POST vindo de outro site
	]);
	session_start();
}

/** Escapa texto para imprimir no HTML */
function h($valor)
{
	return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
}

/** Redireciona e encerra o script */
function redireciona($url)
{
	header('Location: ' . $url);
	exit;
}

/* ===== Mensagens (aparecem uma vez e somem) ===== */

function flash($mensagem, $tipo = 'success')
{
	$_SESSION['message'] = $mensagem;
	$_SESSION['type'] = $tipo;
}

/** Imprime a mensagem guardada na sessão (se houver) e apaga em seguida */
function mostra_flash()
{
	if (empty($_SESSION['message'])) {
		return;
	}

	$tipo = $_SESSION['type'] ?? 'info';
	if (!in_array($tipo, ['success', 'danger', 'warning', 'info'], true)) {
		$tipo = 'info';
	}

	echo '<div class="alert alert-' . $tipo . ' alert-dismissible fade show" role="alert">'
		. $_SESSION['message']
		. '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>'
		. '</div>';

	unset($_SESSION['message'], $_SESSION['type']);
}

/* ===== Login ===== */

function logado()
{
	return !empty($_SESSION['user']);
}

function eh_admin()
{
	return logado() && $_SESSION['nivel'] == 'admin';
}

/** Manda para o login quem não está conectado (e volta para cá depois de entrar) */
function exige_login()
{
	if (logado()) {
		return;
	}

	if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'GET') {
		$_SESSION['voltar'] = $_SERVER['REQUEST_URI'] ?? '';
	}

	flash('Faça login para continuar.', 'warning');
	redireciona(BASEURL . 'inc/login.php');
}

/** Só o administrador passa */
function exige_admin()
{
	exige_login();

	if (!eh_admin()) {
		flash('Acesso restrito ao administrador.', 'danger');
		redireciona(BASEURL . 'index.php');
	}
}

/* ===== CSRF: impede que outro site envie formulários em nome do usuário ===== */

function csrf_token()
{
	if (empty($_SESSION['csrf'])) {
		$_SESSION['csrf'] = bin2hex(random_bytes(32));
	}
	return $_SESSION['csrf'];
}

function csrf_field()
{
	return '<input type="hidden" name="csrf" value="' . h(csrf_token()) . '">';
}

function csrf_valido()
{
	return isset($_POST['csrf'], $_SESSION['csrf'])
		&& is_string($_POST['csrf'])
		&& hash_equals($_SESSION['csrf'], $_POST['csrf']);
}

/** Em POST com token inválido: avisa e volta para $destino */
function exige_csrf($destino)
{
	if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && !csrf_valido()) {
		flash('Sua sessão expirou. Tente novamente.', 'warning');
		redireciona($destino);
	}
}
