var typingTimer; //timer identifier
var doneTypingInterval = 1000; //time in ms, 1 second for example


busca_ultimas_prescricoes($('#cd_prontuario').val());

$('.itens').click(function() {
    $("#btn-"+$(this).attr('data-nome')).val($(this).attr('data-texto'));
    $("#btn-"+$(this).attr('data-nome')).html($(this).attr('data-texto')+" <span class='caret'></span>");
    $("#btn-"+$(this).attr('data-nome')).attr('data-valor',$(this).attr('data-valor'));
});

$('.anterior_proxima_prescricao').click(function() {
    var comando = $(this).val();
    var atual = $('#id_prescricao_ambulatorial').val();
    if ((comando == 'anterior' && atual > 1) || comando == 'proxima'){
        $.ajax({
            type: 'POST',
            url: dir + 'ajax/atendimentos/busca-prescricao-ambulatorial',
            data: {
                "cd_prontuario": $('#cd_prontuario').val(),
                'comando': comando,
                'prescricao_atual': atual,
                "_token": token
            },
            dataType: 'json',
            success: function (data) {
                var itens = data.itens;
                var prescricao= data.prescricao;
                if(data.success === true) {
                    if(itens != undefined) {
                        for (var [key, value] of Object.entries(itens)) {
                            carrega_tabelas_prescricao(key.toUpperCase(), value);
                        }
                    }
                    if(prescricao != undefined) {
                        if (prescricao.prescricao_ambulatorial != undefined) {
                            document.getElementById('div-prescricao-ambulatorial').style.display = 'block';
                            $('#id_prescricao_ambulatorial').val(prescricao.prescricao_ambulatorial.id_atendimento_prescricao);
                            $('#id_prescricao_ambulatorial').attr('data-status',prescricao.prescricao_ambulatorial.status);
                            document.getElementById("btn-imprimir-prescricao-ambulatorial").setAttribute("href", dir+"relatorios/prescricao-ambulatorial/"+$('#cd_prontuario').val()+"/"+prescricao.prescricao_ambulatorial.cd_prescricao);
                            document.getElementById("rota_prescricao_ambulatorial").setAttribute("href", dir+"relatorios/prescricao-ambulatorial/"+$('#cd_prontuario').val()+"/"+prescricao.prescricao_ambulatorial.cd_prescricao);
                            var expira = new Date(prescricao.prescricao_ambulatorial.expira_em);
                            var agora = new Date();
                            var expirado = (expira > agora) ? false : true;

                            var html = "<h5 style='text-align: center; color: " + (expirado ? 'red' : '') + "'>Prescrição Nº " + prescricao.prescricao_ambulatorial.cd_prescricao + " - Criação: " + convertDate(prescricao.prescricao_ambulatorial.created_at) + " - Valida até: " + convertDate(prescricao.prescricao_ambulatorial.expira_em) + "</h5>";
                            $('.titulo-div-ambulatorial').html(html);
                            document.getElementById('titulo-div-ambulatorial').style.display = 'block';
                        }
                    }
                    if( $('#id_prescricao_ambulatorial').attr('data-status') == 'C') {
                        document.getElementById('btn-imprimir-prescricao-ambulatorial').style.display = 'block';
                        $("#painel-geral :input").attr("disabled", true);
                        $("#cd_medicamento").attr("disabled", true);
                        $("#concluir-atendimento-prescricao").attr("disabled", true);
                    }
                    else {
                        document.getElementById('btn-imprimir-prescricao-ambulatorial').style.display = 'none';
                        $("#painel-geral :input").attr("disabled", false);
                        $("#cd_medicamento").attr("disabled", false);
                        $("#concluir-atendimento-prescricao").attr("disabled", false);
                    }
                }
            }
        });
    }
});

