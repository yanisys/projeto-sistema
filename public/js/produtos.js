var temp = Array();
var progresso = 0;
var soma_progresso = 0;
var auto = false;

$(document).ready(function() {
    if($('#cd_movimento').val() != undefined && $('#cd_movimento').val() > 0)
        preenche_movimento($('#cd_movimento').val());

    var valor = parseInt($('#pesquisar_por').val());
    if(valor == 0)
        mostra_estoque($('#cd_sala_origem').val(),$('#nm_produto_origem').val());
    else
        pesquisa_prontuario($('#nm_produto_origem').val());

    if($('#medicamento').val() != undefined)
        configura_div_medicamento();

    if($('#tab_divisao').val() > 0)
        minimiza_maximiza_tabela('tr_dv','btn-zoom-divisao',$('#tab_divisao').val(),'btn_tr_dv_'+$('#tab_divisao').val());
    if($('#tab_grupo').val() > 0 )
        minimiza_maximiza_tabela('tr_gr','btn-zoom-grupo',$('#tab_grupo').val(),'btn_tr_gr_'+$('#tab_grupo').val());
});

$('.dinheiro').mask('###0,00', {reverse: true});

$('#preco_unitario_comercial').on('keyup', function(e) {
    var qtde_embalagem = $('#qtde_embalagem_hidden').val();
    if($('#fracionamento_produto_hidden').val() == 0){
        console.log('Zero');
        $('#preco_total').val(($('#preco_unitario_comercial').val() * $('#qCom').val()).toFixed(2));
        $('#preco_unitario_fracao').val(($(this).val()).toFixed(4));
    }
    else{

        $('#preco_total').val(($('#preco_unitario_comercial').val() * $('#qCom').val()).toFixed(2));
        $('#preco_unitario_fracao').val(($(this).val() / qtde_embalagem).toFixed(4));

    }
});

$('#abre-modal-item-movimentacao').click(function(){
    $("#painel-movimentacao-itens input").val("");
    $('#add-movimentacao-itens').val(0);
    var display = 'none';
    $("#total").prop("disabled", false);
    if($('#tp_movimento_hidden').val() == 'C' || $('#tp_movimento_hidden').val() == 'V') {
        $('#total').prop('disabled', true);
        display = 'block';
    }
    var elementos = document.getElementsByClassName('compra_venda');
    for(var x=0; x<elementos.length; x++)
        elementos[x].style.display = display;
})

$('#add-movimentacao-itens').click(function(){
    cadastra_item_movimentacao($('#cd_movimentacao').val(),$('#cd_produto_hidden').val(),$('#lote').val(),$('#dt_validade').val(),$('#total_hidden').val(),$('#cd_sala').val(),$('#tp_saldo_hidden').val(),'',$('#dt_fabricacao').val(),$('#preco_unitario_fracao').val(),$(this).val());
})

$('#btn-pesquisa-produto-nfe').click(function(){
    var entrada = $('#pesquisa-produto-nfe').val();
    if(entrada.length < 3){
        error_alert("Sua pesquisa deve conter ao menos três caracteres");
    }
    else{
        var itens = JSON.parse($('#btn-pesquisa-produto-nfe').attr('data-itens'));
        var indice = $('#btn-pesquisa-produto-nfe').attr('data-indice');
        verifica_cadastro_do_produto(itens,indice,$(this).val());
    }
});

$('#pesquisa-produto-nfe').on('keydown', function(e) {
    if (e.which == 13) {
        var entrada = $('#pesquisa-produto-nfe').val();
        if(entrada.length < 3){
            error_alert("Sua pesquisa deve conter ao menos três caracteres");
        }
        else{
            var itens = JSON.parse($('#btn-pesquisa-produto-nfe').attr('data-itens'));
            var indice = $('#btn-pesquisa-produto-nfe').attr('data-indice');
            verifica_cadastro_do_produto(itens,indice,$(this).val());
        }
    }
});

$('.btn-preco-produto-plano').click(function(){
    $('#preco_venda_produto_plano').val($(this).attr('data-preco').replace('.',','));
    $('#codigo_produto_plano').val($(this).attr('data-cd-produto-plano'));
    $('#status_produto_plano').val($(this).attr('data-status'));
    $('#btn-salvar-preco-produto-plano').val($(this).attr('data-plano'));
    $('#btn-salvar-preco-produto-plano').attr('data-linha',$(this).attr('data-linha'));
})

$('#btn-salvar-preco-produto-plano').click(function(){
    if($('#cd_produto_hidden').val() != undefined) {
        var plano = $('#btn-salvar-preco-produto-plano').val();
        var preco = $('#preco_venda_produto_plano').val().replace(",", ".");
        var codigo = $('#codigo_produto_plano').val();
        var linha = $(this).attr('data-linha');
        var status = $('#status_produto_plano').val();
        $('#modal-precos-produtos').modal('hide');
        $.ajax({
            type: 'POST',
            url: dir + 'ajax/edita-preco-produto',
            data: {
                "cd_plano": plano,
                "cd_produto_plano": codigo,
                'cd_sub_grupo': 1,
                "cd_produto": $('#cd_produto_hidden').val(),
                "preco": preco,
                "status": status,
                "_token": token
            },
            dataType: 'json',
            success: function (data) {
                if (data.success == true) {
                    if (status == 'A')
                        document.getElementById('linha-' + linha).style.color = 'black';
                    else
                        document.getElementById('linha-' + linha).style.color = 'red';
                    $('#preco-' + linha).html(preco.replace('.', ','));
                    $('#cd-produto-plano-' + linha).html(codigo);
                    $('#btn-' + linha).attr('data-preco', preco.replace('.', ','));
                    $('#btn-' + linha).attr('data-cd-produto-plano', codigo);
                    $('#status-' + linha).html((status == 'A' ? 'Ativo' : 'Inativo'));
                    $('#btn-' + linha).attr('data-status', status);
                }
            }
        });
    }
})

