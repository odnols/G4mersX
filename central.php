<!DOCTYPE html>
<html lang="pt-BR">

<head>
	<title>Central</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="shortcut icon" href="img/Icone/gamerx.png">

	<!-- CSS -->
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link rel="stylesheet" type="text/css" href="css/input.css">
	<link rel="stylesheet" type="text/css" href="css/responsividade.css">
	<link rel="stylesheet" type="text/css" href="css/textos.css">
	<link rel="stylesheet" type="text/css" href="css/historicalissimo.css">

	<!-- Bootstrap -->
	<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
	<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
	<link rel="stylesheet" type="text/css" href="fonts/font-awesome/css/font-awesome.css">
	<link rel="stylesheet" type="text/css" href="web-fonts-with-css/css/font-awesome-all.min.css">

	<script language="JavaScript">
		function pegadata($data_) {
			window.location.href = "php_suporte/estatistificado.php?data=" + $data_;
		}

		function atualiza_dia_verifica(dia_verifica, mes_verifica, ano_verifica) {
			if (confirm("Deseja atualizar o status do dia " + dia_verifica + "-" + mes_verifica + "-" + ano_verifica + " ?")) {
				window.location.href = "php_suporte/atualiza_status_dia.php?data=" + dia_verifica + "-" + mes_verifica + "-" + ano_verifica;
			}
		}
	</script>
</head>

