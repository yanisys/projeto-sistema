
$(document).ready(function() {
    var html = $('#sexo').val() == "M" ? "Sexo Masculino - " : "Sexo Feminino - ";
    $('#dados').html(html);
    $('#classificacao').val($('#classifica').val());
});


$('#peso').keypress(function(e) {
    if(e.which == 46 || e.which == 44) {
        if($('#peso').val().length == 1) {
            $('#peso').val('00' + $('#peso').val() + '.');
        }
        if($('#peso').val().length == 2){
            $('#peso').val('0' + $('#peso').val() + '.');
        }

    }
});

$("#cintura").blur(function() {
    if($('#cintura').val() != '' && $('#quadril').val() != '') {
        var indice = $("#cintura").val() / $("#quadril").val();
        indice = isFinite(indice) ? indice.toFixed(2) : 0;

        $(".indice_cintura_quadril").val(indice);
        risco_cintura_quadril(calculaIdade(), $("#sexo").val());
    }
    else {
        $(".indice_cintura_quadril").val('');
        $('.risco_cintura').val('');
    }
});

$("#quadril").blur(function() {
    if($('#cintura').val() != '' && $('#quadril').val() != '') {
        var indice = $("#cintura").val() / $("#quadril").val();
        indice = isFinite(indice) ? indice.toFixed(2) : 0;
        $(".indice_cintura_quadril").val(indice);
        risco_cintura_quadril(calculaIdade(), $("#sexo").val());
    }
    else {
        $(".indice_cintura_quadril").val('');
        $('.risco_cintura').val('');
    }
});

$("#altura").blur(function() {
    if($('#peso').val() != '' && $('#altura').val() != '') {
        var resultado = $("#peso").val() / ($("#altura").val() * $("#altura").val());
        resultado = isFinite(resultado) ? resultado.toFixed(2) : 0;

        $(".massa_corporal").val(resultado);
        $('.estado_nutricional').val(avaliamassa_corporal());
    }
    else{
        $(".massa_corporal").val('');
        $('.estado_nutricional').val('');
    }
});

$("#peso").blur(function() {
    if($('#peso').val() != '' && $('#altura').val() != '') {
        var resultado = $("#peso").val() / ($("#altura").val() * $("#altura").val());
        resultado = isFinite(resultado) ? resultado.toFixed(2) : 0;

        $(".massa_corporal").val(resultado);
        $('.estado_nutricional').val(avaliamassa_corporal());
    }
    else{
        $(".massa_corporal").val('');
        $('.estado_nutricional').val('');
    }
});

$('.sim_nao_cirurgia').change(function() {
   if($(this).val() === 'false')
       document.getElementById('div_cirurgias_previas').style.display = 'none';
   else
       document.getElementById('div_cirurgias_previas').style.display = 'block';

});

$('#abertura_ocular').change(function() {
    escala_coma_glasgow();
});

$('#resposta_verbal').change(function() {
    escala_coma_glasgow();
});

$('#resposta_motora').change(function() {
    escala_coma_glasgow();
});

$('#classifica').change(function() {
    $('#classificacao').val($('#classifica').val());
    if($('#classificacao').val() == 7)
        document.getElementById('procedimento-acolhimento').style.display = 'block';
    else
        document.getElementById('procedimento-acolhimento').style.display = 'none';
});

$('#painel-planos').click(function() {
    carrega_planos($('.cd_pessoa').val());
});

$(document).on('click', '#abrir-painel-planos', function(){
    document.getElementById('planos_usuario').style.display = 'block';
    document.getElementById('origem_usuario').style.display = 'none';
    carrega_planos($(this).val());
});

$(document).on('click', '.detalhes-procedimento', function(){
    carrega_modal_procedimento($(this).val(),$(this).attr('data-permissao'));
});

$(document).on('click', '.detalhes-procedimento-c', function(){
    carrega_modal_procedimento($(this).attr('id'));
});

$(document).on('click','.pessoa-estabelecimento', function(e){
    $('#label_modal_atendimento').text("Selecione a origem do paciente");
    document.getElementById('planos_usuario').style.display = 'none';
    document.getElementById('origem_usuario').style.display = 'block';
    var id_beneficiario = $(this).val();
    $(document).on('click','#btn_ir_para_origem', function(e){
        $.ajax({
            type: 'POST',
            url: dir + 'ajax/novo-atendimento',
            data: {"cd_origem": $('#cd_origem_usuario').val(),"id_beneficiario":id_beneficiario, "_token": token},
            dataType: 'json',
            success: function (data) {
                if (data.success == true) {
                    $('#novo-atendimento').modal('hide');
                    $('#modal-pesquisa').modal('hide');
                    window.location.replace(dir + 'atendimentos/fila');
                }
                else{
                    error_alert(data.mensagem);
                }
            }
        });
    });
});

