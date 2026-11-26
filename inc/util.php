<?php
/** Funções de apoio usadas por mais de um módulo (revistas, clientes, usuários) */

const MSG_IMAGEM_INVALIDA = 'Imagem inválida. Use JPG, PNG, GIF ou WEBP de até 5 MB.';

/**
 *  Formatar as datas
 */
function formatData($data, $formato)
{
	$dt = new Datetime($data, new DateTimeZone("America/Sao_Paulo")); // "-0300"
	return $dt->format($formato);
}

/**
 *  Endereço (URL) de uma imagem da pasta img/; sem imagem usa a SemImagem.png
 */
function imagem_url($foto)
{
	$arquivo = empty($foto) ? "SemImagem.png" : rawurlencode($foto);
	return htmlspecialchars(BASEURL . "img/" . $arquivo);
}

/**
 *  Envio de imagem para a pasta img/.
 *  Retorna: null   -> nenhuma imagem enviada
 *           false  -> imagem inválida (tipo errado, grande demais ou falha no envio)
 *           string -> nome do arquivo salvo
 */
function upload_imagem($prefixo = 'img_', $campo = 'foto')
{
	if (empty($_FILES[$campo]['name']) || $_FILES[$campo]['error'] === UPLOAD_ERR_NO_FILE) {
		return null;
	}

	if ($_FILES[$campo]['error'] !== UPLOAD_ERR_OK || $_FILES[$campo]['size'] > 5 * 1024 * 1024) {
		return false;
	}

	$permitidas = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
	$extensao = strtolower(pathinfo($_FILES[$campo]['name'], PATHINFO_EXTENSION));

	// getimagesize() devolve false quando o arquivo não é uma imagem de verdade
	if (!in_array($extensao, $permitidas, true) || !@getimagesize($_FILES[$campo]['tmp_name'])) {
		return false;
	}

	// nome único: uma imagem nova nunca sobrescreve a de outro registro
	$arquivo = uniqid($prefixo) . '.' . $extensao;
	if (!move_uploaded_file($_FILES[$campo]['tmp_name'], ABSPATH . 'img/' . $arquivo)) {
		return false;
	}

	return $arquivo;
}

/**
 *  Apaga uma imagem enviada pelo sistema (capa_* ou cliente_*).
 *  As imagens do próprio projeto (fundo, ícone, capas de exemplo) nunca são apagadas.
 */
function apaga_imagem($arquivo)
{
	if (!preg_match('/^(capa_|cliente_)[A-Za-z0-9]+\.(jpe?g|png|gif|webp)$/i', (string) $arquivo)) {
		return;
	}

	$caminho = ABSPATH . 'img/' . $arquivo;
	if (is_file($caminho)) {
		@unlink($caminho);
	}
}
