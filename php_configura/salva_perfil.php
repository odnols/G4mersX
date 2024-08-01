<?php

include_once "../php_suporte/verifica_sessao.php";
include_once "../php_suporte/old_conexao.php";

$iduser = $_POST["iduser"];
$arq_name = $_FILES["arq"]["name"];    //O nome do ficheiro
$arq_size = $_FILES["arq"]["size"];    //O tamanho do ficheiro
$arq_tmp = $_FILES["arq"]["tmp_name"]; //O nome temporário do arquivo
$telefone = $_POST["telefone"];
$data_nasce = $_POST["data_nascimento"];

if (isset($_POST["color_radio"])) {
	$preferencia = $_POST["color_radio"];
} else {
	$preferencia = 0;
}; // Cor de Fundo

// Conferindo qual o caso para inserir no banco de dados
// Inserindo no banco com uma imagem nova
if (strlen($arq_name) > 1 && $arq_size > 0) {
	if (strlen($telefone) >= 0 && isset($telefone)) {
		if (strlen($data_nasce) > 1) {
			if (strlen($preferencia) > 1) {
				$inserindo = "UPDATE usuarios set nome_foto = '$arq_name', numerotel = '$telefone', preferencia = '$preferencia', data_nascimento = '$data_nasce' where userid = '$iduser'";
			} else {
				$inserindo = "UPDATE usuarios set nome_foto = '$arq_name', numerotel = '$telefone', data_nascimento = '$data_nasce' where userid = '$iduser'";
			}
		} else {
			$inserindo = "UPDATE usuarios set nome_foto = '$arq_name', numerotel = '$telefone' where userid = '$iduser'";
		}
	} else {
		$inserindo = "UPDATE usuarios set nome_foto = '$arq_name' where userid = '$iduser'";
	}
	// Inserindo no banco sem uma imagem nova
} else {
	if (strlen($telefone) > 0 && isset($telefone)) {
		if (strlen($data_nasce) > 0 && isset($data_nasce)) {
			if (strlen($preferencia) > 1) {
				$inserindo = "UPDATE usuarios set numerotel = '$telefone', preferencia = '$preferencia', data_nascimento = '$data_nasce' where userid = '$iduser'";
			} else {
				$inserindo = "UPDATE usuarios set numerotel = '$telefone', data_nascimento = '$data_nasce' where userid = '$iduser'";
			}
		} else if (strlen($preferencia) > 1) {
			$inserindo = "UPDATE usuarios set preferencia = '$preferencia' where userid = '$iduser'";
		} else {
			$inserindo = "UPDATE usuarios set numerotel = '$telefone' where userid = '$iduser'";
		}
	}

	if (strlen($telefone) == 0 && strlen($preferencia) == 1) {
		$inserindo = "UPDATE usuarios set numerotel = null where userid = '$iduser'";
	}
	if (strlen($data_nasce) == 0 && strlen($preferencia) == 1 && strlen($telefone) == 0) {
		$inserindo = "UPDATE usuarios set data_nascimento = null where userid = '$iduser'";
	}
}
// Inserindo os dados na base de dados
$executa_add = $conexao->query($inserindo);
var_dump($telefone);
var_dump($preferencia);
var_dump($inserindo);
// Faz uma cópia da imagem enviada para o sistema 
move_uploaded_file($arq_tmp, "C:\wamp64\www\G4mers\WebContent\img\perfil/" . $arq_name);

if ($executa_add) {
	// Verifica se foi adicionado novos dados ou não
	if (strlen($arq_name) == 0 && strlen($preferencia) == 1 && $telefone == $_SESSION["telefone"] && $data_nasce == $_SESSION["data_nasce"]) {
		$_SESSION["msg_user"] = "Sem mudanças por hoje! :P";
	} else {
		$_SESSION["msg_user"] = "Perfil atualizado!";
	}
	// Verifica se a cor tem valores, se tiver atualiza a cor da sessão  
	if (strlen($preferencia) > 1) {
		$_SESSION["color_back"] = $preferencia;
	}
	// Verifica se a data de nascimento alterou
	if ($_SESSION["data_nasce"] != $data_nasce) {
		$_SESSION["data_nasce"] = $data_nasce;
	}
	// Verifica se o número de telefone foi alterado
	if ($telefone != $_SESSION["telefone"]) {
		$_SESSION["telefone"] = $telefone;
	}
}
header("Location: editar_perfil.php");