$(document).on('click','#finalizar-atendimento-sem-medico', function(e){
    $.ajax({
        type: 'POST',
        url: dir + 'ajax/atendimentos/finalizar-atendimento-sem-medico',
        data: {"cd_prontuario":$('#cd_prontuario').val(), "_token": token},
        dataType: 'json',
        success: function (data) {
            if (data.success == true) {
                swal({
                    type: 'success',
                    title: 'Sucesso!',
                    text: data.mensagem,
                    showConfirmButton: false,
                    timer: 3000
                })
                window.location.replace(dir + 'atendimentos/fila');
            }
            else{
                error_alert(data.mensagem);
            }
        }
    });
});

function risco_cintura_quadril(idade, sexo) {
    var risco = $(".indice_cintura_quadril").val();
    var classificacao = 'Não Calculado';

    if (sexo == 'M') {
        if (idade <= 29) {
            if (risco < 0.83)
                classificacao = 'Baixo';
            if (risco >= 0.83 && risco <= 0.88)
                classificacao = 'Moderado';
            if (risco >= 0.89 && risco <= 0.94)
                classificacao = 'Alto';
            if (risco > 0.94)
                classificacao = 'Muito alto';
        }
        if (idade >= 30 && idade <= 39) {
            if (risco < 0.84)
                classificacao = 'Baixo';
            if (risco >= 0.84 && risco <= 0.91)
                classificacao = 'Moderado';
            if (risco >= 0.92 && risco <= 0.96)
                classificacao = 'Alto';
            if (risco > 0.96)
                classificacao = 'Muito alto';
        }
        if (idade >= 40 && idade <= 49) {
            if (risco < 0.88)
                classificacao = 'Baixo';
            if (risco >= 0.88 && risco <= 0.95)
                classificacao = 'Moderado';
            if (risco >= 0.96 && risco <= 1)
                classificacao = 'Alto';
            if (risco > 1)
                classificacao = 'Muito alto';
        }
        if (idade >= 50 && idade <= 59) {
            if (risco < 0.90)
                classificacao = 'Baixo';
            if (risco >= 0.90 && risco <= 0.96)
                classificacao = 'Moderado';
            if (risco >= 0.97 && risco <= 1.02)
                classificacao = 'Alto';
            if (risco > 1.02)
                classificacao = 'Muito alto';
        }
        if (idade >= 60) {
            if (risco < 0.91)
                classificacao = 'Baixo';
            if (risco >= 0.91 && risco <= 0.98)
                classificacao = 'Moderado';
            if (risco >= 0.99 && risco <= 1.03)
                classificacao = 'Alto';
            if (risco > 1.03)
                classificacao = 'Muito alto';
        }
    }
    else {
        if (idade <= 29) {
            if (risco < 0.71)
                classificacao = 'Baixo';
            if (risco >= 0.71 && risco <= 0.77)
                classificacao = 'Moderado';
            if (risco >= 0.78 && risco <= 0.82)
                classificacao = 'Alto';
            if (risco > 0.82)
                classificacao = 'Muito alto';
        }
        if (idade >= 30 && idade <= 39) {
            if (risco < 0.72)
                classificacao = 'Baixo';
            if (risco >= 0.72 && risco <= 0.78)
                classificacao = 'Moderado';
            if (risco >= 0.79 && risco <= 0.84)
                classificacao = 'Alto';
            if (risco > 0.84)
                classificacao = 'Muito alto';
        }
        if (idade >= 40 && idade <= 49) {
            if (risco < 0.73)
                classificacao = 'Baixo';
            if (risco >= 0.73 && risco <= 0.79)
                classificacao = 'Moderado';
            if (risco >= 0.80 && risco <= 0.87)
                classificacao = 'Alto';
            if (risco > 0.87)
                classificacao = 'Muito alto';
        }
        if (idade >= 50 && idade <= 59) {
            if (risco < 0.74)
                classificacao = 'Baixo';
            if (risco >= 0.74 && risco <= 0.81)
                classificacao = 'Moderado';
            if (risco >= 0.82 && risco <= 0.88)
                classificacao = 'Alto';
            if (risco > 0.88)
                classificacao = 'Muito alto';
        }
        if (idade >= 60) {
            if (risco < 0.76)
                classificacao = 'Baixo';
            if (risco >= 0.76 && risco <= 0.83)
                classificacao = 'Moderado';
            if (risco >= 0.84 && risco <= 0.90)
                classificacao = 'Alto';
            if (risco > 0.90)
                classificacao = 'Muito alto';
        }
    }
    $('.risco_cintura').val(classificacao);

};

function avaliamassa_corporal(){
    var massa_corporal = $('.massa_corporal').val();
    if(massa_corporal < 17)
        return("Muito abaixo do peso");
    if(massa_corporal >= 17 && massa_corporal <= 18.49)
        return("Abaixo do peso");
    if(massa_corporal >= 18.5 && massa_corporal <= 24.99)
        return("Peso normal");
    if(massa_corporal >= 25 && massa_corporal <= 29.99)
        return("Acima do peso");
    if(massa_corporal >= 30 && massa_corporal <= 34.99)
        return("Obesidade I");
    if(massa_corporal >= 35 && massa_corporal <= 39.99)
        return("Obesidade II");
    if(massa_corporal > 40)
        return("Obesidade III");
}

