<?php
include_once "../php_suporte/old_conexao.php";

$id_mensagem = $_GET["string"];

// Convertendo os valores da string de mensagem
$dados = str_replace("_", ",", $id_mensagem);

$status = trim($dados);
$status = explode(",", $status);

if (strpos($id_mensagem, "_")) {

    // 1 = Em Aberto, 2 = Em Andamento, 3 = Encerrado
    $atualiza = "UPDATE mensageria set status_msg = '$status[1]' where id_mensagem = '$status[0]'";
} else {
    // Apagando a mensagem
    $atualiza = "DELETE from mensageria where id_mensagem = '$status[0]'";
}

$executa = $conexao->query($atualiza);

header("Location: configura_central.php");
