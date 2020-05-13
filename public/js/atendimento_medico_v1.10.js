$(document).ready(function() {
    lista_atendimento_avaliacao_cid($('#cd_prontuario').val());
    lista_atendimento_procedimento($('#cd_prontuario').val());
    lista_atendimento_evolucao($('#cd_prontuario').val());
    $(document).keydown(function (e) { //Quando uma tecla é pressionada
        if(e.which == 17) pressedCtrl = true; //Informando que Crtl está acionado
        if((e.which == 119|| e.keyCode == 119) && pressedCtrl == true) { //Reconhecendo tecla Enter
            if(document.getElementById('oculta_prescricao').style.display == 'none'){
                document.getElementById('oculta_prescricao').style.display = 'block';
            }
            else{
                document.getElementById('oculta_prescricao').style.display = 'none';
            }
        }
    });
});

$('#add_cid').click(function() {
    var id_cid = $('#id_cid').attr('data-id');
    if (id_cid != '') {
        document.getElementById('add_cid').disabled = true;
        add_atendimento_avaliacao_cid();
        $('#id_cid').val('');
        $('#id_cid').attr('data_id', '');
        document.getElementById('add_cid').disabled = false;
    }
});

$('#minimiza_objetivo').click(function() {
    var display = document.getElementById('avalicao_enfermagem_objetivo').style.display;
    if(display == "none") {
        document.getElementById('avalicao_enfermagem_objetivo').style.display = 'block';
        $('#minimiza_objetivo').html('<span title="Ocultar" class="fas fa-minus"></span>');
    }
    else {
        document.getElementById('avalicao_enfermagem_objetivo').style.display = 'none';
        $('#minimiza_objetivo').html('<span title="Exibir" class="fa fa-plus"></span>');
    }
});


$('#finalizar-atendimento').click(function() {
    $('#status-prontuario').val('C');
});

$('#finalizar-atendimento-apos-medicacao').click(function() {
    $('#status-prontuario').val('E');
});

$(document).on('change', '#motivo_alta', function(){
    if($('#motivo_alta').val() == 5) {
        swal({
            title: 'Confirmação',
            text: "Você escolheu a opção ÓBITO. Tem certeza?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim, tenho certeza!'
        }).then(function (result) {
            if (result.value) {
                $('#status-prontuario').val('C');
            }
            else
            {
                $('#motivo_alta').val(0);
            }
        });
    }
});

$(document).on('click', '.excluir-cid', function(){
    exclui_atendimento_avaliacao_cid($(this).val(),$(this).attr('data-prontuario'));
});

$(document).on('click', '.excluir-procedimento', function(){
    exclui_atendimento_procedimento($(this).attr('data-procedimento'),$(this).attr('data-prontuario'));
});

$('#add-cid-principal').click(function() {
    $('#cid_principal').val('S');
});

$('#add-cid-secundaria').click(function() {
    $('#cid_principal').val('N');
});

$('.search').on('click',function () {
    $('#search').attr('data-destino',$(this).attr('data-destino'));
});

$('#search').on('keyup',function () {
    if(($('#search').val().length > 3)) {
        if($('#search').attr('data-destino') == 'pesquisa_cid')
            pesquisa_cid($('#search').val());
        else if($('#search').attr('data-destino') == 'pesquisa_procedimento')
            pesquisa_procedimento($('#search').val());
        else if($('#search').attr('data-destino') == 'pesquisa_procedimento_ocupacao')
            pesquisa_procedimento_ocupacao($('#search').val());
    }
});

$(document).on('click', '#selecionar-procedimento-ocupacao', function(){
    $('#nome_procedimento').val($(this).attr('data-nome'));
    $('#cd_procedimento').val($(this).val());
    $('#modal-search').modal('hide');
});

$(document).on('click', '#salvar-procedimento-atendimento', function(){
    salva_procedimento_atendimento($('#d_id_atendimento_procedimento').val(),'A',$('#d_execucao').val());
});

