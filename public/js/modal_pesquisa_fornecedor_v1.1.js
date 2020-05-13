$('#btn-modal-pesquisa-fornecedor').click(function(){
    $('#pesquisa_nm_fornecedor').val('');
    $('#table-fornecedor').html('<tr><td colspan="2">Utilize a busca acima para encontrar um fornecedor.</td></tr>');
    $("#btn-pesquisa-fornecedor").removeAttr('disabled');

    $('#modal-pesquisa-fornecedor').modal({
        backdrop: 'static'
    })
    document.getElementById("pesquisa_nm_fornecedor").focus();
})

$('#pesquisa_nm_fornecedor').on('keydown', function(e) {
    if (e.which == 13) {
        pesquisar_fornecedor();
    }
});

$('#btn-pesquisa-fornecedor').click(function() {
    pesquisar_fornecedor();
})

function pesquisar_fornecedor(){
    var nm_fornecedor = $('#pesquisa_nm_fornecedor').val();
    var erro = '';
  /*  if (nm_fornecedor.length <3) {
        erro += 'Sua pesquisa deve ter no mínimo 3 caracteres!<br>';
    }*/
    if (erro != '') {
        Swal('Atenção!',erro,'warning')
    } else {
        $("#btn-pesquisar-fornecedor").attr('disabled', 'disabled');
        $('#table-fornecedor').html('<tr><td colspan="2">Pesquisando fornecedor, por favor aguarde...</td></tr>');
        $.ajax({
            type: 'POST',
            url: dir + 'ajax/materiais/movimentacao/pesquisa-fornecedor',
            data: {"pesquisa": nm_fornecedor, "_token": token},
            dataType: 'json',
            success: function (data) {
                if (data.success == true) {
                    var html = '';
                    if (data.dados.length == 0){
                        html='<tr><td colspan="2">Sem resultados para exibir....</td></tr>';
                    } else {
                        for (var x = 0; x < data.dados.length; x++) {
                            html += "<tr>" +
                                "<td>"+data.dados[x].nm_pessoa + "</td>" +
                                "<td><button data-nome='"+data.dados[x].nm_pessoa + "' value="+ data.dados[x].cd_fornecedor + " class='btn btn-primary btn-sm pull-right selecionar-fornecedor'><span class='fa fa-check'></span></button></td>" +
                                "</tr>";
                        }
                    }
                    $('#table-fornecedor').html(html);
                }
                $("#btn-pesquisar-fornecedor").removeAttr('disabled');
            }
        });
    }
}

$(document).on('click', '.selecionar-fornecedor', function(){
    $('#cd_emitente_destinatario_hidden').val($(this).val());
    $('#cd_fornecedor').val($(this).val());
    $('#nm_pessoa').val($(this).attr('data-nome'));
    $('#cd_emitente_destinatario').val($(this).attr('data-nome'));
    $('#modal-pesquisa-fornecedor').modal('hide');
    $('#cd_emitente_destinatario').removeClass('is-invalid');
});