$('.btn-modal-grupo-produto').click(function(){
    $('#titulo-modal-grupo-produto').html($(this).attr('data-nome-mestre')+'&#10132;'+ 'Cadastrar '+$(this).attr('data-titulo'));
    $('#btn-salvar-grupo-produto').attr('data-nome',$(this).attr('data-nome'));
    if($(this).attr('data-superior') != undefined) {
        $('#btn-salvar-grupo-produto').attr('data-superior', $(this).attr('data-superior'));
        $('#btn-salvar-grupo-produto').attr('data-mestre', $(this).attr('data-mestre'));
    }
    else{
        $('#nome-cadastro-grupo').val($(this).attr('data-valor'));
        $('#btn-salvar-grupo-produto').attr('data-id', $(this).attr('data-id'));
    }
})

$('#btn-salvar-grupo-produto').click(function(){
    var mestre = $(this).attr('data-mestre');
    var superior = $(this).attr('data-superior');
    var id = $(this).attr('data-id');
    $('#modal-grupo-produto').modal('hide');
    $.ajax({
        type: 'POST',
        url: dir + 'ajax/salvar-grupo-produto',
        data: {'id':id, 'cd_mestre':mestre, 'superior':superior, 'tabela':$(this).attr('data-nome').replace('-','_'), "nome": $('#nome-cadastro-grupo').val(), "_token": token},
        dataType: 'json',
        success: function (data) {
            if(data.success == true){
                location.reload();
            }
        }
    });
})

$('.btn-zoom-divisao').click(function(){
    minimiza_maximiza_tabela('tr_dv','btn-zoom-divisao',$(this).attr('data-valor'),$(this).attr('id'));
    $('#tab_divisao').val($(this).attr('data-valor'));
})

$('.btn-zoom-grupo').click(function(){
    minimiza_maximiza_tabela('tr_gr','btn-zoom-grupo',$(this).attr('data-valor'),$(this).attr('id'));
    $('#tab_grupo').val($(this).attr('data-valor'));
})

$(document).on('click', '.transfere-produto', function(evt) {
    var estoque = parseInt($(this).attr('data-quantidade'));
    var qtde = parseInt($('#input_' + $(this).attr('data-nr')).val());
    if (estoque >= qtde) {
        if ($('#input_' + $(this).attr('data-nr')).val() != '' && $('#input_' + $(this).attr('data-nr')).val() > 0) {
            var move = Move_produto($(this).attr('data-nome'), $(this).attr('data-produto'), $(this).attr('data-lote'), $('#input_' + $(this).attr('data-nr')).val(),$(this).attr('data-fornecedor'), $(this).attr('data-fabricacao'), $(this).attr('data-validade'));
            $('#input_' + $(this).attr('data-nr')).val('');
            if (temp.length > 0) {
                for (var x = 0; x < temp.length; x++) {
                    if (move.cd_produto == temp[x].cd_produto && move.lote == temp[x].lote) {
                        var existe = x;
                    }
                }
            }
            if (existe == undefined)
                temp.push(move);
            else
                temp[existe] = move;
            preenche_tabela_transferidos(temp);
        }else{
            error_alert("Por favor, informe a quantidade.");
        }
    }else{
        error_alert("Quantidade não disponível no estoque.");
    }
});

$(document).on('click', '#finalizar-transferencia-produtos', function(evt) {
    var erro = 0;
    if (temp.length == 0) {
        error_alert("Escolha ao menos um produto para transferir!");
        erro++;
    }
    if ($('#cd_sala_origem').val() === $('#cd_sala_destino').val()) {
        error_alert("A origem deve ser diferente do destino!");
        erro++;
    }
    if (parseInt($('#cd_user_hidden').val()) == 0) {
        error_alert("Escolha um profissional.");
        erro++
    }
    if (erro == 0) {
        $.ajax({
            type: 'POST',
            url: dir + 'ajax/materiais/movimentacao/finalizar-transferencia-produtos',
            data: {
                "origem": $('#cd_sala_origem').val(),
                'nr_doc': $('#nr_doc_hidden').val(),
                "destino": $('#cd_sala_destino').val(),
                "id_user": $('#cd_user_hidden').val(),
                "produtos": temp,
                "_token": token
            },
            dataType: 'json',
            success: function (data) {
                if (data.success == true) {
                    swal({
                        type: 'success',
                        title: 'Concluído!',
                        text: 'Transferência realizada com sucesso!!',
                        showConfirmButton: false,
                        timer: 1500,
                        onClose: function () {
                            location.reload();
                            $('#cd_user').val('');
                            $('#cd_user_hidden').val(0);
                        }
                    })
                }
                else {
                    error_alert(data.mensagem);
                }
            }
        });
    }
});

$(document).on('click', '.remove-transfere-produto', function(evt) {
    temp.splice($(this).val(),1);
    preenche_tabela_transferidos(temp);
    if(temp.length == 0){
        $('#cd_sala_origem').removeAttr('disabled');
        $('#pesquisar_por').removeAttr('disabled');
    }
});

$(document).on('click', '.seleciona-prontuario', function(evt) {
    mostra_prescricao($('#cd_sala_origem').val(), $(this).val());
});

