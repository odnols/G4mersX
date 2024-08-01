<!DOCTYPE html>
<html>

<head>
	<!-- Bootstrap -->
	<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
	<link rel="stylesheet" type="text/css" href="../fonts/font-awesome/css/font-awesome.css">

	<!-- <script type="text/javascript">	
		window.onload = function(){

		setTimeout(function() {
    		window.location.href = "usuarios_on.php";
		}, 5000);
        }
	</script> -->
	<style type="text/css">
		#imagem_pequeno_on {
			width: 50px;
			height: 50px;
			border-radius: 50px;
			animation: usuario 1s;
			border: 2px solid white;
		}

		@keyframes usuario {
			0% {
				opacity: 0;
				box-shadow: inset 0px 0px 0px white;
				border: 10px solid black;
			}

			50% {
				opacity: 1;
				box-shadow: inset 0px 0px 300px white;
				border: 0px solid grey;
			}

			100% {
				box-shadow: inset 0px 0px 0px white;
				border: 2px solid white;
			}
		}

		#nome_usuario_on {
			color: white;
			position: absolute;
			left: 20%;
			text-shadow: 0.0rem 0.0rem 0.2rem black;
			animation: texto 1s;
			z-index: 1;
		}

		@keyframes texto {
			0% {
				opacity: 0;
			}

			100% {
				opacity: 1;
			}
		}
	</style>
</head>

<body>
	<?php
	require_once "../php_suporte/old_conexao.php";

	// Função para fornecer os dados do usuário
	$busca_usuario_on = "SELECT * from usuarios where status_user = '1' order by rand() limit 4";
	$online = $conexao->query($busca_usuario_on);

	if ($online->num_rows > 0) {
		while ($dados = $online->fetch_assoc()) {

			$nome_user = $dados["username"];
			$nome_imagem = $dados["nome_foto"];

			if ($online->num_rows == 1) {
				echo "<h4 id='nome_usuario_on'>$nome_user</h4>";
			}

			if (strlen($nome_imagem) > 0) {
				echo "<img id='imagem_pequeno_on' src='../img/perfil/$nome_imagem'>";
			} else {
				echo "<img id='imagem_pequeno_on' src='../img/icone/usuario.png'>";
			}
		}
	} ?>
</body>

</html>