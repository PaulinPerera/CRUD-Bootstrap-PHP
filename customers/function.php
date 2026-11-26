<?php
ob_start(); //output buffer aberto, para não dar erro de header location

include('../config.php');
include(DBAPI);

$clientes = null;
$cliente = null;
$filtro = "";

/**
 *  Endereço (URL) da foto; cliente sem imagem usa a SemImagem.png
 */
function foto($foto)
{
	return imagem_url($foto);
}

function so_digitos($texto)
{
	return preg_replace('/\D/', '', (string) $texto);
}

/**
 *  Confere os dígitos verificadores do CPF
 */
function valida_cpf($cpf)
{
	$c = so_digitos($cpf);

	// 11 dígitos e não pode ser tudo igual (000.000.000-00, 111.111.111-11...)
	if (strlen($c) !== 11 || preg_match('/^(\d)\1{10}$/', $c)) {
		return false;
	}

	for ($t = 9; $t < 11; $t++) {
		$soma = 0;
		for ($i = 0; $i < $t; $i++) {
			$soma += (int) $c[$i] * (($t + 1) - $i);
		}
		$digito = ((10 * $soma) % 11) % 10;
		if ((int) $c[$t] !== $digito) {
			return false;
		}
	}
	return true;
}

/** 00000000000 -> 000.000.000-00 */
function formata_cpf($cpf)
{
	$c = so_digitos($cpf);
	return substr($c, 0, 3) . '.' . substr($c, 3, 3) . '.' . substr($c, 6, 3) . '-' . substr($c, 9, 2);
}

/** 11987654321 -> (11) 98765-4321  |  1187654321 -> (11) 8765-4321 */
function formata_telefone($telefone)
{
	$t = so_digitos($telefone);

	if (strlen($t) === 11) {
		return '(' . substr($t, 0, 2) . ') ' . substr($t, 2, 5) . '-' . substr($t, 7, 4);
	}
	return '(' . substr($t, 0, 2) . ') ' . substr($t, 2, 4) . '-' . substr($t, 6, 4);
}

/**
 *  Dados enviados pelo formulário. Só entram os campos do cliente,
 *  porque os nomes dos campos viram colunas no SQL.
 */
function dados_form()
{
	$form = $_POST['cliente'];

	return [
		'nome'     => trim($form['nome'] ?? ''),
		'cpf'      => formata_cpf($form['cpf'] ?? ''),
		'email'    => trim($form['email'] ?? ''),
		'telefone' => formata_telefone($form['telefone'] ?? ''),
	];
}

/**
 *  Confere os dados no servidor (o navegador pode ser burlado). Retorna a mensagem de erro ou null.
 *  $id: id do cliente que está sendo editado (0 = novo), para não acusar duplicidade consigo mesmo.
 */
function valida_cliente($dados, $id = 0)
{
	if ($dados['nome'] === '') {
		return 'Informe o nome do cliente.';
	}
	if (mb_strlen($dados['nome']) > 50) {
		return 'O nome pode ter no máximo 50 caracteres.';
	}
	if (!valida_cpf($dados['cpf'])) {
		return 'CPF inválido.';
	}
	if (!filter_var($dados['email'], FILTER_VALIDATE_EMAIL) || mb_strlen($dados['email']) > 100) {
		return 'E-mail inválido.';
	}
	$digitos = strlen(so_digitos($dados['telefone']));
	if ($digitos < 10 || $digitos > 11) {
		return 'Telefone inválido. Informe o DDD e o número.';
	}
	if (existe_valor('clientes', 'cpf', $dados['cpf'], $id)) {
		return 'Já existe um cliente com esse CPF.';
	}
	if (existe_valor('clientes', 'email', $dados['email'], $id)) {
		return 'Já existe um cliente com esse e-mail.';
	}
	return null;
}

/**
 *  Listagem de Clientes (com pesquisa opcional pelo nome)
 */
function index()
{
	global $clientes, $filtro;

	$filtro = trim($_GET['filtro'] ?? '');
	$clientes = search("clientes", "nome", $filtro);
}

/**
 *  Visualização de um Cliente
 */
function view($id = null)
{
	global $cliente;

	// find() sem id devolveria a tabela inteira; aqui só interessa um cliente
	$cliente = ((int) $id > 0) ? find('clientes', $id) : null;
}

/**
 *  Cadastro de Clientes
 */
function add()
{
	if (!empty($_POST['cliente']) && is_array($_POST['cliente'])) {

		exige_csrf('add.php');

		$cliente = dados_form();

		if ($erro = valida_cliente($cliente)) {
			flash($erro, 'danger');
			redireciona('add.php');
		}

		$foto = upload_imagem('cliente_');
		if ($foto === false) {
			flash(MSG_IMAGEM_INVALIDA, 'danger');
			redireciona('add.php');
		}

		$today = new DateTime('now', new DateTimeZone('America/Sao_Paulo'));

		$cliente['datacadastro'] = $today->format("Y-m-d H:i:s");
		$cliente['foto'] = $foto ?? ""; // sem imagem: fica vazio e a listagem mostra a SemImagem.png

		save('clientes', $cliente);
		redireciona('index.php');
	}
}

/**
 *	Atualizacao/Edicao de Cliente
 */
function edit()
{
	global $cliente;

	$id = (int) ($_GET['id'] ?? 0);
	if ($id < 1) {
		redireciona('index.php');
	}

	if (!empty($_POST['cliente']) && is_array($_POST['cliente'])) {

		exige_csrf("edit.php?id=$id");

		$dados = dados_form();

		if ($erro = valida_cliente($dados, $id)) {
			flash($erro, 'danger');
			redireciona("edit.php?id=$id");
		}

		// só troca a foto se uma nova imagem foi enviada; senão a atual continua
		$foto = upload_imagem('cliente_');
		if ($foto === false) {
			flash(MSG_IMAGEM_INVALIDA, 'danger');
			redireciona("edit.php?id=$id");
		}

		$atual = find("clientes", $id);

		if ($foto) {
			$dados['foto'] = $foto;
		}

		update("clientes", $id, $dados);

		// foto antiga não serve mais: libera o espaço
		if ($foto && $atual && !empty($atual['foto'])) {
			apaga_imagem($atual['foto']);
		}

		redireciona("index.php");
	}

	$cliente = find("clientes", $id);
}

/**
 *  Exclusão de um Cliente
 */
function delete($id = null)
{
	$atual = ((int) $id > 0) ? find('clientes', $id) : null;

	remove('clientes', $id);

	// apaga também o arquivo da foto (só se foi enviado pelo sistema)
	if ($atual && !empty($atual['foto'])) {
		apaga_imagem($atual['foto']);
	}

	redireciona("index.php");
}

?>
