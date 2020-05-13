$('#grupo_configuracao').change(function(){
    $('#sub_grupo_configuracao').val(0);
    $('#forma_organizacao_configuracao').val(0);
    preenche_select_sub_grupo($(this).val());
    document.getElementById('exibe_sub_grupo').style.display = 'block';
});

$('#sub_grupo_configuracao').change(function(){
    $('#forma_organizacao_configuracao').val(0);
    preenche_select_forma_organizacao($('#grupo_configuracao').val(),$(this).val());
    document.getElementById('exibe_forma_organizacao').style.display = 'block';
});


$('#iniciar_pesquisa_configuracao_procedimento').click(function(){
    pesquisa_procedimentos($('#grupo_configuracao').val(),$('#sub_grupo_configuracao').val(),$('#forma_organizacao_configuracao').val(),$('#pesquisa_configuracao_procedimento').val());
});

$('#upload_arquivos_sus').change(function(event){
    swal({
        title: 'Confirmação',
        text: "Você tem certeza que deseja atualizar a tabela de procedimentos e demais tabelas relacionadas? Essa ação não poderá ser desfeita",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim, tenho certeza!'
    }).then(function (result) {
        /*var form;
        form = new FormData();
        form.append('arquivo', event.target.files[0]);
        form.append('_token', token);*/
        var form = new FormData($('#arquivo-tabela-unificada-sus')[0]);
        if (result.value) {
            $.ajax({
                type: 'POST',
                url: dir + 'ajax/configuracoes/atualizar-tabelas-sus',
                data: form,
                processData: false,
                contentType: false,
                success: function (data) {
                    var html = '';
                    if (data.success == true) {
                        /*for (var x = 0; x < data.retorno.length; x++) {

                        }
                        $('#tabela_configuracao_procedimento').html(html);*/
                        alert("uhuh");
                    }
                }
            });
        }
    });
});

$(document).on('click', '.checkbox_configuracao_procedimento', function(){
    if ($(this).is(':checked')) {
        add_procedimentos($(this).val());
    }
    else{
        remove_procedimentos($(this).val());
    }

});

function preenche_select_sub_grupo(cdGrupo) {
    $.ajax({
        type: 'POST',
        url: dir + 'ajax/configuracoes/preenche-select-sub-grupo',
        data: {"cd_grupo":cdGrupo, "_token": token},
        dataType: 'json',
        success: function (data) {
            if (data.success) {
                var data = data.retorno;
                var selectbox = $('#sub_grupo_configuracao');
                selectbox.find('option').remove();
                $('<option>').val(0).text("Nenhum item selecionado").appendTo(selectbox);
                for(var x=0;x<data.length;x++) {
                    $('<option>').val(data[x].cd_sub_grupo).text(left_pad_zeros(data[x].cd_sub_grupo,2)+" - "+data[x].nm_sub_grupo).appendTo(selectbox);
                }

            }
        }
    });
}

function preenche_select_forma_organizacao(cdGrupo, cdSubGrupo) {
    $.ajax({
        type: 'POST',
        url: dir + 'ajax/configuracoes/preenche-select-forma-organizacao',
        data: {"cd_grupo": cdGrupo,"cd_sub_grupo": cdSubGrupo, "_token": token},
        dataType: 'json',
        success: function (data) {
            if (data.success) {
                var data = data.retorno;
                var selectbox = $('#forma_organizacao_configuracao');
                selectbox.find('option').remove();
                $('<option>').val(0).text("Nenhum item selecionado").appendTo(selectbox);
                for(var x=0;x<data.length;x++) {
                    $('<option>').val(data[x].cd_forma_organizacao).text(left_pad_zeros(data[x].cd_forma_organizacao,2)+" - "+data[x].nm_forma_organizacao).appendTo(selectbox);
                }

            }
        }
    });
}

function pesquisa_procedimentos(cdGrupo, cdSubGrupo, cdFormaOrganizacao, pesquisa) {
    if(cdGrupo ===00)
        cdGrupo = null;
    $.ajax({
        type: 'POST',
        url: dir + 'ajax/configuracoes/pesquisa-procedimentos',
        data: {"cd_grupo": left_pad_zeros(cdGrupo,2),"cd_sub_grupo": left_pad_zeros(cdSubGrupo,2), "cd_forma_organizacao": left_pad_zeros(cdFormaOrganizacao,2),"pesquisa": pesquisa, "_token": token},
        dataType: 'json',
        success: function (data) {
            var html = '';
            if (data.success) {
                for(var x=0;x<data.retorno.length;x++){
                    html += "<tr>";
                    html += "<td>"+data.retorno[x].cd_procedimento+"</td>";
                    html += "<td>"+data.retorno[x].nm_procedimento+"</td>";
                    html += "<td><input type = 'checkbox' value='"+data.retorno[x].cd_procedimento+"' class='checkbox_configuracao_procedimento' ";
                    if(data.retorno[x].cd_procedimento == data.retorno[x].permitido)
                        html += 'checked';
                    html += "></td>";
                    html += "</tr>";
                }
            }
            $('#tabela_configuracao_procedimento').html(html);
        }
    });
}

function add_procedimentos(cdProcedimento) {
    $.ajax({
        type: 'POST',
        url: dir + 'ajax/configuracoes/add-procedimentos',
        data: {"cd_procedimento": cdProcedimento, "_token": token},
        dataType: 'json',
        success: function (data) {

        }
    });
}

function remove_procedimentos(cdProcedimento) {
    $.ajax({
        type: 'POST',
        url: dir + 'ajax/configuracoes/remove-procedimentos',
        data: {"cd_procedimento": cdProcedimento, "_token": token},
        dataType: 'json',
        success: function (data) {

        }
    });
}

function left_pad_zeros(number, length) {
    var my_string = '' + number;
    while (my_string.length < length) {
        my_string = '0' + my_string;
    }
    return my_string;
}

