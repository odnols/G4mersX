// Funções para exibir as telas de configurações da tela de configurações
$(document).ready(function(){
    $("#click_usuario").click(function(){
        $("#click_usuario_on").fadeIn();
        $("#click_mensagens_on").fadeOut();
    });
});

$(document).ready(function(){
    $("#click_mensagens").click(function(){
        $("#click_mensagens_on").fadeIn();
        $("#click_usuario_on").fadeOut();
    });
});