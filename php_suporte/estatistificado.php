<!DOCTYPE html>
<html lang="pt-BR">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="shortcut icon" href="../img/Icone/gamerx.png">

	<!-- CSS -->
	<link rel="stylesheet" type="text/css" href="../css/style_sol.css">
	<link rel="stylesheet" type="text/css" href="../css/input.css">
	<link rel="stylesheet" type="text/css" href="../css/textos.css">
	<link rel="stylesheet" type="text/css" href="../css/responsividade.css">

	<!-- Bootstrap -->
	<link rel="stylesheet" type="text/css" href="../css/bootstrap.css">
	<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
	<link rel="stylesheet" type="text/css" href="../fonts/font-awesome/css/font-awesome.css">

	<?php
	session_start();

	$data = $_GET["data"];
	$twonumbernine = 0;
	$ano = 0;
	$total = 0;
	$maior = 0;
	$menor = 0;
	$diamaior = 0;
	$diapior = 100000;
	$melhordia = 0;
	$datamelhor = 0;
	$datapior = 0;
	$pares = 0;
	$impares = 0;
	$dias_contados = 0;
	$progresso_anual = 0;
	$dias_importants = 0;
	$mes = array('', 'Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro');

	// Variáveis do Gráfico
	$melhordia_grafic = 0;
	$total_dias = 0;

	// Convertendo o valor do $date para o que está no banco
	$data_convertida = str_replace(".", "-", $data);

	// Verificando se existe mensagens no Session
	if (isset($_SESSION['msg'])) {
		echo "<div id='pop-up'><h1>" . $_SESSION['msg'] . "</h1></div>";
		// Matando o $_SESSION para não aparecer novamente caso atualize a página
	}

	// Verificando a entrada é anual ou mensal
	if (strlen($data > 4) || strlen($data = 4)) {
		if (strlen($data > 4)) {
			$recolhe = "SELECT * FROM balanco where datablan LIKE '$data_convertida%'";
		} else {
			$ano = $data;
			$recolhe = "SELECT * FROM balanco where datablan LIKE '$ano%'";
		}
	}
	// Pegando todos os valores do banco de dados
	if ($data == 1000) {
		$recolhe = "SELECT * FROM balanco";
		$twonumbernine = "Global";
	}
	// Fazendo os imports dos arquivos principais
	include_once "verifica_sessao2.php";
	include_once "old_conexao.php";

	$grafico_total = $conexao->query($recolhe);

	// Recolhendo as datas & contando o numero de dias do ano
	date_default_timezone_set('America/Sao_Paulo');
	$date = date('Y-m-d');
	$date2 = date('d/m');
	$date3 = date('Y');

	$converte_data = date('Y', strtotime($data));
	$dataInicial = $data . "-01-01";
	$dataFinal = $data . "-12-31";

	// Calcula a diferença em segundos entre as datas
	$diferenca = strtotime($dataFinal) - strtotime($dataInicial);

	//Calcula a diferença em dias do ano
	$dias = floor($diferenca / (60 * 60 * 24)) + 1;

	// Mudar a cor do fundo da página
	if (isset($_SESSION["color_back"])) {
		$styleBlock = sprintf('
	<style type="text/css">
		input:checked[type="checkbox"]#check_add{
		  	background: %s;
		}
	}
	</style>', $_SESSION["color_back"]);
		echo $styleBlock;
	}

	if ($grafico_total->num_rows > 0) {
		while ($grafico = $grafico_total->fetch_assoc()) {
			$total_dias = $total_dias + 1;
			if ($grafico["qtdmens"] > $melhordia_grafic) {
				$melhordia_grafic = $grafico["qtdmens"];
			}
			// Quantidade de dias no sistema e total de mensagens
			$qtdmsg = $grafico["qtdmens"];
			$total = $qtdmsg + $total;

			// Quantidade de Dias catalogados & progresso anual / media
			$dias_contados = $dias_contados + 1;
			$media = $total / $dias_contados;
			$progresso_anual = ($dias_contados / $dias) * 100;
		}
	}

	// Convertendo o mês para exibir o nome e verificando o ano
	if (strlen($data) > 4) {
		if (substr($data, -2) == 10 || substr($data, -2) == ".1") {
			$nome_mes = "10";
		} else {
			$nome_mes = substr($data, -2);
			$nome_mes = str_replace("0", "", $nome_mes);
		}
	} else {
		$nome_mes = $data;
	}
	// Verificando se o ano é anterior ao atual
	$ano_convertido = date('Y', strtotime($data_convertida));
	?>

	<!-- Cabeçalho da página -->
	<title>Estatísticas de <?php if ($twonumbernine) {
								echo "$twonumbernine";
							} else if ($data_convertida) {
								echo "$data_convertida";
							} else {
								echo "$ano ";
							} ?></title>

	<script type="text/javascript">
		// Função que cria o gráfico
		<?php if ($data != 1000) { ?>

			function grafico_exe() {
				var canvas = document.getElementById("canvasGrafico");
				if (canvas) {
					var altura = <?php echo $melhordia_grafic + 50 ?>;
					var largura = 1250;
					//posição horizontal inicial do gráfico
					var x = 0;
					//valor dos pontos do gráfico, que será alterado aleatoriamente
					var valor;
					//formatando a canvas
					canvas.setAttribute("width", largura);
					canvas.setAttribute("height", altura);
					//obtendo o contexto 2d
					var ctx = canvas.getContext("2d");
					var ctx2 = canvas.getContext("2d");
					var ctx3 = canvas.getContext("2d");

					ctx.fillStyle = "#010112";
					ctx.fillRect(0, 0, largura, altura);
					ctx.font = "30px Courier";
					ctx.strokeStyle = "#2BCBCE";

					function desenharGrafico() {
						<?php
						$grafico2 = $conexao->query($recolhe);

						if ($grafico2->num_rows > 0) { ?>
							<?php while ($grafico = $grafico2->fetch_assoc()) { ?>

								var valor_captado = <?php echo $grafico["qtdmens"]; ?>;
								var data = <?php echo $grafico["datablan"]; ?>;

								//gera um valor aleatório entre 0 e 100
								valor = valor_captado;

								//desenha uma linha até a posição gerada
								ctx.lineTo(x, altura - valor);
								ctx.stroke();

								//desenha o texto indicando o valor do gráfico, na posição x atual
								ctx.font = "20px arial";
								if (valor >= 100) {
									ctx.fillStyle = "white";
								} else {
									ctx.fillStyle = "#F40000";
								}

								ctx.fillText(valor, x, altura - valor - 15);
								ctx3.arc(x, altura - valor, 2, 0, 2 * Math.PI);

								x += 1250 / <?php echo $total_dias ?>;
						<?php }
						} ?>
						// Gera um circulo em torno do dia atual no gráfico
						if (data == <?php echo $date ?>) {
							ctx2.arc(x - 1250 / <?php echo $total_dias ?> + 10, altura - valor - 20, 20, 15, 10 * Math.PI);
							ctx2.stroke();
						}
					}
				}
				setTimeout(desenharGrafico(), 50);
				setTimeout(atualizaRelogio(), 0);
			}

			function atualiza_session() {
				window.location.href = "muda_status_download.php?data=<?php echo $data; ?>";
			}
		<?php } ?>
	</script>
