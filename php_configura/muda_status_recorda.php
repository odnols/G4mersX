<?php
session_start();
include_once "../php_suporte/old_conexao.php";

$estado = $_GET["estado"];
$userid = $_SESSION["id_usuario"];

if ($estado == "undefined" || $estado == "0") {
    $estado = "UPDATE usuarios set recorda = '1' where userid = $userid";
    $_SESSION["msg_user"] = "Entendido, vamos alertar todos!";
} else {
    $estado = "UPDATE usuarios set recorda = '0' where userid = $userid";
    $_SESSION["msg_user"] = "Ok, sem alertas de dias marcados";
}

$executa = $conexao->query($estado);

header("Location: editar_perfil.php");
