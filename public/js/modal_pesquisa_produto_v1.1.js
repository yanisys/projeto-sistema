

$(document).on('click', '#btn-modal-pesquisa-produto', function(){
    $('#pesquisa_nm_produto').val('');
    $('#table-produto').html('<tr><td colspan="2">Utilize a busca acima para encontrar um produto.</td></tr>');
    $("#btn_pesquisar_produto").removeAttr('disabled');
    $('#modal-pesquisa-produto').modal({
        backdrop: 'static'
    })
    document.getElementById("pesquisa_nm_produto").focus();
})

$('#pesquisa_nm_produto').on('keydown', function(e) {
    if (e.which == 13) {
        pesquisa_produto();
    }
});

$('#btn_pesquisar_produto').click(function() {
    pesquisa_produto();
})

$('#qCom').on('keyup', function(e) {
    if($('#cd_produto_hidden').val() != '')
    {
        var qtde_embalagem = $('#qtde_embalagem_hidden').val();
        var tipo = $('#fracao').val();
        var qtde = $('#qCom').val();
        var fracionado = $('#fracionamento_produto_hidden').val();
        var multiplicador = 1;

        if(fracionado == 1)
            multiplicador = qtde_embalagem;
        var total = qtde * multiplicador;
        $('#total').val(total+' '+tipo);
        $('#total_hidden').val(total);
    }
});


function pesquisa_produto(){
    var nm_produto = $('#pesquisa_nm_produto').val();
    var erro = '';
  /*  if (nm_produto.length <3) {
        erro += 'Sua pesquisa deve ter no mínimo 3 caracteres!<br>';
    }*/
    if (erro != '') {
        Swal('Atenção!',erro,'warning')
    } else {
        $("#btn_pesquisar_produto").attr('disabled', 'disabled');
        $('#table-produto').html('<tr><td colspan="2">Pesquisando produto, por favor aguarde...</td></tr>');
        $.ajax({
            type: 'POST',
            url: dir + 'ajax/materiais/movimentacao/pesquisa-produto',
            data: {"pesquisa": nm_produto, "_token": token},
            dataType: 'json',
            success: function (data) {
                if (data.success == true) {
                    var html = '';
                    if (data.dados.length == 0){
                        html='<tr><td colspan="2">Sem resultados para exibir....</td></tr>';
                    } else {
                        for (var x = 0; x < data.dados.length; x++) {
                            html += "<tr>" +
                                "<td>" + data.dados[x].nm_produto+"-"+data.dados[x].ds_produto + "</td>" +
                                "<td><button data-nm_fracao='"+data.dados[x].nm_unidade_medida+"' data-nm_un='"+data.dados[x].nm_unidade_comercial+"' data-fracionamento='"+data.dados[x].fracionamento+"' data-un='"+data.dados[x].cd_unidade_comercial+"' data-qtde-embalagem='"+data.dados[x].qtde_embalagem+"' data-fracao-minima='"+data.dados[x].cd_fracao_minima+"' data-ncm='"+data.dados[x].ncm+"' id='selecionar-produto' data-nome='"+data.dados[x].nm_produto+"-"+data.dados[x].ds_produto + "' value="+ data.dados[x].cd_produto + " class='btn btn-primary btn-sm pull-right'><span class='fa fa-check'></span></button></td>" +
                                "</tr>";
                        }
                    }
                    $('#table-produto').html(html);
                }
                $("#btn_pesquisar_produto").removeAttr('disabled');

            }
        });
    }
}

$(document).on('click', '#selecionar-produto', function(){

    $('#cd_produto').val($(this).attr('data-nome'));
    $('#fracionamento_produto_hidden').val($(this).attr('data-fracionamento'));
    $('#cd_produto_hidden').val($(this).val());
    $('#qtde_embalagem_hidden').val($(this).attr('data-qtde-embalagem'));
    $('#modal-pesquisa-produto').modal('hide');
    if($('#pesquisa-simples') !== undefined){
        $('#uCom').val($(this).attr('data-nm_un'));
        $('#quantidade').val('');
        var fracionamento = $(this).attr('data-fracionamento');
        $('#label_quantidade_comercial').html('Quantidade('+$(this).attr('data-nm_un')+')');
        $('#label_preco_unitario_comercial').html('Valor unitário('+$(this).attr('data-nm_un')+')');
        if (fracionamento == 0) {
            $('#fracao').val($(this).attr('data-nm_un'));
            $('#fracao_hidden').val($(this).attr('data-un'));
            $('#label_quantidade_fracao_minima').html('Quantidade('+$(this).attr('data-nm_un')+')');
            $('#label_preco_unitario_fracao').html('Valor unitário('+$(this).attr('data-nm_un')+')');
        }
        else{
            $('#fracao').val($(this).attr('data-nm_fracao'));
            $('#fracao_hidden').val($(this).attr('data-nm_fracao'));
            $('#label_quantidade_fracao_minima').html('Quantidade('+$(this).attr('data-nm_fracao')+')');
            $('#label_preco_unitario_fracao').html('Valor unitário('+$(this).attr('data-nm_fracao')+')');
        }


    }
    else {
        $('#fracao_minima_hidden').attr('data-nm_fracao', $(this).attr('data-nm_fracao'));
        $('#uCom').val($(this).attr('data-nm_un'));
        $('#NCM').val($(this).attr('data-ncm'));
        $('#fracao_minima_hidden').val($(this).attr('data-fracao-minima'));

        if ($('#fracionamento_produto_hidden').val() === 0)
            document.getElementById('total').style.display = 'none';
        else
            document.getElementById('total').style.display = 'block';
        document.getElementById("qCom").focus();
    }
});

$(document).on('click', '#add-produto-kit', function(){
    $.ajax({
        type: 'POST',
        url: dir + 'ajax/materiais/kits/add-item',
        data: {'cd_kit':$('#cd_kit').val() ,"cd_produto": $('#cd_produto_hidden').val(), 'quantidade':$('#quantidade').val(), "_token": token},
        dataType: 'json',
        success: function (data) {
            if (data.success == true) {
                location.reload();
            }
        }
    });
})

$(document).on('click', '#add-produto-requisicao', function(){
    $.ajax({
        type: 'POST',
        url: dir + 'ajax/materiais/requisicoes/add-item',
        data: {'cd_requisicao':$('#cd_requisicao').val() ,"cd_produto": $('#cd_produto_hidden').val(), 'quantidade':$('#quantidade').val(), "_token": token},
        dataType: 'json',
        success: function (data) {
            if (data.success == true) {
                location.reload();
            }
        }
    });
})