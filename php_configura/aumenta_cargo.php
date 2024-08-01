<?php

session_start();
require_once "../php_suporte/old_conexao.php";

$userid = $_SESSION["id_usuario"];

$pegadados = "SELECT * FROM usuarios where userid = '$userid'";
$resultado = $conexao->query($pegadados);

while ($linha = $resultado->fetch_assoc()) {
	$nome_usuario = $linha["username"];
}

$solicitacao = "Solicitação de aumento de cargo para o usuario de id( " . $userid . " ) e nome( " . $nome_usuario . " ) ";

// Status_msg = 1 ( Em Andamento )
// Status_msg = 2 ( Em Observacao )
// Status_msg = 3 ( Fechado )

$envia_solicitacao = "INSERT INTO mensageria (mensagem, id_usuario, destinatario, status_msg) values ('$solicitacao', '$userid', '1' ,'1')";

$entra_msg = $conexao->query($envia_solicitacao);

header("Location: ../central.php");
