<!DOCTYPE html>
<html>
<head>
<link rel="shortcut icon" href="../img/Icone/gamerx.png">

<!-- CSS -->
	<link rel="stylesheet" type="text/css" href="../css/style_sol.css">

<!-- Bootstrap -->
	<link rel="stylesheet" type="text/css" href="../css/bootstrap.css">
	<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
	<link rel="stylesheet" type="text/css" href="../fonts/font-awesome/css/font-awesome.css">

<?php 
session_start();

$data = $_GET["data"];
$total = 0;
$total_msg = "O Total foi de : ";
$caminho = "../../Balanço/";
$compacto = "../../Celula/";
$executado = 0; ?>

<title>Aguarde...</title>
	<style type="text/css">
		body{
			background-color: black;
			align-items: center;
			text-align: center;
		}
	</style>
</head>
<body>

<div id="loading">
	<img class="loadingif" src="../img/Especiais/loading.gif">
	<h2 style="color: white"><em>Transferindo os valores para o bloco de notas...</em></h2>
</div>
<?php

// Convertendo a data recebida
$data_convertida = str_replace(".", "-", $data);

if(strlen($data_convertida) > 4){
	$nome_arquivo = "Balanço de ".date('Y-m', strtotime($data_convertida));
	$nome2 = "Balanço de ".date('m-Y', strtotime($data_convertida))."\r\n";
}else{
	$nome_arquivo = "Balanço de ".$data;
	$nome2 = "Balanço do Ano de ".$data."\r\n";
}

	// Abrindo conexão com o banco
	include_once "old_conexao.php";

	$recolhe = "SELECT * FROM balanco where datablan LIKE '$data_convertida%'";

	$resultado = $conexao->query($recolhe);

	// Criando o arquivo visuavelmente arrumado
	$file = fopen($caminho.$nome_arquivo.".txt", 'w');
	// Verificando se existem registros para a data
	if($resultado->num_rows > 0){

	// Abrindo conexão com o arquivo e inserindo informações
		fwrite($file, $nome2);

		while($linha = $resultado->fetch_assoc()){
			if(strlen($data_convertida) != 4){
				$dia = "Em ( ". date('d/m', strtotime($linha['datablan'])) ." ) Foram Registradas : ". $linha['qtdmens']."\r\n";
			}else{
				$dia = "Em ( ". date('d/m/Y', strtotime($linha['datablan'])) ." ) Foram Registradas : ". $linha['qtdmens']."\r\n";
			}
			// Calculando o total
			$total = $linha['qtdmens'] + $total;

			// Escrevendo os dias
			fwrite($file, $dia);
		}
		// Escrevendo o total
		fwrite($file, $total_msg);
		fwrite($file, $total);

		// Fechando o arquivo
		fclose($file);
		$executado = 1;
	}

	// Verifica se foi criado o primeiro arquivo para começar a fazer a célula de dados
	if($executado = 1){
	$recolhe2 = "SELECT * FROM balanco where datablan LIKE '$data_convertida%'";

	$resultado2 = $conexao->query($recolhe2);

	// Criando o arquivo bruto
	$file_compact = fopen($compacto.$nome_arquivo.".txt", 'w');
	// Verificando se existem registros para a data
	if($resultado2->num_rows > 0){

	// Aberta a conexão com o banco, inserindo informações na célula de dados
		while($linha2 = $resultado2->fetch_assoc()){
			$dia2 = $linha2['datablan'] ."¨s¨". $linha2['userid'] ."¨s¨". $linha2['qtdmens'] ."¨s¨". $linha2['hora_updt'] ."¨s¨". $linha2['verificado'] ."¨s¨". $linha2['categoria'] ."¨s¨". $linha2['dia_marcado']."¨s¨". $linha2['nome_imagem']."¨s¨". $linha2['descricao']."\r\n";
			
			// Escrevendo os dias
			fwrite($file_compact, $dia2);
		}
		// Fechando a célula de dados
		fclose($file_compact);

		$_SESSION['msg'] = "Foi criado um arquivo padrão e uma célula de dados na pasta do projeto";
		}
	}

	$arquivo = $compacto."".$nome_arquivo.".txt"; ?>
	<script type="text/javascript">	
		window.onload = function(){
		
<?php if(isset($_SESSION["download_auto"])){
 		if($_SESSION["download_auto"] == 1){ // Verifica se a opção de download automatico está ativada ?>
		setTimeout(function() {
			window.location.href = "baixar_arquivo.php?data=<?php echo $data; ?>?<?php echo $arquivo?>";
		}, 1000);
	<?php } } ?>
		
		setTimeout(function() {
			window.location.href = "estatistificado.php?data=<?php echo $data; ?>";
		}, 1050);
        }
	</script>
</body>
</html>