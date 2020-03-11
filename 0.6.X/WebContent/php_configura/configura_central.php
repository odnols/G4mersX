<!DOCTYPE html>
<html>
<head>
	<title>Configurações</title>
	<meta charset="utf-8">
  	<meta name="viewport" content="width=device-width, initial-scale=1">
  	<link rel="shortcut icon" href="../img/Icone/gamerx.png">

	<!-- CSS -->
	<link rel="stylesheet" type="text/css" href="../css/style.css">
	<link rel="stylesheet" type="text/css" href="../css/input.css">
	<link rel="stylesheet" type="text/css" href="../css/textos.css">
	<link rel="stylesheet" type="text/css" href="../css/central.css">
	<link rel="stylesheet" type="text/css" href="../css/responsividade.css">

	<!-- Bootstrap -->
	<link rel="stylesheet" type="text/css" href="../css/bootstrap.css">
	<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
	<link rel="stylesheet" type="text/css" href="../fonts/font-awesome/css/font-awesome.css">
	<?php 
		session_start();
		include_once "../php_suporte/old_conexao.php";

		$preferencia = $_SESSION["color_back"];
		if (isset($_SESSION["color_back"])) {
  		$styleBlock = sprintf('
    	<style type="text/css">
       		#cor_fundo{
         		background-color: %s;
       		}
    	</style>', $preferencia);
  		}
  		echo $styleBlock;
	?>
