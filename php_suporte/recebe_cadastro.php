<?php include_once "old_conexao.php";

$nome_usuario = $_POST["nome"];
$senha = $_POST["senha"];
$email = $_POST["email"];
$iduser = $_POST["id"];
$hierarquia = 0;

// Verificando se não existe usuário com o mesmo id
$verifica = "SELECT * FROM usuarios";
$executa = $conexao->query($verifica);

if ($executa->num_rows > 0) {
	while ($existe = $executa->fetch_assoc()) {
		if ($email != $existe["email"]) {
			$cadast = "INSERT INTO usuarios (email, userid, username, senha, hierarquia) values ('$email', '$iduser', '$nome_usuario', '$senha', $hierarquia)";

			$executa_cadastro = $conexao->query($cadast);
		}
	}
}

$query = "SELECT * FROM usuarios where email = '$email' and senha = '$senha';";
$resultado = $conexao->query($query);

if ($resultado->num_rows > 0) {
	while ($linha = $resultado->fetch_assoc()) {

		$email = $linha["email"];
		$iduser = $linha["userid"];

		session_start();
		$_SESSION["email"] = $linha["email"];
		$_SESSION["id_usuario"] = $linha["userid"];
		$_SESSION["hierarquia"] = $linha["hierarquia"];

		$_SESSION["logado"] = 1;

		$atualizar = "UPDATE usuarios set status_user = '1' where userid = '$iduser'";
		$conexao->query($atualizar);

		header("Location: ../central.php?id=" . $iduser . "");
		$_SESSION["msg_cadstro"] = "<strong>Além do seu email, anote seu (" . $iduser . "), Ele será usado em outras áreas do sistema.</strong>";
	}
}
