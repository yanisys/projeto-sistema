$(document).ready(function() {
    lista_medicamentos_em_uso($('#cd_pessoa_disabled').val());
});

$(document).on('click', '#excluir-medicamento-em-uso', function(){
    exclui_medicamento_em_uso($(this).attr('data-id-pessoa-medicamentos-em-uso'));
});

$(document).on('click', '#btn-cadastra-medicamento-em-uso', function(){
    if($('#descricao_medicamento_em_uso').val() == '')
    {
        swal({
            title: "Erro!",
            html: "Você deve preencher a descrição do medicamento",
            type: "error",
            confirmButtonText: 'OK'
        })

    }
    else {
        var cd_pessoa = $('#cd_pessoa_disabled').val();
        $.ajax({
            type: 'POST',
            url: dir + 'ajax/atendimentos/add-medicamento-em-uso',
            data: {
                "cd_pessoa": cd_pessoa,
                "descricao_medicamento": $('#descricao_medicamento_em_uso').val(),
                "_token": token
            },
            dataType: 'json',
            success: function (data) {
                if (data.success == true) {
                    lista_medicamentos_em_uso(cd_pessoa);
                    $('#descricao_medicamento_em_uso').val("");
                    $('#modal-medicamentos-em-uso').modal('hide');
                }

            }
        });
    }
});

function lista_medicamentos_em_uso(cdPessoa) {
    $.ajax({
        type: 'POST',
        url: dir + 'ajax/atendimentos/lista-medicamentos-em-uso',
        data: {"cd_pessoa": cdPessoa, "_token": token},
        dataType: 'json',
        success: function (data) {
            if (data.success) {
                var html =
                    "<div class='table-responsive tabela-acolhimento-atendimento'>" +
                    "<table class='table table-hover table-striped'>" +
                    "<tbody id='corpo-tabela-medicamentos-em-uso'>";
                var footer = "</tbody></table></div>";
                for (var x = 0; x < data.retorno.length; x++) {
                    html +=
                        "<tr>" +
                        "<td>" + data.retorno[x].descricao_medicamento + "</td>" +
                        "<td style='width: 10%'>" +
                        "<button type='button' id='excluir-medicamento-em-uso' class='btn btn-danger btn-xs' data-id-pessoa-medicamentos-em-uso = '" + data.retorno[x].id_pessoa_medicamentos_em_uso + "' title='Excluir'><span class='fa fa-trash'></span></button>" +
                        "</td></tr>";
                }
                html = html + footer;
                $('.lista-medicamentos-em-uso').html(html);
            }
        }
    });
}

function exclui_medicamento_em_uso(id_pessoa_medicamentos_em_uso) {
    swal({
        title: 'Confirmação',
        text: "Você tem certeza que quer remover o medicamento do cadastro de medicamentos do paciente?",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim, tenho certeza!'
    }).then(function (result) {
        if (result.value) {
            $.ajax({
                type: 'POST',
                url: dir + 'ajax/atendimentos/exclui-medicamento-em-uso',
                data: {"id_pessoa_medicamentos_em_uso":id_pessoa_medicamentos_em_uso, "_token": token},
                dataType: 'json',
                success: function (data) {
                    if(data.success) {
                        lista_medicamentos_em_uso($('#cd_pessoa_disabled').val());
                    }
                }
            });
        }
    });
}