
$('#btn-modal-medicamento').click(function(){
    $('#pesquisa_nm_medicamento').val('');
    $('#table-medicamento').html('<tr><td colspan="2">Utilize a busca acima para encontrar medicamentos.</td></tr>');
    $("#btn-pesquisar-medicamento").removeAttr('disabled');

    $('#modal-medicamento').modal({
        backdrop: 'static'
    })
    document.getElementById("pesquisa_nm_medicamento").focus();
})

$('.btn-modal-medicamento').click(function(){
    $('#pesquisa_nm_medicamento').val('');
    $('#table-medicamento').html('<tr><td colspan="2">Utilize a busca acima para encontrar medicamentos.</td></tr>');
    $("#btn-pesquisar-medicamento").removeAttr('disabled');

    $('#modal-medicamento').modal({
        backdrop: 'static'
    })
    document.getElementById("pesquisa_nm_medicamento").focus();
})

$('#pesquisa_nm_medicamento').on('keydown', function(e) {
    if (e.which == 13) {
        pesquisar_medicamento();
    }
});

$('#btn-pesquisar-medicamento').click(function() {
    pesquisar_medicamento();
})

function pesquisar_medicamento(){
    var controlar_estoque = false;
    if($('#tipo_conduta_hidden').val() === 'prescricao_ambulatorial')
        controlar_estoque = true;
    var nm_medicamento = $('#pesquisa_nm_medicamento').val();
    var erro = '';
    /*if (nm_medicamento.length <3) {
        erro += 'Sua pesquisa deve ter no mínimo 3 caracteres!<br>';
    }*/
    if (erro != '') {
        Swal('Atenção!',erro,'warning')
    } else {
        $("#btn-pesquisar-medicamento").attr('disabled', 'disabled');
        $('#table-medicamento').html('<tr><td colspan="2">Pesquisando medicamento, por favor aguarde...</td></tr>');
        $.ajax({
            type: 'POST',
            url: dir + 'ajax/atendimentos/pesquisa-medicamento',
            data: {"pesquisa": nm_medicamento, 'controle':controlar_estoque, "_token": token},
            dataType: 'json',
            success: function (data) {
                if (data.success == true) {
                    var html = '';
                    if (data.dados.length == 0){
                        html='<tr><td colspan="2">Sem resultados para exibir....</td></tr>';
                    } else {
                        for (var x = 0; x < data.dados.length; x++) {
                            html += "<tr style='color:"+ (controlar_estoque && data.dados[x].quantidade == 0 ? "red" : "black")+"'>" +
                                "<td>" + data.dados[x].nm_med + " - " +data.dados[x].ds_apr + " - Lab. " +data.dados[x].nm_lab + "</td>";
                                    if((controlar_estoque && data.dados[x].quantidade > 0) || !controlar_estoque) {
                                        html += "<td><button id='selecionar-medicamento' data-nm-un-medida='"+data.dados[x].nm_unidade_medida+"' data-cd-un-medida='"+data.dados[x].cd_unidade_medida+"' data-nome='" + data.dados[x].nm_med + "-" + data.dados[x].ds_apr + "' value=" + data.dados[x].cd_produto + " class='btn btn-primary btn-sm pull-right'><span class='fa fa-check'></span></button></td>";
                                    }
                                    else {
                                        html += "<td>Sem estoque</td>";
                                    }
                                html += "</tr>";
                        }
                    }
                    $('#table-medicamento').html(html);
                }
                $("#btn-pesquisar-medicamento").removeAttr('disabled');

            }
        });
    }
}

$(document).on('click', '#selecionar-medicamento', function(){
    $('#cd_medicamento').val($(this).attr('data-nome'));
    $('#cd_medicamento_hidden').val($(this).val());
    $('#cd_medicamento').attr('data-id',$(this).val());
    $('#modal-medicamento').modal('hide');
    $("#btn-dose").val($(this).attr('data-cd-un-medida'));
    $("#btn-dose").html($(this).attr('data-nm-un-medida')+" <span class='caret'></span>");
    $("#btn-dose").attr('data-valor',$(this).attr('data-cd-un-medida'));
     $.ajax({
     type: 'POST',
     url: dir + 'ajax/atendimentos/busca-vias-aplicacao-medicamento',
     data: {"cd_produto": $(this).val(), "_token": token},
     dataType: 'json',
     success: function (data) {
         if (data.success == true) {
            var vias = data.vias;
            var html = '';
             $('#dropdown-vias-aplicacao li').each(function(i)
             {
                $(this).hide();
             });
            for(var x=0; x<vias.length; x++){
                $('#dropdown-vias-aplicacao li').each(function(i)
                {
                    if($(this).attr('data-valor') == vias[x].cd_via_aplicacao) {
                        $(this).show();
                    }
                });
            }
         }
     }
 });
   /* $.ajax({
        type: 'POST',
        url: dir + 'ajax/atendimentos/sugere-posologia-medicacao',
        data: {"cd_medicamento": $(this).val(), "_token": token},
        dataType: 'json',
        success: function (data) {
            if (data.success == true) {
                //$('#quantidade').val(data.dados.quantidade);
                //ajusta_texto_botao('btn-embalagem',data.dados.desc_tp_embalagem,data.dados.tp_embalagem);
                $('#dose').val(data.dados.dose);
                ajusta_texto_botao('btn-dose',data.dados.desc_tp_dose,data.dados.tp_dose);
                ajusta_texto_botao('btn-via',data.dados.desc_tp_via,data.dados.tp_via);
                $('#intervalo').val(data.dados.intervalo);
                ajusta_texto_botao('btn-intervalo',data.dados.desc_tp_intervalo,data.dados.tp_intervalo);
                $('#prazo').val(data.dados.prazo);
                ajusta_texto_botao('btn-prazo',data.dados.desc_tp_prazo,data.dados.tp_prazo);
                $('#observacao_medicamento').val(data.dados.observacao_medicamento);
            }
            else{
                ajusta_texto_botao('btn-dose',data.dados.desc_tp_dose,data.dados.tp_dose);
                ajusta_texto_botao('btn-via',data.dados.desc_tp_via,data.dados.tp_via);
            }
        }
    });*/
});

function ajusta_texto_botao(nome,texto,valor){
    $("#"+nome).val(texto);
    $("#"+nome).html(texto+" <span class='caret'></span>");
    $("#"+nome).attr('data-valor',valor);
}