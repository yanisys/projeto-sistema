
$('#btn-modal-cid').click(function(){

    $('#pesquisa_nm_cid').val('');
    $('#table-cid').html('<tr><td colspan="2">Utilize a busca acima para encontrar CIDs.</td></tr>');
    $("#btn-pesquisar-cid").removeAttr('disabled');

    $('#modal-cid').modal({
        backdrop: 'static'
    })
    document.getElementById("pesquisa_nm_cid").focus();

})

$('#pesquisa_nm_cid').on('keydown', function(e) {
    if (e.which == 13) {
        pesquisar_cid();
    }
});

$('#btn-pesquisar-cid').click(function() {
    pesquisar_cid();
})

function pesquisar_cid(){
    var nm_cid = $('#pesquisa_nm_cid').val();
    var erro = '';

    if (nm_cid.length <3) {
        erro += 'Sua pesquisa deve ter no mínimo 3 caracteres!<br>';
    }
    if (erro != '') {
        Swal('Atenção!',erro,'warning')
    } else {
        $("#btn-pesquisar-cid").attr('disabled', 'disabled');
        $('#table-cid').html('<tr><td colspan="2">Pesquisando CID, por favor aguarde...</td></tr>');
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
                                    "<td><button id='selecionar-cid' data-nome='"+data.dados[x].nm_cid + "' value="+ data.dados[x].id_cid + " class='btn btn-primary btn-sm pull-right'><span class='fa fa-check'></span></button></td>" +
                                    "</tr>";
                        }
                    }
                    $('#table-cid').html(html);
                }
                $("#btn-pesquisar-cid").removeAttr('disabled');

            }
        });
    }
}

$(document).on('click', '#selecionar-cid', function(){
    $('#id_cid').val($(this).attr('data-nome'));
    $('#id_cid').attr('data-id',$(this).val());
    $('#modal-cid').modal('hide');
});
