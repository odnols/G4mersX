<?php

$email = $_POST["email"];
$senha = $_POST["senha"];
$contador = 0;

require_once "old_conexao.php";

$query = "SELECT * FROM usuarios";

$resultado = $conexao->query($query);

if ($resultado->num_rows > 0) {
	while ($linha = $resultado->fetch_assoc()) {

		//  Verifica se o email ou o id ou o telefone corresponte e se a senha está correta
		if ($email == $linha["email"] && $senha == $linha["senha"] || $email == $linha["userid"] && $senha == $linha["senha"] || $email == $linha["numerotel"] && $senha == $linha["senha"]) {
			if ($linha["status_user"] == 0 || $linha["status_user"] == null) {

				$contador = 1;
				$email = $linha["email"];
				$iduser = $linha["userid"];
				$status_user = $linha["status_user"];

				session_start();
				$_SESSION["email_user"] = $linha["email"];
				$_SESSION["id_usuario"] = $linha["userid"];
				$_SESSION["telefone"]  =  $linha["numerotel"];
				$_SESSION["hierarquia"] = $linha["hierarquia"];
				$_SESSION["color_back"] = $linha["preferencia"];
				$_SESSION["data_nasce"] = $linha["data_nascimento"];

				$atualizar = "UPDATE usuarios set status_user = '1' where userid = '$iduser'";
				$conexao->query($atualizar);

				$_SESSION["logado"] = 1;
				header("Location: ../central.php");
			} else {
				header("Location: ../index.php?ERROR=002");
			}
		} else if ($contador == 0) {
			header("Location: ../index.php?ERROR=001");
		}
	}
}
