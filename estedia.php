<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <title>Este dia na História</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="shortcut icon" href="img/Icone/gamerx.png">

  <!-- CSS -->
  <link rel="stylesheet" type="text/css" href="css/style.css">
  <link rel="stylesheet" type="text/css" href="css/input.css">
  <link rel="stylesheet" type="text/css" href="css/textos.css">
  <link rel="stylesheet" type="text/css" href="css/historicalissimo.css">
  <link rel="stylesheet" type="text/css" href="css/responsividade.css">

  <!-- Bootstrap -->
  <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
  <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
  <link rel="stylesheet" type="text/css" href="fonts/font-awesome/css/font-awesome.css">

  <?php
  $data = $_GET["data"];
  session_start();

  include_once "php_suporte/verifica_sessao.php";

  include_once "php_suporte/old_conexao.php";

  $querySelecao = "SELECT * FROM balanco where datablan = '$data'";
  $resultado = $conexao->query($querySelecao);

  if ($resultado->num_rows > 0) {
    while ($arquivo = $resultado->fetch_assoc()) {
      $categoria = $arquivo["categoria"];
      $nome_imagem = $arquivo["nome_imagem"];
      $acontecimento = $arquivo["dia_marcado"];

      if (strlen($categoria) > 0) {
        if ($categoria == 0) {
          $checkbox = "checked";
        } else {
          $checkbox = "";
        }
      } else {
        $checkbox = null;
      }

      if ($arquivo["descricao"] == null) {
        $descricao = "";
      } else {
        $descricao = $arquivo["descricao"];
      }
    }
  }

  $styleBlock = sprintf('
  <style type="text/css">
     #click_historico2{
        background-color: #5D0D03;
      }
      #descricao_dia{
        border-color: #430B03;
      }
      input:checked[type="checkbox"]#checkbox_historico{
        background: #722626;
      }
  </style>');

  if ($categoria == 0 && $categoria != null) {
    echo $styleBlock;
  } ?>

  <script type="text/javascript">
    function _apagar() {

      var confirma = confirm("Deseja mesmo remover a marcação de dia histórico? :(");
      if (confirma == true) {
        window.location.href = "php_suporte/remover_marcacao.php?data=<?php echo $data; ?>";
      }
    }
  </script>
</head>

<body onload="atualiza()" id="historica">
  <nav id="menu" class="navbar navbar-default navbar-fixed-top">
    <div class="container">

      <!-- Lista de usuários online na barra de atalho -->
      <iframe src="php_configura/usuarios_on.php" id="usuarios_on_iframe" name="content" marginheight="0" scrolling="no" frameborder="0" hspace="0" vspace="0" allowtransparency="true" application="true"></iframe>

      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1"><span class="sr-only">Toggle navigation</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button>

      <div class="navbar-header">
        <a class="navbar-brand page-scroll navbar-right">
          <h4><em>De volta ao dia <?php echo date('d-m-Y', strtotime($data)) ?></em></h4>
        </a>
      </div>

      <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
        <ul class="nav navbar-nav navbar-right">
          <li><a href="central.php" class="btn btn-custom btn-lg page-scroll">Retornar a central</a></li>
          <?php if ($_SESSION["hierarquia"] == 1 && $categoria != null) { ?>
            <li><button id="apagar" onclick="_apagar()" class="btn">Apagar</button></li>
          <?php } else { ?>
            <li><button id="apagar_" readonly title="Atualize o dia pelo menos 1 vez antes de poder apagar!" class="btn">Apagar</button></li>
          <?php } ?>
        </ul>
      </div>
    </div>
  </nav>

  <div id="click_historico2">
    <div id="imagem_este">
      <?php if (strlen($nome_imagem) > 0) { ?>
        <img class="img_destaq" src="img/histórico/<?php echo $nome_imagem; ?> ">

        <img class="img_destaq_fun" src="img/histórico/<?php echo $nome_imagem; ?> ">
        <img class="img_destaq_fun2" src="img/histórico/<?php echo $nome_imagem; ?> ">
      <?php } else { ?>

        <img class="img_destaq" src="img/fundo/Historico.png ">
      <?php }
      // Convertendo as descrições para algo mais refinado
      $acontecimento = str_replace(" e ", " & ", $acontecimento);
      ?>
      <form enctype="multipart/form-data" action="php_suporte/dia_historico.php" method="post">
        <div id="imagem_texto">

          <input name="data" type="text" value="<?php echo $data ?>" style="display: none">
          <?php if ($_SESSION["hierarquia"] == 1) { ?>

            <input type="file" id="arquivo_imagem" size="60" name="arq" value="<?php echo $nome_imagem ?>">
          <?php } ?>
        </div>
    </div>
  </div>

  <!-- Enviando a descrição do dia histórico-->
  <div id="texto_este">
    <h1 id="titulo"><?php echo $acontecimento ?></h1><br>
    <?php if ($_SESSION["hierarquia"] == 1) { ?>

      <?php if ($descricao == null) {
        echo "<textarea rows='8' cols='45' maxlength='250' placeholder='Clique aqui e faça um pequeno resumo de 250 caracteres definindo este dia.' name='descricao' id='descricao_dia'></textarea>";
      } else {
        echo "<textarea rows='8' cols='45' maxlength='250' placeholder='$descricao' value='$descricao' name='descricao' id='descricao_dia'></textarea>";
      } ?>

      <!-- Operador para descobrir se é dia bom ou ruim -->
      <h3 id="titulo">Dia bom <input type="checkbox" name="categoria" id="checkbox_historico" value="on" <?php echo $checkbox; ?>> Dia ruim</h3>

      <input type="submit" id="submit_geral" value="Atualizar este Dia" class="btn historic">
    <?php } else {
      echo "<textarea rows='8' cols='45' maxlength='250' placeholder='$descricao' readonly id='descricao_dia'></textarea>";
    } ?>
    </form>
    <div id="info_hist">
      <?php // Mensagem de feedback
      if (isset($_SESSION["msg_historic"])) {
        echo $_SESSION["msg_historic"];
        // Matando o $_SESSION para não aparecer novamente caso atualize a página
        unset($_SESSION["msg_historic"]);
      } ?>
    </div>
  </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
  <script type="text/javascript" src="js/bootstrap.js"></script>
  <script type="text/javascript" src="js/SmoothScroll.js"></script>
  <script type="text/javascript" src="js/nivo-lightbox.js"></script>
  <script type="text/javascript" src="js/main.js"></script>
</body>

</html>