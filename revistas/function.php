<?php
ob_start(); //output buffer aberto, para não dar erro de header location

include('../config.php');
include(DBAPI);

$revistas = null;
$revista = null;
$filtro = "";

/**
 *  Endereço (URL) da capa; revista sem imagem usa a SemImagem.png
 */
function capa($foto)
{
	return imagem_url($foto);
}

/**
 *  Dados enviados pelo formulário. Só entram os campos da revista,
 *  porque os nomes dos campos viram colunas no SQL.
 */
function dados_form()
{
	$form = $_POST['revista'];

	return [
		'nome'      => trim($form['nome'] ?? ''),
		'ano'       => (int) ($form['ano'] ?? 0),
		'edicao'    => (int) ($form['edicao'] ?? 0),
		'descricao' => trim($form['descricao'] ?? ''),
	];
}

/**
 *  Confere os dados no servidor (o navegador pode ser burlado). Retorna a mensagem de erro ou null.
 */
function valida_revista($dados)
{
	if ($dados['nome'] === '') {
		return 'Informe o nome da revista.';
	}
	if (mb_strlen($dados['nome']) > 50) {
		return 'O nome pode ter no máximo 50 caracteres.';
	}
	if ($dados['ano'] < 1) {
		return 'Informe um ano válido.';
	}
	if ($dados['edicao'] < 1) {
		return 'Informe uma edição válida.';
	}
	return null;
}

/**
 *  Listagem de Revistas (com pesquisa opcional pelo nome)
 */
function index()
{
	global $revistas, $filtro;

	$filtro = trim($_GET['filtro'] ?? '');
	$revistas = search("tabelarevista", "nome", $filtro);
}

/**
 *  Visualização de uma Revista
 */
function view($id = null)
{
	global $revista;

	// find() sem id devolveria a tabela inteira; aqui só interessa uma revista
	$revista = ((int) $id > 0) ? find('tabelarevista', $id) : null;
}

/**
 *  Cadastro de Revistas
 */
function add()
{
	if (!empty($_POST['revista']) && is_array($_POST['revista'])) {

		exige_csrf('add.php');

		$revista = dados_form();

		if ($erro = valida_revista($revista)) {
			flash($erro, 'danger');
			redireciona('add.php');
		}

		$foto = upload_imagem('capa_');
		if ($foto === false) {
			flash(MSG_IMAGEM_INVALIDA, 'danger');
			redireciona('add.php');
		}

		$today = new DateTime('now', new DateTimeZone('America/Sao_Paulo'));

		$revista['datacadastro'] = $today->format("Y-m-d H:i:s");
		$revista['foto'] = $foto ?? ""; // sem imagem: fica vazio e a listagem mostra a SemImagem.png

		save('tabelarevista', $revista); // 'tabelarevista' -> nome da tabela; $revista -> associative array
		redireciona('index.php');
	}
}

/**
 *	Atualizacao/Edicao de Revista
 */
function edit()
{
	global $revista;

	$id = (int) ($_GET['id'] ?? 0);
	if ($id < 1) {
		redireciona('index.php');
	}

	if (!empty($_POST['revista']) && is_array($_POST['revista'])) {

		exige_csrf("edit.php?id=$id");

		$dados = dados_form();

		if ($erro = valida_revista($dados)) {
			flash($erro, 'danger');
			redireciona("edit.php?id=$id");
		}

		// só troca a capa se uma nova imagem foi enviada; senão a atual continua
		$foto = upload_imagem('capa_');
		if ($foto === false) {
			flash(MSG_IMAGEM_INVALIDA, 'danger');
			redireciona("edit.php?id=$id");
		}

		$atual = find("tabelarevista", $id);

		if ($foto) {
			$dados['foto'] = $foto;
		}

		update("tabelarevista", $id, $dados);

		// capa antiga não serve mais: libera o espaço
		if ($foto && $atual && !empty($atual['foto'])) {
			apaga_imagem($atual['foto']);
		}

		redireciona("index.php");
	}

	$revista = find("tabelarevista", $id);
}

/**
 *  Exclusão de uma Revista
 */
function delete($id = null)
{
	$atual = ((int) $id > 0) ? find('tabelarevista', $id) : null;

	remove('tabelarevista', $id);

	// apaga também o arquivo da capa (só se foi enviado pelo sistema)
	if ($atual && !empty($atual['foto'])) {
		apaga_imagem($atual['foto']);
	}

	redireciona("index.php");
}

?>
