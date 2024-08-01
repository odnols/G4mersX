// Funções da tela "Index"
// Botão do cadastro para esconder o formulário do login
$(document).ready(function () {
    $("#cadastrar_button").click(function () {
        $("#cadastrar_button").toggle();
        $("#logar_button").toggle();
        $(".Logah").toggle();
        $(".cadastrah").toggle();
    });
});

// Botão do login para esconder o formulário de cadastro
$(document).ready(function () {
    $("#logar_button").click(function () {
        $("#cadastrar_button").toggle();
        $("#logar_button").toggle();
        $(".Logah").toggle();
        $(".cadastrah").toggle();
    });
});