</head>

<body id="estatistificado_body">

	<!-- Relógio Atualizado de segundo em segundo -->
	<form style="display:none" name="form_relogio">
		<input type="text" class="Input-text" name="hora_att">
	</form>
	<!-- Gráfico -->
	<?php if ($data != 1000) { ?>
		<div id="fundo_grafic">
			<section id="Grafico">
				<div>
					<canvas id="canvasGrafico"></canvas>
				</div>
				<?php if ($total_dias < 28) {
					echo "<h3 style='color: white'>Note que este mês pode não estar fechado ainda, podendo possuir poucos dias a mostra!</h3>";
				} ?>
			</section>
		</div>
	<?php } ?>

	<!-- Corpo da página -->
	<div id="fundo">
		<nav id="menu" class="navbar navbar-default navbar-fixed-top">
			<div class="container">

				<div class="navbar-header">
					<a class="nav navbar-nav navbar-right" href="#mes" style="color: white; text-decoration: none">
						<h5 id="hora">Dados d<?php if ($twonumbernine) {
													echo "o $twonumbernine";
												} else if (strlen($data) == 4) {
													echo "o Ano de " . $data;
												} else {
													echo "e $mes[$nome_mes]";
												}
												if ($ano_convertido != $date3) {
													echo " de " . $ano_convertido;
												} ?> ][ Agora são <em id="relogio"></em> de <em><?php echo "$date2" ?></em></h5>
					</a>
					<a class="nav navbar-nav navbar-right" href="#">
						<h5 id="total_back"><?php echo "Total de mensagens enviadas: <em id='num' style='color: yellow'>$total</em>" ?></h5>
					</a>
					<h5 id="media_back"><?php

										// Informação do progresso anual
										if (strlen($data) == 4 && $data != 1000) {
											echo "Progresso anual: <em id='num' style='color: #F67C03'>" . round($progresso_anual, 2) . "%</em>";
										}
										// Calculando a média de mensagens no mês
										if ($dias_contados < 32 && strlen($data_convertida) != 4 && $dias_contados != 1) {
											echo "Média de mensagens: <em id='num' style='color: #F67C03'>" . round($media, 0) . "</em>";
										}

										?></h5>
					<h6><?php
						// Aviso de possibilidade de erros sobre os dados 
						if ($data == 1000 || $data < $date3) {
							echo "<div id='lembrete_peqno'>Lembre-se que os dados podem conter divergências e não representarem 100% do real total, a taxa de acertos está em 99.9998%</div>";
						} ?></h6>
					<div id="pop-up2"><?php
										// Verificando se existe mensagens no Session
										if (isset($_SESSION['msg'])) {
											echo "<h6>" . $_SESSION['msg'] . "</h6>";
											// Matando o $_SESSION para não aparecer novamente caso atualize a página
											unset($_SESSION['msg']);
										}
										?></div>
				</div>

				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1"><span class="sr-only">Toggle navigation</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button>

				<!-- Collect the nav links, forms, and other content for toggling -->
				<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
					<ul class="nav navbar-nav navbar-right">
						<?php if ($data != 1000) { ?>
							<li id="click_grafical"><a href="#" class="btn btn-custom btn-lg page-scroll">Gráfico</a></li><?php } ?>
						<?php if ($data != 1000 && $_SESSION["hierarquia"] == 1) { ?>
							<li><a href="criar_arquivo.php?data=<?php echo $data ?>" class="btn btn-custom btn-lg page-scroll">Criar um .txt</a></li>
						<?php } ?>
						<li><a href="../central.php" class="btn btn-custom btn-lg page-scroll">Voltar</a></li>
						<li id="deslg"><a href="../php_suporte/redireciona_logoff.php" id="deslogar" class="btn btn-custom btn-lg page-scroll" style="color: red;">Deslogar</a></li>
					</ul>
				</div>
				<?php
				// Verifica se a opção de download automático está ativada
				if (isset($_SESSION["download_auto"])) {
					if ($_SESSION["download_auto"] == 1) {
						$checkbox = "checked";
					} else {
						$checkbox = null;
					}
				} else {
					$checkbox = null;
				}
				?>
				<?php if ($data != 1000) { ?>
					<div id="download_automatic">
						<h3 id="titulo" class="check_box_download">Download automático<input type="checkbox" id="check_add" onclick="atualiza_session()" <?php echo $checkbox; ?>></h3>
					</div>
				<?php } ?>
			</div>
		</nav>

		<div id="mes">
			<?php
			// Aviso de possibilidade de erros sobre os dados 
			if ($data == 1000 || $data < $date3) {

				echo "<div id='lembrete'>Lembre-se que os dados podem conter divergências e não representarem 100% do real total, a taxa de acertos está em 99.9998%</div>";
			} ?>

			<?php
			// Fazendo a requisição dos dados do mês, do ano & Global
			$resultado = $conexao->query($recolhe);

			if ($resultado->num_rows > 0) {
			?>

				<div class="alinhadora">
					<table id="tabela_">
						<tr id="linhatabela">
							<td>Data</td>
							<td>Mensagens enviadas</td>
						</tr>
						<?php

						// Calculando o total de mensagens do mês, do ano & Global
						if ($resultado->num_rows > 0) {
							while ($linha = $resultado->fetch_assoc()) {

								echo "<tr id='linha_tab'><td>" . date('d/m/Y', strtotime($linha['datablan'])) . "</td> <td>" . $linha["qtdmens"] . "</td></tr>";
								echo "<tr></tr>";
								// Quantidade de dias no sistema e total de mensagens
								$qtdmsg = $linha["qtdmens"];

								// Melhor dia
								if ($melhordia < $linha["qtdmens"]) {
									$melhordia = $linha["qtdmens"];
									$datamelhor = $linha["datablan"];
								}
								// Pior dia
								if ($diapior > $linha["qtdmens"]) {
									$diapior = $linha["qtdmens"];
									$datapior = $linha["datablan"];
								}
								// Quantidade de Dias ruins
								if ($qtdmsg < 100) {
									$menor = $menor + 1;
								}
								// Quantidade de Dias bons
								if ($qtdmsg >= 100) {
									$maior = $maior + 1;
								}
								// Calcular Par ou ímpar
								if ($qtdmsg % 2 == 0 && $qtdmsg % 1 == 0) {
									$pares = $pares + 1;
								} else {
									$impares = $impares + 1;
								}
								// Dias Especiais
								if ($linha["dia_marcado"] != null) {
									$dias_importants = $dias_importants + 1;
								}
							}
						}

						// Informação do progresso anual
						if (strlen($data) == 4 && $data != 1000) {
							echo "<div id='progresso_'><h3>Progresso anual: <em id='num' style='color: #F67C03'>" . round($progresso_anual, 2) . "%</em></h3></div>";
						}

						// Calculando a média de mensagens no mês
						if ($dias_contados < 32 && strlen($data_convertida) != 4 && $dias_contados != 1) {
							echo "<div id='progresso_'><h3>Média de mensagens: <em id='num' style='color: #F67C03'>" . round($media, 0) . "</em></h3></div>";
						}

						echo "</table></div><h2 id='total_msg'>Total de mensagens enviadas: <em id='num'>$total</em></h2><hr>";
						// Infos rápidos mensalmente, anualmente & Globalmente 
						?>
						<div id="alinhadeira_exp2">
							<div id="separador_estatic"></div>
							<div id="melhor_pior" class="colorama">
								<?php if (strlen($data) == 4) { ?>
									<h2><em>Dias catalogados</em></h2>
								<?php
									if ($dias_contados > 1) {
										echo "<h3>Foram registrados <em id='num' style='color: #F67C03'>$dias_contados</em> dias!</h3><hr>";
									} else {
										echo "<h3>Foi registrado apenas <em id='num' style='color: #F67C03'>$dias_contados</em> dia</h3><hr>";
									}
								} ?>

								<h2><em>“O dia +movimentado”</em></h2><?php

																		if (strlen($data) > 4) {
																			echo "<h3>No dia <em class='datas_' id='num'>" . date('d', strtotime($datamelhor)) . "</em> foram enviadas <br>incríveis <em style='color: #0CDD0C' id='num'>$melhordia</em> mensagens!</h3>";
																		} else if ($data == 1000) {
																			echo "<h3>Em <em class='datas_' id='num'>" . date('d/m/y', strtotime($datamelhor)) . "</em> foram enviadas <br>incríveis <em style='color: #0CDD0C' id='num'>$melhordia</em> mensagens!</h3>";
																		} else {
																			echo "<h3>Em <em class='datas_' id='num'>" . date('d/m', strtotime($datamelhor)) . "</em> foram enviadas <br>incríveis <em style='color: #0CDD0C' id='num'>$melhordia</em> mensagens!</h3>";
																		} ?>
								<hr>

								<h2><em>“O dia -movimentado”</em></h2><?php

																		if (strlen($data) > 4) {
																			echo "<h3>No dia <em class='datas_' id='num'>" . date('d', strtotime($datapior)) . "</em> foram enviadas <br>apenas <em style='color: red' id='num'>$diapior</em> mensagens :(</h3>";
																		} else if ($data == 1000) {
																			echo "<h3>Em <em class='datas_' id='num'>" . date('d/m/y', strtotime($datapior)) . "</em> foram enviadas <br>apenas <em style='color: red' id='num'>$diapior</em> mensagens :(</h3>";
																		} else {
																			echo "<h3>Em <em class='datas_' id='num'>" . date('d/m', strtotime($datapior)) . "</em> foram enviadas <br>apenas <em style='color: red' id='num'>$diapior</em> mensagens :(</h3>";
																		} ?>
								<hr>

								<?php if ($dias_importants != 0) { ?>
									<h2><em id="historical">“N° de dias Históricos”</em></h2><?php

																								if ($dias_importants > 1) {
																									echo "<h3 id='historical'>Foram <em id='num'><em> $dias_importants </em></em></h3><hr>";
																								} else {
																									echo "<h3 id='historical'>Foi apenas <em id='num'> $dias_importants </em></h3><hr>";
																								}
																							}

																							// Verificando se existem dias pares ou ímpares e exibindo
																							if ($pares > 0) { ?>
									<h2 class="impares"><em>“N° de dias Pares”</em></h2><?php

																								echo "<h3 class='impares'>Foram <em id='num'> $pares </em></h3>"; ?>
								<?php }
																							if ($impares > 0) { ?>
									<hr>
									<h2 class="impares"><em>“N° de dias Ímpares”</em></h2><?php

																								echo "<h3 class='impares'>Foram <em id='num'> $impares </em></h3>"; ?>
								<?php } ?>
								<br>
							</div>
						</div>

						<!-- Dar um espaço entre os blocos para telas menores-->
						<div id="separador_estatic"></div>
						<div id="alinhadeira_exp">
							<div id="lista_dias"> <?php

													// Dias em que o número de mensagens ultrapassou os 100 mensalmente, anualmente & Globalmente
													if ($maior != 0) {
														if ($maior > 1) {
													?><br>
										<h2 id="msgs_dias"><em style='color: #0CDD0C' id='num'><?php echo $maior ?></em> Dias passaram de <em class='datas_' id="num">100</em><br> Mensagens <br>
											<div id="alinhadeira_exp2"><?php if ($maior > 0) { ?><button id="cima_mais" class="lista_dias_bons" onclick="maior_lista()">⤵</button><button id="baixo_mais" class="lista_dias_bons">⤴</button> <?php } ?> </div>
											</h1><?php
														} else {
													?><br>
											<h2 id="msgs_dias">Apenas <em style='color: #0CDD0C' id='num'><?php echo $maior ?></em> dia passou<br> de <em class='datas_' id="num">100</em> Mensagens <br>
												<div id="alinhadeira_exp2"><?php if ($maior > 0) { ?><button id="cima_mais" class="lista_dias_bons" onclick="maior_lista()">⤵</button><button id="baixo_mais" class="lista_dias_bons">⤴</button> <?php } ?> </div>
												</h1><?php
														}
													} else {
														echo "<br><h2 id='msgs_dias'>Nenhum dia passou <br>de <em class='datas_' nid='num'>100</em> Mensagens</h2>";
													}

													if (strlen($data > 4) || strlen($data = 4)) {
														if (strlen($data > 4)) {
															$recolhe2 = "SELECT * FROM balanco where datablan LIKE '$data_convertida%'";
														} else {
															$ano = $data;
															$recolhe2 = "SELECT * FROM balanco where datablan LIKE '$ano%'";
														}
													}
													if ($data == 1000) {
														$recolhe2 = "SELECT * FROM balanco";
													}

													$resultado = $conexao->query($recolhe2);

													// Div da função recolher/expandir (Dias Bons)
													echo "<div id='dias_bons'><br>";
													if ($resultado->num_rows > 0) {
														while ($linha2 = $resultado->fetch_assoc()) {

															$msgs = $linha2["qtdmens"];
															$diamaior = $linha2["datablan"];
															$dia_historico = $linha2["dia_marcado"];

															// Exibir ou Esconder os dias bons
															if ($msgs > 100 || $msgs == 100) {
																if ($linha2["dia_marcado"] == null) {
																	if (strlen($data) > 4) {
																		echo "<h5 class='colorama'>No dia <em id='num' class='datas_'>" . date('d', strtotime($diamaior)) . "</em> foram <em class='dias_bons' id='num'>$msgs</em> mensagens.";
																	} else if ($data == 1000) {
																		echo "<h5 class='colorama'>Em <em id='num' class='datas_'>" . date('d/m/y', strtotime($diamaior)) . "</em> foram <em class='dias_bons' id='num'>$msgs</em> mensagens.";
																	} else {
																		echo "<h5 class='colorama'>Em <em id='num' class='datas_'>" . date('d/m', strtotime($diamaior)) . "</em> foram <em class='dias_bons' id='num'>$msgs</em> mensagens.";
																	}
																	echo "</h5>";
																} else {
																	if (strlen($data) > 4) {
																		echo "<h5 class='colorama_ht'>No dia <em id='num'>" . date('d', strtotime($diamaior)) . "</em> foram <em id='num'>$msgs</em> mensagens.";
																	} else if ($data == 1000) {
																		echo "<h5 class='colorama_ht'>Em <em id='num'>" . date('d/m/y', strtotime($diamaior)) . "</em> foram <em id='num'>$msgs</em> mensagens.";
																	} else {
																		echo "<h5 class='colorama_ht'>Em <em id='num'>" . date('d/m', strtotime($diamaior)) . "</em> foram <em id='num'>$msgs</em> mensagens.";
																	}
																	if ($dia_historico != null) {
																		echo "<img id='estrela' title='Este dia: $dia_historico' src='../img/icone/estrela.png'>";
																	}
																}
																echo "</h5>";
															}
														}
													}
													echo "</div>";

													if ($melhordia > 420) {
														$scroll_fundo_grafico = sprintf('
				<style type="text/css">
					#fundo_grafic{
						overflow-y: scroll;
					}
					#Grafico{
						position: relative;
					}
					</style>');
														echo $scroll_fundo_grafico;
													}

													// Dias em que o número de mensagens não ultrapassou os 100 mensalmente, anualmente & Globalmente
													if ($menor != 0) {
														if ($menor > 1) {
														?><br>
												<h2 id="msgs_dias"><em style='color: red' id='num'><?php echo $menor ?></em> Dias não passaram de <br><em class='datas_' id="num">100</em> Mensagens <br>
													<div id="alinhadeira_exp2"><?php if ($maior > 0) { ?><button id="cima_menos" class="lista_dias_ruins">⤵</button><button id="baixo_menos" class="lista_dias_ruins">⤴</button> <?php } ?> </div>
													</h1><?php
														} else {
															?><br>
													<h2 id="msgs_dias">Apenas <em style='color: red' id='num'><?php echo $menor ?></em> dia não passou<br> de <em class='datas_' id="num">100</em> Mensagens <br>
														<div id="alinhadeira_exp2"><?php if ($maior > 0) { ?><button id="cima_menos" class="lista_dias_ruins">⤵</button><button id="baixo_menos" class="lista_dias_ruins">⤴</button> <?php } ?> </div>
														</h1><?php
															}
														} else {
															echo "<br><h2 id='msgs_dias'>Nenhum dia ficou <br> abaixo de <em class='datas_' id='num'> 100</em> Mensagens</h2>";
														}

														// Exibir ou Esconder os dias ruins
														if (strlen($data > 4) || strlen($data = 4)) {
															if (strlen($data > 4)) {
																$recolhe = "SELECT * FROM balanco where datablan LIKE '$data_convertida%'";
															} else {
																$ano = $data;
																$recolhe = "SELECT * FROM balanco where datablan LIKE '$ano%'";
															}
														}
														if ($data == 1000) {
															$recolhe = "SELECT * FROM balanco";
														}

														$resultado = $conexao->query($recolhe);
														// Div da função recolher/expandir (Dias Ruins)
														echo "<div id='dias_ruins'><br>";
														if ($resultado->num_rows > 0) {
															while ($linha2 = $resultado->fetch_assoc()) {
																$msgs = $linha2["qtdmens"];
																$diamenor = $linha2["datablan"];
																$dia_historico = $linha2["dia_marcado"];

																// Exibir ou Esconder os dias ruins
																if ($msgs < 100) {
																	if ($linha2["dia_marcado"] == null) {
																		if (strlen($data) > 4) {
																			echo "<h5 class='colorama'>No dia <em id='num' class='datas_'>" . date('d', strtotime($diamenor)) . "</em> foram <em class='dias_ruins' id='num'>$msgs</em> mensagens.";
																		} else if ($data == 1000) {
																			echo "<h5 class='colorama'>Em <em id='num' class='datas_'>" . date('d/m/y', strtotime($diamenor)) . "</em> foram <em class='dias_ruins' id='num'>$msgs</em> mensagens.";
																		} else {
																			echo "<h5 class='colorama'>Em <em id='num' class='datas_'>" . date('d/m', strtotime($diamenor)) . "</em> foram <em class='dias_ruins' id='num'>$msgs</em> mensagens.";
																		}
																		echo "</h5>";
																	} else {
																		if (strlen($data) > 4) {
																			echo "<h5 class='colorama_ht'>No dia <em id='num'>" . date('d', strtotime($diamenor)) . "</em> foram <em id='num'>$msgs</em> mensagens.";
																		} else if ($data == 1000) {
																			echo "<h5 class='colorama_ht'>Em <em id='num'>" . date('d/m/y', strtotime($diamenor)) . "</em> foram <em id='num'>$msgs</em> mensagens.";
																		} else {
																			echo "<h5 class='colorama_ht'>Em <em id='num'>" . date('d/m', strtotime($diamenor)) . "</em> foram <em id='num'>$msgs</em> mensagens.";
																		}
																		if ($dia_historico != null) {
																			echo "<img id='estrela' title='Este dia: $dia_historico' src='../img/icone/estrela.png'>";
																		}
																	}
																	echo "</h5>";
																}
															}
														}
														echo "</div>";
													} ?>
											<br>
							</div>
						</div>
				</div>
				<!-- rodapé -->
				<div id="footer_estatic">
					<div class="container text-center">
						<div class="fnav">
							<h4>Todos os direitos reservados <a id="link" href="../gamersx.php"><em>G4mersx</em></a> Copyright © 2019.</h4>
						</div>
					</div>
				</div>
		</div>

		<script type="text/javascript" src="../js/jquery.1.11.1.js"></script>
		<script type="text/javascript" src="../js/bootstrap.js"></script>
		<script type="text/javascript" src="../js/SmoothScroll.js"></script>
		<script type="text/javascript" src="../js/nivo-lightbox.js"></script>
		<script type="text/javascript" src="../js/jquery.isotope.js"></script>
		<script type="text/javascript" src="../js/jqBootstrapValidation.js"></script>
		<script type="text/javascript" src="../js/main.js"></script>
		<script type="text/javascript" src="../js/funcoes.js"></script>
		<script type="text/javascript" src="../js/relogio.js"></script>
</body>

</html>