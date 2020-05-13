$('.verifica-estoque').on('change', function(e) {
    preenche_valores_lote($(this));
});

function preenche_valores_lote(valores) {
    var cd_linha = valores.attr('data-key');
    var estoque = JSON.parse($('#quantidade-'+ cd_linha).attr('data-estoque'));
    var produto = estoque[valores.val()];
    var qtde_solicitada = parseFloat($('#quantidade-'+ cd_linha).attr('data-qtde-solicitada'));

    if(produto != undefined) {
        $('#validade-' + cd_linha).val(produto.dt_validade);
        $('#dt_fabricacao-' + cd_linha).val(produto.dt_fabricacao);
        $('#cd_fornecedor-' + cd_linha).val(produto.cd_fornecedor);
        $('#quantidade-' + cd_linha).attr("max", produto.quantidade);
        if (parseFloat(produto.quantidade) >= qtde_solicitada) {
            $('#quantidade-' + cd_linha).val(qtde_solicitada);
            var proxima_linha = parseInt(cd_linha) + 1;
            $('#cod_barras-' + proxima_linha).focus();
        }
        else {
            $('#quantidade-' + cd_linha).val(produto.quantidade);
        }
    }
    else{
        $('#validade-' + cd_linha).val('');
        $('#quantidade-' + cd_linha).val('');
        $('#dt_fabricacao-' + cd_linha).val('');
        $('#cd_fornecedor-' + cd_linha).val('');
    }
}

