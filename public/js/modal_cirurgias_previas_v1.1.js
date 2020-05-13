$(document).ready(function() {
    lista_cirurgia_previa($('#cd_pessoa_disabled').val());
});

$(document).on('click', '#excluir-cirurgia-previa', function(){
    exclui_cirurgia_previa($(this).attr('data-id-pessoa-cirurgia-previa'));
});

$(document).on('click', '#btn-cadastra-cirurgia-previa', function(){
    if($('#descricao_cirurgia').val() == '')
    {
        swal({
            title: "Erro!",
            html: "Você deve preencher a descrição da cirurgia",
            type: "error",
            confirmButtonText: 'OK'
        })

    }
    else {
        var cd_pessoa = $('#cd_pessoa_disabled').val();
        $.ajax({
            type: 'POST',
            url: dir + 'ajax/atendimentos/add-cirurgia-previa',
            data: {
                "cd_pessoa": cd_pessoa,
                "descricao_cirurgia": $('#descricao_cirurgia').val(),
                "dt_cirurgia": $('#data_cirurgia').val(),
                "_token": token
            },
            dataType: 'json',
            success: function (data) {
                if (data.success == true) {
                    lista_cirurgia_previa(cd_pessoa);
                    $('#descricao_cirurgia').val("");
                    $('#data_cirurgia').val("")
                    $('#modal-cirurgias-previas').modal('hide');
                }

            }
        });
    }
});

function lista_cirurgia_previa(cdPessoa) {
    $.ajax({
        type: 'POST',
        url: dir + 'ajax/atendimentos/lista-cirurgia-previa',
        data: {"cd_pessoa": cdPessoa, "_token": token},
        dataType: 'json',
        success: function (data) {
            if (data.success) {
                var html =
                    "<div class='table-responsive tabela-acolhimento-atendimento'>" +
                    "<table class='table table-hover table-striped'>" +
                    "<tbody id='corpo-tabela-cirurgias-previas'>";
                var footer = "</tbody></table></div>";
                for (var x = 0; x < data.retorno.length; x++) {
                    html +=
                        "<tr>" +
                        "<td width='12%'>" + (data.retorno[x].dt_cirurgia == null ? 'Não informado' : convertDate(data.retorno[x].dt_cirurgia)) + "</td>" +
                        "<td>" + data.retorno[x].descricao_cirurgia + "</td>" +
                        "<td style='width: 10%'>" +
                        "<button type='button' id='excluir-cirurgia-previa' class='btn btn-danger btn-xs' data-id-pessoa-cirurgia-previa = '" + data.retorno[x].id_pessoa_cirurgia_previa + "' title='Excluir'><span class='fa fa-trash'></span></button>" +
                        "</td></tr>";
                }
                html = html + footer;
                $('.lista-cirurgias-previas').html(html);
            }
        }
    });
}

function exclui_cirurgia_previa(id_pessoa_cirurgia_previa) {
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
                url: dir + 'ajax/atendimentos/exclui-cirurgia-previa',
                data: {"id_pessoa_cirurgia_previa":id_pessoa_cirurgia_previa, "_token": token},
                dataType: 'json',
                success: function (data) {
                    if(data.success) {
                        lista_cirurgia_previa($('#cd_pessoa_disabled').val());
                    }
                }
            });
        }
    });
}