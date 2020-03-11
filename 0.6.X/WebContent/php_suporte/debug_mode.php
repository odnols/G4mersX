<?php session_start();

include_once "old_conexao.php";

$verifica = "SELECT * FROM usuarios where userid = '$_SESSION[id_usuario]'";
$executa_verificacao = $conexao->query($verifica);

var_dump($_SESSION["id_usuario"]);

var_dump($executa_verificacao);

if($executa_verificacao->num_rows > 0){
    while($dados = $executa_verificacao->fetch_assoc()){
        $debug = $dados["debug_mode"];
    }
}

if($debug == 0 || $debug == null){
    $atualiza_debug = "UPDATE usuarios set debug_mode = '1' where userid = '$_SESSION[id_usuario]'";
}else{
    $atualiza_debug = "UPDATE usuarios set debug_mode = '0' where userid = '$_SESSION[id_usuario]'";
}

$executa_atualizacao = $conexao->query($atualiza_debug);

header("Location: ../central.php");