$(document).on('click', '#finalizar-procedimento-atendimento', function(){
    salva_procedimento_atendimento($('#d_id_atendimento_procedimento').val(),'C',$('#d_execucao').val());
});

function salva_procedimento_atendimento(id_atendimento_procedimento,id_status,descricao_execucao) {
    $.ajax({
        type: 'POST',
        url: dir + 'ajax/atendimentos/salva-procedimento-atendimento',
        data: {"id_atendimento_procedimento":id_atendimento_procedimento, "id_status":id_status, "descricao_execucao": descricao_execucao, "_token": token},
        dataType: 'json',
        success: function (data) {
            if (data.success == true) {
                lista_atendimento_procedimento($('#cd_prontuario').val());
                $('#modal-detalhes-procedimento').modal('hide');
            }
        }
    });
}

function add_atendimento_avaliacao_cid() {
    $.ajax({
        type: 'POST',
        url: dir + 'ajax/atendimentos/add-atendimento-avaliacao-cid',
        data: {"cd_prontuario":$('#cd_prontuario').val(), "id_cid": $('#id_cid').attr('data-id'), "cid_principal":$('#cid_principal').val(), "_token": token,
            'dt_primeiros_sintomas':$('#dt_primeiros_sintomas').val(), 'tipo_diagnostico':$('#tipo_diagnostico').val(),
            'diagnostico_trabalho':$('#diagnostico_trabalho').val(), 'diagnostico_transito':$('#diagnostico_transito').val()},
        dataType: 'json',
        success: function (data) {
            if(data.success) {
                $('#escolhe-cid').modal('hide');
                lista_atendimento_avaliacao_cid($('#cd_prontuario').val());
            } else {
                error_alert(data.mensagem);
            }
        }
    });
    $('#search').val('');
}

function lista_atendimento_avaliacao_cid(cdProntuario) {
    $.ajax({
        type: 'POST',
        url: dir + 'ajax/atendimentos/lista-atendimento-avaliacao-cid',
        data: {"cd_prontuario":cdProntuario, "_token": token},
        dataType: 'json',
        success: function (data) {
            var opcao = {N:"Não", S:"Sim", I:"Não informado"};
            var secundario = false;
            var principal = false;
            var header =
                "<div class='table-responsive'>" +
                "<table class='table table-bordered table-hover table-striped'>" +
                "<thead>" +
                "<tr>" +
                "<th>Cid10</th>" +
                "<th>Descrição</th>" +
                "<th>Diagnóstico</th>" +
                "<th>Acidente</th>" +
                "<th>Primeiros Sintomas</th>" +
                "<th class='text-center'>Ação</th>" +
                "</tr>" +
                "</thead>" +
                "<tbody>";
            var footer = "</tbody></table></div>"
            var html = '';
            var html1 = header;
            var html2 = header;
            for(var x=0;x<data.retorno.length;x++){
                html =
                    "<tr>" +
                    "<td>"+data.retorno[x].cd_cid+"</td>" +
                    "<td>"+data.retorno[x].nm_cid+"</td>" +
                    "<td>"+(data.retorno[x].tipo_diagnostico == 'D' ? 'Definitivo' : 'Provisório')+"</td>" +
                    "<td><b>Trabalho:</b> "+opcao[data.retorno[x].diagnostico_trabalho] +
                    " <b>Trânsito:</b> "+opcao[data.retorno[x].diagnostico_transito]+"</td>" +
                    "<td>"+convertDate(data.retorno[x].dt_primeiros_sintomas)+"</td>";
                if(data.retorno[x].status == 'A' && data.id_user_atual==data.retorno[x].id_user) {
                    html += "<td><button type='button' class='btn btn-danger btn-sm excluir-cid' data-prontuario=" + cdProntuario + " value=" + data.retorno[x].id_cid + " title='Excluir'><span class='fa fa-trash'></span></button></td>";
                }
                else html += "<td></td>";
                html += "<tr>";
                if(data.retorno[x].cid_principal == 'S'){
                    html1 += html;
                    principal = true;
                }
                else{
                    html2 += html;
                    secundario = true;
                }
            }
            if(principal == true) {
                if((!data.retorno.length == 0) && data.retorno[0].status == 'A') {
                    document.getElementById('add-cid-principal').style.display = 'none';
                    document.getElementById('add-cid-secundaria').style.display = 'block';
                }
                $('#diagnostico_principal').html(html1 + footer);
                if(secundario == true)
                    $('#diagnostico_secundario').html(html2 + footer);
                else
                    $('#diagnostico_secundario').html('');
            }
            else {
                if((!data.retorno.length == 0) && data.retorno[0].status == 'A') {
                    document.getElementById('add-cid-principal').style.display = 'block';
                    document.getElementById('add-cid-secundaria').style.display = 'none';
                }
                $('#diagnostico_principal').html('');
                if(secundario == true)
                    $('#diagnostico_secundario').html(html2 + footer);
                else
                    $('#diagnostico_secundario').html('');
            }


        }
    });
}