$(document).on('click', '.detalhes-item-movimentacao', function(evt) {
    $.ajax({
        type: 'POST',
        url: dir + 'ajax/materiais/movimentacao/detalhes-movimentacao-item',
        data: {
            "cd_movimentacao_itens": $(this).val(),
            "_token": token
        },
        dataType: 'json',
        success: function(data) {
            var item = data.retorno;
            var tipo = (item.fracionamento === 0) ? item.nm_unidade_comercial : item.nm_fracao_minima;
            var display = 'none';
            if($('#tp_movimento_hidden').val() == 'C' || $('#tp_movimento_hidden').val() == 'V')
                display = 'block';
            var elementos = document.getElementsByClassName('compra_venda');
            for(var x=0; x<elementos.length; x++)
                elementos[x].style.display = display;
            $('#cd_produto').val(item.nm_produto+" "+item.ds_produto);
            $('#cd_produto_hidden').val(item.cd_produto);
            $('#qtde_embalagem_hidden').val(item.qtde_embalagem);
            $('#qCom').val(item.qCom * item.proporcao);
            $('#uCom').val(item.nm_unidade_comercial);
            $('#total').val(item.quantidade + " "+ tipo);
            $('#total_hidden').val(item.quantidade);
            $('#dt_fabricacao').val(item.dt_fabricacao);
            $('#dt_validade').val(item.dt_validade);
            $('#lote').val(item.lote);
            $('#preco_unitario_comercial').val(item.vUnCom);
            $('#preco_unitario_fracao').val(item.valor_unitario);
            $('#preco_total').val(item.vProd);
            $('#fracionamento_produto_hidden').val(item.fracionamento);
            $('#cd_produto_fornecedor').val(item.cd_produto_fornecedor)
            $('#NCM').val(item.NCM);
            $('#CFOP').val(item.CFOP);
            $('#icms_vICMS').val(item.icms_vICMS);
            $('#ipi_vIPI').val(item.ipi_vIPI);
            $('#add-movimentacao-itens').val(item.cd_movimentacao_itens);
            $('#label_quantidade_comercial').html('Quantidade('+item.nm_unidade_comercial+')');
            $('#label_preco_unitario_comercial').html('Valor unitário('+item.nm_unidade_comercial+')');

            if (item.fracionamento === 0) {
                $('#fracao').val(item.nm_unidade_comercial);
                $('#fracao_hidden').val(item.nm_unidade_comercial);
                $('#label_quantidade_fracao_minima').html('Quantidade('+item.nm_unidade_comercial+')');
                $('#label_preco_unitario_fracao').html('Valor unitário('+item.nm_unidade_comercial+')');
            }
            else{
                $('#fracao').val(item.nm_fracao_minima);
                $('#fracao_hidden').val(item.nm_fracao_minima);
                $('#label_quantidade_fracao_minima').html('Quantidade('+item.nm_fracao_minima+')');
                $('#label_preco_unitario_fracao').html('Valor unitário('+item.nm_fracao_minima+')');
            }

            $('#modal-add-item-movimentacao').modal('show');
        },
        error: function() {
            console.log('Erro ao buscar a o item da movimentação');
        }
    });
});

$('#buscar_produto_origem').on('click', function(e) {
    var origem = parseInt($('#pesquisar_por').val());
    if(origem === 0)
        mostra_estoque($('#cd_sala_origem').val(),$('#nm_produto_origem').val());
    else
        pesquisa_prontuario($('#nm_produto_origem').val());
});

$('#cd_produto_divisao').change(function(){
    $('#cd_produto_grupo').val(0);
    $('#cd_produto_sub_grupo').val(0);
    preenche_select_grupo($(this).val());
});

$('#cd_movimento').change(function(){
    preenche_movimento($('#cd_movimento').val());
});

$('#medicamento').change(function(){
    configura_div_medicamento();
});

function configura_div_medicamento(){
    var display = document.getElementsByClassName('div-medicamento');
    if($('#medicamento').val() == 0){
        for(var x=0;x<display.length;x++)
            display[x].style.display = 'none';
    }
    else {
        for (var x = 0; x < display.length; x++)
            display[x].style.display = 'block';
    }
}

$('#pesquisar_por').change(function(){
    var origem = parseInt($(this).val());
    if(origem === 0) {
        mostra_estoque($('#cd_sala_origem').val(), $('#nm_produto_origem').val());
    }
    else {
        pesquisa_prontuario($('#nm_produto_origem').val());
    }
});

$('#cd_sala_origem').change(function(){
    var origem = parseInt($('#pesquisar_por').val());
    if(origem === 0) {
        mostra_estoque($('#cd_sala_origem').val(), $('#nm_produto_origem').val());
    }
    else {
        pesquisa_prontuario($('#nm_produto_origem').val());
    }
});

$('#cd_produto_grupo').change(function(){
    $('#cd_produto_sub_grupo').val(0);
    preenche_select_sub_grupo($(this).val());
});

$('#ind_forma_pagamento').change(function(){
    if($(this).val() == 1)
        document.getElementById('div-parcelamento').style.display = 'block';
    else
        document.getElementById('div-parcelamento').style.display = 'none';
});

$('#nm_produto_origem').on('keydown', function(e) {
    if (e.which == 13) {
        var origem = parseInt($('#pesquisar_por').val());
        if(origem === 0)
            mostra_estoque($('#cd_sala_origem').val(),$('#nm_produto_origem').val());
        else
            pesquisa_prontuario($('#nm_produto_origem').val());
    }
});

function preenche_tabela_transferidos(temp){
    if(temp.length > 0){
        $('#cd_sala_origem').attr('disabled','true');
        $('#pesquisar_por').attr('disabled','true');
    }
    var html = "";
    for(var x=0;x<temp.length;x++) {
        if(temp[x].quantidade > 0) {
            html += "<tr>";
            html += "<td>";
            html += "<div class='input-group'>";
            html += "<button value=" + x + " type='button' class='btn btn-danger btn-sm remove-transfere-produto'><span class='fas fa-arrow-alt-circle-left'></span></button>";
            html += "</td>";
            html += "<td>" + temp[x].nm_produto + "</td>";
            html += "<td>" + temp[x].lote + "</td>";
            html += "<td>" + temp[x].quantidade + "</td>";
            html += "</tr>";
        }
    }
    $('#tabela-mostra-movimentos').html(html);
}

function minimiza_maximiza_tabela(linha, botao, valor,id){
    var status = $('#'+linha+'_'+valor).css('display');
    $.each( $( "."+linha ), function() {
        $('#'+this.id).css('display', 'none');
    });
    $.each( $( "."+botao ), function() {
        $('#'+this.id).html("<span class='fas fa-plus-square fa-xs'></span>");
    });
    if (status == 'none') {
        $('#' + linha + '_' + valor).css('display', 'block');
        $('#' + id).html("<span class='fas fa-minus-square fa-xs'></span>");
        $('#' + id).attr('title', 'Minimizar');
    }
    else {
        $('#' + linha + '_' + valor).css('display', 'none');
        $('#' + id).html("<span class='fas fa-plus-square fa-xs'></span>");
        $('#' + id).attr('title', 'Expandir');
    }

}

