<?php session_start();

include_once "old_conexao.php";

$data = $_POST["data_dia"];
$telefone = $_POST["telefone_user"];
$tamanho = count($_POST["telefone_user"]);
$quantidade = $_POST["qtd_atribuida"];
$i = 0;
$validador = 0;
$posicao = 0;

// Verifica se já existem registros no banco de Atribuição para a data atual
$verifica = "SELECT * FROM atribuicao_diaria where datablan = '$data'";
$executa_verificacao = $conexao->query($verifica);

if($executa_verificacao->num_rows > 0){
    while($dados = $executa_verificacao->fetch_assoc()){
        if($telefone[$i] == $dados["telefone"]){
            $validador += 1;
            $id_atribuicao[$posicao] = $dados["id_atribui"];
        }
        $posicao += 1;
        $i += 1;
    }
}else{
    while($i < $tamanho){
        $inserindo = "INSERT INTO atribuicao_diaria (telefone, quantidade, datablan) values ('$telefone[$i]', '$quantidade[$i]', '$data')";
        $i += 1;
    }
}

$posicao = 0;
// Percorre o vetor todo verificando se existem entradas válidas e repetidas
for($i = 0; $i < $tamanho; $i++){
    if($validador != 0){
        $inserindo = "UPDATE atribuicao_diaria set quantidade = '$quantidade[$i]' where id_atribui = '$id_atribuicao[$posicao]'";
        $posicao += 1;
    }else{
        $inserindo = "INSERT INTO atribuicao_diaria (telefone, quantidade, datablan) values ('$telefone[$i]', '$quantidade[$i]', '$data')";
    }
    // Executando a inserção dos dados
    $executa_insercao = $conexao->query($inserindo);
}

if($executa_insercao){
    $_SESSION["msg_atribui"] = "Atribuições Atualizadas, Obrigado!";
}
// header("Location: ../central.php");