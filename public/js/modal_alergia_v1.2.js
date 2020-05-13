$(document).ready(function() {
    lista_alergia_pessoa($('#cd_pessoa_disabled').val());
});

$(document).on('click', '#selecionar-alergia', function(){
    add_alergia_pessoa($(this).val(),$('#cd_pessoa_disabled').val());
});

$(document).on('click', '#excluir-alergia', function(){
    exclui_alergia_pessoa($(this).attr('data-pessoa-alergia'));
});

$('#btn-modal-alergia').click(function(){

    $('#pesquisa_nm_alergia').val('');
    $('#table-alergia').html('<tr><td colspan="2">Utilize a busca acima para encontrar alergias.</td></tr>');
    $("#btn-pesquisar-alergia").removeAttr('disabled');

    $('#modal-alergia').modal({
        backdrop: 'static'
    })
    document.getElementById("pesquisa_nm_alergia").focus();

})

$('#pesquisa_nm_alergia').on('keydown', function(e) {
    if (e.which == 13) {
        pesquisar_alergia();
    }
});

$('#btn-pesquisar-alergia').click(function() {
    pesquisar_alergia();
})

function pesquisar_alergia(){
    var nm_alergia = $('#pesquisa_nm_alergia').val();
    var erro = '';

    if (nm_alergia.length <3) {
        erro += 'Sua pesquisa deve ter no mínimo 3 caracteres!<br>';
    }
    if (erro != '') {
        Swal('Atenção!',erro,'warning')
    } else {
        $("#btn-pesquisar-alergia").attr('disabled', 'disabled');
        $('#table-alergia').html('<tr><td colspan="2">Pesquisando alergia, por favor aguarde...</td></tr>');
        $.ajax({
            type: 'POST',
            url: dir + 'ajax/atendimentos/pesquisa-alergia',
            data: {"pesquisa": nm_alergia, "_token": token},
            dataType: 'json',
            success: function (data) {
                if (data.success == true) {
                    var html = '';
                    if (data.dados.length == 0){
                        html='<tr><td colspan="2">Sem resultados para exibir....</td></tr>';
                    } else {
                        for (var x = 0; x < data.dados.length; x++) {
                            html += "<tr>" +
                                    "<td title='"+data.dados[x].descricao+"'>" +data.dados[x].nm_alergia + "</td>" +
                                    "<td><button id='selecionar-alergia' data-nome='"+data.dados[x].nm_alergia + "' value="+ data.dados[x].cd_alergia + " class='btn btn-primary btn-sm pull-right'><span class='fa fa-check'></span></button></td>" +
                                    "</tr>";
                        }
                    }
                    $('#table-alergia').html(html);
                }
                $("#btn-pesquisar-alergia").removeAttr('disabled');

            }
        });
    }
}

function add_alergia_pessoa(cd_alergia,cd_pessoa) {
    $.ajax({
        type: 'POST',
        url: dir + 'ajax/atendimentos/add-alergia-pessoa',
        data: {"cd_pessoa":cd_pessoa, "cd_alergia": cd_alergia, "_token": token},
        dataType: 'json',
        success: function (data) {
            if(data.success) {
                swal({
                    title: 'Sucesso!',
                    html: 'Alergia cadastrada com sucesso. Ela ficará cadastrada e será exibida em futuros atendimentos desse paciente.',
                    type: 'success',
                    confirmButtonText: 'OK'
                })
                lista_alergia_pessoa(cd_pessoa);
                $('#modal-alergia').modal('hide');
            } else {
                error_alert(data.mensagem);
            }
        }
    });
    $('#search').val('');
}

function lista_alergia_pessoa(cd_pessoa) {
    $.ajax({
        type: 'POST',
        url: dir + 'ajax/atendimentos/lista-alergia-pessoa',
        data: {"cd_pessoa":cd_pessoa, "_token": token},
        dataType: 'json',
        success: function (data) {
            if (data.success) {
                var html =
                    "<div class='table-responsive tabela-acolhimento-atendimento'>" +
                    "<table class='table table-hover table-striped'>";
                    "<tbody";
                var footer = "</tbody></table></div>";
                for (var x = 0; x < data.retorno.length; x++) {
                    html +=
                        "<tr>" +
                        "<td>" + data.retorno[x].nm_alergia + "</td>" +
                        "<td>" + data.retorno[x].descricao + "</td>" +
                        "<td style='width: 10%'>" +
                        "<button type='button' id='excluir-alergia' class='btn btn-danger btn-xs' data-pessoa-alergia = '" + data.retorno[x].id_pessoa_alergia + "' title='Excluir'><span class='fa fa-trash'></span></button>" +
                        "</td></tr>";
                }
                html = html + footer;
                $('.lista-alergias-pessoa').html(html);
            }
        }
    });
}

function exclui_alergia_pessoa(id_pessoa_alergia) {
    swal({
    title: 'Confirmação',
    text: "Você tem certeza que quer remover a alergia do cadastro da pessoa?",
    type: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Sim, tenho certeza!'
    }).then(function (result) {
        if (result.value) {
            $.ajax({
                type: 'POST',
                url: dir + 'ajax/atendimentos/exclui-alergia-pessoa',
                data: {"id_pessoa_alergia":id_pessoa_alergia, "_token": token},
                dataType: 'json',
                success: function (data) {
                    if(data.success) {
                        lista_alergia_pessoa($('#cd_pessoa_disabled').val());
                    }
                }
            });
        }
    });
}
