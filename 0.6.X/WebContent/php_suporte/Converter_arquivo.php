<?php
session_start();

// Abrindo Conexão
include_once "old_conexao.php";

$compacto = "../../Celula/";

$arquivo_tmp = $_FILES["conversao"]["tmp_name"];
$nome_arquivo = $_FILES["conversao"]["name"];
$categoria = null;
$descricao = null;

// Convertando o nome do arquivo bruto caso seja mensalmente
$nome_convertido = str_replace("Balanço de ", "", $nome_arquivo);
$ano_convertido = substr($nome_arquivo, -11, -7);
$ano_convertido = str_replace(".txt", "", $ano_convertido);
var_dump($ano_convertido);

// Convertendo o nome do arquivo bruto caso seja anualmente
$nome_arquivo = str_replace(".txt", "", $nome_arquivo);
$userid = 1;

// Lendo o arquivo para um array
$dados = file($arquivo_tmp);

// Verificando se o arquivo é anual
if(strlen($nome_arquivo) < 17){

// Criando o arquivo bruto
$file = fopen($compacto.$nome_arquivo.".txt", 'w');

// Criando posições na linha e criando o novo arquivo
foreach ($dados as $linha){

	$confirma = 70;

	// Retirando os textos do arquivo original
	$linha = str_replace("Em ( ", "", $linha);
	$linha = str_replace(" ) Foram Registradas : ", ", ", $linha);

	// Separando os dados por posição
	$convert = trim($linha);
	$valor = explode(',', $convert);

	// Convertendo o ano
	$data = str_replace("/", "-", $valor[0]);
	$data_minima = date('y', strtotime($data));
	$data = date('Y-m-d', strtotime($data));

	if(isset($valor[3])){ $hora_updt; }else{ $hora_updt = null; }
	if(isset($valor[4])){ $dia_marcado; }else{ $dia_marcado = null; }
	if(isset($valor[5])){ $nome_dia; }else{ $nome_dia = null; }
	
		if($data_minima != $confirma && $data != $data_anterior){
			$escreve = $data ."¨s¨".$userid."¨s¨".$valor[1]."¨s¨".$hora_updt."¨s¨".$categoria."¨s¨".$dia_marcado."¨s¨".$nome_dia."¨s¨".$descricao."\r\n";
			// Escrevendo os dias
			fwrite($file, $escreve);
			
		}
		$executa = 1;
		$data_anterior = $data;
	}
	// Fechando o arquivo
	fclose($file);

	if($executa = 1){
		$_SESSION['msg2'] = "Seu arquivo anual foi convertido com Sucesso!";
	}

}else{

// Criando o arquivo bruto caso seja mensalmente
$file = fopen($compacto.$nome_arquivo.".txt", 'w');

// Criando posições na linha e criando o novo arquivo
foreach ($dados as $linha){

	$confirma = 70;
	// Retirando os textos do arquivo original
	$linha = str_replace("Em ( ", "", $linha);
	$linha = str_replace(" ) Foram Registradas : ", ", ", $linha);

	// Retirando o ano do baçanço
	$ano = str_replace("Balanço de ","", $linha);
//	$ano = date('y-m-d', strtotime($ano));
	
//	$ano = $conversor .",". substr($ano, 3);
//	var_dump($ano);
	// Separando os dados por posição
	$convert = trim($ano);
	$valor = explode(',', $convert);

	$conversor = substr($valor[0], +2);
	$conversor = $ano_convertido ."". $conversor ."/".substr($valor[0], 0, 2);
	$conversor = str_replace("/", "-", $conversor);
//	var_dump($conversor." Conversor");

//	echo date('Y-m-d', strtotime($conversor))." Convertido<br>";
//	echo substr($conversor, 2,2)."<br>";

	if(isset($valor[3])){ $hora_updt; }else{ $hora_updt = null; }
	if(isset($valor[4])){ $dia_marcado; }else{ $dia_marcado = null; }
	if(isset($valor[5])){ $nome_dia; }else{ $nome_dia = null; }
//	echo " dd ".$confirma." Confirmação 1<br>"; 
//	var_dump($convert);
//	var_dump($data);
//	var_dump($conversor);
//	echo substr($data, 0, 4)." confirma";
//	echo substr($conversor, 2, 2);

	if($confirma != substr($conversor, 2, 2) && $conversor != $data_anterior && strlen($valor[0]) == 5){
		// Escrevendo os dias
		$escreve = $conversor ."¨s¨".$userid."¨s¨".$valor[1]."¨s¨".$hora_updt."¨s¨".$categoria."¨s¨".$dia_marcado."¨s¨".$nome_dia."¨s¨".$descricao."\r\n";
		fwrite($file, $escreve);
	}
		$executa = 1;
		$data_anterior = $conversor;
	}
	// Fechando o arquivo
	fclose($file);

	if($executa = 1){
		$_SESSION['msg2'] = "Seu arquivo mensal foi convertido com Sucesso!";
	}
}
header("Location: ../central.php");