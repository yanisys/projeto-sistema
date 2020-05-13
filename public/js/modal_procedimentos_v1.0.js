$(document).on('click', '#abre-modal-add-procedimentos', function() {
    $('#pesquisa_nm_procedimento').val('');
    $('#table-procedimentos').html('<tr><td colspan="2">Utilize a busca acima para encontrar procedimentos.</td></tr>');
    $('#modal_obs_procedimento').val('');
    $("#btn-pesquisar-procedimento").removeAttr('disabled');

    $('#modal-procedimentos').modal({
        backdrop: 'static'
    });
    document.getElementById("pesquisa_nm_procedimento").focus();
})

$('#pesquisa_nm_procedimento').on('keydown', function(e) {
    if (e.which == 13) {
        pesquisar_procedimento();
    }
});

$(document).on('click', '.selecionar-procedimento', function(){
    cd_procedimento = $(this).val();
    if (cd_procedimento != '') {
        $.ajax({
            type: 'POST',
            url: dir + 'ajax/atendimentos/add-atendimento-procedimento',
            data: {
                "cd_prontuario": $('#cd_prontuario').val(),
                "descricao_solicitacao": $('#modal_obs_procedimento').val(),
                'cd_procedimento': cd_procedimento,
                "_token": token
            },
            dataType: 'json',
            success: function (data) {
                if (data.success == true) {
                    lista_atendimento_procedimento($('#cd_prontuario').val());
                    $('#modal-procedimentos').modal('hide');
                } else {
                    Swal('Atenção!',data.mensagem,'warning');
                }
            }
        });
    }
})

$('#btn-pesquisar-procedimento').click(function(){
    pesquisar_procedimento();
})

function pesquisar_procedimento(){
    var nm_procedimento = $('#pesquisa_nm_procedimento').val();
    var erro = '';

    if (nm_procedimento.length < 3) {
        erro += 'Sua pesquisa deve conter no mínimo 3 caracteres!<br>';
    }
    if (erro != '') {
        Swal('Atenção!',erro,'warning')
    } else {
        $("#btn-pesquisar-procedimento").attr('disabled', 'disabled');
        $('#table-procedimentos').html('<tr><td colspan="2">Pesquisando procedimentos, por favor aguarde...</td></tr>');

        $.ajax({
            type: 'POST',
            url: dir + 'ajax/atendimentos/pesquisa-procedimento',
            data: {"pesquisa": nm_procedimento, "_token": token},
            dataType: 'json',
            success: function (data) {
                var html = '';
                if (data.success) {
                    for (var x = 0; x < data.retorno.length; x++) {
                        html += "<tr>" +
                            "<td>" + data.retorno[x].cd_procedimento + " - " + data.retorno[x].nm_procedimento + "</td>" +
                            "<td><button type='button' value=" + data.retorno[x].cd_procedimento + " class='btn btn-primary btn-sm pull-right selecionar-procedimento'><span class='fa fa-check'></span></button></td>" +
                            "</tr>";
                    }
                    $('#table-procedimentos').html(html);
                }
                $("#btn-pesquisar-procedimento").removeAttr('disabled');
            }
        });
    }
}