function exclui_atendimento_avaliacao_cid(idCid, cdProntuario) {
    $.ajax({
        type: 'POST',
        url: dir + 'ajax/atendimentos/exclui-atendimento-avaliacao-cid',
        data: {"cd_prontuario":cdProntuario, "id_cid": idCid, "_token": token},
        dataType: 'json',
        success: function (data) {
            if(data.success) {
                lista_atendimento_avaliacao_cid(cdProntuario);
            }

        }
    });
}

function lista_atendimento_procedimento(cdProntuario) {
    $.ajax({
        type: 'POST',
        url: dir + 'ajax/atendimentos/lista-atendimento-procedimento',
        data: {"cd_prontuario":cdProntuario, "_token": token},
        dataType: 'json',
        success: function (data) {
            var html = "<div class='table-responsive'>"+
                "<table class='table table-hover table-striped font-size-9pt'>"+
                "<thead>"+
                "<tr>"+
                "<th>Código</th>"+
                "<th>Nome do Procedimento</th>"+
                "<th>Situação</th>"+
                "<th width='100px'>Ação</th>"+
                "</tr>"+
                "</thead>"+
                "<tbody  id='lista-procedimentos' data-permissoes='false'>";
            if(data.success) {
                if(data.retorno.length > 0 && document.getElementById('lista-procedimentos-atendimento-medico') !== null)
                    document.getElementById('lista-procedimentos-atendimento-medico').style.display = 'block';
                for(var x=0;x<data.retorno.length;x++){
                    html +=
                        "<tr title='"+(data.retorno[x].descricao_solicitacao !== null ? data.retorno[x].descricao_solicitacao : '')+"'>" +
                        "<td>"+data.retorno[x].cd_procedimento+"</td>" +
                        "<td>"+data.retorno[x].nm_procedimento+"</td>";
                        if(data.retorno[x].id_status === 'E')
                            html += "<td>Excluído</td>";
                        else if(data.retorno[x].id_status === 'C')
                            html += "<td>Concluído</td>";
                        else
                            html += "<td>Pendente</td>";
                        html += "<td>";

                    if((data.retorno[x].status !== 'C') && (data.id_user_atual == data.retorno[x].id_user_solicitante || data.id_user_atual == 31)) {
                        if (data.retorno[x].id_status === 'A') {
                            html += "<button type='button' class='btn btn-danger btn-xs excluir-procedimento pull-right' value='Excluir' data-prontuario=" + cdProntuario + " data-procedimento=" + data.retorno[x].cd_procedimento + "><span class='fa fa-trash'></span></button>";
                        }
                    }
                    if(($('#lista-procedimentos').attr('data-permissoes')=="true" && data.retorno[x].permitido == data.retorno[x].cd_procedimento) || $('#lista-procedimentos').attr('data-permissoes')=="false")
                        html +="<button type='button' class='btn btn-primary btn-xs detalhes-procedimento pull-right' data-permissao='"+((data.retorno[x].permitido == data.retorno[x].cd_procedimento) ? 1 : 0)+"' data-toggle='modal' data-target='#modal-detalhes-procedimento' value="+data.retorno[x].id_atendimento_procedimento+" title='Detalhes'><span class='fa fa-eye'></span></button>";

                    html += "</td><tr>";
                }
                $('#descricao_solicitacao').val('');
                $('.lista-procedimentos').html(html+"</tbody></table></div>");
            }
        }
    });
}