$('.btn-add-prescricao-receita').click(function() {
  /*  $("#painel-geral :input").attr("disabled", false);
    $("#cd_medicamento :input").attr("disabled", true);*/
    document.getElementById('div-dieta').style.display = 'none';
    document.getElementById('div-oxigenoterapia').style.display = 'none';
    document.getElementById('div-sinais-vitais').style.display = 'none';
    document.getElementById('div-medicacao').style.display = 'none';
    document.getElementById('div-outros-cuidados').style.display = 'none';
    document.getElementById('div-laboratoriais').style.display = 'none';
    if($(this).attr('data-botoes') !== 'receituario_prescricao') {
        document.getElementById('div-impressao-medicacao').style.display = 'none';
        document.getElementById('div-mostra-botoes-prescricao-ambulatorial').style.display = 'block';
        document.getElementById('div-mostra-botoes-prescricao-receita').style.display = 'none';
    }
    else {
        document.getElementById('div-impressao-medicacao').style.display = 'block';
        document.getElementById('div-mostra-botoes-prescricao-ambulatorial').style.display = 'none';
        document.getElementById('div-mostra-botoes-prescricao-receita').style.display = 'block';
    }
    if($(this).attr('data-tp-conduta') !== undefined) {
        $("#painel-geral .limpar").html("");
        $("#painel-geral :input").attr("disabled", false);
        $("#painel-geral :input").val("")
        $("#cd_medicamento").attr("disabled", true);
        $('#tipo_conduta_hidden').val($(this).attr('data-tp-conduta'));
        if($('#tipo_conduta_hidden').val() === 'prescricao_ambulatorial') {
            $('#btn-dose').attr('readonly',true);
            document.getElementById('intervalo-medicacao-dia').style.display = 'none';
            document.getElementById('ver_todas_prescricoes').style.display = 'inline-block';
            if ($('#id_' + $(this).attr('data-tp-conduta')).attr('data-status') == 'A') {
                document.getElementById('concluir-atendimento-prescricao').style.display = 'block';
                document.getElementById('add-atendimento-prescricao').style.display = 'none';
            }
            else {
                $("#painel-geral :input").attr("disabled", true);
                document.getElementById('concluir-atendimento-prescricao').style.display = 'none';
                document.getElementById('add-atendimento-prescricao').style.display = 'block';
            }
            if ($('#id_' + $(this).attr('data-tp-conduta')).attr('data-status') == 'C') {
                document.getElementById('titulo-div-ambulatorial-modal').style.display = 'block';
                document.getElementById('rota_prescricao_ambulatorial').style.display = 'block';
                document.getElementById('rota_prescricao_hospitalar').style.display = 'none';
            }
        }
        else{
            $('#btn-dose').attr('readonly',false);
            document.getElementById('intervalo-medicacao-dia').style.display = 'block';
            $('#btn-prazo-medicacao').attr('data-valor','3');
            $('#btn-prazo-medicacao').attr('data-texto','dia(s)');
            $('#btn-prazo-medicacao').text('dia(s)');
            document.getElementById('ver_todas_prescricoes').style.display = 'none';
            document.getElementById('concluir-atendimento-prescricao').style.display = 'none';
            document.getElementById('add-atendimento-prescricao').style.display = 'none';
            document.getElementById('titulo-div-ambulatorial-modal').style.display = 'none';
            document.getElementById('rota_prescricao_ambulatorial').style.display = 'none';
            document.getElementById('rota_prescricao_hospitalar').style.display = 'block';
        }

        $('#tipo_conduta_hidden').attr('data-titulo',$(this).attr('data-titulo-principal'));
        busca_ultimas_prescricoes($('#cd_prontuario').val());
        $('#id_'+$('#tipo_conduta_hidden').val()).val()


    }

    $('#div-titulo-receituario').text($('#tipo_conduta_hidden').attr('data-titulo')+": "+$(this).attr('data-titulo'));
    document.getElementById('div-'+$(this).attr('data-configuracao')).style.display = 'block';

});

$('#fechar-modal-receituario').click(function() {
    $('.table_medicacao').html('');
    $('#tipo_conduta_hidden').val('')
    document.getElementById('div-dieta').style.display = 'none';
    document.getElementById('div-sinais-vitais').style.display = 'none';
    document.getElementById('div-medicacao').style.display = 'none';
    document.getElementById('div-outros-cuidados').style.display = 'none';
});

$('#add-atendimento-prescricao').click(function() {
    $.ajax({
        type: 'POST',
        url: dir + 'ajax/atendimentos/add-atendimento-prescricao',
        data: {
            'cd_prontuario': $('#cd_prontuario').val(),
            'tp_prescricao': $('#tipo_conduta_hidden').val(),
            "_token": token
        },
        dataType: 'json',
        success: function (data) {
            var dados = data.dados;
            $("#painel-geral :input").attr("disabled", false);
            $("#cd_medicamento :input").attr("disabled", true);
            $('#cd_prescricao_hidden').val(dados.cd_prescricao);
         /*   $('#id_'+$('#tipo_conduta_hidden').val()).val(dados.id_atendimento_prescricao);
            $('#id_'+$('#tipo_conduta_hidden').val()).attr('data-status',dados.status);*/
            document.getElementById('add-atendimento-prescricao').style.display = 'none';
            document.getElementById('concluir-atendimento-prescricao').style.display = 'block';
            var html = "<h5 style='text-align: center; color: white'>Prescrição Nº " + dados.id_atendimento_prescricao + " - Criação: " + convertDate(dados.created_at) + " - Valida até: " + convertDate(dados.expira_em) + "</h5>";
            $('.titulo-div-ambulatorial').html(html);
            document.getElementById('titulo-div-ambulatorial').style.display = 'block';
            busca_ultimas_prescricoes($('#cd_prontuario').val());
        }
    });
});

$('#concluir-atendimento-prescricao').click(function() {
    $.ajax({
        type: 'POST',
        url: dir + 'ajax/atendimentos/concluir-atendimento-prescricao',
        data: {
            'id_atendimento_prescricao': $('#id_'+$('#tipo_conduta_hidden').val()).val(),
            "_token": token
        },
        dataType: 'json',
        success: function (data) {
            busca_ultimas_prescricoes($('#cd_prontuario').val());
            swal({
                title: 'Sucesso!',
                html: 'Prescrição salva com sucesso',
                type: 'success',
                confirmButtonText: 'OK'
            })
            $('#modal-receituario').modal('hide');
        }
    });
});

function add_item_atendimento_prescricao(dados){
    dados.append('_token', token);
    dados.append('id_atendimento_prescricao', $('#id_'+$('#tipo_conduta_hidden').val()).val());
    dados.append('cd_prontuario', $('#cd_prontuario').val());
    dados.append('tp_conduta', $('#tipo_conduta_hidden').val());
    $.ajax({
        type: 'POST',
        url: dir + 'ajax/atendimentos/add-item-atendimento-prescricao',
        data: dados,
        contentType: false,
        processData: false,
        success: function (data) {
            var dados = JSON.parse(data);
            if(dados.id_atendimento_prescricao != undefined) {
                $('#id_' + $('#tipo_conduta_hidden').val()).val(dados.id_atendimento_prescricao);
            }
            busca_ultimas_prescricoes($('#cd_prontuario').val());
        }
    });
}

