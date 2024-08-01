<?php session_start();

include_once "old_conexao.php";
$data = $_GET["data"];

$deletar = "UPDATE balanco SET categoria = null, dia_marcado = null, nome_imagem = null, descricao = null where datablan = '$data'";

$executa = $conexao->query($deletar);

if ($executa) {
	$_SESSION['msg_his'] = "<hr style='position: absolute; left: 27%;'><br><em><h3 style='color: red'>As marcações de dia histórico foram removidas :)</h3></em>";
}

header("Location: ../central.php#click_historico");
