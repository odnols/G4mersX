// Funções utilizadas nas páginas principais
// Funções da tela "Central"

// Exibir e esconder a caixa do Guia ( Central )
$(document).ready(function () {
    $("#guia_").click(function () {
        $("#perfil").fadeOut();
        $("#fundo_perfil").fadeOut();
        $("#atalho_lateral").toggle();
        $("#usuarios_on_iframe_lateral").toggle();
        $("#puxador_lateral").fadeOut();
        $("#guia_importa").toggle();
        $("#add_dia").toggle();
        $("#estatisticas").toggle();
        $("#click_historico").toggle();
        $("#footer").toggle();
        $("#regis_").toggle();
        $("#estat_").toggle();
        $("#acerv_").toggle();
        $("#clik_historico").toggle();
    });
});
// Esconder as opções da prancheta ( Central )
$(document).ready(function () {
    $("#dias_prancheta_add").click(function () {
        $("#quadro_add").fadeIn(0);
        $("#quadro_add_user").fadeOut(0);
        $("#quadro_add_historico_dia").fadeOut(0);
    });
});
$(document).ready(function () {
    $("#usuarios_prancheta_add").click(function () {
        $("#quadro_add").fadeOut(0);
        $("#quadro_add_historico_dia").fadeOut(0);
        $("#quadro_add_user").fadeIn(0);
    });
});
$(document).ready(function () {
    $("#historico_prancheta_add").click(function () {
        $("#quadro_add").fadeOut(0);
        $("#quadro_add_historico_dia").fadeIn(0);
        $("#quadro_add_user").fadeOut(0);
    });
});
// Exibir e esconder a caixa para marcar um dia histórico ( Central )
$(document).ready(function () {
    $("#dia_historico_click").click(function () {
        $("#dia_impta").fadeToggle();
    });
});
// Exibir e esconder o gráfico ( Estatistificado)
$(document).ready(function () {
    $("#click_grafical").click(function () {
        $("#mes").fadeToggle();
        $("#download_automatic").fadeToggle();
        $("#fundo_grafic").fadeToggle();
    });
});
// Botões do perfil ( Central )
$(document).ready(function () {
    $(".perfil").click(function () {
        $("#perfil").fadeToggle();
        $("#fundo_perfil").fadeToggle();
    });
});
// Animações
$(document).ready(function () {
    $("#configura_perfil").click(function () {
        $("#menu").fadeToggle();
        $("#perfil").fadeToggle();
    });
});
// Função para abrir o atalho lateral
$(document).ready(function () {
    $("#atalho_lateral").click(function () {
        $("#puxador_lateral").fadeToggle();
        $("#usuarios_on_iframe_lateral").fadeToggle();
    });
});

// Funções da tela "Estatistificado"
// Exibir e esconder as caixas de dias
$(document).ready(function () {
    $(".lista_dias_bons").click(function () {
        $("#cima_mais").toggle();
        $("#baixo_mais").toggle();
        $("#dias_bons").fadeToggle();
        $("#pop-up").fadeToggle();
    });
});

$(document).ready(function () {
    $(".lista_dias_ruins").click(function () {
        $("#cima_menos").toggle();
        $("#baixo_menos").toggle();
        $("#dias_ruins").fadeToggle();
        $("#pop-up").fadeToggle();
    });
});
// Aleatorizador de fundos para a tela de estatísticas
$(document).ready(function () {
    var back2 = ["Floral.png", "Naves.png", "Cosmos.png", "Primavera.png", "Ondularis.png", "Geometria.png", "Ornamental.png"];
    var rand2 = back2[Math.floor(Math.random() * back2.length)];

    $('#melhor_pior').css('background', "url(../img/fundo/" + rand2 + ")");
    $('#lista_dias').css('background', "url(../img/fundo/" + rand2 + ")");
});
// Aleatorizador de fundos para a tela central
$(document).ready(function () {
    var back = ["Calçada.png", "Quadrantes.png", "Calculos.png", "Circularis.png", "Rotundas.png", "Psicodelico.png", "mosaico.png"];
    var rand = back[Math.floor(Math.random() * back.length)];

    var anim = ["abre_prancheta", "abre_prancheta2", "abre_prancheta3"];
    var rand_anima = anim[Math.floor(Math.random() * anim.length)];

    document.getElementById("quadro_add").style.animationName = rand_anima;
    $('#quadro_add').css('background', "url(img/fundo/" + rand + ")");
    $('#quadro_add_user').css('background', "url(img/fundo/" + rand + ")");
    $('#quadro_add_historico_dia').css('background', "url(img/fundo/" + rand + ")");
});
// Funções da tela de edição do Perfil
function escolhe(fundo) {
    fundo.style.boxShadow = "inset 0px 0px 5px black";
    fundo.style.border = "5px solid white";
}

function atualiza_status_usuario() {
    if (confirm("Estamos enviando uma mensagem para um administrador solicitando sua melhoria de perfil, confira sua caixa de mensagens na aba Status para mais informações ;)")) {
        window.location.href = "php_configura/aumenta_cargo.php";
    }
}

setTimeout("atualizaCores()", 10);
setTimeout("grafico_exe()", 10);

function atualiza_dias_historicos(caso) {
    // Esconder dias ruins ou dias bons
    if (caso == "0_hide") {
        $(".0_hide").fadeIn();
        $(".1_hide").fadeOut();
    } else if (caso == "1_hide") {
        $(".1_hide").fadeIn();
        $(".0_hide").fadeOut();
    } else {
        $(".0_hide").fadeIn();
        $(".1_hide").fadeIn();
    }
}

function mostra_anos_verificados(data_anual) {
    // Ordenando a visualização dos dias
    if (data_anual == "coringa") {
        $(".2016").fadeIn(0);
        $(".2017").fadeIn(0);
        $(".2018").fadeIn(0);
        $(".2019").fadeIn(0);
    } else if (data_anual == 2016) {
        $(".2016").fadeIn(0);
        $(".2017").fadeOut(0);
        $(".2018").fadeOut(0);
        $(".2019").fadeOut(0);
    } else if (data_anual == 2017) {
        $(".2016").fadeOut(0);
        $(".2017").fadeIn(0);
        $(".2018").fadeOut(0);
        $(".2019").fadeOut(0);
    } else if (data_anual == 2018) {
        $(".2016").fadeOut(0);
        $(".2017").fadeOut(0);
        $(".2018").fadeIn(0);
        $(".2019").fadeOut(0);
    } else {
        $(".2016").fadeOut(0);
        $(".2017").fadeOut(0);
        $(".2018").fadeOut(0);
        $(".2019").fadeIn(0);
    }
}

function add_user() {
    var $wrapper = document.querySelector('.novo_atributo'),

        // String de texto
        HTMLNovo = "<input type='text' id='input' class='Input-text_quadro' name='telefone_user[]' placeholder='Telefone' maxlength='13'>" +
            "<input type='text' id='input' class='Input-text_quadro' name='qtd_atribuida[]' placeholder='Enviou...'><br>";

    // Insere o texto antes do conteúdo atual do elemento
    $wrapper.insertAdjacentHTML('afterbegin', HTMLNovo);
}

function debug_mode() {
    window.location.href = "php_suporte/debug_mode.php";
}