$('#add-item-dieta').click(function() {
    var formData = new FormData();
    formData.append('dieta', $('#dieta').val());
    formData.append('via_dieta', $('#via_dieta').val());
    formData.append('descricao_dieta', $('#descricao_dieta').val());
    formData.append('nome', 'dieta');
    formData.append('intervalo_dieta', $('#intervalo_dieta').val());
    formData.append('tp_intervalo_dieta', $('#btn-intervalo-dieta').attr('data-valor'));
    formData.append('prazo_dieta', $('#prazo_dieta').val());
    formData.append('tp_prazo_dieta', $('#btn-prazo-dieta').attr('data-valor'));
    var aprazamento = $('#aprazamento_dieta').val() + transformaAprazamentoemString(document.getElementsByName('aprazamento_dieta[]'));
    formData.append('aprazamento', aprazamento);
    add_item_atendimento_prescricao(formData);
});

$('#add-item-csv').click(function() {
    var formData = new FormData();
    formData.append('descricao_csv', $('#descricao_csv').val());
    formData.append('nome', 'csv');
    formData.append('intervalo_csv', $('#intervalo_csv').val());
    formData.append('tp_intervalo_csv', $('#btn-intervalo-csv').attr('data-valor'));
    formData.append('prazo_csv', $('#prazo_csv').val());
    formData.append('tp_prazo_csv', $('#btn-prazo-csv').attr('data-valor'));
    var aprazamento = $('#aprazamento_csv').val() + transformaAprazamentoemString(document.getElementsByName('aprazamento_csv[]'));
    formData.append('aprazamento', aprazamento);
    add_item_atendimento_prescricao(formData);
});

$('#add-item-outros-cuidados').click(function() {
    var formData = new FormData();
    formData.append('posicao', $('#posicao').val());
    formData.append('descricao_posicao', $('#descricao_posicao').val());
    formData.append('nome', 'outros_cuidados');
    add_item_atendimento_prescricao(formData);
});

$('#add-item-oxigenoterapia').click(function() {
    if($('#qtde_oxigenio').val() >= 0 && $('#qtde_oxigenio').val() <= 15) {
        var formData = new FormData();
        formData.append('qtde_oxigenio', $('#qtde_oxigenio').val());
        formData.append('administracao_oxigenio', $('#administracao_oxigenio').val());
        formData.append('descricao_oxigenio', $('#descricao_oxigenio').val());
        formData.append('nome', 'oxigenoterapia');
        formData.append('intervalo_oxigenoterapia', $('#intervalo_oxigenoterapia').val());
        formData.append('tp_intervalo_oxigenoterapia', $('#btn-intervalo-oxigenoterapia').attr('data-valor'));
        formData.append('prazo_oxigenoterapia', $('#prazo_oxigenoterapia').val());
        formData.append('tp_prazo_oxigenoterapia', $('#btn-prazo-oxigenoterapia').attr('data-valor'));
        var aprazamento = $('#aprazamento_oxigenoterapia').val() + transformaAprazamentoemString(document.getElementsByName('aprazamento_oxigenoterapia[]'));
        formData.append('aprazamento', aprazamento);
        add_item_atendimento_prescricao(formData);
    }
});

$('.add-medicacao').click(function() {
    if(($('#cd_medicamento_hidden').val() !== '') && ($('#quantidade').val() !== '') && ($('#dose').val() !== '') && ($('#intervalo').val() !== '') && ($('#prazo').val() !== '')){
        var formData = new FormData();
        formData.append('cd_medicamento', $('#cd_medicamento_hidden').val());
        formData.append('dose', $('#dose').val());
        formData.append('tp_dose', $('#btn-dose').attr('data-valor'));
        formData.append('tp_via', $('#btn-via').attr('data-valor'));
        formData.append('intervalo', $('#intervalo_medicacao').val());
        formData.append('tp_intervalo', $('#btn-intervalo-medicacao').attr('data-valor'));
        formData.append('prazo', $('#prazo_medicacao').val());
        formData.append('tp_prazo', $('#btn-prazo-medicacao').attr('data-valor'));
        formData.append('observacao_medicamento', $('#observacao_medicamento').val());
        formData.append('nome', 'medicacao');
        formData.append('se_necessario', (document.getElementById('se-necessario').checked) ? '1' : '0');
        var aprazamento = $('#aprazamento_medicacao').val() + transformaAprazamentoemString(document.getElementsByName('aprazamento_medicacao[]'));
        formData.append('aprazamento', aprazamento);
        add_item_atendimento_prescricao(formData);
    }
    else {
        var mensagem = '';
        existe = false;
        if($('#cd_medicamento_hidden').val() == '') {
            mensagem += 'Medicamento';
            existe = true
        }
        if($('#quantidade').val() == ''){
            mensagem += (existe ? ', ' : '')+'Qtde/ Embalagem';
            existe = true
        }
        if($('#dose').val() == ''){
            mensagem += (existe ? ', ' : '')+'Dose';
            existe = true
        }
        if($('#intervalo').val() == ''){
            mensagem += (existe ? ', ' : '')+'Intervalo';
            existe = true
        }
        if($('#prazo').val() == ''){
            mensagem += (existe ? ', ' : '')+'Prazo';
            existe = true
        }
        if($('#tipo_conduta_hidden').val() !== 'prescricao_ambulatorial' && $('#prazo').val() == '')
            mensagem += (existe ? ', ' : '')+'Prazo';
        swal({
            type: 'error',
            title: 'Erro!',
            text: "Você deve preencher o"+(existe ? 's' : '')+" campo"+(existe ? 's: ' : ': ')+ mensagem,
            showConfirmButton: true,
            timer: 5000
        })
    }
});