<body><?php

		// Fazendo os imports dos arquivos principais
		include_once "php_suporte/verifica_sessao.php";
		include_once "php_suporte/old_conexao.php";

		$userid = $_SESSION["id_usuario"];
		$hierarquia_usuario = $_SESSION["hierarquia"];

		date_default_timezone_set('America/Sao_Paulo');

		$userid = $_SESSION["id_usuario"];
		$contador_mes = 1;
		$anos_anteriores = 0;
		$dias_historicos = 0;
		$dia_especial = "";
		$date = date('Y-m-d');
		$date2 = date('d/m/Y');
		$date3 = date('d/m');
		$date4 = date('Y-m');
		$date5 = date('Y');
		$qtd_hj = 0;
		$hj_na_hist = 0;
		$coringa = date('Y');
		$total = "1000";
		$total_msg = 0;
		$mes = array('', 'Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro');

		// Função para fornecer os dados do dia atual
		$busca_dia = "SELECT * From balanco where datablan = '$date';";
		$executa_busca = $conexao->query($busca_dia);

		if ($executa_busca->num_rows > 0) {
			while ($linha = $executa_busca->fetch_assoc()) {
				$qtd_hj = $linha["qtdmens"];
				$hora_old = $linha["hora_updt"];
				$dia_especial = $linha["dia_marcado"];
				$nome_imagem_especial = $linha["nome_imagem"];
			}
		}

		// Função para verificar se existe dados para o ano atual
		$busca_ano = "SELECT * From balanco where datablan like '$date5-%'";
		$executa_busca2 = $conexao->query($busca_ano);

		// Função para fornecer os dados do usuário
		$busca_usuario = "SELECT * from usuarios where userid = '$userid'";
		$executa_usuario = $conexao->query($busca_usuario);

		if ($executa_usuario->num_rows > 0) {
			while ($dados = $executa_usuario->fetch_assoc()) {
				$nome_user = $dados["username"];
				$numero_telefone = $dados["numerotel"];
				$nome_imagem = $dados["nome_foto"];
				$preferencia = $dados["preferencia"];
				$recorda = $dados["recorda"];
				$debug_mode = $dados["debug_mode"];
			}
		}

		// Função para contar quantos dias se superaram, dias históricos e total de mensagens
		$hoje_ = "SELECT * FROM balanco";
		$executa_hj = $conexao->query($hoje_);

		if ($executa_hj->num_rows > 0) {
			while ($hoje_dia = $executa_hj->fetch_assoc()) {
				if ($qtd_hj > $hoje_dia["qtdmens"]) {
					$hj_na_hist = $hj_na_hist + 1;
				}
				// Contando a quantidade de dias históricos no sistema
				if ($hoje_dia["dia_marcado"] != null) {
					$dias_historicos = $dias_historicos + 1;
				}
				// Contando o total de mensagens
				$total_msg += $hoje_dia["qtdmens"];

				// Verificando se existe anos anteriores registrados
				$menor_ano = date("Y", strtotime($hoje_dia["datablan"]));
				if ($coringa > $menor_ano) {
					$anos_anteriores = 1;
				}
			}
		}

		if ($debug_mode == 1) {
			if ($preferencia != "#F8E910") {
				$cor_texto = sprintf('
			<style type="text/css">
			#debug_mode{
				color: yellow;
				box-shadow: 0rem 0rem 2rem yellow;
				animation: anima_sombra 5s infinite;
			}</style>');
			} else {
				$cor_texto = sprintf('
			<style type="text/css">
			#debug_mode{
				box-shadow: 0rem 0rem 2rem black;
				animation: anima_sombra_black 5s infinite;
			}</style>');
			}
			echo $cor_texto;

			$cor_texto = sprintf('
		<style type="text/css">
		#historico_prancheta_add{
			color: grey;
		}
		#historico_prancheta_add:hover{
			text-shadow: 0rem 0rem .3rem black;
		}</style>');
			echo $cor_texto;
		}

		// Verificar se é necessário dar prioridade a uma tela
		if (isset($_SESSION["prioridade_display"]) && $_SESSION["prioridade_display"] == 1) {
			$prioriza_display = sprintf('
		<style type="text/css">
		#quadro_add{
			display: none;
		}
		#quadro_add_historico_dia{
			display: block;
		}</style>');
			echo $prioriza_display;
			unset($_SESSION["prioridade_display"]);
		}

		if ($qtd_hj == 0) {
			$cor_texto = sprintf('
		<style type="text/css">
		#usuarios_prancheta_add{
			color: grey;
		}
		#usuarios_prancheta_add:hover{
			text-shadow: 0rem 0rem .3rem black;
		}</style>');
			echo $cor_texto;
		}

		function recorda_dia($dataFinal, $nome_dia, $data_dia, $nome_imagem_historic)
		{
			if ($dataFinal % 30 == 0 || $dataFinal == 365) {
				if (strlen($nome_imagem_historic) > 0) {
					if ($_SESSION["hierarquia"] == 1) {
						$fundo_especial = sprintf('
						<style type="text/css">
						#add_dia{
							background: url("img/histórico/%s");
							background-repeat: no-repeat;
							background-size: 1350px 700px;
						}</style>', $nome_imagem_historic);
					} else {
						$fundo_especial = sprintf('
						<style type="text/css">
						#estatisticas::after{
							background: url("img/histórico/%s");
							position: fixed;
							background-size: 1350px 700px;
						}</style>', $nome_imagem_historic);
					}
				} else {
					if ($_SESSION["hierarquia"] == 1) {
						$fundo_especial = sprintf('
					<style type="text/css">
						#add_dia{
							background: url("img/icone/estrela.png");
					}</style>');
					} else {
						$fundo_especial = sprintf('
					<style type="text/css">
						#estatisticas::after{
							background: url("img/icone/estrela.png");
					}</style>');
					}
				}
				echo $fundo_especial;
			}
		}
		// Mudar a foto de fundo para casos especiais separando por usuário comum ou administrador
		if ($qtd_hj == 404 && $dia_especial == null) {
			$fundo_especial = sprintf('
		<style type="text/css">
			#add_dia{
				background: url("img/especiais/404.jpg");
			}</style>');
			echo $fundo_especial;
		}
		if ($date3 == "20/07" && $dia_especial == null) {
			if ($hierarquia_usuario == 1) {
				$fundo_especial = sprintf('
		<style type="text/css">
			#add_dia{
				background: url("img/especiais/Apollo.jpg");
				background-repeat: no-repeat;
				background-size: 1350px 700px;
			}</style>');
			} else {
				$fundo_especial = sprintf('
		<style type="text/css">
			#estatisticas::after{
				background: url("img/especiais/Apollo.jpg");
				background-size: 1350px 700px;
				position: fixed;
			}</style>');
			}
			echo $fundo_especial;
		}
		if ($date3 == "11/09" && $dia_especial == null) {
			if ($hierarquia_usuario == 1) {
				$fundo_especial = sprintf('
		<style type="text/css">
			#add_dia{
				background: url("img/especiais/wtc.jpg");
				background-repeat: no-repeat;
				background-size: 1350px 700px;
			</style>');
			} else {
				$fundo_especial = sprintf('
		<style type="text/css">
			#estatisticas::after{
				background: url("img/especiais/wtc.jpg");
				background-size: 1350px 700px;
				position: fixed;
			}</style>');
			}
			echo $fundo_especial;
		}

		// Verifica se é aniversário do usuário
		if (date('d/m', strtotime($_SESSION["data_nasce"])) == $date3) {
			if ($hierarquia_usuario == 1) {
				$fundo_especial = sprintf('
			<style type="text/css">
			#add_dia{
				background: url("img/especiais/festa.jpg");
				background-repeat: no-repeat;
				background-size: 1350px 700px;
			}</style>');
			} else {
				$fundo_especial = sprintf('
			<style type="text/css">
			#estatisticas::after{
				background: url("img/especiais/festa.jpg");
				position: fixed;
				background-size: 1350px 700px;
			}</style>');
			}
			echo $fundo_especial;
		}

		if ($dia_especial != null && date('d/m', strtotime($_SESSION["data_nasce"])) != $date3) {
			if (strlen($nome_imagem_especial) > 0) {
				if ($hierarquia_usuario == 1) {
					$fundo_especial = sprintf('
					<style type="text/css">
					#add_dia{
						background: url("img/histórico/%s");
						background-repeat: no-repeat;
						background-size: 1350px 700px;
					}</style>', $nome_imagem_especial);
				} else {
					$fundo_especial = sprintf('
					<style type="text/css">
					#estatisticas::after{
						background: url("img/histórico/%s");
						position: fixed;
						background-size: 1350px 700px;
					}</style>', $nome_imagem_especial);
				}
			} else {
				if ($hierarquia_usuario == 1) {
					$fundo_especial = sprintf('
				<style type="text/css">
					#add_dia{
						background: url("img/icone/estrela.png");
				}</style>');
				} else {
					$fundo_especial = sprintf('
				<style type="text/css">
					#estatisticas::after{
						background: url("img/icone/estrela.png");
				}</style>');
				}
			}
			echo $fundo_especial;
		}

		// Mudar a cor do fundo da página
		if (isset($_SESSION["color_back"])) {
			$styleBlock = sprintf('
    <style type="text/css">
       .perfil{
         color: %s;
       }
       #perfil{
       	 background-color: %s;
   	   }
   	   #carrega_perfil{
   	     background-color: %s;
   	   }
   	   #add_dia{
   	    background-color: %s;
	   }
	   #atrib_msg{
		color: %s;
		text-shadow: 0rem 0rem .5rem black;
	   }
	   input:checked[type="checkbox"]#check_add{
		background: %s;
	   }
   	}
    </style>', $preferencia, $preferencia, $preferencia, $preferencia, $preferencia, $preferencia);
			echo $styleBlock;
			// Verificar se a cor do usuário é escura ou não
			if ($preferencia == "#003264" || $preferencia == "#6E0C91" || $preferencia == "#D60A7D") {
				$alto_contraste = sprintf('
	<style type="text/css">
		#numero_telefone_usuario{
			color: white;
		}
	</style>
		');
				echo $alto_contraste;
			}
		} ?>

	<nav id="menu" class="navbar navbar-default navbar-fixed-top">
		<div class="container">
			<!-- Brand and toggle get grouped for better mobile display -->
			<div class="navbar-header" id="navbar-header_central">

				<a class="navbar-brand page-scroll" href="#page-top"><a class="navbar_brand navbar-right" href="#">
						<h5 class="tempo" id="tempo_">Agora são <em id="relogio"></em> de <em><?php echo "$date2" ?></em></h5>
					</a></a>

				<a class="page-scroll" href="#"><a class="navbar_brand" href="#perfil">
						<h5 class="perfil" id="tempo_">Perfil</h5>
					</a>
			</div>

			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1"><span class="sr-only">Toggle navigation</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button>

			<!-- Collect the nav links, forms, and other content for toggling -->
			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				<ul class="nav navbar-nav navbar-right">
					<!-- Verificando se o usuário tem permisão para acessar esta função -->
					<?php if ($hierarquia_usuario == 1) { ?>
						<li><a href="#add_dia" class="btn btn-custom btn-lg page-scroll" id="regis_">Prancheta</a></li>
					<?php } ?>
					<li><a href="#estatisticas" class="btn btn-custom btn-lg page-scroll" id="estat_">Estatísticas</a></li>
					<!-- Botão do Acervo -->
					<?php if ($coringa > 2016) {
						echo "<li><a href='#click_anual' class='btn btn-custom btn-lg page-scroll' id='acerv_'>Acervo</a></li>";
					} ?>
					<li><a href="#click_historico" class="btn btn-custom btn-lg page-scroll" id="clik_historico">Histórico</a></li>
					<!-- Verificando se o usuário tem permisão para acessar esta função -->
					<?php if ($hierarquia_usuario == 1) { ?>
						<li id="guia_"><a href="#guia" class="btn btn-custom btn-lg" id="guia_" style="color: #14EA14">Guia</a></li>
					<?php } ?>
					<li><a href="php_suporte/redireciona_logoff.php" id="click_deslogar" class="btn btn-custom btn-lg page-scroll" style="color: red">Deslogar</a></li>
				</ul>
			</div>
		</div>
	</nav>

	<!-- Botão do Debug Mode -->
	<?php if ($_SESSION["hierarquia"] == 1) { ?>
		<button class="btn texto_debug_mode" id="debug_mode" onclick="debug_mode()">Debug Mode</button>
	<?php } ?>
	<!-- Lista do iframe com usuarios online  -->
	<iframe src="php_configura/usuarios_on.php" id="usuarios_on_iframe_lateral" name="content" marginheight="0" scrolling="no" frameborder="0" hspace="0" vspace="0" allowtransparency="true" application="true"></iframe>

	<!-- Atalho -->
	<header id="puxador_lateral">
		<div class="usuarios_disponiveis">
			<h1>Usuários Online</h1>
			<?php
			// Função para fornecer os dados do usuário
			$busca_usuario_on = "SELECT * from usuarios where status_user = '1' order by rand() limit 3";
			$online = $conexao->query($busca_usuario_on);

			if ($online->num_rows > 0) {
				while ($dados = $online->fetch_assoc()) {
					$nome_user_online = $dados["username"];
					$nome_imagem_online = $dados["nome_foto"];

					if (strlen($nome_imagem_online) > 0) {
						echo "<img id='imagem_pequeno_on' src='img/perfil/$nome_imagem_online'>";
					} else {
						echo "<img id='imagem_pequeno_on' src='img/icone/usuario.png'>";
					}

					echo "<h4 id='nome_usuario_on'>$nome_user_online</h4>";
				}
			} ?>
		</div>
		<div class="opcoes_puxa_lateral">
			<h1>Caixa de Entrada</h1>
			<?php
			// Buscar mensagens enviadas pelo usuario
			$busca_mensagem = "SELECT * from mensageria order by id_mensagem desc limit 2";
			$busca_msg = $conexao->query($busca_mensagem);

			if ($busca_msg->num_rows > 0) {
				while ($mensagens = $busca_msg->fetch_assoc()) {

					if ($mensagens["id_usuario"] == $userid || $mensagens["destinatario"] == $userid || $mensagens["status_msg"] == 4) { ?>
						<div id="mensagem_usuario_<?php echo $mensagens["id_mensagem"] ?>" class="_color_msg">
							<?php echo $mensagens["mensagem"];

							echo "<div class='mgs_feed'> <hr id='hr_mensagens'>";
							echo "Status:";
							if ($mensagens["status_msg"] == 1) {
								echo " Em Aberto";
								$cor_msg = sprintf('
    						<style type="text/css">
      							#mensagem_usuario_' . $mensagens["id_mensagem"] . '{ background-color: orange; }
   							</style>');
							} else if ($mensagens["status_msg"] == 2) {
								echo " Em Observação";
								$cor_msg = sprintf('
    						<style type="text/css">
      							#mensagem_usuario_' . $mensagens["id_mensagem"] . '{ background-color: green; }
   							</style>');
							} else if ($mensagens["status_msg"] == 3) {
								echo " Encerrado";
								$cor_msg = sprintf('
    						<style type="text/css">
      							#mensagem_usuario_' . $mensagens["id_mensagem"] . '{ background-color: #263F68; }
   							</style>');
							} else {
								echo " Pública";
								$cor_msg = sprintf('
    						<style type="text/css">
      							#mensagem_usuario_' . $mensagens["id_mensagem"] . '{ background-color: #CD0808; }
   							</style>');
							}
							echo $cor_msg;
							echo "</div>"; ?>
						</div><br>
			<?php   }
				}
			}
			?>
		</div>
		<?php if ($_SESSION["hierarquia"] == 1) { ?>
			<a href="php_configura/configura_central.php">
				<div id="configura_lateral_central">
					<h1 id="configura_lateral_">Configura</h1>
				</div>
			</a> <?php } ?>
	</header>

	<div id="atalho_lateral" onclick="puxa_lateral()">
		<h1>S<br>t<br>a<br>t<br>u<br>s</h1>
	</div>

	<!-- Perfil -->
	<div id="perfil">
		<?php
		if (strlen($nome_imagem) > 0) { ?>
			<img src="img/perfil/<?php echo $nome_imagem ?>" id="img_perfil" alt="Sua foto de perfil!">
		<?php } else { ?>
			<img src="img/icone/usuario.png" id="img_perfil" alt="Você pode escolher uma foto nas configurações"> <?php } ?>

		<h3 id="nome_user">Bem vindo(a) <?php echo $nome_user; ?>!</h3>
		<i class="fas fa-phone-slash"></i>

		<?php
		// Mensagem de alerta para usuários novos
		if (isset($_SESSION['msg_cadstro'])) {
			echo "<h5 style='color: red'>" . $_SESSION['msg_cadstro'] . "</h5>";
			unset($_SESSION['msg_cadstro']);
		} ?>

		<div id="numero_telefone_usuario"> <?php
											if ($numero_telefone) {
												echo "<h5><i class='fa fa-phone' aria-hidden='true'></i> $numero_telefone </h5>";
											} else {
												echo "<h5>Você pode registrar um número de telefone nas configurações ;)</h5>";
											}
											?></div><br><br>
		<?php if ($_SESSION["hierarquia"] == 0) {
			echo "<input type='button' class='btn' onclick='atualiza_status_usuario()' value='Solicitar aprovação para inserir dados'><br><br>";
		} ?>
		<input type="button" id="configura_perfil" class="btn" onclick="configura_perfil()" value="Abrir as configurações >>">
	</div>
	<div id="fundo_perfil">
		<div id="carrega_perfil"></div>
	</div>

	<!-- Guia para fazer a importação de células de dados -->
	<div id="guia_importa">
		<img id="import_img" src="img/fundo/importação.png">

		<header class="lista_importa">
			<!-- Ferramenta para importar célula de dados automaticamente -->
			<form id="import_dia" action="php_suporte/importar_arquivo.php" method="POST" enctype="multipart/form-data">
				<h4 id="titulo">Se possuir uma Célula de dados, Importe ela para poupar tempo!</h4>
				<input type="file" name="arquivo" style="color: white" class="escolhe_arquiv_impt">
				<?php
				if (isset($_SESSION['msg_impt'])) {
					echo "<h5 style='color: yellow'>" . $_SESSION['msg_impt'] . "<h5>";
					unset($_SESSION['msg_impt']);
				} ?>
				<button class="btn btn-custom btn-lg" id="button_ok">Importar</button>
			</form><br><br>

			<!-- Ferramenta para converter arquivos de visualização em células de dados-->
			<form id="import_dia" action="php_suporte/converter_arquivo.php" method="POST" enctype="multipart/form-data">
				<h4 id="titulo">Arquivo de Visualização? Converta ele para uma Célula de dados.</h4>
				<input type="file" name="conversao" style="color: white" class="escolhe_arquiv_impt">
				<?php
				if (isset($_SESSION['msg2'])) {
					echo "<h5 style='color: yellow'>" . $_SESSION['msg2'] . "</h5>";
					unset($_SESSION['msg2']);
				} ?>
				<button class="btn btn-custom btn-lg" id="button_ok">Converter</button>
			</form>
		</header>
	</div>

	<!-- Verificando se o usuário tem permisão para acessar esta função -->
	<?php if ($hierarquia_usuario == 1) { ?>

		<!-- Prancheta para adicionar dias, atribuir usuários e verificar histórico -->
		<div id="add_dia">
			<div id="espia_fundo_global">

				<?php // Verifica se existe um dia marcado ou especial
				if (isset($fundo_especial)) {
					echo "<i class='fa fa-eye fa-3x' id='espiar_fundo' onmouseover='esconde_fundo()' onmouseout='aparece_fundo()'></i>";
				}
				// Retirando valores do mês para o link dos dias especiais
				$mes_dd2 = date('m', strtotime($date4));

				if (substr($mes_dd2, -1) != 0) {
					$mes_convertido2 = str_replace("0", "", $mes_dd2); // Retira o valor 0 caso seja meses de 1 a 9
				} else {
					$mes_convertido2 = $mes_dd2; // Mantem o valor para o mês de outubro
				}  ?>

				<div id="escolhe_quadro_add_">
					<h3 class="escolhe_quadro_add" id="dias_prancheta_add">Adicionar Dias</h3>
					<h3 class="escolhe_quadro_add" id="usuarios_prancheta_add">Gerenciar Atribuições</h3>
					<h3 class="escolhe_quadro_add" id="historico_prancheta_add">Histórico de Dias</h3>
				</div>

				<!-- Ferramenta para adicionar dias manualmente -->
				<form name="form_relogio" action="php_suporte/recebe_qtd.php" method="POST">
					<fieldset id="quadro_add">
						<!-- Dias Históricos -->
						<div id="dia_impt">
							<?php if ($dia_especial != null) { ?>

								<img id="estrela" src="img/icone/estrela.png"><br>
								<h2 id="titulo">Dia Histórico!</h2>
								<hr>
								<h4 id="historical">Note que hoje é um <br>dia Histórico, para poder editá-lo, vá para o mural <br><a href="#click_historico" style="color: white" class="page-scroll">>> Histórico <<< /a>
											<hr>
											<a id="titulo" href="php_suporte/estatistificado?data=<?php echo $date4 ?>"> Ir para as <br>estatísticas de<br> <?php echo ">> $mes[$mes_convertido2] <<" ?></a></h4>
								<?php echo "<h5 style='color: yellow'>O Acontecimento de hoje é:<br> <em>$dia_especial</em></h5>"; ?>
							<?php } else { ?>

								<h2 id="titulo">Dia Histórico?</h2>
								<a id="dia_historico_click"><img id="estrela" src="img/icone/estrela.png"></a>
								<hr>
								<h4 id="historical">Defina hoje como um<br> dia <em style='color: #F5F50F'>Histórico</em> para <br>liberar várias<br> funções!</h4>
								<input id="dia_impta" class="Input-text" name="dia_import" maxlength="45"> <?php } ?>
						</div>

						<div id="info">
							<?php if (!isset($hora_old)) {
								echo "<h2 id='titulo'>Hoje $date3 <hr>Ainda não foram<br> registradas<br> mensagens<hr><em style='color: $preferencia'>Registre agora<br>mesmo</em></h2>";
							} else {
								if ($qtd_hj > 1) {
									echo "<h2 id='titulo'>Hoje $date3 <hr>Foram registradas<br> $qtd_hj<br> mensagens<hr>Último update às<br><em style='color: $preferencia'>$hora_old</em><hr></h2>";
								} else if ($qtd_hj > 0 && $qtd_hj < 2) {
									echo "<h2 id='titulo'>Hoje $date3 <hr>Foi registrada<br> apenas $qtd_hj<br> mensagem<hr>Último update às<br><em style='color: $preferencia'>$hora_old</em><hr></h2>";
								} else {
									echo "<h2 id='titulo'>Hoje $date3 <hr>$qtd_hj mensagens :(<br><hr>Último update às<br><em style='color: $preferencia'>$hora_old</em><hr></h2>";
								}
								if ($hj_na_hist > 1) {
									echo "<h4 id='titulo'>Hoje ultrapassou $hj_na_hist dias! </h4>";
								} else if ($hj_na_hist > 0 && $hj_na_hist < 2) {
									echo "<h4 id='titulo'>Hoje ultrapassou apenas $hj_na_hist dia </h4>";
								} else {
									echo "<h4 id='titulo'>Hoje não ultrapassou<br>nenhum dia</h4>";
								}
							}
							echo "<h5 id='titulo' class='infos_rapidos'>Dias contabilizados<br> no sistema: $executa_hj->num_rows </h5>";

							if ($executa_hj->num_rows > 0) {
								echo "<h5 id='titulo' class='infos_rapidos'>Média de mensagens por dia: " . round($total_msg / $executa_hj->num_rows, 0) . "</h5>";
							} ?>
							<br>
						</div>

						<h1 id="titulo">Inserir um novo dia</h1>
						<!-- Relógio Atualizado de segundo em segundo -->
						<input style="display: none" type="text" class="Input-text" id="relogio" name="hora_att">

						<!-- somar ou adicionar novo -->
						<h3 id="titulo" class="check_box_soma">Criar um Novo <input type="checkbox" name="operador" id="check_add"> Somar ao dia</h3>

						<div id="alinhadora">
							<hr id="loading_add">
							<hr id="loading_add2">
							<div class="Input" style="padding-top: 50px">
								<input type="text" id="input" class="Input-text" name="data_dia" required="" value="<?php echo $date ?>" maxlength="10">
								<label for="input" class="Input-label">Data para Inserir</label>
							</div><br>

							<div class="Input">
								<input type="text" id="input" class="Input-text" name="qtdmensagem" required="" placeholder="Quantas msg?">
								<label for="input" class="Input-label">Magnífico!</label>
							</div><br>

							<div class="Input" style="display: none">
								<input style="color: grey" type="text" id="input" class="Input-text" name="iduser" required="" value="<?php echo $userid ?>" readonly>
								<label for="input" class="Input-label">Seu Id</label>
							</div>
						</div>

						<button class="btn btn-custom btn-lg" id="button_ok"><em>OK!</em></button>
					</fieldset>
				</form>

				<!-- Ferramenta para adicionar usuários aos dias -->
				<form action="php_suporte/recebe_atribuicao.php" method="POST">
					<fieldset id="quadro_add_user">
						<?php
						if ($qtd_hj > 0) {
							// Contabilizando o total de mensagens que foram atribuidas aos usuários
							$atribuicao = 0;
							$total_usuario = "SELECT telefone, quantidade from atribuicao_diaria where datablan like '$date'";
							$busca_total_soma = $conexao->query($total_usuario);

							if ($busca_total_soma->num_rows > 0) {
								while ($soma = $busca_total_soma->fetch_assoc()) {
									$atribuicao += $soma["quantidade"];
								}
							} ?>
							<h1 id="titulo">Atribuição de Usuário</h1>
							<?php if ($qtd_hj > $atribuicao) {
								echo "<p id='titulo'>Hoje temos $qtd_hj mensagens registradas e $atribuicao mensagens referênciadas a usuários</p>";
							} else if ($qtd_hj == $atribuicao) {
								echo "<p id='titulo'>Parabéns! Todas as atribuições de hoje foram feitas😍🥳</p>";
							} else {
								echo "<p id='titulo'>Algo está errado! Existem $qtd_hj mensagens no dia e $atribuicao mensagens atribuidas!<br> Corrija as atribuições 🤭😉</p>";
							}
							if (isset($_SESSION["msg_atribui"])) {
								echo "<h3 id='atrib_msg'>" . $_SESSION["msg_atribui"] . "</h3>";
								unset($_SESSION["msg_atribui"]);
							} ?>

							<div id="alinhadora">
								<hr id="loading_add">
								<hr id="loading_add2">
							</div>

							<!-- Lista com os contribuintes do dia -->
							<header id="usuarios_atribuidos">
								<h3>Usuários Contribuidores</h3>
								<?php
								$usuarios_atribuidos = "SELECT telefone, quantidade from atribuicao_diaria where datablan like '$date' order by quantidade desc limit 8";

								$executa_busca_atrib = $conexao->query($usuarios_atribuidos);

								if ($executa_busca_atrib->num_rows > 0) {
									while ($dados_atrib = $executa_busca_atrib->fetch_assoc()) {

										$numero_tel_atrib = $dados_atrib["telefone"];

										// Função para fornecer os dados dos usuários
										$busca_usuario_atrib_2 = "SELECT * from usuarios where numerotel = '$numero_tel_atrib'";
										$executa_usuario_atrib_2 = $conexao->query($busca_usuario_atrib_2);

										while ($dados = $executa_usuario_atrib_2->fetch_assoc()) {
											if ($numero_tel_atrib == $dados["numerotel"]) {
												$nome_usuario_atrib = $dados["username"];
												$numero_tel_atrib = $dados["numerotel"];

												if (strlen($nome_usuario_atrib) != 0) {
													echo $dados["username"] . " / ";
												}
											}
										}
										echo $numero_tel_atrib;
										echo " -> " . $dados_atrib["quantidade"] . "<br>";
									}
								} ?>
							</header>

							<!-- Fazer atribuição de novos usuários -->
							<header id="insere_atribuicao">

								<input type="text" id="input" class="Input-text" name="data_dia" value="<?php echo $date ?>" maxlength="10"><br>
								<input type="text" id="input" class="Input-text_quadro" name="telefone_user[]" placeholder="Telefone" maxlength="13" required>
								<input type="text" id="input" class="Input-text_quadro" name="qtd_atribuida[]" placeholder="Enviou..." required><br>

								<!-- Adicionar novos usuários -->
								<div class="novo_atributo"></div>
							</header>

							<a class="btn btn-custom btn-lg" id="button_ok" onclick="add_user()">Adicionar Usuário</a>
							<button class="btn btn-custom btn-lg" id="button_ok"><em>Atribuir</em></button>

						<?php } else { ?>
							<h1 id="titulo">Ainda não há mensagens Hoje<br> Registre algumas para poder Atribuir os valores aos usuários!😉🤖 </h1>
							<div id="alinhadora">
								<hr id="loading_add">
								<hr id="loading_add2">
							</div>
						<?php } ?>
					</fieldset>
				</form>

				<!-- Ferramenta para verificar o histórico de dias -->
				<fieldset id="quadro_add_historico_dia">
					<?php
					// Verificar se o debug_mode está ativo para poupar recursos de processamento
					if ($debug_mode == null || $debug_mode == 0) {

						$verifica_status_dia = "SELECT verificado FROM balanco where verificado is not null";
						$quantidade_dias_verificados = $conexao->query($verifica_status_dia);

						$verifica_status_dia_total = "SELECT * FROM balanco";
						$executa_verifica_dia_total = $conexao->query($verifica_status_dia_total); ?>

						<h1 id="titulo">Histórico de Dias</h1>
						<p id="titulo">Dias Contabilizados no sistema <?php echo $executa_hj->num_rows; ?></p>

						<?php if ($quantidade_dias_verificados->num_rows > 0) { ?>
							<p id="titulo">Dias marcados como conferidos ou em observação <?php echo $quantidade_dias_verificados->num_rows; ?></p>
						<?php } else { ?>
							<p id="titulo">Nenhum dia foi conferido ainda :(</p>
						<?php } ?>
						<div id="alinhadora">
							<hr id="loading_add">
							<hr id="loading_add2">
						</div>
						<!-- Lista com os contribuintes do dia -->
						<header id="verifica_dias">

							<!-- Botões para ordenar a visualização -->
							<div class="escolhe_blocos_verificados">
								<button class="btn" id="seleciona" onclick="mostra_anos_verificados('coringa')">Mostrar Todos</button>

								<?php $coleta_anos_verificados = "SELECT datablan FROM balanco group by year(datablan)";
								$executa_coleta = $conexao->query($coleta_anos_verificados);

								while ($dados_anuais = $executa_coleta->fetch_assoc()) {
									// $mes_pg_verifica = str_replace("-", "", $mes_pg_verifica);
									$ano_verificado = date('Y', strtotime($dados_anuais["datablan"]));
									// Chamando a função para ordenar os anos 
									echo "<button class='btn' id='seleciona' onclick='mostra_anos_verificados(" . $ano_verificado . ")'>" . $ano_verificado . "</button>";
								}
								echo "</div>";

								if ($executa_verifica_dia_total->num_rows > 0) {
									while ($dados_verifica_dia = $executa_verifica_dia_total->fetch_assoc()) {

										// Convertendo as datas para o formato certo
										$mes_pg_verifica = $dados_verifica_dia["datablan"];
										$verificacao = $dados_verifica_dia["verificado"];

										if ($verificacao == null) {
											$verificacao = 0;
										}

										$ano_interno = date("Y", strtotime($mes_pg_verifica));
										$palheta_de_cores = array('white', 'black', 'red', '#E3C19A', '#E30707', '#DDB587');

										$cor_quadrado_dia = sprintf('
    					<style type="text/css">
      						.dia_marc_' . $mes_pg_verifica . '{ background-color:' . $palheta_de_cores[$verificacao] . '; }
   					</style>');

										echo $cor_quadrado_dia;

										echo "<div class='botao_verifica_dia dia_marc_$mes_pg_verifica $ano_interno' onclick='atualiza_dia_verifica(" . date('d', strtotime($mes_pg_verifica)) . ", " . date('m', strtotime($mes_pg_verifica)) . ", " . date('Y', strtotime($mes_pg_verifica)) . ")'></div>";
								?>
									<?php } ?>
							</div>
						</header>

					<?php } else { ?>
						<h1 id="titulo">Ainda não há dias registrados :(<br> Registre alguns para poder verifica-los por aqui!😉🤖</h1>
				<?php }
							} else {
								echo "<h1 id='titulo'>Com o modo Debug ativado, o histórico de dias é desativado para poupar recursos!😉</h1>";
							} ?>
				</fieldset>
			</div>
		</div>
	<?php }

	// Verificando se existem dados para poder exibir o quadro de estatísticas
	if ($executa_hj->num_rows > 0) { ?>
		<!-- Solicitar Estatísticas de um mês ou um ano -->
		<div id="estatisticas">
			<h1 id="titulo">Navegando por Estatísticas</h1>

			<p style="color: <?php echo $preferencia ?>; text-shadow: 0rem 0rem .5rem grey;"><strong>
					<?php
					if ($anos_anteriores != 0 && $executa_busca2->num_rows > 0) {
						echo "Note: Nesta listagem aparecem apenas dados do ano atual, para ver anos anteriores <a id='titulo' href='#click_anual' class='page-scroll'>clique aqui</a>";
					} else if ($anos_anteriores != 0 && $executa_busca2->num_rows == 0) {
						echo "Note: Não há dados para o ano atual, mas existem anos anteriores <a id='titulo' href='#click_anual' class='page-scroll'>clique aqui</a>";
					} else {
						echo "Note: Nesta listagem aparecem apenas dados do ano atual, infelizmente não há dados de anos anteriores!";
					} ?></strong></p>

			<div id="alinhadora2">
				<hr id="loading_add2">
				<hr id="loading_add">

				<?php

				// Criando lista de meses e anos com dados
				$busca_mes = "SELECT * From balanco where day(datablan) = '1' and year(datablan) like $coringa order by(datablan)desc limit 3";

				$exe_busca_mes = $conexao->query($busca_mes);

				if ($exe_busca_mes->num_rows > 0) {

					if ($exe_busca_mes) {
						echo "</div><h3 id='titulo'><em>Estes são os últimos meses que apresentam registros</em></h3><div id='alinhadora2'>";
					}

					while ($linha = $exe_busca_mes->fetch_assoc()) {
						$mes_pg = $linha["datablan"];

						// Coletando os meses anteriores
						$mes_dd = date('m', strtotime($mes_pg));

						if (substr($mes_dd, -1) != 0) {
							$mes_convertido = str_replace("0", "", $mes_dd); // Retira o valor 0 caso seja meses de 1 a 9, 11 e 12
						} else {
							$mes_convertido = $mes_dd; // Mantem o valor para o mês de outubro
						}

						echo "<div class='Input' id='select_mes_top' style='padding-top: 5px'>
    			<a type='text' id='select_mes' href='#' class='Input-text' maxlength='7' onclick='pegadata(" . date('Y', strtotime($mes_pg)) . "." . date('m', strtotime($mes_pg)) . ")'>" . $mes[$mes_convertido] . "</a>
			  </div>";
						$contador_mes = $contador_mes + 1;
					} ?>

					<div id='botoes_op'><?php
										echo "<a type='text' id='select_ano' href='#' class='Input-text' maxlength='7' onclick='pegadata($coringa)'>O Ano todo</a>";

										echo "<a type='text' id='select_total' href='#' class='Input-text' maxlength='7' onclick='pegadata($total)'>Global</a>
    		  <br><br>";
										?></div>
					<div id="click_anual"></div> <?php
												} else {
													echo "</div><h2 style='color: rgba(255, 255, 255, .8)'>Ainda não existem registros para este ano, faça um agora mesmo!</h2><div id='alinhadora2'>";
												}
												// Condições para diálogo caso exista anos anteriores
												if ($anos_anteriores != 0) {
													if ($coringa > 2016 && $executa_hj->num_rows > 0 && $anos_anteriores >= 1) {
														echo "<div id='separador_estatic'></div>";
														echo "<hr></div><br><h2 id='titulo'>Anos anteriores</h2><div id='alinhadora2'>";
													}
												} else {
													echo "<hr></div><h2 style='color: #0ED6D6; text-shadow 0.0rem 0.0rem 2rem;'>Primeiro ano né?<br></h2><h5 style='color: #0ED6D6;'>Você pode incluir anos anteriores para poder acessar o acervo ;)</h5><div id='alinhadora2'>";
												} ?>
			</div>

			<div id="alinhadora2">
				<?php

				// Criando listas de anos anteriores
				$busca_ano = "SELECT * from balanco where year(datablan) < $coringa and month(datablan) like '12' and day(datablan) like '31' order by datablan desc";

				$exe_busca_ano = $conexao->query($busca_ano);

				if ($exe_busca_ano->num_rows > 0) {
					while ($linha = $exe_busca_ano->fetch_assoc()) {
						$ano_pg = $linha["datablan"];

						echo "<div class='Input' id='select_mes_top' style='padding-top: 15px'>
    			<a type='text' id='select_ano_old' href='#' class='Input-text' maxlength='7' onclick='pegadata(" . date('Y', strtotime($ano_pg)) . ")'>" . date('Y', strtotime($ano_pg)) . "</a>
    		  </div>";
					}
				}
				?>
			</div>
		</div>

	<?php }
	if ($dias_historicos > 0) { ?>
		<!-- Dias Históricos -->
		<div id="click_historico">
			<h1 id="titulo"><img id="estrela" src="img/icone/estrela.png">Boas vindas ao Histórico!<img id="estrela" src="img/icone/estrela.png"></h1>
			<?php if ($dias_historicos != 1) { ?>
				<em>
					<h2>Atualmente existem <?php echo $dias_historicos; ?> dias históricos no sistema</h2>
				</em>
			<?php } else { ?>
				<h2>Atualmente existe apenas 1 dia histórico no sistema, registre mais dias!</h2>
			<?php }

			if (isset($_SESSION['msg_his'])) {
				echo $_SESSION['msg_his'];
				unset($_SESSION['msg_his']);
			} ?>
			<!-- Botões de escolha -->
			<div class="escolhe_blocos">
				<button class="btn" id="seleciona" onclick="atualiza_dias_historicos('coringa')">Mostrar Todos</button>
				<button class="btn" id="seleciona" onclick="atualiza_dias_historicos('1_hide')">Dias Bons</button>
				<button class="btn" id="seleciona" onclick="atualiza_dias_historicos('0_hide')">Dias Ruins</button>
			</div>

			<!-- Fundo animado para pc/ telas maiores -->
			<section id="section_for_pc">
				<div class="set">
					<div><img id="historicos_" src="img/icone/estrela.png"></div>
					<div><img id="historicos_" src="img/icone/trofeu.png"></div>
					<div><img id="historicos_" src="img/icone/relogio.png"></div>
					<div><img id="historicos_" src="img/icone/disco.png"></div>
				</div>
				<div class="set set2">
					<div><img id="historicos_" src="img/icone/trofeu.png"></div>
					<div><img id="historicos_" src="img/icone/estrela.png"></div>
					<div><img id="historicos_" src="img/icone/relogio.png"></div>
					<div><img id="historicos_" src="img/icone/disco.png"></div>
				</div>
				<div class="set set3">
					<div><img id="historicos_" src="img/icone/disco.png"></div>
					<div><img id="historicos_" src="img/icone/estrela.png"></div>
					<div><img id="historicos_" src="img/icone/trofeu.png"></div>
					<div><img id="historicos_" src="img/icone/relogio.png"></div>
				</div>
			</section>

			<div class="container_hist">
				<!-- Fundo animado para o celular -->
				<section id="section_for_smart_fone">
					<div class="set">
						<div><img id="historicos_" src="img/icone/estrela.png"></div>
						<div><img id="historicos_" src="img/icone/trofeu.png"></div>
						<div><img id="historicos_" src="img/icone/relogio.png"></div>
						<div><img id="historicos_" src="img/icone/disco.png"></div>
					</div>
					<div class="set set2">
						<div><img id="historicos_" src="img/icone/trofeu.png"></div>
						<div><img id="historicos_" src="img/icone/estrela.png"></div>
						<div><img id="historicos_" src="img/icone/relogio.png"></div>
						<div><img id="historicos_" src="img/icone/disco.png"></div>
					</div>
					<div class="set set3">
						<div><img id="historicos_" src="img/icone/disco.png"></div>
						<div><img id="historicos_" src="img/icone/estrela.png"></div>
						<div><img id="historicos_" src="img/icone/trofeu.png"></div>
						<div><img id="historicos_" src="img/icone/relogio.png"></div>
					</div>
				</section>
				<?php

				// Criando os blocos dos dias históricos
				$lista_diahistoric = "SELECT * FROM balanco order by datablan desc";
				$executa_historic = $conexao->query($lista_diahistoric);

				if ($executa_historic->num_rows > 0) {
					while ($hoje_dia_hist = $executa_historic->fetch_assoc()) {
						if ($hoje_dia_hist["dia_marcado"] != null) {

							$data1 = $hoje_dia_hist["datablan"];
							$data2 = "22/02/2013";
							// converte as datas para o formato timestamp
							$d1 = strtotime($data1);
							$d2 = strtotime($date);
							// verifica a diferença em segundos entre as duas datas e divide pelo número de segundos que um dia possui
							$dataFinal = ($d2 - $d1) / 86400;
							// caso a data 2 seja menor que a data 1
							if ($dataFinal < 0)
								$dataFinal = $dataFinal * -1;

							$dataFinal = round($dataFinal, 0);
							$nome_dia = $hoje_dia_hist["dia_marcado"];
							$data_dia = $hoje_dia_hist["datablan"];
							$categoria_dia = $hoje_dia_hist["categoria"];
							$nome_imagem_historic = $hoje_dia_hist["nome_imagem"];

							if ($recorda == 1)
								recorda_dia($dataFinal, $nome_dia, $data_dia, $nome_imagem_historic); ?>

							<div id="blocos_historicos" class="bloco_historic <?php echo $categoria_dia; ?>_hide">
								<a href="estedia.php?data=<?php echo $data_dia ?>">
									<!-- Nome e data do dia -->
									<div class="conteudo_hist">
										<h2> <?php echo $nome_dia; ?> </h2>
										<h5> <?php echo date('d/m/Y', strtotime($data_dia)); ?> </h5>
										<?php if (round($dataFinal, 0) != 0) { ?>
											<h5> <?php echo "Há " . round($dataFinal, 0) . " dias "; ?> </h5>
										<?php } else { ?>
											<h5> <?php echo "Hoje!"; ?> </h5>
										<?php } ?>
									</div>
									<!-- Imagem do dia marcado -->
									<div class="imgBox_hist"><?php
																if ($nome_imagem_historic != null) { ?>
											<img src="img/histórico/<?php echo $nome_imagem_historic; ?>">
										<?php } else { ?>
											<img src="img/icone/estrela.png"> <?php } ?>
									</div>
								</a>
							</div> <!-- Nome e data do dia para telas menores-->
							<!-- <div id="info_historic">
        						<h2> <?php echo $nome_dia ?> </h2>
								<h4> <?php echo date('d/m/Y', strtotime($data_dia)) ?> </h4>
							</div> -->
				<?php }
					}
				} ?>
			</div>
		</div><?php }

			// Aviso de sistema sem dados
			if ($executa_hj->num_rows == 0 && $_SESSION["hierarquia"] != 1) { ?>
		<div id="offline">
			<h3 style="color: white">Ainda não há nada no sistema!<br> porém você pode ajudar a construir ele, envie uma solicitação<br> agora mesmo para poder adicionar dados ;)<br></h3>
		</div>
	<?php } ?>

	<!-- rodapé -->
	<div id="footer">
		<div class="text-center">
			<div class="fnav">
				<h4>Todos os direitos reservados <a id="link" href="../gamersx.php"><em>G4mersx</em></a> Copyright © 2019.</h4>
			</div>
		</div>
	</div>

	<script type="text/javascript">
		// Função para atualizar as cores das letras
		function atualizaCores() {
			var contador = 0;

			var atualiza = <?php echo $contador_mes; ?>;
			if (atualiza < 2) {
				const estatistica = document.querySelector("#estat_");
				estatistica.style.color = "#686259";
			} else {
				const estatistica = document.querySelector("#estat_");
				estatistica.style.color = "";
			}

			var anos_anteriores = <?php echo $anos_anteriores; ?>;
			if (anos_anteriores < 1) {
				const acervo = document.querySelector("#acerv_");
				acervo.style.color = "#686259";
			} else {
				const acervo = document.querySelector("#acerv_");
				acervo.style.color = "#0ED6D6";
			}

			var historico = <?php echo $dias_historicos; ?>;
			if (historico < 1) {
				const historico = document.querySelector("#clik_historico");
				historico.style.color = "#686259";
			} else {
				const historico = document.querySelector("#clik_historico");
				historico.style.color = "#CCCC09";
			}
			var usuario = <?php echo $_SESSION["hierarquia"]; ?>;
			if (usuario == 0) {
				const estatistica = document.querySelector("#estatisticas");
				estatistica.style.paddingTop = "105px";
			}
		}

		function configura_perfil() {
			const sombra = document.querySelector("#perfil");
			sombra.style.boxShadow = "none";

			const fundo_anima = document.querySelector("#carrega_perfil");
			fundo_anima.style.display = "block";

			setTimeout(function() {
				window.location.href = "php_configura/editar_perfil.php";
			}, 800);
		}

		function aparece_fundo() {
			const quadro_add = document.querySelector("#espia_fundo_global");
			quadro_add.style.animation = "guarda_fundo .5s";
			quadro_add.style.opacity = "1";
		}

		function esconde_fundo() {
			const quadro_add = document.querySelector("#espia_fundo_global");
			quadro_add.style.animation = "espia_fundo .5s";
			quadro_add.style.opacity = ".001";
		}
	</script>
	<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
	<script type="text/javascript" src="js/bootstrap.js"></script>
	<script type="text/javascript" src="js/SmoothScroll.js"></script>
	<script type="text/javascript" src="js/nivo-lightbox.js"></script>
	<script type="text/javascript" src="js/main.js"></script>
	<script type="text/javascript" src="js/funcoes.js"></script>

	<?php
	// Verificar se o usuário é adm
	if ($_SESSION["hierarquia"] == 1) { ?>
		<script type="text/javascript" src="js/relogio_adm.js"></script>
	<?php } else { ?>
		<script type="text/javascript" src="js/relogio.js"></script>
	<?php } ?>

</body>

</html>