$('#btn-modal-pesquisa-user').click(function(){
    alert
    $('#pesquisa_nm_user').val('');
    $('#table-user').html('<tr><td colspan="2">Utilize a busca acima para encontrar um profissional.</td></tr>');
    $("#btn-pesquisa-user").removeAttr('disabled');

    $('#modal-pesquisa-user').modal({
        backdrop: 'static'
    })
    document.getElementById("pesquisa_nm_user").focus();
})

$('#pesquisa_nm_user').on('keydown', function(e) {
    if (e.which == 13) {
        pesquisar_user();
    }
});

$('#btn-pesquisa-user').click(function() {
    pesquisar_user();
})

function pesquisar_user(){
    var nm_user = $('#pesquisa_nm_user').val();
    var erro = '';
    if (nm_user.length <3) {
        erro += 'Sua pesquisa deve ter no mínimo 3 caracteres!<br>';
    }
    if (erro != '') {
        Swal('Atenção!',erro,'warning')
    } else {
        $("#btn-pesquisar-user").attr('disabled', 'disabled');
        $('#table-user').html('<tr><td colspan="2">Pesquisando profissional, por favor aguarde...</td></tr>');
        $.ajax({
            type: 'POST',
            url: dir + 'ajax/sala/pesquisa-user',
            data: {"pesquisa": nm_user, "_token": token},
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
                                "<td><button data-nome='"+data.dados[x].nm_pessoa + "' value="+ data.dados[x].id + " class='btn btn-primary btn-sm pull-right selecionar-user'><span class='fa fa-check'></span></button></td>" +
                                "</tr>";
                        }
                    }
                    $('#table-user').html(html);
                }
                $("#btn-pesquisar-user").removeAttr('disabled');
            }
        });
    }
}

$(document).on('click', '.selecionar-user', function(){
    $('#cd_user_hidden').val($(this).val());
    $('#cd_user').val($(this).attr('data-nome'));
    $('#modal-pesquisa-user').modal('hide');
});
