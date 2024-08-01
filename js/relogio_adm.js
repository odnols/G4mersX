// Função do Relógio para a página central para administradores
onload.function = atualizaRelogio();

function atualizaRelogio() {
    momentoAtual = new Date();

    horaImprimivel = momentoAtual.toLocaleTimeString('pt-BR', { timeZone: 'America/Sao_Paulo' });
    document.getElementById("relogio").innerHTML = horaImprimivel;
    document.form_relogio.hora_att.value = horaImprimivel;
    setTimeout("atualizaRelogio()", 1000);
}