<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<title>Edição de Perfil</title>
	<meta charset="utf-8">
  	<meta name="viewport" content="width=device-width, initial-scale=1">
  	<link rel="shortcut icon" href="../img/Icone/gamerx.png">

	<!-- CSS -->
	<link rel="stylesheet" type="text/css" href="../css/style_perfil.css">
	<link rel="stylesheet" type="text/css" href="../css/input.css">
	<link rel="stylesheet" type="text/css" href="../css/textos.css">
	<link rel="stylesheet" type="text/css" href="../css/responsividade.css">

	<!-- Bootstrap -->
	<link rel="stylesheet" type="text/css" href="../css/bootstrap.css">
	<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
	<link rel="stylesheet" type="text/css" href="../fonts/font-awesome/css/font-awesome.css">
	<?php 
	
	include_once "../php_suporte/verifica_sessao2.php";
	include_once "../php_suporte/old_conexao.php";

	$nome_imagem = "Sem imagem!";
	$userid = $_SESSION["id_usuario"];

	// Função para fornecer os dados do usuário
	$busca_usuario = "SELECT * from usuarios where userid = '$userid'";
	$executa_usuario = $conexao->query($busca_usuario);

	if($executa_usuario->num_rows > 0){
		while ($dados = $executa_usuario->fetch_assoc()) {
			$nome_user = $dados["username"];
			$numero_telefone = $dados["numerotel"];
			$nome_imagem = $dados["nome_foto"];
			$preferencia = $dados["preferencia"];
			$data_nascimento = $dados["data_nascimento"];
			$recorda = $dados["recorda"];
		}
	}
	
	// Mudar a cor do fundo da página
	if(strlen($preferencia) > 0){
  	$styleBlock = sprintf('
    <style type="text/css">
       	#fundo_perfil{
        	background-color: %s;
	    }
	   	input:checked[type="checkbox"]#check_add{
			background: %s;
			box-shadow: 0px 0px 10px black;
  		}
    </style>', $preferencia, $preferencia);
	}else{
		$styleBlock = sprintf('
    <style type="text/css">
       #fundo_perfil{
         background-color: grey;
       }
    </style>');
	} echo $styleBlock; ?>
	<script type="text/javascript">
	function atualiza_session(dado){
		window.location.href = "muda_status_recorda.php?estado="+ dado;
	}
	function retorna_central(){
		const quadro_add = document.querySelector("#quadro_edita");
		quadro_add.style.animation = "gira_fundo .5s";

		const fundo = document.querySelector(".fundo_puxado");
		fundo.style.animation = "esconde_bloco .5s";
		fundo.style.opacity = "0";

		setTimeout(function() {
    	 	window.location.href = "../central.php";
		}, 450);
	}
