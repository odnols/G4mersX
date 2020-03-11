<?php session_start();

include_once "old_conexao.php";

$data = $_GET["data"];

$data_arrumada = date('Y-m-d', strtotime($data));

$verifica_data = "SELECT * from balanco where datablan like '$data_arrumada'";
$executa_verificacao = $conexao->query($verifica_data);

if($executa_verificacao->num_rows > 0){
    while($dados = $executa_verificacao->fetch_assoc()){
        $estado = $dados["verificado"];
    }
}
// Verificando se existe valor e somando mais um digito ao estado
if($estado == null){
    $estado = 1;
}else if($estado == 5){
    $estado = 0;
}else{
    $estado += 1;
}

$atualiza = "UPDATE balanco set verificado = '$estado' where datablan = '$data_arrumada'";
$executa_atualizacao = $conexao->query($atualiza);

$_SESSION["prioridade_display"] = 1;

header("Location: ../central.php");