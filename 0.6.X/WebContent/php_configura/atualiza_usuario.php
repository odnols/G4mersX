<?php

include_once "../php_suporte/old_conexao.php";
include_once "../php_suporte/verifica_sessao.php";

$userid = $_POST["iduser"];
if(isset($_POST["adm_add"])){ $hierarquia = 1; }else{ $hierarquia = 0; }

$atualiza = "UPDATE usuarios set hierarquia = '$hierarquia' where userid = '$userid'";

$executa = $conexao->query($atualiza);

if($executa){
    $_SESSION["msg_salva"] = "O Usuário foi atualizado";
}
var_dump($Atualiza);
header("Location: configura_usuario.php?id_user=". $userid);