$('#add-exame-laboratorial').click(function() {
    var formData = new FormData();
    formData.append('cd_exame_laboratorial', $('#cd_exame_laboratorial').val());
    formData.append('observacao_exame_laboratorial', $('#observacao_exame_laboratorial').val());
    formData.append('nome', 'exame_laboratorial');
    add_item_atendimento_prescricao(formData);
});

$('#intervalo_medicacao').on('blur',function () {
    if($('#prazo_medicacao').val() != '' && $('#intervalo_medicacao').val() != '')
        apraza("medicacao");
});

$('#prazo_medicacao').on('blur',function () {
    if($('#prazo_medicacao').val() != '' && $('#intervalo_medicacao').val() != '')
        apraza("medicacao");
});

$('#recalcula_aprazamento_medicacao').on('click',function () {
    if($('#prazo_medicacao').val() == '') {
        swal("Preencha o período");
        $('#prazo_medicacao').focus();
    }
    else if ($('#intervalo_medicacao').val() == '') {
        swal('Preencha a frequência');
        $('#intervalo_medicacao').focus();
    }
    else {
        apraza('medicacao');
    }
});

$('#intervalo_csv').on('blur',function () {
    if($('#prazo_csv').val() != '' && $('#intervalo_csv').val() != '')
        apraza("csv");
});

$('#prazo_csv').on('blur',function () {
    if($('#prazo_csv').val() != '' && $('#intervalo_csv').val() != '')
        apraza("csv");
});

$('#recalcula_aprazamento_csv').on('click',function () {
    if($('#prazo_csv').val() == '') {
        swal("Preencha o período");
        $('#prazo_csv').focus();
    }
    else if ($('#intervalo_csv').val() == '') {
        swal('Preencha a frequência');
        $('#intervalo_csv').focus();
    }
    else {
        apraza('csv');
    }
});

$('#intervalo_dieta').on('blur',function () {
    if($('#prazo_dieta').val() != '' && $('#intervalo_dieta').val() != '')
        apraza("dieta");
});

$('#prazo_dieta').on('blur',function () {
    if($('#prazo_dieta').val() != '' && $('#intervalo_dieta').val() != '')
        apraza("dieta");
});

$('#recalcula_aprazamento_dieta').on('click',function () {
    if($('#prazo_dieta').val() == '') {
        swal("Preencha o período");
        $('#prazo_dieta').focus();
    }
    else if ($('#intervalo_dieta').val() == '') {
        swal('Preencha a frequência');
        $('#intervalo_dieta').focus();
    }
    else {
        apraza('dieta');
    }
});

$('#intervalo_oxigenoterapia').on('blur',function () {
    if($('#prazo_oxigenoterapia').val() != '' && $('#intervalo_oxigenoterapia').val() != '')
        apraza("oxigenoterapia");
});

$('#prazo_oxigenoterapia').on('blur',function () {
    if($('#prazo_oxigenoterapia').val() != '' && $('#intervalo_oxigenoterapia').val() != '')
        apraza("oxigenoterapia");
});

$('#recalcula_aprazamento_oxigenoterapia').on('click',function () {
    if($('#prazo_oxigenoterapia').val() == '') {
        swal("Preencha o período");
        $('#prazo_oxigenoterapia').focus();
    }
    else if ($('#intervalo_oxigenoterapia').val() == '') {
        swal('Preencha a frequência');
        $('#intervalo_oxigenoterapia').focus();
    }
    else {
        apraza('oxigenoterapia');
    }
});

function apraza(tipo){
    tipo = tipo.toString();
    if($('#aprazamento_' + tipo).val() == ''){
        var data = new Date();
        var horas = data.getHours();
        var minutos = data.getMinutes();
        minutos = (minutos >= 0  && minutos < 5) ? 0 : minutos;
        minutos = (minutos >= 5  && minutos <=15) ? 15 : minutos;
        minutos = (minutos >= 16 && minutos < 20) ? 15 : minutos;
        minutos = (minutos >= 20 && minutos <=30) ? 30 : minutos;
        minutos = (minutos >= 31 && minutos < 35) ? 30 : minutos;
        minutos = (minutos >= 35 && minutos <=45) ? 45 : minutos;
        minutos = (minutos >= 45 && minutos < 50) ? 45 : minutos;
        if(minutos >=50 && minutos <=59){
            minutos = 0;
            horas  = (horas < 24) ? horas + 1 : 1;
        }
        $('#aprazamento_' + tipo).val(left_pad_zeros(horas,2) + ":" + left_pad_zeros(minutos,2));
    }
    else{
        var data = $('#aprazamento_'+tipo).val();
        var horas = data.substring(0,2);
        var minutos = data.substring(3,5)
    }
    if( $('#btn-prazo-'+tipo).attr('data-valor') == '2')
        var prazo = $('#prazo_'+tipo).val();
    else
        var prazo = 24;
    var frequencia = $('#intervalo_'+tipo).val();
    calcula_aprazamento(horas, minutos, frequencia,prazo,tipo);
}