</head>
<body id="cor_fundo">
	<!-- Cabeçalho -->
	<nav id="menu" class="navbar navbar-default navbar-fixed-top">
    	<div class="container">

    	<div class="navbar-header">    
      		<h2 style="color: white;">Configurações</h2>
		</div>
		  <!-- Lista de usuários online na barra de atalho -->
      		<iframe src="usuarios_on.php" id="usuarios_on_iframe" name="content" marginheight="0" scrolling="no" frameborder="0" hspace="0" vspace="0" allowtransparency="true" application="true"></iframe>
      		
      		<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1"><span class="sr-only">Toggle navigation</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button>

      		<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      			<ul class="nav navbar-nav navbar-right">
					<li><a href="../central.php" class="btn btn-custom btn-lg page-scroll">Voltar</a></li>
					<li id="deslg"><a href="../php_suporte/redireciona_logoff.php" id="deslogar" class="btn btn-custom btn-lg page-scroll" style="color: red;">Deslogar</a></li>
				</ul>
			</div>

			<div id="itens_escolhe">
				<a href="#" class="item_bg"><i id="click_usuario" class="fa fa-user fa-3x"></i></a>
				<a href="#" class="item_bg"><i id="click_mensagens" class="fa fa-envelope fa-3x"></i></a>
				<a href="#" class="item_bg"><i id="click_atualiza" class="fa fa-refresh fa-3x" aria-hidden="true"></i></a>
				<a href="#" class="item_bg"><i id="click_relatorio" class="fa fa-flag-checkered fa-3x"></i></a>
    		</div>
	</nav>

	<div id="click_usuario_on">
		<div id="alinha_topo">
			<h1 id="titulo_2">Usuários</h1><br>
		
		<?php
		$usuarios = "SELECT userid, username, nome_foto FROM usuarios order by rand()";
		$verifica = $conexao->query($usuarios);
		
		while($dados = $verifica->fetch_assoc()){

			echo "<a id='quadro_usuario' href='configura_usuario.php?id_user=".$dados['userid']."'>";
			if(strlen($dados["nome_foto"]) > 0){
				echo "<img id='foto_usuario' src='../img/perfil/".$dados["nome_foto"]."'>";
			}else{
				echo "<img id='foto_usuario' src='../img/icone/usuario.png'>";
			}
			echo "<h4 id='nome_usuario'>".$dados["username"]."</h4>";
	} ?></a></div>
	</div>

	<div id="click_mensagens_on">
		<div id="alinha_topo">
			<h1 id="titulo_2">Caixa de Entrada</h1><br>

			<?php
			$mensagens = "SELECT * from mensageria order by id_mensagem desc";
			$captura = $conexao->query($mensagens);

			if($captura->num_rows > 0){
				while ($mensagens = $captura->fetch_assoc()){
				
				$id_mensagem = $mensagens["id_mensagem"];

				if($mensagens["id_usuario"] == $_SESSION["id_usuario"] || $mensagens["destinatario"] == $_SESSION["hierarquia"]){ ?>
				<div id="mensagem_usuario_<?php echo $mensagens["id_mensagem"] ?>" class="bloco_msg_">
					<?php echo $mensagens["mensagem"]."<hr id='hr_mensagens'>";

					echo "<div class='mgs_feed'>";
					echo "Status:";
					if($mensagens["status_msg"] == 1){
						echo " Em Aberto";
						$cor_msg = sprintf('
    						<style type="text/css">
      							#mensagem_usuario_'.$mensagens["id_mensagem"].'{ background-color: orange; }
   							</style>');
					}else if($mensagens["status_msg"] == 2){
						echo " Em Observação";
						$cor_msg = sprintf('
    						<style type="text/css">
      							#mensagem_usuario_'.$mensagens["id_mensagem"].'{ background-color: green; }
   							</style>');
					}else if($mensagens["status_msg"] == 3){
						echo " Encerrado";
						$cor_msg = sprintf('
    						<style type="text/css">
      							#mensagem_usuario_'.$mensagens["id_mensagem"].'{ background-color: #263F68; }
   							</style>');
					}else{
						echo " Pública";
						$cor_msg = sprintf('
    						<style type="text/css">
      							#mensagem_usuario_'.$mensagens["id_mensagem"].'{ background-color: #CD0808; }
   							</style>');
					} echo $cor_msg; ?>

				</div>
					<button id="button_msg" class="btn" onclick="status_msg('<?php echo $id_mensagem; ?>')" style="color: red;">Apagar</button>
					<?php if($mensagens["id_usuario"] != $_SESSION["id_usuario"]){ ?>
					<!-- 1 = Em Aberto, 2 = Em Andamento, 3 = Encerrado, 4 = Público -->
					<button id="button_msg" class="btn" onclick="status_msg('<?php echo $id_mensagem; ?>_2')">Quarentena</button>
					<button id="button_msg" class="btn" onclick="status_msg('<?php echo $id_mensagem; ?>_3')">Encerrado</button>
					<!-- mensagem pública ou privada -->
					<?php if($mensagens["status_msg"] != 4){ ?>
						<button id="button_msg" class="btn" onclick="status_msg('<?php echo $id_mensagem; ?>_4')">Público</button>
					<?php }else{ ?>
						<button id="button_msg" class="btn" onclick="status_msg('<?php echo $id_mensagem; ?>_3')">Privado</button>
					<?php } ?>
				</div><br>
		<?php   } } } } ?>
		</div>
		<p id="info_requ">As requisições padrões dos usuários sempre aparecem por aqui, quem decide o que fazer é você ;)</p>
	</div>

	<div id="click_atualiza_on">
		<div id="alinha_topo">
			<h1>Atualizações</h1>
		</div>
	</div>

	<div id="click_relatorio_on">
		<div id="alinha_topo">
			<h1>Relatório</h1>
		</div>
	</div>

<script type="text/javascript">
	function status_msg(status){

		// Verificar se o valor contêm uma underline para poder executar a operação de exclusão ou atualização
		if(!status.includes("_")){
			if(confirm("Deseja realmente apagar está mensagem?") == true){
		 		window.location.href = "atualiza_mensagem.php?string="+ status;
			}
		}else{
			window.location.href = "atualiza_mensagem.php?string="+ status;
		}
	}
</script>
<script type="text/javascript" src="../js/jquery.1.11.1.js"></script> 
<script type="text/javascript" src="../js/bootstrap.js"></script> 
<script type="text/javascript" src="../js/SmoothScroll.js"></script> 
<script type="text/javascript" src="../js/nivo-lightbox.js"></script> 
<script type="text/javascript" src="../js/jquery.isotope.js"></script> 
<script type="text/javascript" src="../js/jqBootstrapValidation.js"></script> 
<script type="text/javascript" src="../js/main.js"></script>
<script type="text/javascript" src="../js/central_config.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>

</body>
</html>