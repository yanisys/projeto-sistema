$('#btn-modal-pesquisa-cfop').click(function(){
    alert
    $('#pesquisa_nm_cfop').val('');
    $('#table-cfop').html('<tr><td colspan="2">Utilize a busca acima para encontrar um Cfop.</td></tr>');
    $("#btn-pesquisa-cfop").removeAttr('disabled');

    $('#modal-pesquisa-cfop').modal({
        backdrop: 'static'
    })
    document.getElementById("pesquisa_nm_cfop").focus();
})

$('#pesquisa_nm_cfop').on('keydown', function(e) {
    if (e.which == 13) {
        pesquisar_cfop();
    }
});

$('#btn-pesquisa-cfop').click(function() {
    pesquisar_cfop();
})

$('#limpa-cfop').click(function(){
    $('#cd_cfop').val('');
    $('#cd_cfop_hidden').val(0);
})

function pesquisar_cfop(){
    var nm_cfop = $('#pesquisa_nm_cfop').val();
    var erro = '';
    if (nm_cfop.length <3) {
        erro += 'Sua pesquisa deve ter no mínimo 3 caracteres!<br>';
    }
    if (erro != '') {
        Swal('Atenção!',erro,'warning')
    } else {
        $("#btn-pesquisar-cfop").attr('disabled', 'disabled');
        $('#table-cfop').html('<tr><td colspan="2">Pesquisando cfop, por favor aguarde...</td></tr>');
        $.ajax({
            type: 'POST',
            url: dir + 'ajax/materiais/movimento/pesquisa-cfop',
            data: {"pesquisa": nm_cfop, "_token": token},
            dataType: 'json',
            success: function (data) {
                if (data.success == true) {
                    var html = '';
                    if (data.dados.length == 0){
                        html='<tr><td colspan="2">Sem resultados para exibir....</td></tr>';
                    } else {
                        for (var x = 0; x < data.dados.length; x++) {
                            html += "<tr>" +
                                "<td>"+data.dados[x].cd_cfop+" - "+data.dados[x].ds_cfop + "</td>" +
                                "<td><button data-nome='"+data.dados[x].ds_cfop + "' value="+ data.dados[x].cd_cfop + " class='btn btn-primary btn-sm pull-right selecionar-cfop'><span class='fa fa-check'></span></button></td>" +
                                "</tr>";
                        }
                    }
                    $('#table-cfop').html(html);
                }
                $("#btn-pesquisar-cfop").removeAttr('disabled');
            }
        });
    }
}

$(document).on('click', '.selecionar-cfop', function(){
    $('#cd_cfop_hidden').val($(this).val());
    $('#cd_cfop').val($(this).val()+" - "+$(this).attr('data-nome'));
    $('#modal-pesquisa-cfop').modal('hide');
});
