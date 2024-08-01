<?php
session_start();

require_once "old_conexao.php";

$iduser = $_SESSION["id_usuario"];

// Atualizando o status no banco de dados
$atualizar = "UPDATE usuarios set status_user = '0' where userid = '$iduser'";
$conexao->query($atualizar);

// Deletando a sessão
session_unset($_SESSION);
session_destroy();
header("Location: ../index.php");
