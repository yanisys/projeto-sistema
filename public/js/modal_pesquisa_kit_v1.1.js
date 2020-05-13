
$(document).on('click', '#btn-modal-pesquisa-kit', function(){
    $('#pesquisa_nm_kit').val('');
    $('#table-kit').html('<tr><td colspan="2">Utilize a busca acima para encontrar um kit.</td></tr>');
    $("#btn_pesquisar_kit").removeAttr('disabled');
    $('#modal-pesquisa-kit').modal({
        backdrop: 'static'
    })
    document.getElementById("pesquisa_nm_kit").focus();
})

$('#pesquisa_nm_kit').on('keydown', function(e) {
    if (e.which == 13) {
        pesquisa_kit();
    }
});

$('#btn_pesquisar_kit').click(function() {
    pesquisa_kit();
})

function pesquisa_kit(){
    var nm_kit = $('#pesquisa_nm_kit').val();
    var erro = '';
    if (nm_kit.length <3) {
        erro += 'Sua pesquisa deve ter no mínimo 3 caracteres!<br>';
    }
    if (erro != '') {
        Swal('Atenção!',erro,'warning')
    } else {
        $("#btn_pesquisar_kit").attr('disabled', 'disabled');
        $('#table-kit').html('<tr><td colspan="2">Pesquisando kit, por favor aguarde...</td></tr>');
        $.ajax({
            type: 'POST',
            url: dir + 'ajax/materiais/kits/pesquisa-kit',
            data: {"pesquisa": nm_kit, "_token": token},
            dataType: 'json',
            success: function (data) {
                if (data.success == true) {
                    var html = '';
                    if (data.dados.length == 0){
                        html='<tr><td colspan="2">Sem resultados para exibir....</td></tr>';
                    } else {
                        for (var x = 0; x < data.dados.length; x++) {
                            html += "<tr>" +
                                "<td>" + data.dados[x].nm_kit+"</td>" +
                                "<td><button id='selecionar-kit' data-nome='"+data.dados[x].nm_kit+ "' value="+ data.dados[x].cd_kit + " class='btn btn-primary btn-sm pull-right'><span class='fa fa-check'></span></button></td>" +
                                "</tr>";
                        }
                    }
                    $('#table-kit').html(html);
                }
                $("#btn_pesquisar_kit").removeAttr('disabled');

            }
        });
    }
}

$(document).on('click', '#selecionar-kit', function(){
    $('#nm_kit_vinculado').val($(this).attr('data-nome'));
    $('#cd_kit_vinculado_hidden').val($(this).val());
    $('#modal-pesquisa-kit').modal('hide');
});
