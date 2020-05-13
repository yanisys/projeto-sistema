$(document).ready(function() {
    lista_cid_historia_medica_pregressa($('#cd_pessoa_disabled').val());
});

$('#btn-modal-historia-medica-pregressa').click(function(){
    $('#pesquisa_nm_cid_historia_medica').val('');
    $('#table-historia-medica-pregressa').html('<tr><td colspan="2">Utilize a busca acima para encontrar CID.</td></tr>');
    $("#btn_pesquisar_cid_historia_medica").removeAttr('disabled');

    $('#modal-historia-medica-pregressa').modal({
        backdrop: 'static'
    })
    document.getElementById("pesquisa_nm_cid_historia_medica").focus();
})

$('#pesquisa_nm_cid_historia_medica').on('keydown', function(e) {
    if (e.which == 13) {
        pesquisa_cid();
    }
});

$(document).on('click', '#excluir-cid-historia-medica', function(){
    exclui_cid_historia_medica_pregressa($(this).attr('data-pessoa-historia-medica'));
});

$('#btn_pesquisar_cid_historia_medica').click(function() {
    pesquisa_cid();
})

function pesquisa_cid(){
    var nm_cid = $('#pesquisa_nm_cid_historia_medica').val();
    var erro = '';
    if (nm_cid.length <3) {
        erro += 'Sua pesquisa deve ter no mínimo 3 caracteres!<br>';
    }
    if (erro != '') {
        Swal('Atenção!',erro,'warning')
    } else {
        $("#btn_pesquisar_cid_historia_medica").attr('disabled', 'disabled');
        $('#table-historia-medica-pregressa').html('<tr><td colspan="2">Pesquisando CID, por favor aguarde...</td></tr>');
        $.ajax({
            type: 'POST',
            url: dir + 'ajax/atendimentos/pesquisa-cid',
            data: {"pesquisa": nm_cid, "_token": token},
            dataType: 'json',
            success: function (data) {
                if (data.success == true) {
                    var html = '';
                    if (data.dados.length == 0){
                        html='<tr><td colspan="2">Sem resultados para exibir....</td></tr>';
                    } else {
                        for (var x = 0; x < data.dados.length; x++) {
                            html += "<tr>" +
                                "<td>" + data.dados[x].cd_cid + " - " +data.dados[x].nm_cid + "</td>" +
                                "<td><button id='selecionar-cid-historia-medica-pregressa' data-nome='"+data.dados[x].nm_cid + "' value="+ data.dados[x].id_cid + " class='btn btn-primary btn-sm pull-right'><span class='fa fa-check'></span></button></td>" +
                                "</tr>";
                        }
                    }
                    $('#table-historia-medica-pregressa').html(html);
                }
                $("#btn_pesquisar_cid_historia_medica").removeAttr('disabled');

            }
        });
    }
}

$(document).on('click', '#selecionar-cid-historia-medica-pregressa', function(){
    var id_cid = $(this).val();
    var cd_pessoa = $('#cd_pessoa_disabled').val();
    $('#modal-historia-medica-pregressa').modal('hide');
    $.ajax({
        type: 'POST',
        url: dir + 'ajax/atendimentos/add-cid-historia-medica-pregressa',
        data: {"id_cid": id_cid, "cd_pessoa": cd_pessoa, "_token": token},
        dataType: 'json',
        success: function (data) {
            if (data.success == true) {
                lista_cid_historia_medica_pregressa(cd_pessoa);
            }
            else{
                class_alert = "error";
                titulo = "Erro!";

            swal({
                title: titulo,
                html: data.mensagem,
                type: class_alert,
                confirmButtonText: 'OK'
            })
            }
        }
    });
});

function lista_cid_historia_medica_pregressa(cdPessoa) {
    $.ajax({
        type: 'POST',
        url: dir + 'ajax/atendimentos/lista-cid-historia-medica-pregressa',
        data: {"cd_pessoa":cdPessoa, "_token": token},
        dataType: 'json',
        success: function (data) {
            var html = "";
            if(data.success) {
                var html =
                    "<div class='table-responsive tabela-acolhimento-atendimento'>" +
                    "<table class='table table-hover table-striped'>" +
                    "<tbody id='corpo-tabela-historia-medica-pregressa'>";
                var footer = "</tbody></table></div>"
                for(var x=0;x<data.retorno.length;x++){
                    html +=
                        "<tr>" +
                        "<td>"+data.retorno[x].cd_cid+" - " +
                        data.retorno[x].nm_cid+"</td>" +
                        "<td style='width: 10%'>" +
                        "<button type='button' id='excluir-cid-historia-medica' class='btn btn-danger btn-xs' data-pessoa-historia-medica = '"+ data.retorno[x].id_pessoa_historia_medica + "' title='Excluir'><span class='fa fa-trash'></span></button>" +
                        "</td>";
                }
                html = html + footer;
                $('.lista-historia-medica-pregressa').html(html);
            }
        }
    });
}

function exclui_cid_historia_medica_pregressa(idHistoriaMedica) {
    swal({
        title: 'Confirmação',
        text: "Você tem certeza que quer remover a Cid do histórico da pessoa?",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim, tenho certeza!'
    }).then(function (result) {
        if (result.value) {
            $.ajax({
                type: 'POST',
                url: dir + 'ajax/atendimentos/exclui-cid-historia-medica-pregressa',
                data: {"id_pessoa_historia_medica":idHistoriaMedica, "_token": token},
                dataType: 'json',
                success: function (data) {
                    if(data.success) {
                        lista_cid_historia_medica_pregressa($('#cd_pessoa_disabled').val());
                    }
                }
            });
        }
    });
}