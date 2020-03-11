<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<title>Edição de Usuário</title>
	<meta charset="utf-8">
  	<meta name="viewport" content="width=device-width, initial-scale=1">
  	<link rel="shortcut icon" href="../img/Icone/gamerx.png">

	<!-- CSS -->
	<link rel="stylesheet" type="text/css" href="../css/style_perfil.css">
	<link rel="stylesheet" type="text/css" href="../css/input.css">
	<link rel="stylesheet" type="text/css" href="../css/textos.css">
    <link rel="stylesheet" type="text/css" href="../css/responsividade.css">
    <link rel="stylesheet" type="text/css" href="../css/central.css">

	<!-- Bootstrap -->
	<link rel="stylesheet" type="text/css" href="../css/bootstrap.css">
	<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
	<link rel="stylesheet" type="text/css" href="../fonts/font-awesome/css/font-awesome.css">
	<?php 
	
	include_once "../php_suporte/verifica_sessao2.php";
	include_once "../php_suporte/old_conexao.php";

	$userid = $_GET["id_user"];

	// Função para fornecer os dados do usuário
	$busca_usuario = "SELECT * from usuarios where userid = '$userid'";
	$executa_usuario = $conexao->query($busca_usuario);

	if($executa_usuario->num_rows > 0){
		while ($dados = $executa_usuario->fetch_assoc()) {
			$nome_user = $dados["username"];
			$numero_telefone = $dados["numerotel"];
			$nome_imagem = $dados["nome_foto"];
            $preferencia = $dados["preferencia"];
            $hierarquia = $dados["hierarquia"];
		}
    }

    if($hierarquia == 1){
        $checkbox = "checked";
    }else{
        $checkbox = "";
    }

	// Mudar a cor do fundo da página
	if(isset($_SESSION["color_back"])) {
  	$styleBlock = sprintf('
    <style type="text/css">
       #fundo_perfil{
         background-color: %s;
	   }
	   img#img_perfil_config{
		 border: 10px solid %s;
		 box-shadow: 0px 0px 15px %s;
	   }
       input:checked[type="checkbox"]#check_add{
		background: %s;
	   }
    </style>', $_SESSION["color_back"], $preferencia, $preferencia, $preferencia);
	}else{
		$styleBlock = sprintf('
    <style type="text/css">
       #fundo_perfil{
         background-color: grey;
	   }
	   img#img_perfil_config{
		 border: 10px solid grey;
		 box-shadow: 0px 0px 15px black;
	   }
       input:checked[type="checkbox"]#check_add{
		background: %s;
	   }
    </style>', $preferencia);
	} echo $styleBlock;
	
	if($_SESSION["color_back"] == $preferencia){
		$styleBlock = sprintf('
    <style type="text/css">
	   img#img_perfil_config{
		 border: 10px solid white;
		 box-shadow: 0px 0px 15px black;
	   }
       input:checked[type="checkbox"]#check_add{
		box-shadow: 0px 0px 15px black;
	   }
    </style>');
	echo $styleBlock;
	} ?>
    
</head>
<body id="fundo_perfil">

	<!-- Cabeçalho -->
	<nav id="menu" class="navbar navbar-default navbar-fixed-top">
    	<div class="container">

    	<div class="navbar-header">    
              <a href="#" class="btn btn-custom btn-lg page-scroll" id="edita">Edição de Usuário</a>
              
      		<?php if(isset($_SESSION['msg_salva'])){
				echo "<h3 id='atualizado_ok'>".$_SESSION['msg_salva']."</h3>";
				unset($_SESSION['msg_salva']);
			}?>
      	</div>
            <iframe src="usuarios_on.php" id="usuarios_on_iframe_confg" name="content" marginheight="0" scrolling="no" frameborder="0" hspace="0" vspace="0" allowtransparency="true" application="true"></iframe>

      		<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1"><span class="sr-only">Toggle navigation</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button>

      		<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      			<ul class="nav navbar-nav navbar-right">
					<li><a href="configura_central.php" class="btn btn-custom btn-lg page-scroll">Voltar</a></li>
					<li id="deslg"><a href="../php_suporte/redireciona_logoff.php" id="deslogar" class="btn btn-custom btn-lg page-scroll" style="color: red;">Deslogar</a></li>
				</ul>
			</div>
    	</div>
	</nav>
    
	<form enctype="multipart/form-data" action="atualiza_usuario.php" method="post" id="quadro_edita">

		<div id="imagem_texto">
  <?php if(strlen($nome_imagem) > 0 ){ ?>
			<img src="../img/perfil/<?php echo $nome_imagem ?>" id="img_perfil_config" alt="Sua foto de perfil!"> 
  <?php } else { ?>
			<img src="../img/icone/usuario.png" id="img_perfil_config">
  <?php } ?>
            <h2 id="nome_usuario_2"> <?php echo $nome_user; ?></h2>
            <input type="text" name="iduser" value="<?php echo $userid ?>" style="display: none">
        </div>
	
	<div id="insere_dados">
        <?php if($userid != $_SESSION["id_usuario"] && $userid != 1){ ?><br>
        <div class="Input" id="bloco_insere_x">
    		<input type="text" id="input" class="Input-text telefone_x_input" name="telefone" value="<?php echo $numero_telefone ?>" maxlength="10" readonly>
    		<label for="input" class="Input-label telefone_x_label">Telefone em uso</label><br>
		</div>
	</div>

        <div id="adm_updt">
            <?php if($hierarquia == 0){ ?>
                <h2>Este usuário não é um administrador</h2>
            <?php } else { ?> 
                <h2>Este usuário é um administrador</h2>
            <?php } ?>

            <h4 id="modera_text">Remover moderação <input type="checkbox" name="adm_add" id="check_add" <?php echo $checkbox; ?>> Ativar Moderação</h4>
        </div>
        
		<input type="submit" value="Atualizar" class="btn" id="botao_salva">
		
		<?php }else if($userid != 1 || $_SESSION["id_usuario"] == 1 ){ ?>
			<div id="auto_user">
                <h2>Olá! ;) <br> É muito bom ver você por aqui</h2>
                <hr>
				<p>Infelizmente não é possível se auto-atualizar por este lugar, para fazer isso volte até o início vá para o Perfil->Configurações, lá tem tudo o que você precisa!</p>
			</div>
		<?php }else{ ?>
			<div id="auto_user">
                <h2>Calma, vamos aos poucos!</h2>    
                <hr>
                <p>Não tome controle de tudo! :0 Prezamos pela ordem e a boa conduta, por esse e muitos outros motivos mantemos pelo menos 2 administradores ativos simultâneamente.</p>
			</div>
		<?php } ?>
    </form>

<script type="text/javascript" src="../js/jquery.1.11.1.js"></script> 
<script type="text/javascript" src="../js/bootstrap.js"></script> 
<script type="text/javascript" src="../js/SmoothScroll.js"></script> 
<script type="text/javascript" src="../js/nivo-lightbox.js"></script> 
<script type="text/javascript" src="../js/jquery.isotope.js"></script> 
<script type="text/javascript" src="../js/jqBootstrapValidation.js"></script> 
<script type="text/javascript" src="../js/main.js"></script>
<script type="text/javascript" src="../js/funcoes.js"></script>
</body>
</html>