function lista_dieta(prescricao, tipo) {
    var dieta = "";
    for (var x = 0; x < prescricao.length; x++) {
        var classe = '';
        if (prescricao[x].status == 'E')
            classe = " class='riscado' title='Excluído'";
        if (prescricao[x].status == 'C')
            classe = " class='concluido' title='Concluído'";
        dieta +=
            "<tr" + classe + ">" +
            "<td style='width: 10%'>" + (prescricao[x].dieta !== null ? prescricao[x].dieta : '') + "</td>" +
            "<td style='width: 20%'>" + (prescricao[x].via_dieta !== null ? prescricao[x].via_dieta : '') + "</td>" +
            "<td style='width: 40%'>" + (prescricao[x].descricao_dieta !== null ? prescricao[x].descricao_dieta : '') + "</td>" +
            "<td>" + (prescricao[x].aprazamento !== null ? prescricao[x].aprazamento : '') + "</td>" +
            "<td style='width: 10%'>";
        if (prescricao[x].status_prescricao == 'A' && prescricao[x].status == 'A')
            dieta += "<button type='button' data-tipo='dieta' data-id=" + prescricao[x].id_atendimento_prescricao_dieta + " data-valor='E' class='btn btn-danger btn-xs pull-right editar-atendimento-prescricao' title='Excluir'><span class='fa fa-trash'></span></button>";
    //    if (prescricao[x].status_prescricao == 'C' && prescricao[x].status == 'A')
    //        dieta += "<button type='button' data-tipo='dieta' data-id=" + prescricao[x].id_atendimento_prescricao_dieta + " data-valor='C' class='btn btn-success btn-xs pull-right editar-atendimento-prescricao' title='Concluir'><span class='fa fa-check'></span></button>";
        if (prescricao[x].status == 'C')
            dieta += "Concluído";
        if (prescricao[x].status == 'E')
            dieta += "Excluído";
        dieta += "</td>" +
            "</tr>";

    }

    testa_visibilidade_divs('dieta',tipo, dieta);
}

function lista_csv(prescricao, tipo) {
    var csv = "";
    for (var x = 0; x < prescricao.length; x++) {
        var classe = '';
        if (prescricao[x].status == 'E')
            classe = " class='riscado' title='Excluído'";
        if (prescricao[x].status == 'C')
            classe = " class='concluido' title='Concluído'";
        csv +=
            "<tr" + classe + ">" +
            "<td style='width: 30%'>" + (prescricao[x].intervalo_csv !== null ? prescricao[x].intervalo_csv + '/' + prescricao[x].intervalo_csv + ' ' : '') + (prescricao[x].tp_intervalo_csv !== null ? prescricao[x].tp_intervalo_csv : '') + " durante "+prescricao[x].prazo_csv+" "+prescricao[x].tp_prazo_csv + "</td>" +
            "<td style='width: 40%'>" + (prescricao[x].descricao_csv !== null ? prescricao[x].descricao_csv : '') + "</td>" +
            "<td>" + (prescricao[x].aprazamento !== null ? prescricao[x].aprazamento : '') + "</td>" +
            "<td style='width: 10%'>";
        if (prescricao[x].status_prescricao == 'A' && prescricao[x].status == 'A')
            csv += "<button type='button' data-tipo='csv' data-id=" + prescricao[x].id_atendimento_prescricao_csv + " data-valor='E' class='btn btn-danger btn-xs pull-right editar-atendimento-prescricao' title='Excluir'><span class='fa fa-trash'></span></button>";
     //   if (prescricao[x].status_prescricao == 'C' && prescricao[x].status == 'A')
    //        csv += "<button type='button' data-tipo='csv' data-id=" + prescricao[x].id_atendimento_prescricao_csv + " data-valor='C' class='btn btn-success btn-xs pull-right editar-atendimento-prescricao' title='Concluir'><span class='fa fa-check'></span></button>";

        if (prescricao[x].status == 'C')
            csv += 'Concluído';
        if (prescricao[x].status == 'E')
            csv += 'Excluído';
        csv += "</td>" +
            "</tr>";

    }
    testa_visibilidade_divs('sinais-vitais',tipo, csv);
}

function lista_outros_cuidados(prescricao, tipo) {
    var outros_cuidados = "";
    for (var x = 0; x < prescricao.length; x++) {
        var classe = '';
        if (prescricao[x].status == 'E')
            classe = " class='riscado' title='Excluído'";
        if (prescricao[x].status == 'C')
            classe = " class='concluido' title='Concluído'";
        outros_cuidados +=
            "<tr" + classe + ">" +
            "<td style='width: 30%'>" + (prescricao[x].posicao !== null ? prescricao[x].posicao : '') + "</td>" +
            "<td>" + (prescricao[x].descricao_posicao !== null ? prescricao[x].descricao_posicao : '') + "</td>" +
           // "<td>" + (prescricao[x].hora_exibida !== null ? prescricao[x].hora_exibida : '') + "</td>" +
            "<td style='width: 10%'>";
        if (prescricao[x].status_prescricao == 'A' && prescricao[x].status == 'A')
            outros_cuidados += "<button type='button' data-tipo='outros_cuidados' data-id=" + prescricao[x].id_atendimento_prescricao_outros_cuidados + " data-valor='E' class='btn btn-danger btn-xs pull-right editar-atendimento-prescricao' title='Excluir'><span class='fa fa-trash'></span></button>";
    //    if (prescricao[x].status_prescricao == 'C' && prescricao[x].status == 'A')
     //           outros_cuidados += "<button type='button' data-tipo='outros_cuidados' data-id=" + prescricao[x].id_atendimento_prescricao_outros_cuidados + " data-valor='C' class='btn btn-success btn-xs pull-right editar-atendimento-prescricao' title='Concluir'><span class='fa fa-check'></span></button>";
        if (prescricao[x].status == 'C')
            outros_cuidados += 'Concluído';
        if (prescricao[x].status == 'E')
            outros_cuidados += 'Excluído';
        outros_cuidados += "</td>" +
            "</tr>";
    }
    testa_visibilidade_divs('outros-cuidados',tipo, outros_cuidados);
}