function preenche_movimento(cd_movimento){
    $.ajax({
        type: 'POST',
        url: dir + 'ajax/preenche-parametros-movimento',
        data: {"cd_movimento":cd_movimento, "_token": token},
        dataType: 'json',
        success: function (data) {
            if (data.success) {
                $('#tp_movimento').val(data.retorno.ds_movimento);
                $('#tp_movimento_hidden').val(data.retorno.tp_movimento);
                $('#tp_nf').val(data.retorno.ds_nf);
                $('#nr_documento').text(data.retorno.nr_documento);
                $('#tp_nf_hidden').val(data.retorno.tp_nf);
                $('#tp_conta').val(data.retorno.ds_conta);
                $('#tp_conta_hidden').val(data.retorno.tp_conta);
                $('#tp_saldo').val(data.retorno.ds_saldo);
                $('#tp_saldo_hidden').val(data.retorno.tp_saldo);
                $('#cd_cfop').val((data.retorno.cd_cfop !== null) ? data.retorno.cd_cfop+" - "+data.retorno.ds_cfop : '');
                $('#cd_cfop').attr('title',data.retorno.cd_cfop+" - "+data.retorno.ds_cfop);
                $('#cd_cfop_hidden').val(data.retorno.cd_cfop);
            }
            if(data.retorno. tp_movimento == 'C'){
                $('#localizacao_movimento').html('Destino');
            }
            else
            {
                $('#localizacao_movimento').html('Origem');
            }
        }

    });
}

function preenche_select_grupo(cdDivisao) {
    $.ajax({
        type: 'POST',
        url: dir + 'ajax/preenche-select-produto-grupo',
        data: {"cd_divisao":cdDivisao, "_token": token},
        dataType: 'json',
        success: function (data) {
            if (data.success) {
                var data = data.retorno;
                var selectbox = $('#cd_produto_grupo');
                selectbox.find('option').remove();
                $('<option>').val(0).text("Nenhum item selecionado").appendTo(selectbox);
                for(var x=0;x<data.length;x++) {
                    $('<option>').val(data[x].cd_produto_grupo).text(data[x].nm_produto_grupo).appendTo(selectbox);
                }
            }
        }
    });
}

function preenche_select_sub_grupo(cdGrupo) {
    $.ajax({
        type: 'POST',
        url: dir + 'ajax/preenche-select-produto-sub-grupo',
        data: {"cd_grupo":cdGrupo, "_token": token},
        dataType: 'json',
        success: function (data) {
            if (data.success) {
                var data = data.retorno;
                var selectbox = $('#cd_produto_sub_grupo');
                selectbox.find('option').remove();
                $('<option>').val(0).text("Nenhum item selecionado").appendTo(selectbox);
                for(var x=0;x<data.length;x++) {
                    $('<option>').val(data[x].cd_produto_sub_grupo).text(data[x].nm_produto_sub_grupo).appendTo(selectbox);
                }

            }
        }
    });
}

function mostra_estoque(cdSala, nmProduto) {
    $('#nr_doc_hidden').val(0);
    $('#nm_produto_origem').attr('placeholder','Informe o nome ou o cod. barras');
    $.ajax({
        type: 'POST',
        url: dir + 'ajax/materiais/movimentacao/mostra-estoque',
        data: {
            'cd_sala': cdSala,
            "nome": nmProduto,
            "_token": token
        },
        dataType: 'json',
        success: function (data) {
            if (data.success == true) {
                var html =
                    "<table class='table table-hover table-striped table-responsive-sm'>"+
                        "<thead>"+
                            "<tr>"+
                                "<th>Produto</th>"+
                                "<th>Lote</th>"+
                                "<th>Validade</th>"+
                                "<th>Estoque</th>"+
                                "<th width='150px'>Mover</th>"+
                            "</tr>"+
                        "</thead>"+
                        "<tbody>";
                for(var x=0;x<data.retorno.length;x++) {
                    if(data.retorno[x].quantidade != 0) {
                        html += "<tr>"+
                                    "<td>" + data.retorno[x].nm_produto + " - " + data.retorno[x].ds_produto + "</td>"+
                                    "<td>" + data.retorno[x].lote + "</td>"+
                                    "<td>" + convertDate(data.retorno[x].dt_validade) + "</td>"+
                                    "<td>" + data.retorno[x].quantidade + "</td>"+
                                    "<td class='text-center'>"+
                                    "<div class='input-group'>"+
                                        "<input id='input_"+x+"' type='text' class='form-control input-sm' pattern=\'[0-9]+$\' max="+data.retorno[x].quantidade+ " placeholder='Quantidade'>"+
                                        "<span class='input-group-btn'>"+
                                            "<button data-nr="+x+" data-fabricacao='"+data.retorno[x].dt_fabricacao+"' data-validade='"+data.retorno[x].dt_validade+"' data-fornecedor='"+data.retorno[x].cd_fornecedor+"' data-nome='"+data.retorno[x].nm_produto + " - " + data.retorno[x].ds_produto + "' data-produto='"+data.retorno[x].cd_produto+"' data-quantidade="+data.retorno[x].quantidade+" data-lote='"+data.retorno[x].lote+"' type='button' class='btn btn-primary btn-sm transfere-produto'><span class='fas fa-arrow-alt-circle-right'></span></button>"+
                                        "</span>"+
                                    "</div>"+
                                    "</td>"+
                                "</tr>";
                    }
                }
                html += "</tbody>" +
"                    </table>";
                $('#tabela-pesquisa-estoque').html(html);
            }
        }
    });
}

function Move_produto(nm_produto, cd_produto, lote, quantidade, cd_fornecedor, dt_fabricacao, dt_validade) {
    var json = {nm_produto: nm_produto, cd_produto: cd_produto, lote: lote, quantidade: quantidade, cd_fornecedor: cd_fornecedor, dt_fabricacao: dt_fabricacao, dt_validade: dt_validade};
    return json;
}

