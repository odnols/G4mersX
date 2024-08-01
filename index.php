<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <title>G4mers_X</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="shortcut icon" href="img/Icone/gamerx.png">

  <meta name="description" content="uso_privado">
  <meta name="author" content="alonso">

  <!-- CSS -->
  <link rel="stylesheet" type="text/css" href="css/style.css">
  <link rel="stylesheet" type="text/css" href="css/input.css">

  <!-- Bootstrap -->
  <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
  <link rel="stylesheet" type="text/css" href="css/responsividade.css">
  <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
  <link rel="stylesheet" type="text/css" href="fonts/font-awesome/css/font-awesome.css">

  <script type="text/javascript" src="js/funcoes.js"></script>
  <?php include_once "php_suporte/old_conexao.php";

  $idusuario = 1;

  $hoje_ = "SELECT * from usuarios";
  $executa_hj = $conexao->query($hoje_);

  if ($executa_hj->num_rows > 0) {
    while ($hoje_dia = $executa_hj->fetch_assoc()) {
      if ($idusuario < $hoje_dia["userid"] || $idusuario == $hoje_dia["userid"]) {
        $idusuario = $idusuario + 1;
      }
    }
  } ?>
  <script type="text/javascript" src="js/funcoes_index.js"></script>
</head>

<body>

  <div id="fundo">
    <!-- Seção de Login -->
    <nav id="menu" class="navbar navbar-default navbar-fixed-top">
      <div class="container">

        <div class="navbar-header">
          <a href="" class="btn btn-custom btn-lg page-scroll" id="gmx">G4mers_X</a>
        </div>

        <a href="" class="btn btn-custom btn-lg page-scroll" id="gmx" style="float: right">Versão 0.6.X</a>
        <?php if (isset($_GET["ERROR"])) {
          $error = $_GET["ERROR"];

          if ($error == 001) {
            echo "<h5 style='color: red' id='index_alerta'>Dados incorretos, tente novamente.</h5>";
          } else if ($error == 002) {
            echo "<h5 style='color: red' id='index_alerta'>Não é permitido login simultâneo.</h5>";
          } else {
            echo "<h5 style='color: red' id='index_alerta'>Erro desconhecido, tente novamente mais tarde.</h5>";
          }
        } ?>
      </div>
      <!-- <audio autoplay><source src="EntradaApp.mp3" type="audio/mp3"></audio> -->
    </nav>

    <div id="img">
      <img id="img_ind" src="img/Icone/gamerx.png" width="700" height="600">
    </div>

    <div class="loga"><br><br>
      <!-- Botão de login e cadastro -->
      <div id="botoes_index">
        <button id="cadastrar_button" class="btn btn-custom btn-lg page-scroll">Fazer Cadastro</button>
        <button id="logar_button" class="btn btn-custom btn-lg page-scroll">Fazer Login</button>
      </div>
      <!-- Formulário de Login -->
      <form name="loga" class="Logah" action="php_suporte/confirma_login.php" method="post">
        <h1 id="titulo">Login</h1>
        <div class="Input">
          <input type="text" id="inputlogin" class="Input-text" name="email" required="" placeholder="Email ou Identificador">
        </div>

        <div class="Input">
          <input type="password" id="inputlogin" class="Input-text" name="senha" required="" placeholder="Senha">
        </div><br>

        <button class="btn btn-custom btn-lg page-scroll" id="configura_perfil">Entrar</button>
        <hr id="hr_log">
      </form>

      <!-- Formulário de Cadastro -->
      <form name="cadastro" class="cadastrah" action="php_suporte/recebe_cadastro.php" method="post">
        <h1 id="titulo">Cadastro</h1>
        <div class="Input">
          <input type="text" id="inputlogin" class="Input-text" name="nome" required="" placeholder="Seu Nome">
        </div>

        <div class="Input">
          <input type="text" id="inputlogin" name="email" class="Input-text" required="" placeholder="Email" maxlength="50">
        </div>

        <div class="Input">
          <input type="password" id="inputlogin" class="Input-text" name="senha" required="" placeholder="Senha">
        </div>

        <div class="Input">
          <input type="text" id="inputlogin" name="id" class="Input-text" value="<?php echo $idusuario ?>" style="display: none">
        </div>

        <button class="btn btn-custom btn-lg page-scroll" id="configura_perfil">Cadastrar</button>
        <hr id="hr_log">
      </form>

      <p style="color: white"><em>O G4mers_X é um sisteminha bacanudo para fazer vários nadas :D</em></p>
      <h6 style="color: white">Todos os direitos reservados <a id="link" href="gamersx.php"><em>G4mersx</em></a> Copyright © 2019.</h6>
    </div>
  </div>

</body>

</html>