function escala_coma_glasgow() {
    var escala = parseInt($('#abertura_ocular').val()) + parseInt($('#resposta_verbal').val()) + parseInt($('#resposta_motora').val());
    if ($('#abertura_ocular').val() > 0 && $('#resposta_verbal').val() > 0 && $('#resposta_motora').val() > 0){
        if (escala >= 3 && escala <= 8) {
            $('#t_escore_glasgow').val(escala + " - Trauma grave");
            $('#escore_glasgow').val(escala);
        }
        if (escala >= 9 && escala <= 12) {
            $('#t_escore_glasgow').val(escala + " - Trauma moderado");
            $('#escore_glasgow').val(escala);
        }
        if (escala >= 13 && escala <= 15) {
            $('#t_escore_glasgow').val(escala + " - Trauma leve");
            $('#escore_glasgow').val(escala);
        }
    }
    else{
        $('#t_escore_glasgow').val('');
        $('#escore_glasgow').val('');
    }
}

function carrega_planos(cd_pessoa) {
    $.ajax({
        type: 'POST',
        url: dir + 'ajax/buscar-planos',
        data: {"cd_pessoa":cd_pessoa, "_token": token},
        dataType: 'json',
        success: function (data) {
            var html = '';
            for(var x=0;x<data.dados.length;x++){
                if((data.dados[x].cd_beneficiario !== null && data.dados[x].cd_beneficiario !== '') || data.dados[x].cd_plano <= 1){
                    html += "<tr>";
                    html += "<td class='text-center'>"+data.dados[x].ds_plano+"</td>";
                    html += "<td class='text-center'>"+data.dados[x].cd_beneficiario+"</td>";
                    html += "<td><button type='button' value=" + data.dados[x].id_beneficiario + " class='btn btn-info pessoa-estabelecimento'>Selecionar</button></td>";
                    html += "</tr>";
                }
            }
            if(html == '')
                html += "<tr><td class='text-center'  colspan='3'><h4>Nenhum plano disponível para o usuário</h4></td></tr>";
            $('#tabela_plano').html(html);
        }
    });
}

function calculaIdade(dateString) {
    var birthday = +new Date(dateString);
    return ~~((Date.now() - birthday) / (31557600000));
}

function carrega_modal_procedimento(id_atendimento_procedimento,permissao) {
    $.ajax({
        type: 'POST',
        url: dir + 'ajax/atendimentos/carrega-modal-procedimento',
        data: {"id_atendimento_procedimento":id_atendimento_procedimento, "_token": token},
        dataType: 'json',
        success: function (data) {
            if (data.success == true) {
                for (var x = 0; x < data.retorno.length; x++) {
                    $('#d_procedimento').val(data.retorno[x].cd_procedimento+" "+data.retorno[x].nm_procedimento);
                    $('#d_user_solicitacao').val(data.retorno[x].profissional_solicitante.trim() +" "+data.retorno[x].pessoa_solicitante);
                    $('#d_hr_solicitacao').val(data.retorno[x].dt_hr_solicitacao);
                    $('#d_solicitacao').val(data.retorno[x].descricao_solicitacao );
                    $('#d_execucao').val((data.retorno[x].descricao_execucao != null) ? data.retorno[x].descricao_execucao : '');
                    $('#d_hr_execucao').val((data.retorno[x].dt_hr_execucao != null) ?data.retorno[x].dt_hr_execucao : '' );
                    $('#d_user_execucao').val((data.retorno[x].profissional_executante != null) ? data.retorno[x].profissional_executante.trim() +" "+data.retorno[x].pessoa_executante : '');
                    $('#d_id_atendimento_procedimento').val(data.retorno[x].id_atendimento_procedimento);
                    if($('#d_execucao') != null)
                        $('#d_solicitacao').attr('disabled');

                    if(data.retorno[x].id_status == 'C' || permissao == 0) {
                        if(document.getElementById('salvar-procedimento')!=undefined) {
                            document.getElementById('salvar-procedimento').style.display = 'none';
                            document.getElementById('finalizar-procedimento').style.display = 'none';
                            document.getElementById("d_execucao").disabled = true;
                        }
                        else{
                            document.getElementById('salvar-procedimento-atendimento').style.display = 'none';
                            document.getElementById('finalizar-procedimento-atendimento').style.display = 'none';
                            document.getElementById("d_execucao").disabled = true;
                        }
                    }
                    else{
                        if(document.getElementById('salvar-procedimento')!=undefined) {
                            document.getElementById('salvar-procedimento').style.display = 'block';
                            document.getElementById('finalizar-procedimento').style.display = 'block';
                            document.getElementById("d_execucao").disabled = false;
                        }else{
                            document.getElementById('salvar-procedimento-atendimento').style.display = 'block';
                            document.getElementById('finalizar-procedimento-atendimento').style.display = 'block';
                            document.getElementById("d_execucao").disabled = false;
                        }
                    }
                }

            }
        }
    });
}

