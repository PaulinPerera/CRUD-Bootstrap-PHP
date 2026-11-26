<?php
ob_start(); //output buffer aberto, para não dar erro de header location

include('../config.php');
include(DBAPI);

$usuarios = null;
$usuario = null;
$filtro = "";

/**
 *  Dados enviados pelo formulário. Só entram os campos do usuário,
 *  porque os nomes dos campos viram colunas no SQL (a senha é tratada à parte).
 */
function dados_form()
{
	$form = $_POST['usuario'];

	return [
		'nome' => trim($form['nome'] ?? ''),
		'user' => trim($form['user'] ?? ''),
	];
}

/**
 *  Confere os dados no servidor. Retorna a mensagem de erro ou null.
 *  $id: id do usuário que está sendo editado (0 = novo).
 *  Na edição a senha é opcional: em branco = continua a mesma.
 */
function valida_usuario($dados, $senha, $confirma, $id = 0)
{
	if ($dados['nome'] === '') {
		return 'Informe o nome do usuário.';
	}
	if (mb_strlen($dados['nome']) > 50) {
		return 'O nome pode ter no máximo 50 caracteres.';
	}
	if (!preg_match('/^[A-Za-z0-9_.-]{3,30}$/', $dados['user'])) {
		return 'O login deve ter de 3 a 30 caracteres (letras, números, ponto, hífen ou sublinhado).';
	}
	if (existe_valor('usuarios', 'user', $dados['user'], $id)) {
		return 'Esse login já está em uso.';
	}
	if ($id === 0 || $senha !== '') {
		if (strlen($senha) < 6) {
			return 'A senha deve ter pelo menos 6 caracteres.';
		}
		if ($senha !== $confirma) {
			return 'A confirmação não é igual à senha.';
		}
	}
	return null;
}

/**
 *  Listagem de Usuários (com pesquisa opcional pelo nome)
 */
function index()
{
	global $usuarios, $filtro;

	$filtro = trim($_GET['filtro'] ?? '');
	$usuarios = search("usuarios", "nome", $filtro);
}

/**
 *  Cadastro de Usuários
 */
function add()
{
	if (!empty($_POST['usuario']) && is_array($_POST['usuario'])) {

		exige_csrf('add.php');

		$usuario = dados_form();
		$senha = (string) ($_POST['senha'] ?? '');
		$confirma = (string) ($_POST['confirma'] ?? '');

		if ($erro = valida_usuario($usuario, $senha, $confirma)) {
			flash($erro, 'danger');
			redireciona('add.php');
		}

		$today = new DateTime('now', new DateTimeZone('America/Sao_Paulo'));

		$usuario['password'] = password_hash($senha, PASSWORD_DEFAULT); // nunca guardamos a senha em texto
		$usuario['datacadastro'] = $today->format("Y-m-d H:i:s");

		save('usuarios', $usuario);
		redireciona('index.php');
	}
}

/**
 *	Atualizacao/Edicao de Usuário
 */
function edit()
{
	global $usuario;

	$id = (int) ($_GET['id'] ?? 0);
	if ($id < 1) {
		redireciona('index.php');
	}

	if (!empty($_POST['usuario']) && is_array($_POST['usuario'])) {

		exige_csrf("edit.php?id=$id");

		$atual = find('usuarios', $id);
		if (!$atual) {
			redireciona('index.php');
		}

		$dados = dados_form();
		$senha = (string) ($_POST['senha'] ?? '');
		$confirma = (string) ($_POST['confirma'] ?? '');

		// o login do administrador não muda: o sistema reconhece o admin por ele
		if ($atual['user'] === 'admin') {
			$dados['user'] = 'admin';
		}

		if ($erro = valida_usuario($dados, $senha, $confirma, $id)) {
			flash($erro, 'danger');
			redireciona("edit.php?id=$id");
		}

		if ($senha !== '') {
			$dados['password'] = password_hash($senha, PASSWORD_DEFAULT);
		}

		update('usuarios', $id, $dados);

		// editou a própria conta: atualiza o nome mostrado no menu
		if ($id === (int) ($_SESSION['id'] ?? 0)) {
			$_SESSION['nome'] = $dados['nome'];
			$_SESSION['user'] = $dados['user'];
		}

		redireciona('index.php');
	}

	$usuario = find('usuarios', $id);
}

/**
 *  Exclusão de um Usuário
 */
function delete($id = null)
{
	$id = (int) $id;
	$atual = ($id > 0) ? find('usuarios', $id) : null;

	if (!$atual) {
		flash('Usuário não encontrado.', 'danger');
	} elseif ($atual['user'] === 'admin') {
		flash('O administrador não pode ser excluído.', 'danger');
	} elseif ($id === (int) ($_SESSION['id'] ?? 0)) {
		flash('Você não pode excluir a sua própria conta.', 'danger');
	} else {
		remove('usuarios', $id);
	}

	redireciona('index.php');
}

?>
