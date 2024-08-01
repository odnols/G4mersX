// Função do Relógio para a página central para usuários comuns
onload.function = atualizaRelogio_user();

function atualizaRelogio_user() {
    momentoAtual = new Date();

    horaImprimivel = momentoAtual.toLocaleTimeString('pt-BR', { timeZone: 'America/Sao_Paulo' });
    document.getElementById("relogio").innerHTML = horaImprimivel;
    setTimeout("atualizaRelogio_user()", 1000);
}