function lista_oxigenoterapia(prescricao, tipo) {
    var oxigenoterapia = "";
    for (var x = 0; x < prescricao.length; x++) {
        var classe = '';
        if (prescricao[x].status == 'E')
            classe = " class='riscado' title='Excluído'";
        if (prescricao[x].status == 'C')
            classe = " class='concluido' title='Concluído'";
        oxigenoterapia +=
            "<tr" + classe + ">" +
            "<td style='width: 10%'>" + prescricao[x].qtde_oxigenio + " L/min</td>" +
            "<td style='width: 20%'>" + prescricao[x].administracao_oxigenio + "</td>" +
            "<td style='width: 40%'>" + (prescricao[x].descricao_oxigenio !== null ? prescricao[x].descricao_oxigenio : '') + "</td>" +
            "<td>" + (prescricao[x].aprazamento !== null ? prescricao[x].aprazamento : '') + "</td>" +
            "<td style='width: 10%'>";
        if (prescricao[x].status_prescricao == 'A' && prescricao[x].status == 'A')
            oxigenoterapia += "<button type='button' data-tipo='oxigenoterapia' data-id=" + prescricao[x].id_atendimento_prescricao_oxigenoterapia + " data-valor='E' class='btn btn-danger btn-xs pull-right editar-atendimento-prescricao' title='Excluir'><span class='fa fa-trash'></span></button>";
    //    if (prescricao[x].status_prescricao == 'C' && prescricao[x].status == 'A')
    //        oxigenoterapia += "<button type='button' data-tipo='oxigenoterapia' data-id=" + prescricao[x].id_atendimento_prescricao_oxigenoterapia + " data-valor='C' class='btn btn-success btn-xs pull-right editar-atendimento-prescricao' title='Concluir'><span class='fa fa-check'></span></button>";
        if (prescricao[x].status == 'C')
            oxigenoterapia += 'Concluído';
        if (prescricao[x].status == 'E')
            oxigenoterapia += 'Excluído';
        oxigenoterapia += "</td>" +
            "</tr>";

    }
    testa_visibilidade_divs('oxigenoterapia',tipo, oxigenoterapia);
}

function lista_medicacao(prescricao, tipo) {
    var receituario=''; var prescricao_ambulatorial=''; var prescricao_hospitalar='';
    var head_html =
        "<thead>" +
        "<th>Medicamento</th>" +
        "<th>Dose</th>" +
        "<th>Via</th>" +
        "<th>Frequência</th>" +
        "<th>Período</th>" +
        "<th>S.N.</th>" +
        "<th>Observação</th>" +
        "<th>Aprazamento</th>" +
        "<th class='text-center'></th>" +
        "</tr>" +
        "</thead>" +
        "<tbody id='corpo-tabela-receita'>";
    for(var x=0;x<prescricao.length;x++){
        var classe = '';
        if(prescricao[x].status == 'E')
            classe = " class='riscado' title='Excluído'";
        if(prescricao[x].status == 'C')
            classe = " class='concluido' title='Concluído'";
        html =
            "<tr "+classe+" title = '"+prescricao[x].nm_produto+" - "+prescricao[x].ds_produto+" - LABORATÓRIO "+prescricao[x].nm_laboratorio+"'>" +
            "<td>"+(prescricao[x].nm_produto !== null ? prescricao[x].nm_produto : '')+"</td>" +
            "<td>"+prescricao[x].dose+" "+prescricao[x].abreviacao+"</td>" +
            "<td>"+prescricao[x].sigla+"</td>" +
            "<td>"+prescricao[x].intervalo+"/"+prescricao[x].intervalo+" "+prescricao[x].tp_intervalo+"</td>" +
            "<td>"+prescricao[x].prazo+" "+prescricao[x].tp_prazo+"</td>" +
            "<td><b>"+(prescricao[x].se_necessario == 1 ? '&check;' : '')+"</b></td>" +
            "<td>"+(prescricao[x].observacao_medicamento !== null ? prescricao[x].observacao_medicamento : '')+"</td>" +
            "<td>"+(prescricao[x].aprazamento != null ? prescricao[x].aprazamento : '')+"</td>" +
            "<td style='width: 10%'>";
        if (prescricao[x].status_prescricao == 'A' && prescricao[x].status == 'A')
            html += "<button type='button' data-tipo='medicacao' data-id=" + prescricao[x].id_atendimento_prescricao_medicacao + " data-valor='E' class='btn btn-danger btn-xs pull-right editar-atendimento-prescricao' title='Excluir'><span class='fa fa-trash'></span></button>";
     //   if (prescricao[x].status_prescricao == 'C' && prescricao[x].status == 'A')
    //        html += "<button type='button' data-tipo='medicacao' data-id=" + prescricao[x].id_atendimento_prescricao_medicacao + " data-valor='C' class='btn btn-success btn-xs pull-right editar-atendimento-prescricao' title='Concluir'><span class='fa fa-check'></span></button>";

        if(prescricao[x].status == 'C')
            html += 'Concluído';
        if(prescricao[x].status == 'E')
            html += 'Excluído';
        html += "</td></tr>";
        if(tipo === 'RECEITUARIO')
            receituario += html;
        else if(tipo === 'PRESCRICAO_HOSPITALAR')
            prescricao_hospitalar += html;
        else
            prescricao_ambulatorial += html;
    }

    if(receituario !== ""){
        document.getElementById('div-receituario').style.display = 'block';
        $('.table_medicacao_receituario').html(head_html + receituario + '</tbody>');
        if($('#tipo_conduta_hidden').val()==="receituario") {
            $('.table_medicacao').html(head_html + receituario + '</tbody>');
        }
    }
    if(prescricao_hospitalar !== ""){
        document.getElementById('div-mostra-prescricao-hospitalar').style.display = 'block';
        $('.table_medicacao_prescricao_medica').html(head_html + prescricao_hospitalar + '</tbody>');
        if($('#tipo_conduta_hidden').val()==="prescricao_hospitalar") {
            $('.table_medicacao').html(head_html + prescricao_hospitalar + '</tbody>');
        }
    }
    if(prescricao_ambulatorial !== ""){
        document.getElementById('div-mostra-prescricao-ambulatorial').style.display = 'block';
        $('.table_medicacao_prescricao_ambulatorial').html(head_html + prescricao_ambulatorial + '</tbody>');
        if($('#tipo_conduta_hidden').val()==="prescricao_ambulatorial") {
            $('.table_medicacao').html(head_html + prescricao_ambulatorial + '</tbody>');
        }
    }

}

