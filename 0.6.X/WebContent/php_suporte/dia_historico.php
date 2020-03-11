<?php

include_once "old_conexao.php";
include_once "verifica_sessao.php";

$data = $_POST["data"];
$arq_name = $_FILES["arq"]["name"]; //O nome do ficheiro
$arq_size = $_FILES["arq"]["size"]; //O tamanho do ficheiro
$arq_tmp = $_FILES["arq"]["tmp_name"]; //O nome temporário do arquivo
$descricao = $_POST["descricao"];

// Verificando se é dia bom(1) ou ruim(0)
if(!isset($_POST["categoria"])){
	$categoria = 1;
}else{
	$categoria = 0;
}

// Conferindo qual o caso para inserir na base de dados
// Conferindo qual o caso para inserir no banco de dados
if(isset($arq_name) && $arq_size > 0){
	if(strlen($descricao) > 0){
		$inserindo = "UPDATE balanco set nome_imagem = '$arq_name', descricao = '$descricao', categoria = '$categoria' where datablan = '$data'";
	}else{
		$inserindo = "UPDATE balanco set nome_imagem = '$arq_name', categoria = '$categoria' where datablan = '$data'";
	}
}else{
	if(strlen($descricao) > 0){
		$inserindo = "UPDATE balanco set descricao = '$descricao', categoria = '$categoria' where datablan = '$data'";
	}else{
		$inserindo = "UPDATE balanco set categoria = '$categoria' where datablan = '$data'";
	}
}

// Inserindo os dados na base de dados
$executa_add = $conexao->query($inserindo);

//Aqui Grava a imagem a diretória desejada, na esquecer de dar as permissões no servido
move_uploaded_file($arq_tmp, "C:\wamp64\www\G4mers\WebContent\img\histórico/".$arq_name);

if($executa_add){
  $_SESSION["msg_historic"] = "<h5 style='color: white' id='msg_updte'>Dia histórico atualizado com sucesso, você pode atualizar quantas vezes quiser!</h5>";
}
header("Location: ../estedia.php?data=".$data);