function mostra_prescricao(cdSala, cdProntuario) {
    $('#nr_doc_hidden').val(cdProntuario);
    $.ajax({
        type: 'POST',
        url: dir + 'ajax/materiais/movimentacao/mostra-prescricao',
        data: {
            'cd_sala': cdSala,
            "cd_prontuario": cdProntuario,
            "_token": token
        },
        dataType: 'json',
        success: function (data) {
            if (data.success == true) {
                if (data.retorno.length > 0){
                    var html =
                        "<table class='table table-hover table-striped table-sm'>" +
                        "<thead>" +
                            "<tr>" +
                                "<th colspan='12'>PRONTUÁRIO Nº: "+data.retorno[0].cd_prontuario+" - "+data.retorno[0].nm_pessoa+"</th>" +
                            "</tr>" +
                        "</thead>" +
                        "<tbody>";
                for (var x = 0; x < data.retorno.length; x++) {
                    html +=
                        "<tr>" +
                            "<td width='120px'>" + data.retorno[x].quantidade + " " + (data.retorno[x].fracao_minima != null ? data.retorno[x].fracao_minima : "") + "</td>" +
                            "<td>" + data.retorno[x].nm_produto + " - " + data.retorno[x].ds_produto + "</td>" +
                        "</tr>";
                    if (data.retorno[x].estoque.length == 0) {
                        html += "<tr><td colspan='12'>Sem estoque</td></tr>";
                    }
                    else {
                        html += "<tr style='font-size: 11px;'><td width='100px'></td><td>";
                        var dados = data.retorno[x].estoque;
                        html +=
                            "<table class='table table-hover table-striped table-responsive-sm'>" +
                            "<thead>" +
                            "<tr>" +
                            "<th>Lote</th>" +
                            "<th>Estoque</th>" +
                            "<th width='150px'>Mover</th>" +
                            "</tr>" +
                            "</thead>" +
                            "<tbody>";
                        for (var y = 0; y < dados.length; y++) {
                            if (dados[y].quantidade != 0) {
                                html += "<tr>" +
                                    "<td>" + (dados[y].lote != null ? dados[y].lote : '') + "</td>" +
                                    "<td>" + dados[y].quantidade + "</td>" +
                                    "<td class='text-center'>" +
                                        "<div class='input-group input-group-sm'>" +
                                            "<input id='input_" + x + y + "' value=" + data.retorno[x].quantidade + " type='text' class='form-control' pattern=\'[0-9]+$\' max=" + dados[y].quantidade + " placeholder='Quantidade'>" +
                                            "<span class='input-group-btn input-group-xs'>" +
                                                "<button data-nr=" + x + y + " data-fabricacao='"+data.retorno[x].dt_fabricacao+"' data-validade='"+data.retorno[x].dt_validade+"' data-fornecedor='"+data.retorno[x].cd_fornecedor+"' data-prontuario=" + cdProntuario + " data-nome='" + dados[y].nm_produto + " - " + dados[y].ds_produto + "' data-produto='" + dados[y].cd_produto + "' data-quantidade=" + dados[y].quantidade + " data-lote='" + dados[y].lote + "' type='button' class='btn btn-primary btn-xs transfere-produto'><span class='fas fa-arrow-alt-circle-right'></span></button>" +
                                            "</span>" +
                                        "</div>" +
                                    "</td>" +
                                    "</tr>";
                            }
                        }
                        html += "</tbody>" +
                            "</table>";
                        html += "</td></tr>";

                    }
                }
                html += "</tbody>" +
                    "                    </table>";
                $('#tabela-pesquisa-estoque').html(html);
                }
            }
        }
    });



}

function pesquisa_prontuario(nome) {
    $('#nr_doc_hidden').val(0);
    $('#nm_produto_origem').attr('placeholder','Informe o nome ou o Nº prontuário');
    $.ajax({
        type: 'POST',
        url: dir + 'ajax/materiais/movimentacao/pesquisa-prontuario',
        data: {
            "nome": nome,
            "_token": token
        },
        dataType: 'json',
        success: function (data) {
            if (data.success == true) {
                var html =
                    "<table class='table table-hover table-striped'>"+
                    "<thead>"+
                    "<tr>"+
                    "<th>Prontuário</th>"+
                    "<th>Nome</th>"+
                    "<th width='80px'>Selecionar</th>"+
                    "</tr>"+
                    "</thead>"+
                    "<tbody>";
                for(var x=0;x<data.retorno.length;x++) {
                    if(data.retorno[x].quantidade != 0) {
                        html += "<tr>"+
                            "<td>" + data.retorno[x].cd_prontuario + "</td>"+
                            "<td>" + data.retorno[x].nm_pessoa + "</td>"+
                            "<td class='text-center'>"+
                            "<div class='input-group'>"+
                            "<button value="+data.retorno[x].cd_prontuario+" type='button' class='btn btn-primary btn-sm seleciona-prontuario'><span class='fas fa-check'></span></button>"+
                            "</div>"+
                            "</td>"+
                            "</tr>";
                    }
                }
                html += "</tbody>" +
                    "                    </table>";
                $('#tabela-pesquisa-estoque').html(html);
            }
        }
    });
}

//----------------------------------Parte responsável pela importação da Nfe---------------------------------------------------------------------------------------
$('#importar-xml').click(function(e){
    if($('#cd_sala').val() == 0) {
        swal('Você deve selecionar uma localização');
        $('#cd_sala').addClass('is-invalid');
        e.preventDefault();
    }
    if($('#cd_emitente_destinatario_hidden').val() == '') {
        swal('Você deve selecionar um fornecedor');
        $('#cd_emitente_destinatario').addClass('is-invalid');
        e.preventDefault();
    }
    if($('#cd_movimento').val() == 0) {
        swal('Você deve selecionar um movimento');
        $('#cd_movimento').addClass('is-invalid');
        e.preventDefault();
    }
});

$('#cd_sala').change(function(){
    $('#cd_sala').removeClass('is-invalid');
});

$('#cd_movimento').change(function(){
    $('#cd_movimento').removeClass('is-invalid');
});

$('#importar-xml').change(function(){
    $('#modal-importa-nfe').modal('show');
    var arquivo = document.getElementById('importar-xml').files[0];
    var form_data = new FormData();
    form_data.append('arquivo', arquivo);
    form_data.append('_token', token);
    importa_xml(form_data);
});

function importa_xml(form_data){
    auto = true;
    progresso = 0;
    soma_progresso = 10;
    progresso += soma_progresso;
    atualiza_barra_progresso('barra-progresso-nfe', progresso,'Lendo Xml Nfe...','progresso-nfe');
    $.ajax({
        type: 'POST',
        url: dir + 'ajax/materiais/movimentacao/importa-xml-nfe',
        data: form_data,
        contentType: false,
        processData: false,
        success: function(data) {
            var retorno = JSON.parse(data);
            var nfe = retorno.nfe;
            var qtde_itens = 1;
            if(nfe.NFe.infNFe.det[0] !== undefined)
                qtde_itens = nfe.NFe.infNFe.det.length;
            soma_progresso = 90/((qtde_itens*2) + 4);

            pesquisa_fornecedor(nfe);
        },
        error: function() {
            console.log('ERRO ETAPA 1');
        }
    });
}