function lista_exame_laboratorial(prescricao, tipo) {
    var receituario="", prescricao_ambulatorial = "";
    var head_html =
        "<thead>" +
        "<th>Exame</th>" +
        "<th>Observação</th>" +
        "<th class='text-center'>Ação</th>" +
        "</tr>" +
        "</thead>" +
        "<tbody id='corpo-tabela-exame-laboratorial'>";
    for(var x=0;x<prescricao.length;x++){
        var classe = '';
        if(prescricao[x].status == 'E')
            classe = " class='riscado' title='Excluído'";
        if(prescricao[x].status == 'C')
            classe = " class='concluido' title='Concluído'";
        html =
            "<tr "+classe+">" +
            "<td>"+(prescricao[x].exame_laboratorial !== null ? prescricao[x].exame_laboratorial : '')+"</td>" +
            "<td>"+(prescricao[x].observacao_exame_laboratorial !== null ? prescricao[x].observacao_exame_laboratorial : '')+"</td>" +
            "<td style='width: 10%'>";
        if (prescricao[x].status_prescricao == 'A' && prescricao[x].status == 'A')
            html += "<button type='button' data-tipo='exame_laboratorial' data-id=" + prescricao[x].id_atendimento_prescricao_exame_laboratorial + " data-valor='E' class='btn btn-danger btn-xs pull-right editar-atendimento-prescricao' title='Excluir'><span class='fa fa-trash'></span></button>";
        if(prescricao[x].status == 'C')
            html += 'Concluído';
        if(prescricao[x].status == 'E')
            html += 'Excluído';
        html += "</td>";
        if(tipo == 'RECEITUARIO') {
            receituario += html;
        }
        else {
            prescricao_ambulatorial += html;
        }
    }
    $('.table_exames_laboratoriais').html('');
    if(receituario !== ""){
        document.getElementById('div-receituario').style.display = 'block';
        $('.table_exames_laboratoriais_receituario').html(head_html + receituario + '</tbody>');
        if($('#tipo_conduta_hidden').val()==="receituario") {
            $('.table_exames_laboratoriais').html(head_html + receituario + '</tbody>');
        }
    }
    if(prescricao_ambulatorial !== ""){
        document.getElementById('div-prescricao-ambulatorial').style.display = 'block';
        $('.table_exames_laboratoriais_prescricao_ambulatorial').html(head_html + prescricao_ambulatorial + '</tbody>');
        if($('#tipo_conduta_hidden').val()==="prescricao_ambulatorial") {
            $('.table_exames_laboratoriais').html(head_html + prescricao_ambulatorial + '</tbody>');
        }
    }

}

$(document).on('click', '.editar-atendimento-prescricao', function(){
    var par1 = $(this).attr('data-tipo');
    var par2 = $(this).attr('data-id');
    var par3 = $(this).attr('data-valor');
    var acao = "finalizar";
    if($(this).attr('data-valor') == 'E')
        acao = 'remover';
    swal({
        title: 'Confirmação',
        text: "Você tem certeza que quer "+ acao +" esse item? Essa ação não poderá ser desfeita.",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim, tenho certeza!'
    }).then(function (result) {
        if (result.value) {
            $.ajax({
                type: 'POST',
                url: dir + 'ajax/atendimentos/editar-prescricao',
                data: {"par1": par1, "par2": par2,"par3":par3,"_token": token},
                dataType: 'json',
                success: function (data) {
                    busca_ultimas_prescricoes($('#cd_prontuario').val());
                }
            });
        }
    });
});

function testa_visibilidade_divs(nome, tipo, html) {
    var aux_nome = nome.replace("-"," ");
    aux_nome = aux_nome.replace(/(?:^|\s)\S/g, function(a) { return a.toUpperCase(); });
    var aux_tipo = tipo.toLowerCase();
    tipo = aux_tipo.replace('_','-');
    var header = "<div class='table-responsive'>" +
        "<table class='table table-hover table-striped font-size-9pt'>" +
        "<thead>" +
        "<th>"+aux_nome+"</th>" +
        "</thead>" +
        "<tbody>";
    var footer =
        "</tbody>" +
        "</table>" +
        "</div>";
    //if(html != '') {
        document.getElementById('div-'+tipo).style.display = 'block';
        $('.div-mostra-'+nome+'-'+tipo).html(header + html + footer);
   // }
    if($('#tipo_conduta_hidden').val() != undefined) {
        if ($('#tipo_conduta_hidden').val() == aux_tipo) {
            $('.div-mostra-' + nome).html((html != '' ? header + html + footer : ''));
        }
    }
}

