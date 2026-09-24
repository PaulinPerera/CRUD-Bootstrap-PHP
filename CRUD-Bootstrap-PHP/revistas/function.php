<?php
ob_start(); //output buffer aberto, para não dar erro de header location

include('../config.php');
include(DBAPI);

$revistas = null;
$revista = null;
$filtro = "";

/**
 *  Formatar as datas
 */
function formatData($data, $formato)
{
	$dt = new Datetime($data, new DateTimeZone("America/Sao_Paulo"));// "-0300"
	return $dt->format($formato);
}

/**
 *  Endereço (URL) da capa; revista sem imagem usa a SemImagem.png
 */
function capa($foto)
{
	$arquivo = empty($foto) ? "SemImagem.png" : rawurlencode($foto);
	return htmlspecialchars(BASEURL . "img/" . $arquivo);
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
 *  Envio da imagem da capa para a pasta img/.
 *  Retorna o nome do arquivo salvo ou null (nenhuma imagem enviada / arquivo inválido).
 */
function upload_capa()
{
	if (empty($_FILES['foto']['name']) || $_FILES['foto']['error'] !== UPLOAD_ERR_OK) {
		return null;
	}

	$permitidas = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
	$extensao = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));

	// getimagesize() devolve false quando o arquivo não é uma imagem de verdade
	if (!in_array($extensao, $permitidas) || !getimagesize($_FILES['foto']['tmp_name'])) {
		return null;
	}

	// nome único: uma capa nova nunca sobrescreve a de outra revista
	$arquivo = uniqid('capa_') . '.' . $extensao;
	move_uploaded_file($_FILES['foto']['tmp_name'], ABSPATH . 'img/' . $arquivo);

	return $arquivo;
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
	$revista = find('tabelarevista', $id);
}

/**
 *  Cadastro de Revistas
 */
function add()
{
	if (!empty($_POST['revista']) && is_array($_POST['revista'])) {

		$today = new DateTime('now', new DateTimeZone('America/Sao_Paulo'));

		$revista = dados_form();
		$revista['datacadastro'] = $today->format("Y-m-d H:i:s");
		$revista['foto'] = upload_capa() ?? ""; // sem imagem: fica vazio e a listagem mostra a SemImagem.png

		save('tabelarevista', $revista); // 'tabelarevista' -> nome da tabela; $revista -> associative array
		header('location: index.php'); // output buffer
		exit;
	}
}

/**
 *	Atualizacao/Edicao de Revista
 */
function edit()
{
	global $revista;

	if (isset($_GET['id'])) {

		$id = $_GET['id'];

		if (!empty($_POST['revista']) && is_array($_POST['revista'])) {

			$dados = dados_form();

			// só troca a capa se uma nova imagem foi enviada; senão a atual continua
			$foto = upload_capa();
			if ($foto) {
				$dados['foto'] = $foto;
			}

			update("tabelarevista", $id, $dados);
			header("location: index.php");
			exit;
		} else {

			$revista = find("tabelarevista", $id);
		}
	} else {
		header("location: index.php");
		exit;
	}
}

/**
 *  Exclusão de uma Revista
 */
function delete($id = null)
{
	remove('tabelarevista', $id);

	header("location: index.php");
	exit;
}

?>