function pesquisa_fornecedor(nfe){
    progresso += soma_progresso;
    var fornecedor = nfe.NFe.infNFe.emit;
    atualiza_barra_progresso('barra-progresso-nfe', progresso,'Verificando cadastro do fornecedor: '+nfe.NFe.infNFe.emit.xNome,'progresso-nfe');
    $.ajax({
        type: 'POST',
        url: dir + 'ajax/materiais/movimentacao/existe-fornecedor',
        data: {
            "cd_fornecedor": $('#cd_emitente_destinatario_hidden').val(),
            "fornecedor": fornecedor,
            "_token": token
        },
        dataType: 'json',
        success: function(data) {
            if(data.success === true) {
                var nr_nfe = nfe.NFe.infNFe.ide.nNF;
                var cd_fornecedor = data.cd_fornecedor;
                $('#cd_fornecedor_hidden').val(cd_fornecedor);
                cadastra_movimentacao(nr_nfe, nfe);
            }
            else{
                alert(data.mensagem);
                $('#modal-importa-nfe').modal('hide');
            }
        },
        error: function() {
            console.log('ERRO ETAPA 2');
        }
    });
}

function cadastra_movimentacao(nr_nfe,nfe){
    progresso += soma_progresso;
    atualiza_barra_progresso('barra-progresso-nfe', progresso,'Cadastrando movimentação...','progresso-nfe');
    $.ajax({
        type: 'POST',
        url: dir + 'ajax/materiais/movimentacao/cadastra-movimentacao',
        data: {
            'nr_documento': nr_nfe,
            'cd_sala': $('#cd_sala').val(),
            'cd_movimento': $('#cd_movimento').val(),
            "_token": token
        },
        dataType: 'json',
        success: function(data) {
            var cd_movimentacao = data.cd_movimentacao;
            $('#cd_movimentacao').val(cd_movimentacao);
            $('#cd_movimentacao_hidden').val(cd_movimentacao);
            $('#nr_documento').val(nr_nfe);
            cadastra_movimentacao_nfe(cd_movimentacao, nfe);
        },
        error: function() {
            console.log('ERRO ETAPA 3');
        }
    });
}

function cadastra_movimentacao_nfe(cd_movimentacao, nfe){
    progresso += soma_progresso;
    var itens = nfe.NFe.infNFe.det;
    var nfe_aux = nfe;
    delete nfe_aux.NFe.infNFe.det;

    atualiza_barra_progresso('barra-progresso-nfe', progresso,'Cadastrando movimentação da Nfe...','progresso-nfe');
    $.ajax({
        type: 'POST',
        url: dir + 'ajax/materiais/movimentacao/cadastra-movimentacao-nfe',
        data: {
            'cd_movimentacao': cd_movimentacao,
            'cd_emitente_destinatario': $('#cd_emitente_destinatario_hidden').val(),
            "nfe": nfe_aux,
            "_token": token
        },
        dataType: 'json',
        success: function(data) {
            if(itens[0] === undefined)
                itens = [itens];
            verifica_cadastro_do_produto(itens,0);
        },
        error: function() {
            console.log('ERRO ETAPA 4');
        }
    });
}

function verifica_cadastro_do_produto(itens,indice){
    $('#btn-pesquisa-produto-nfe').attr('data-itens',JSON.stringify(itens));
    $('#btn-pesquisa-produto-nfe').attr('data-indice',indice);
    if($('#pesquisa-produto-nfe').val() === "") {
        progresso += soma_progresso;
        atualiza_barra_progresso('barra-progresso-nfe', progresso, 'Verificando cadastro: ' + itens[indice].prod.xProd, 'progresso-nfe');
    }
    $.ajax({
        type: 'POST',
        url: dir + 'ajax/materiais/movimentacao/verifica-cadastro-produto',
        data: {
            "produto": itens[indice],
            "pesquisa": $('#pesquisa-produto-nfe').val(),
            "cd_fornecedor": $('#cd_emitente_destinatario_hidden').val(),
            "_token": token
        },
        dataType: 'json',
        success: function(data) {
            $('#pesquisa-produto-nfe').val('');
            define_acao_produto(data,itens,indice);
        },
        error: function() {
            console.log('ERRO ETAPA 5');
        }
    });
}

function define_acao_produto(retorno,itens,indice) {
    var html = "";
    $('#tabela-pesquisa-produto').html(html);
    document.getElementById('painel-produto').style.display = 'none';
    if(typeof retorno.cadastro === 'undefined' || typeof retorno.cadastro.importado_anteriormente === 'undefined' || retorno.cadastro.importado_anteriormente === false)
        document.getElementById('painel-nfe').style.display = 'block';
    else
        document.getElementById('painel-nfe').style.display = 'none';
    if (retorno.existe == true) {
        if(retorno.cadastro.importado_anteriormente == true){
          solicita_parametros_item(itens,indice,retorno.cadastro);
        }
        else{
            for(var x=0; x<retorno.cadastro.length;x++) {
                var cadastro_incompleto = false;
                var produto = retorno.cadastro[x];
                if (produto.embalagem == 'NAOCAD' || produto.cd_fracao_minima == null || produto.qtde_embalagem == null) {
                    cadastro_incompleto = true;
                    html += "<tr style='color: red'>";
                }
                else {
                    html += "<tr>";
                }
                html += "<td>" + produto.cd_produto + "</td>" +
                    "<td>" + produto.nm_produto + " - " + produto.ds_produto + "</td>" +
                    "<td>" + produto.nm_laboratorio + "</td>";
                if (!cadastro_incompleto)
                    html += "<td><button class='btn btn-primary btn-sm seleciona-produto-nfe' data-ean-principal='" + produto.cd_ean + "' data-embalagem='" + produto.embalagem + "' data-fracionado='" + produto.fracionamento + "' data-un-medida='" + (produto.un_medida !== null ? produto.un_medida : "Não se aplica") + "' data-fracao='" + produto.cd_fracao_minima + "'  data-qtde='" + produto.qtde_embalagem + "' title='Selecionar este produto' value='" + produto.cd_produto + "'><span class='fas fa-check'></span></button></td>";
                else
                    html += "<td>Problema no cadastro do <a href='" + dir + 'materiais/produto/cadastro/' + produto.cd_produto + "' target='_blank' title='Clique aqui para ir para o cadastro do produto.' style='color: red'><b>PRODUTO</b></a></td>";
                html += "</tr>";
            }
        }
    } else{
        html +=
            "<tr>" +
            "<td colspan='12'>Nenhum resultado encontrado. Efetue o cadastro do produto e efetue uma nova pesquisa para continuar.</td>" +
            "</tr>";
    }
    $('#tabela-pesquisa-produto').html(html);
}

