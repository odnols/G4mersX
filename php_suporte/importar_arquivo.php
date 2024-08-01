<?php session_start();

// Abrindo Conexão
include_once "old_conexao.php";

$arquivo_tmp = $_FILES["arquivo"]["tmp_name"];

$contador = 0;
$datas = array();

// Começando a verificar se existe o dia no banco de dados
$recolhe = "SELECT * FROM balanco";

$resultado = $conexao->query($recolhe);

// Verifica se o banco de dados já possui arquivos registrados.
if ($resultado->num_rows > 0) {
	while ($linha = $resultado->fetch_assoc()) {
		$datas[$contador] = $linha["datablan"];
		$contador += 1;
	}
}

$contador = 0;
// Lendo o arquivo para um array
$dados = file($arquivo_tmp);

// Criando posições numa linha comparando se existe no banco e inserindo ou atualizando
foreach ($dados as $linha) {

	$linha = trim($linha);
	$valor = explode('¨s¨', $linha);

	$data = $valor[0];
	$id_user = $valor[1];
	$quantidade = $valor[2];

	// Verificando se existem dados para a hora, a descrição e a categoria
	if (strlen($valor[3]) == 0) {
		$hora_updt = null;
	} else {
		$hora_updt = $valor[3];
	}	// Hora de atualização
	if (strlen($valor[4]) == 0) {
		$verificado = null;
	} else {
		$verificado = $valor[4];
	} 	// Verificação
	if (strlen($valor[5]) == 0) {
		$categoria = null;
	} else {
		$categoria = $valor[5];
	}     // Categoria
	if (strlen($valor[6]) == 0) {
		$dia_marcado = null;
	} else {
		$dia_marcado = $valor[6];
	} // Nome do dia
	if ($valor[7] == "") {
		$nome_imagem = null;
	} else {
		$nome_imagem = $valor[7];
	}		// Nome da imagem
	if ($valor[8] == "") {
		$descricao = null;
	} else {
		$descricao = $valor[8];
	}			// Descrição do dia

	if ($resultado->num_rows > 0) {
		// Inserindo dias novos ou atualizando algum dia já instalado, e verificando se eles são históricos ou não
		if ($data != $datas[$contador]) {
			if (strlen($valor[6]) > 0 || strlen($valor[7]) > 0) {
				if (strlen($verificado) == 0) {
					$inserir = "INSERT INTO balanco (datablan, userid, qtdmens, hora_updt, verificado, categoria, dia_marcado, nome_imagem, descricao) values ('$data', '$id_user', '$quantidade', '$hora_updt', null, '$categoria', '$dia_marcado', '$nome_imagem', '$descricao')";
				} else {
					$inserir = "INSERT INTO balanco (datablan, userid, qtdmens, hora_updt, verificado, categoria, dia_marcado, nome_imagem, descricao) values ('$data', '$id_user', '$quantidade', '$hora_updt', '$verificado', '$categoria', '$dia_marcado', '$nome_imagem', '$descricao')";
				}
			} else {
				if (strlen($verificado) == 0) {
					$inserir = "INSERT INTO balanco (datablan, userid, qtdmens, hora_updt, verificado, categoria, dia_marcado, nome_imagem, descricao) values ('$data', '$id_user', '$quantidade', '$hora_updt', null, null, null, null, null)";
				} else {
					$inserir = "INSERT INTO balanco (datablan, userid, qtdmens, hora_updt, verificado, categoria, dia_marcado, nome_imagem, descricao) values ('$data', '$id_user', '$quantidade', '$hora_updt', '$verificado', null, null, null, null)";
				}
			}
		} else {
			if (strlen($valor[6]) > 0 || strlen($valor[7]) > 0) {
				if (strlen($verificado) == 0) {
					$inserir = "UPDATE balanco set qtdmens = '$quantidade', hora_updt = '$hora_updt', verificado = null, categoria = '$categoria', dia_marcado = '$dia_marcado', nome_imagem = '$nome_imagem', descricao = '$descricao' where datablan = '$data'";
				} else {
					$inserir = "UPDATE balanco set qtdmens = '$quantidade', hora_updt = '$hora_updt', verificado = '$verificado', categoria = '$categoria', dia_marcado = '$dia_marcado', nome_imagem = '$nome_imagem', descricao = '$descricao' where datablan = '$data'";
				}
			} else {
				if (strlen($verificado) == 0) {
					$inserir = "UPDATE balanco set qtdmens = '$quantidade', hora_updt = '$hora_updt', verificado = null, categoria = null, dia_marcado = null, nome_imagem = null, descricao = null where datablan = '$data'";
				} else {
					$inserir = "UPDATE balanco set qtdmens = '$quantidade', hora_updt = '$hora_updt', verificado = '$verificado', categoria = null, dia_marcado = null , nome_imagem = null, descricao = null where datablan = '$data'";
				}
			}
		}
		// Inserindo ou atualizando o banco de dados
		$executa = $conexao->query($inserir);
	} else {
		// Inserindo valores no novo banco de dados
		if (strlen($valor[6]) > 0 || strlen($valor[7]) > 0) {
			if (strlen($verificado) == 0) {
				$inserir = "INSERT INTO balanco (datablan, userid, qtdmens, hora_updt, verificado, categoria, dia_marcado, nome_imagem, descricao) values ('$data', '$id_user', '$quantidade', '$hora_updt', null, '$categoria', '$dia_marcado', '$nome_imagem', '$descricao')";
			} else {
				$inserir = "INSERT INTO balanco (datablan, userid, qtdmens, hora_updt, verificado, categoria, dia_marcado, nome_imagem, descricao) values ('$data', '$id_user', '$quantidade', '$hora_updt', '$verificado', '$categoria', '$dia_marcado', '$nome_imagem', '$descricao')";
			}
		} else {
			if (strlen($verificado) == 0) {
				$inserir = "INSERT INTO balanco (datablan, userid, qtdmens, hora_updt, verificado, categoria, dia_marcado, nome_imagem, descricao) values ('$data', '$id_user', '$quantidade', '$hora_updt', null, null, null, null, null)";
			} else {
				$inserir = "INSERT INTO balanco (datablan, userid, qtdmens, hora_updt, verificado, categoria, dia_marcado, nome_imagem, descricao) values ('$data', '$id_user', '$quantidade', '$hora_updt', '$verificado', null, null, null, null)";
			}
		}
		$executa = $conexao->query($inserir);
	}
	$contador += 1;
}
// Verificando se foi realizado a importação da célula de dados
if (isset($executa)) {
	$_SESSION['msg_impt'] = "Arquivo importado com sucesso!";
} else {
	$_SESSION['msg_impt'] = "<h5 style='color: red'>Não foi possivel importar o arquivo, verifique ele e tente novamente</h5>";
}

header("Location: ../central.php");