function exclui_atendimento_procedimento(cdProcedimento, cdProntuario) {
    $.ajax({
        type: 'POST',
        url: dir + 'ajax/atendimentos/exclui-atendimento-procedimento',
        data: {"cd_prontuario":cdProntuario, "cd_procedimento": cdProcedimento, "_token": token},
        dataType: 'json',
        success: function (data) {
            if(data.success) {
                lista_atendimento_procedimento(cdProntuario);
            }
        }
    });
}

function pesquisa_cid(pesquisa) {
    $.ajax({
        type: 'POST',
        url: dir + 'ajax/atendimentos/pesquisa-cid',
        data: {"pesquisa":pesquisa, "_token": token},
        dataType: 'json',
        success: function (data) {
            var html = '';
            if (data.success) {
                for (var x = 0; x < data.retorno.length; x++) {
                    html += "<tr><td>" + data.retorno[x].nm_cid + " - " +data.retorno[x].nm_cid + "<button id='selecionar-cid' data-nome='"+data.retorno[x].nm_cid + "' value="+ data.retorno[x].id_cid + " class='btn btn-primary btn-sm pull-right'><span class='fa fa-check'></span></button></td></tr>";
                }
            }
            $('#retorno-search').html(html);
        }
    });
}

function pesquisa_procedimento_ocupacao(pesquisa) {
    $.ajax({
        type: 'POST',
        url: dir + 'ajax/atendimentos/pesquisa-procedimento-ocupacao',
        data: {"pesquisa":pesquisa, "_token": token},
        dataType: 'json',
        success: function (data) {
            var html = '';
            if (data.success) {
                for (var x = 0; x < data.retorno.length; x++) {
                    html += "<tr><td>" + data.retorno[x].cd_procedimento + " - " +data.retorno[x].nm_procedimento + "<button id='selecionar-procedimento-ocupacao' data-nome='"+data.retorno[x].nm_procedimento + "' value="+ data.retorno[x].cd_procedimento + " class='btn btn-primary btn-sm pull-right'><span class='fa fa-check'></span></button></td></tr>";
                }
            }
            $('#retorno-search').html(html);
        }
    });
}

function lista_atendimento_evolucao(cdProntuario) {
    $.ajax({
        type: 'POST',
        url: dir + 'ajax/atendimentos/lista-atendimento-evolucao',
        data: {"cd_prontuario":cdProntuario, "_token": token},
        dataType: 'json',
        success: function (data) {
            var html = "";
            if(data.success) {
                for(var x=0;x<data.retorno.length;x++){
                    html +=
                        "<tr>"+
                        "<td>"+convertDate(data.retorno[x].created_at)+"</td>" +
                        "<td>"+data.retorno[x].nm_sala;
                    if(data.retorno[x].cd_leito > 0)
                        html += '/ LEITO '+data.retorno[x].cd_leito;
                    html += "</td>" +
                        "<td>"+data.retorno[x].nm_pessoa+"</td>" +
                        "<td>"+data.retorno[x].descricao_evolucao+"</td>" +
                        "<tr>";
                }
                $('.tabela-detalhes-evolucao').html(html);
            }
        }
    });
}

