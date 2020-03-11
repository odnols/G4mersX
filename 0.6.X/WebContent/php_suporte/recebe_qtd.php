<?php
include_once "old_conexao.php";

$dia = $_POST["data_dia"];
$iduser = $_POST["iduser"];
$qtd = $_POST["qtdmensagem"];
$hora = $_POST["hora_att"];
$valor_old = 0;
$data_atualiza = 0;
$valor_atualizado = 0;
if(isset($_POST["dia_import"])){ $dia_importante = $_POST["dia_import"]; } else { $dia_importante = null; }
if(isset($_POST["operador"])){ $operacao = 1; }else{ $operacao = 0; } // 0 -> Novo | 1 -> Somar

if($operacao == 0){
	// Registrando novo valor
	$operacao = "Novo valor";
    $recolhe = "SELECT * FROM balanco where datablan like '$dia'";

	$result = $conexao->query($recolhe);
	// Verificando se existem entradas com o respectivo dia
	if($result->num_rows > 0){
		while($linha = $result->fetch_assoc()){
			$categoria = $linha["categoria"];
			$dia_importante_banco = $linha["dia_marcado"];
		}

		if(strlen($dia_importante) > 0 ){
			// Atualizando um dia já registrado com valores novos
			$inserir = "UPDATE balanco set qtdmens = $qtd, hora_updt = '$hora', dia_marcado = '$dia_importante' where datablan = '$dia'";
		}else if(strlen($dia_importante_banco) > 0){
			$inserir = "UPDATE balanco set qtdmens = $qtd, hora_updt = '$hora' where datablan = '$dia'";
		}else{
			$inserir = "UPDATE balanco set qtdmens = $qtd, hora_updt = '$hora', dia_marcado = null where datablan = '$dia'";
		}
	}else{
		if(strlen($dia_importante) > 0){
			// Adicionando novo registro
			$inserir = "INSERT into balanco (datablan, userid, qtdmens, hora_updt, dia_marcado) values ('$dia', $iduser, $qtd, '$hora', '$dia_importante')";
		}else{
			$inserir = "INSERT into balanco (datablan, userid, qtdmens, hora_updt) values ('$dia', $iduser, $qtd, '$hora')";
		}
	}
	// Executando a inserção no banco de dados
	$executa_add = $conexao->query($inserir);
}else{
	// Somando o valor 
	// Buscando os valores no banco
	$operacao = "Somando";
	$busca_dia = "SELECT * From balanco where datablan like '$dia'";
	
	$executa_busca = $conexao->query($busca_dia);

	if($executa_busca ->num_rows > 0 ){
		while($linha = $executa_busca->fetch_assoc()){
			$data_atualiza = $linha["datablan"];
			$valor_old = $linha["qtdmens"];
			$categoria = $linha["categoria"];
		}
	}else{
		if(strlen($dia_importante) > 0){
			// Somando no banco, caso não haja dados anteriores e marcando dias históricos *( Com a opção de somar selecionada )
			$atualiza_valor = "INSERT into balanco (datablan, userid, qtdmens, hora_updt, dia_marcado) values ('$dia', '$iduser', '$qtd', '$hora', '$dia_importante')";
		}else{
			$atualiza_valor = "INSERT into balanco (datablan, userid, qtdmens, hora_updt) values ('$dia', '$iduser', '$qtd', '$hora')";
		}
	}

	$valor_atualizado = $valor_old + $qtd;
	//Conferindo se há dados no Dia Histórico
	if(strlen($dia_importante) > 0){
		// Atualizando os valores antigos somando com os novos e inserindo no banco
		$atualiza_valor = "UPDATE balanco set qtdmens = $valor_atualizado, hora_updt = '$hora', dia_marcado = '$dia_importante' where datablan = '$dia'";
	}else if(strlen($categoria) > 0 && strlen($dia_importante) > 0){
		$atualiza_valor = "UPDATE balanco set qtdmens = $valor_atualizado, hora_updt = '$hora', dia_marcado = null where datablan = '$dia'";
	}else{
		$atualiza_valor = "UPDATE balanco set qtdmens = $valor_atualizado, hora_updt = '$hora' where datablan = '$dia'";
	}
	$executa_att = $conexao->query($atualiza_valor);
}

header("Location: ../central.php");