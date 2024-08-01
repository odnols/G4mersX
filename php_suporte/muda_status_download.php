<?php
// Fazendo os imports dos arquivos principais
include_once "verifica_sessao2.php";
include_once "old_conexao.php";
session_start();

$data = $_GET["data"];

if (isset($_SESSION["download_auto"]) || $_SESSION["download_auto"] == 0) {
    if ($_SESSION["download_auto"] == 0) {
        $_SESSION["download_auto"] = 1;
        $_SESSION["msg"] = "Ok, iremos iniciar um download para você toda vez que você criar um .txt";
    } else {
        $_SESSION["download_auto"] = 0;
        $_SESSION["msg"] = "Função de download automático desligado ;)";
    }
}
header("Location: estatistificado.php?data=$data");