$(document).on('click', '.seleciona-produto-nfe', function(evt) {
    document.getElementById('painel-nfe').style.display = 'none';
    document.getElementById('painel-produto').style.display = 'block';
    var itens = JSON.parse($('#btn-pesquisa-produto-nfe').attr('data-itens'));
    var indice = $('#btn-pesquisa-produto-nfe').attr('data-indice');

    var qtde = null;
    if($(this).attr('data-ean-principal') === itens[indice].prod.cEAN || $(this).attr('data-embalagem') === itens[indice].prod.uCom)
        qtde = 1;
    else {
        do {
            qtde = prompt(itens[indice]['prod']['xProd'] + "\nPrecisamos configurar o sistema para suas futuras importações. \nQuantas " + $(this).attr('data-embalagem') + " existem em uma " + itens[indice]['prod']['uCom'] + "?", "1");
        } while (qtde == null || qtde == "" || !isNumber(qtde));
    }
    $.ajax({
        type: 'POST',
        url: dir + 'ajax/materiais/movimentacao/add-produto-vinculo',
        data: {
            "quantidade": qtde,
            "produto": itens[indice],
            "cd_produto": $(this).val(),
            "cd_fornecedor": $('#cd_emitente_destinatario_hidden').val(),
            "_token": token
        },
        dataType: 'json',
        success: function(data) {
            if(data.success == true) {
                verifica_cadastro_do_produto(itens, indice);
            }
            else{
                alert(data.mensagem);
            }
        },
        error: function() {
            console.log('ERRO NO CADASTRO DO VÍNCULO DO PRODUTO');
        }
    });

});

function isNumber(n) {
    return !isNaN(parseFloat(n)) && isFinite(n);
}

function solicita_parametros_item(itens,indice,cadastro) {
    var htmlnfe = "";
    var htmlprod = "";
    $('#tabela-dados-nfe').html(htmlnfe);
    $('#tabela-dados-produto').html(htmlprod);
    document.getElementById('painel-produto').style.display = 'block';
    document.getElementById('painel-nfe').style.display = 'none';

    var nomes = {
        cEAN:"Cód. barras",
        xProd:"Nome produto",
        NCM:"NCM",
        uCom:"Un. comercial",
        qCom:"Quantidade",
        vUnCom:"Vlr. unitário",
        vProd:"Vlr total"}
    var obj = itens[indice].prod;
    for (key in obj) {
        if (typeof obj[key] !== 'object') {
            if(nomes[key] != undefined) {
                htmlnfe +=
                    "<tr>" +
                    "<td><b>" + nomes[key] + "</b></td>" +
                    "<td>" + obj[key] + "</td>" +
                    "</tr>";
            }
        }
    }

    var quantidade = parseFloat(obj['qCom']) * parseFloat(cadastro.multiplicador);
    var total = parseFloat(cadastro.qtde_embalagem) * parseFloat(quantidade);
    $('#cd_produto_hidden').val(cadastro.cd_produto);

    htmlprod +=
        "<tr><td><b>Nome do produto</b></td><td>"+cadastro.nm_produto+" - "+cadastro.ds_produto+"</td></tr>" +
        "<tr><td><b>Quantidade por embalagem</b></td><td>"+cadastro.qtde_embalagem+"</td></tr>" +
        "<tr><td><b>Fração mínima</b></td><td>"+(cadastro.un_medida != null ? cadastro.un_medida : 'Não se aplica')+"</td></tr>" +
        "<tr><td><b>Fracionável</b></td><td>"+(cadastro.fracionamento == 1 ? "Sim" : "Não")+"</td></tr>" +
        "<tr><td><b>Unidade Comercial</b></td><td>"+cadastro.embalagem+"</td></tr>" +
        "<tr><td colspan='2'>&nbsp;</td></tr>";
        if(obj['cEAN'] == cadastro.cd_ean && cadastro.fracionamento == 1)
            htmlprod += "<tr><td colspan='2'>Verifique a quantidade e corrija se necessário: "+obj['qCom']+cadastro.embalagem+" = "+total+cadastro.un_medida+"</td></tr>";
        if(obj['cEAN'] != cadastro.cd_ean && cadastro.fracionamento == 1)
            htmlprod += "<tr><td colspan='2'>Verifique a quantidade e corrija se necessário: "+obj['qCom']+obj['uCom']+" = "+quantidade+cadastro.embalagem+" = "+total+cadastro.un_medida+"</td></tr>";
        if(obj['cEAN'] != cadastro.cd_ean && cadastro.fracionamento == 0)
            htmlprod += "<tr><td colspan='2'>Verifique a quantidade e corrija se necessário: "+obj['qCom']+obj['uCom']+" = "+quantidade+cadastro.embalagem+"</td></tr>";

        htmlprod += "<tr style='font-size: 14px'><td><b>Qtde a ser lançada ("+(cadastro.fracionamento == 1 ? cadastro.un_medida : cadastro.embalagem)+")</td><td><input id='quantidade' style='border: #727272' value='"+(cadastro.fracionamento == 1 ? total : quantidade)+"'/></b></td></tr>";
        htmlprod += "<tr style='font-size: 14px'><td><b>Valor de custo de cada "+(cadastro.fracionamento == 1 ? cadastro.un_medida : cadastro.embalagem)+"</td><td><input id='valor_unitario' style='border: #727272' value='"+(cadastro.fracionamento == 1 ? obj['vProd']/total : obj['vProd']/quantidade)+"'/></b></td></tr>";
    $('#tabela-dados-nfe').html(htmlnfe);
    $('#tabela-dados-produto').html(htmlprod);
    document.getElementById('quantidade').focus();
}