function busca_ultimas_prescricoes(cd_prontuario) {
    $("#painel-geral :input").val("")
    $.ajax({
        type: 'POST',
        url: dir + 'ajax/atendimentos/busca-ultimas-prescricoes',
        data: {"cd_prontuario":cd_prontuario, "_token": token},
        dataType: 'json',
        success: function (data) {
            var itens = data.itens;
            var prescricao= data.prescricao;

            if(data.success === true) {
                if(itens != undefined) {
                    for (var [key, value] of Object.entries(itens)) {
                        carrega_tabelas_prescricao(key.toUpperCase(), value);
                    }
                }
                if(prescricao != undefined) {
                    if (prescricao.receituario != undefined) {
                        $('#id_receituario').val(prescricao.receituario.id_atendimento_prescricao);
                        $('#id_receituario').attr('data-status',prescricao.receituario.status);
                    }
                    if (prescricao.prescricao_hospitalar != undefined) {
                        document.getElementById('div-prescricao-hospitalar').style.display = 'block';
                        $('#id_prescricao_hospitalar').val(prescricao.prescricao_hospitalar.id_atendimento_prescricao);
                        $('#id_prescricao_hospitalar').attr('data-status',prescricao.prescricao_hospitalar.status);
                    }
                    if (prescricao.prescricao_ambulatorial != undefined) {
                        document.getElementById("btn-imprimir-prescricao-ambulatorial").setAttribute("href", dir+"relatorios/prescricao-ambulatorial/"+$('#cd_prontuario').val()+"/"+prescricao.prescricao_ambulatorial.cd_prescricao);
                        document.getElementById("rota_prescricao_ambulatorial").setAttribute("href", dir+"relatorios/prescricao-ambulatorial/"+$('#cd_prontuario').val()+"/"+prescricao.prescricao_ambulatorial.cd_prescricao);
                        document.getElementById('div-prescricao-ambulatorial').style.display = 'block';
                        $('#id_prescricao_ambulatorial').val(prescricao.prescricao_ambulatorial.id_atendimento_prescricao);
                        $('#id_prescricao_ambulatorial').attr('data-status',prescricao.prescricao_ambulatorial.status);
                        var expira = new Date(prescricao.prescricao_ambulatorial.expira_em);
                        var agora = new Date();
                        var expirado = (expira > agora) ? false : true;

                        var html = "<h5 style='text-align: center; color: " + (expirado ? 'red' : '') + "'>Prescrição Nº " + prescricao.prescricao_ambulatorial.cd_prescricao + " - Criação: " + convertDate(prescricao.prescricao_ambulatorial.created_at) + " - Valida até: " + convertDate(prescricao.prescricao_ambulatorial.expira_em) + "</h5>";
                        $('.titulo-div-ambulatorial').html(html);
                        document.getElementById('titulo-div-ambulatorial').style.display = 'block';
                    }
                }
                if( $('#id_prescricao_ambulatorial').attr('data-status') == 'C')
                    document.getElementById('btn-imprimir-prescricao-ambulatorial').style.display = 'block';
                else
                    document.getElementById('btn-imprimir-prescricao-ambulatorial').style.display = 'none';
            }
        }
    });
}

function carrega_tabelas_prescricao(nome, parametros) {
    for (var [key, value] of Object.entries(parametros)) {
        if(value.length > 0) {
            window['lista_' + key](value, nome);
        }
    }
}

function calcula_aprazamento(horas, minutos, frequencia,prazo,nome){
    var vezes_dia = prazo/parseInt(frequencia);
    var calcula_hora = 0;
    var aprazamento = [];
    var html = "";
    for(var x=0;x<vezes_dia;x++){
        calcula_hora = 0;
        calcula_hora = parseInt(horas) + (parseInt(frequencia)*parseInt(x));
        calcula_hora = (calcula_hora >= 24) ? calcula_hora - 24 : calcula_hora;
        aprazamento[x] = left_pad_zeros(calcula_hora,2)+":"+left_pad_zeros(minutos,2);
        if(x>0) {
            html += "<div class='col-md-1'>";
            html += "<label for='aprazamento_"+nome+"'>&nbsp;</label>";
            html += "<input class='form-control font-8 mask-hora margin-top-17' name='aprazamento_"+nome+"[]' type='text' value='" + aprazamento[x] + "'/>";
            html += "</div>";
        }
    }
    $("#div-aprazamento-"+nome).html(html);
}

function left_pad_zeros(number, length) {
    var my_string = '' + number;
    while (my_string.length < length) {
        my_string = '0' + my_string;
    }
    return my_string;
}

function transformaAprazamentoemString(array){
    var string = "";
    if(array.length > 0) {
        for (var x = 1; x < array.length; x++) {
            string = string + "; "+array[x].value;
        }
    }
    return string;
}

function convertDate(inputFormat) {
    if(inputFormat == null)
        return '';
    else {
        var converte = inputFormat.split("-");
        if(converte[2].length > 2) {
            var hora = converte[2].split(" ");
            converte[2] = hora[0];
            var resposta = converte[2] + '/' + converte[1] + '/' + converte[0] + ' '+hora[1];
        }
        else {
            var resposta = converte[2] + '/' + converte[1] + '/' + converte[0];
        }
        return (resposta);
    }
}