</script>
</head>
<body id="fundo_perfil">

	<div id="atalho_lateral" onclick="retorna_central()">
		<h1>V<br>o<br>l<br>t<br>a<br>r</h1>
	</div>

	<!-- Cabeçalho -->
	<nav id="menu" class="navbar navbar-default navbar-fixed-top">
    	<div class="container">

    	<div class="navbar-header">    
      		<a href="#" class="btn btn-custom btn-lg page-scroll" id="edita">Edição de Perfil</a>

      		<?php if(isset($_SESSION["msg_user"])){
				echo "<h3 id='atualizado_ok'>".$_SESSION["msg_user"]."</h3>";
				unset($_SESSION["msg_user"]);
			}?>
      	</div>
      		
      		<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1"><span class="sr-only">Toggle navigation</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button>

      		<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      			<ul class="nav navbar-nav navbar-right">
					<li id="deslg"><a href="../php_suporte/redireciona_logoff.php" id="deslogar" class="btn btn-custom btn-lg page-scroll" style="color: red;">Deslogar</a></li>
				</ul>
			</div>
    	</div>
	</nav>

	<form enctype="multipart/form-data" action="salva_perfil.php" method="post" id="quadro_edita">

		<div id="imagem_texto">
  <?php if(strlen($nome_imagem) > 0 ){ ?>
			<img src="../img/perfil/<?php echo $nome_imagem ?>" id="img_perfil_edit" alt="Sua foto de perfil!"> 
  <?php } else { ?>
			<img src="../img/icone/usuario.png" id="img_perfil_edit">
  <?php } ?>

            <input type="file" id="arquivo_imagem" size="100" name="arq" value="<?php echo $nome_imagem ?>" class="btn">
            
            <input type="text" name="iduser" value="<?php echo $userid ?>" style="display: none">
        </div>

	<div id="insere_dados">
        <div class="Input" id="bloco_insere_x">
    		<input type="text" id="input" class="Input-text telefone_x_input" name="telefone" value="<?php echo $numero_telefone ?>" maxlength="13">
    		<label for="input" class="Input-label telefone_x_label">Telefone</label><br>
		</div>

		<div class="Input" id="bloco_insere_x">
    		<input type="text" id="input" class="Input-text" name="data_nascimento" value="<?php echo $data_nascimento ?>" maxlength="10">
    		<label for="input" class="Input-label telefone_x_label">Data de Nascimento</label><br>
		</div>

		<?php
		// Verifica se a opção de download automático está ativada
		if(isset($recorda)){
        	if($recorda == 1){
          		$checkbox = "checked";
        	}else{
          		$checkbox = null;
        	}
      	}else{
        	$checkbox = null;
      	} ?>
		<h3 id="titulo">Recordações <input type="checkbox" id="check_add" onclick="atualiza_session(<?php echo $recorda ?>)" <?php echo $checkbox; ?>></h3>
  	</div>

		<!-- Palheta de cores de Fundo -->
		<div id="colorama_perfil_edit">	
			<h3>Cor de Fundo</h3>

			<label class="color_radio">
				<div class="color-responsive" style="background-color: #229E7B" onclick="escolhe(this)">
				<input id="radio" type="radio" name="color_radio" value="#229E7B"></div>
				<i class="glyphicon glyphicon-ok hidden"></i>
			</label>

			<label class="color_radio">
				<div class="color-responsive" style="background-color: #11E8D6" onclick="escolhe(this)">
				<input id="radio" type="radio" name="color_radio" value="#11E8D6"></div>
				<i class="glyphicon glyphicon-ok hidden"></i>
			</label>

			<label class="color_radio">
				<div class="color-responsive" style="background-color: #0F68A3" onclick="escolhe(this)">
				<input id="radio" type="radio" name="color_radio" value="#0F68A3"></div>
				<i class="glyphicon glyphicon-ok hidden"></i>
			</label>

			<label class="color_radio">
				<div class="color-responsive" style="background-color: #003264" onclick="escolhe(this)">
				<input id="radio" type="radio" name="color_radio" value="#003264"></div>
				<i class="glyphicon glyphicon-ok hidden"></i>
			</label>

			<label class="color_radio">
				<div class="color-responsive" style="background-color: #6E0C91" onclick="escolhe(this)">
				<input id="radio" type="radio" name="color_radio" value="#6E0C91"></div>
				<i class="glyphicon glyphicon-ok hidden"></i>
			</label>

			<label class="color_radio">
				<div class="color-responsive" style="background-color: #D60A7D" onclick="escolhe(this)">
				<input id="radio" type="radio" name="color_radio" value="#D60A7D"></div>
				<i class="glyphicon glyphicon-ok hidden"></i>
			</label><br>

			<label class="color_radio">
				<div class="color-responsive" style="background-color: #F820C9" onclick="escolhe(this)">
				<input id="radio" type="radio" name="color_radio" value="#F820C9"></div>
				<i class="glyphicon glyphicon-ok hidden"></i>
			</label>

			<label class="color_radio">
				<div class="color-responsive" style="background-color: #F82039" onclick="escolhe(this)">
				<input id="radio" type="radio" name="color_radio" value="#F82039"></div>
				<i class="glyphicon glyphicon-ok hidden"></i>
			</label>

			<label class="color_radio">
				<div class="color-responsive" style="background-color: #ED7E16" onclick="escolhe(this)">
				<input id="radio" type="radio" name="color_radio" value="#ED7E16"></div>
				<i class="glyphicon glyphicon-ok hidden"></i>
			</label>

			<label class="color_radio">
				<div class="color-responsive" style="background-color: #F8E910" onclick="escolhe(this)">
				<input id="radio" type="radio" name="color_radio" value="#F8E910"></div>
				<i class="glyphicon glyphicon-ok hidden"></i>
			</label>

			<label class="color_radio">
				<div class="color-responsive" style="background-color: #A4F324" onclick="escolhe(this)">
				<input id="radio" type="radio" name="color_radio" value="#A4F324"></div>
				<i class="glyphicon glyphicon-ok hidden"></i>
			</label>

			<label class="color_radio">
				<div class="color-responsive" style="background-color: #42E20D" onclick="escolhe(this)">
				<input id="radio" type="radio" name="color_radio" value="#42E20D"></div>
				<i class="glyphicon glyphicon-ok hidden"></i>
			</label>
		</div>

		<input type="submit" value="Salvar" class="btn" id="botao_salva">
	</form>

	<div class="fundo_puxado"></div>
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