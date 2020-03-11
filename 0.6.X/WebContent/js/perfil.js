// Botões do perfil ( Central )
$(document).ready(function(){
    $(".perfil").click(function(){
        $("#perfil").fadeToggle();
        $("#fundo_perfil").fadeToggle();
    });
});

$(document).ready(function(){
    $("#configura_perfil").click(function(){
        $("#menu").fadeToggle();
        $("#perfil").fadeToggle();
    });
});