$(document).on('click', '#btn-avancar', function(evt) {
    var itens = JSON.parse($('#btn-pesquisa-produto-nfe').attr('data-itens'));
    var indice = $('#btn-pesquisa-produto-nfe').attr('data-indice');
    var produto = itens[indice].prod;
    var cd_produto = $('#cd_produto_hidden').val();
    var cd_movimentacao = $('#cd_movimentacao').val();
    var cd_sala = $('#cd_sala').val();
    var lote = 1;
    var validade = null;
    var fabricacao = null;
    if(produto.rastro != 'undefined' && produto.rastro != undefined) {
        lote = produto.rastro.nLote;
        validade = produto.rastro.dVal;
        fabricacao = produto.rastro.dFab;
    }
    var quantidade = $('#quantidade').val();
    var tp_saldo = $('#tp_saldo_hidden').val();
    var preco_unitario = $('#valor_unitario').val();
    var nm_produto = produto.xProd;
    cadastra_item_movimentacao(cd_movimentacao,cd_produto,lote,validade,quantidade,cd_sala,tp_saldo,nm_produto,fabricacao,preco_unitario,0);
});

function atualiza_barra_progresso(nome_barra, percentual_barra, etapa, nome_div_etapa) {
    document.getElementById(nome_barra).style.width = percentual_barra + '%';
    document.getElementById(nome_barra).setAttribute('aria-valuenow', percentual_barra);
    // document.getElementById(nome_barra).innerHTML = parseInt(percentual_barra) + '%';
    document.getElementById(nome_div_etapa).innerHTML = etapa;
    sleep(700);
}

function sleep(milliseconds) {
    var start = new Date().getTime();
    for (var i = 0; i < 1e7; i++) {
        if ((new Date().getTime() - start) > milliseconds) {
            break;
        }
    }
}

function cadastra_item_movimentacao(cd_movimentacao, cd_produto,lote,dt_validade,quantidade,cd_sala,tp_saldo,nm_produto,dt_fabricacao,valor_unitario_fracao,cd_item_movimentacao) {
    var formData = new FormData();
    if (auto == true) {
        progresso += soma_progresso;
        atualiza_barra_progresso('barra-progresso-nfe', progresso, 'Cadastrando item: ' + nm_produto, 'progresso-nfe');
        var cd_fornecedor = $('#cd_emitente_destinatario_hidden').val();
    }
    else {
        var cd_fornecedor = $('#cd_emitente_destinatario_hidden').val();
        formData.append('cd_produto_fornecedor', $('#cd_produto_fornecedor').val());
        formData.append('dFab', dt_fabricacao);
        formData.append('dVal', dt_validade);
        formData.append('nLote', lote);
        formData.append('NCM', $('#NCM').val());
        formData.append('CFOP', $('#CFOP').val());
        formData.append('uCom', $('#uCom').val());
        formData.append('qCom', $('#qCom').val());
        formData.append('vUnCom', $('#preco_unitario_comercial').val());
        formData.append('vProd', $('#preco_total').val());
        formData.append('icms_vICMS', $('#icms_vICMS').val());
        formData.append('ipi_vIPI', $('#ipi_vIPI').val());
    }

    formData.append('_token', token);
    formData.append('cd_movimentacao', cd_movimentacao);
    formData.append('cd_fornecedor', cd_fornecedor);
    formData.append('cd_movimentacao_itens',cd_item_movimentacao);
    formData.append('cd_produto', cd_produto);
    formData.append('lote', lote);
    formData.append('valor_unitario', valor_unitario_fracao);
    formData.append('dt_validade', dt_validade);
    formData.append('dt_fabricacao', dt_fabricacao);
    formData.append('quantidade', quantidade);
    formData.append('cd_sala', cd_sala);
    formData.append('tp_saldo', tp_saldo);
    formData.append('auto', auto);
    $.ajax({
        type: 'POST',
        url: dir + 'ajax/materiais/movimentacao/adicionar-item',
        data: formData,
        dataType: 'json',
        contentType: false,
        contentType: false,
        processData: false,
        success: function (data) {
            if(data.success == true){
                if(auto != true)
                    location.reload();
                else
                    cadastra_item_movimentacao_nfe(data.cd_item_movimentacao);
            }
            else{
                if(data.retorno == false){
                    erro = "Esse produto não existe em estoque nesse local.";
                }
                else {
                    erro = "O produto " + data.retorno.nm_produto + " possui " + data.retorno.quantidade + " unidades em estoque no(a) " + data.retorno.nm_sala + ". Verifique os valores de entrada e tente novamente.";
                }
                Swal('Atenção!', erro, 'warning');
            }
        }
    });
}

function cadastra_item_movimentacao_nfe(cd_item_movimentacao) {
    var itens = JSON.parse($('#btn-pesquisa-produto-nfe').attr('data-itens'));
    var indice = $('#btn-pesquisa-produto-nfe').attr('data-indice');
    $.ajax({
        type: 'POST',
        url: dir + 'ajax/materiais/movimentacao/cadastra-item-movimentacao-nfe',
        data: {
            "produto": itens[indice],
            "cd_item_movimentacao": cd_item_movimentacao,
            "_token": token
        },
        dataType: 'json',
        success: function (data) {
            if (data.success == true) {
                indice++;
                if(indice < itens.length)
                    verifica_cadastro_do_produto(itens,indice);
                else {
                    atualiza_barra_progresso('barra-progresso-nfe', 100,'Importação realizada com sucesso!','progresso-nfe');
                    swal({
                        type: 'success',
                        title: 'Concluído!',
                        text: 'Importação realizada com sucesso!!',
                        showConfirmButton: true,
                        onClose: function () {
                            $('#modal-importa-nfe').modal('hide');
                            auto = false;
                            document.getElementById('itens-movimentacao').style.display = 'block';
                            location.href = window.location.href+"/"+$('#cd_movimentacao_hidden').val();
                        }
                    })
                }
            }
            else {
                alert("PROBLEMA NO PASSO 7 - CADASTRA ITEM DA MOVIMENTAÇÃO NFE "+x);
            }
        }
    });
}


