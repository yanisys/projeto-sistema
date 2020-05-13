/*-------------------------------------------------------------------------
| CLASSES DE MASCARAS PARA INPUTS
|--------------------------------------------------------------------------*/
$('.mask-cep').mask('00.000-000');
$('.mask-cpf').mask('000.000.000-00');
$('.mask-cnpj').mask('00.000.000/0000-00');
$('.mask-telefone').mask("(00) 0000-00009");
$('.dinheiro').mask('##0.00', {reverse: true});
$('.mask-decimal-x-4').mask('##0.0000', {reverse: true});
$('.mask-numeros-20').mask("99999999999999999999");
$('.mask-numeros-14').mask("99999999999999");
$('.mask-numeros-9').mask("999999999");
$('.mask-inteiro').mask("9999999999999");
$('.mask-data').mask("99/99/9999");
$('.mask-hora').mask("99:99");
$('.mask-data-hora').mask("99/99/9999 99:99");
$('.mask-inteiro3').mask('099');
$('.mask-decimal31').mask('990.0');
$('.mask-decimal22').mask('00.00');
$('.mask-decimal-x-2').mask('##0.00', {reverse: true});
$('.mask-decimal32').mask("#00.00");
$('.mask-decimal33').mask("#00.000");
$('.mask-decimal12').mask('0.00');
$('.mask-pressao-arterial').mask('000/000');
$('.mask-numeros-3').mask('999');
$('.mask-numeros-2').mask('99');




/*-------------------------------------------------------------------------
| FUNÇÃO: convertDate
| DESCRIÇÃO: Transforma data do mysql aaaa-mm-dd para o formato dd/mm/aaaa
| -PARAMETRO inputFormat: nome do arquivo js
|--------------------------------------------------------------------------*/
function convertDate(inputFormat) {
    if(inputFormat == null)
        return '';
    else {
        var converte = inputFormat.split("-");
        if(converte[2].length > 2) {
            var hora = converte[2].split(" ");
            converte[2] = hora[0];
            var resposta = converte[2] + '/' + converte[1] + '/' + converte[0] + ' '+hora[1];
        }
        else {
            var resposta = converte[2] + '/' + converte[1] + '/' + converte[0];
        }
        return (resposta);
    }
}

/*-------------------------------------------------------------------------
| FUNÇÃO: error_alert
| DESCRIÇÃO: Alerta de erro no formato do sweet alert
| -PARAMETRO mensagem: mensagem que vai aparecer no corpo do alerta (pode ser html)
|--------------------------------------------------------------------------*/
function error_alert(mensagem) {
    swal({
        title: 'Erro!',
        html: mensagem,
        type: 'error',
        confirmButtonText: 'OK'
    })
}

/*-------------------------------------------------------------------------
| CLASSE: remember-tabs
| DESCRIÇÃO: tab panel: depois do refresh, lembrar qual tab estava aberto
| EXEMPLO:   <ul class="nav nav-tabs remember-tabs">
|--------------------------------------------------------------------------*/
$('.remember-tabs a').click(function(e) {
    e.preventDefault();
    $(this).tab('show');
});
$("ul.nav-tabs > li > a").on("shown.bs.tab", function(e) {
    var id = $(e.target).attr("href").substr(1);
    window.location.hash = id;
});
var hash = window.location.hash;
$('.remember-tabs a[href="' + hash + '"]').tab('show');

/*-------------------------------------------------------------------------
| FUNÇÃO: calcAge
| DESCRIÇÃO: Calcula a idade
| -PARAMETRO dateString: data em formato de string
|--------------------------------------------------------------------------*/
function calcAge(dateString) {
    var birthday = +new Date(dateString);
    return ~~((Date.now() - birthday) / (31557600000));
}
/*-------------------------------------------------------------------------
| FUNÇÃO: validaCPF
| DESCRIÇÃO: retorna true se CPF for válido
| -PARAMETRO s: CPF
|--------------------------------------------------------------------------*/
function validaCPF(s) {
    var c = s.substr(0,9);
    var dv = s.substr(9,2);
    var d1 = 0;
    for (var i=0; i<9; i++) {
        d1 += c.charAt(i)*(10-i);
    }
    if (d1 == 0) return false;
    d1 = 11 - (d1 % 11);
    if (d1 > 9) d1 = 0;
    if (dv.charAt(0) != d1){
        return false;
    }
    d1 *= 2;
    for (var i = 0; i < 9; i++)	{
        d1 += c.charAt(i)*(11-i);
    }
    d1 = 11 - (d1 % 11);
    if (d1 > 9) d1 = 0;
    if (dv.charAt(1) != d1){
        return false;
    }
    return true;
}

/*-------------------------------------------------------------------------
| FUNÇÃO: validaCNPJ
| DESCRIÇÃO: retorna true se CNPJ for válido
| -PARAMETRO CNPJ: CNPJ
|--------------------------------------------------------------------------*/
function validaCNPJ(CNPJ) {
    var a = new Array();
    var b = new Number;
    var c = [6,5,4,3,2,9,8,7,6,5,4,3,2];
    for (i=0; i<12; i++){
        a[i] = CNPJ.charAt(i);
        b += a[i] * c[i+1];
    }
    if ((x = b % 11) < 2) { a[12] = 0 } else { a[12] = 11-x }
    b = 0;
    for (y=0; y<13; y++) {
        b += (a[y] * c[y]);
    }
    if ((x = b % 11) < 2) { a[13] = 0; } else { a[13] = 11-x; }
    if ((CNPJ.charAt(12) != a[12]) || (CNPJ.charAt(13) != a[13])){
        return false;
    }
    return true;
}
/*-------------------------------------------------------------------------
| CLASSE: btn-excluir
| DESCRIÇÃO: classe padrão para botão de excluir
| -PARAMETROS data-tabela: tabela do registro a ser excluido
              data-chave: chave do registro a ser excluido
              data-valor: valor do registro a ser excluido
  EXEMPLO: <button data-tabela="pessoa" data-chave="cd_pessoa" data-valor="123" class='btn-excluir'>Excluir</button>
|--------------------------------------------------------------------------*/
$(document).on('click', '.btn-excluir', function(){
    var tabela =($(this).attr('data-tabela'));
    var chave = ($(this).attr('data-chave'));
    var valor = ($(this).attr('data-valor'));

    swal({
        title: 'Confirmação',
        text: "Tem certeza? Essa ação não poderá ser desfeita!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim, exclua este registro!'
    }).then(function(result)  {
        if (result.value) {
            $.ajax({
                type: 'POST',
                url: dir + 'api/delete',
                data: {
                    "tabela": tabela,
                    "chave": chave,
                    "valor": valor
                },
                dataType: 'json',
                success: function (data) {
                    if (data.success == true) {
                        swal({
                            type: 'success',
                            title: 'Excluido!',
                            text: 'O registro foi excluído com sucesso!',
                            showConfirmButton: false,
                            timer: 1500,
                            onClose: function () {
                                location.reload();
                            }
                        })
                    } else {
                        swal({
                            type: 'error',
                            title: 'Erro!',
                            text: data.mensagem,
                            showConfirmButton: false,
                            timer: 2500
                        })
                    }
                }
            });
        }
    });
});

/*-------------------------------------------------------------------------
| CLASSE: btn[type='submit']
| DESCRIÇÃO: previne que o form seja submetido multiplas vezes
|--------------------------------------------------------------------------*/
/*$(".btn[type='submit']").on('click',function(){
    $(this).attr('disabled','disabled');
    //$(this).val('Aguarde ...');
});*/

/****************************************************************************** DAQUI PRA BAIXO NAO ESTA ORGANIZADO */

$('#relatorio_prontuario_tela').click(function() {
    $('#tp_relatorio_prontuario').val('T');
});

$('#relatorio_prontuario_pdf').click(function() {
    $('#tp_relatorio_prontuario').val('P');
});

$('#finalizar-procedimento').click(function() {
    $('#id_status_procedimento').val('C');
});

$('#salvar-procedimento').click(function() {
    $('#id_status_procedimento').val('A');
});

$('#gerar_arquivo_bpa').click(function() {
    $('#opcao_bpa').val('A');
});

$('#gerar_relatorio_bpa').click(function() {
    $('#opcao_bpa').val('R');
});

$('#gerar_relatorio_controle_bpa').click(function() {
    $('#opcao_bpa').val('C');
});

$('.search').on('click',function () {
    $('#search').attr('data-destino',$(this).attr('data-destino'));
});

$('#search').on('keyup',function () {
    if(($('#search').val().length > 3)) {
        if($('#search').attr('data-destino') == 'pesquisa_ocupacao')
            pesquisa_ocupacao($('#search').val());
    }
});

$('.intervalo').on('keyup',function () {
    if($(this).val() > 15){
       $(this).val(15);
    }
});

$(document).on('click', '#selecionar-ocupacao', function(){
    $('#nome_ocupacao').val($(this).val()+" - "+$(this).attr('data-nome'));
    $('#cd_ocupacao').val($(this).val());
    $('#modal-search').modal('hide');
});
/*$('#nome_pesquisa').on('keyup',function () {
    if(($('#nome_pesquisa').val().length > 3)) {
        Pesquisar_Pessoa($('#nome_pesquisa').val(), $('#p_id_pessoa').val());
    }
    if($('#nome_pesquisa').val()=="")
    {
        $('#table-pessoas').html('');
    }
});*/

$('#modal-pesquisa').on('hide.bs.modal', function (event) {
    $('#form-modal-pessoas').each (function(){
        this.reset();
    });
    $('#nome-header').text("Cadastro de pessoas");
})

$('#modal-search').on('hide.bs.modal', function (event) {
    $('#search').val('');
    $('#retorno-search').html('');
})

$(document).on('click', '#novo_cadastro', function(){
    $('#form-modal-pessoas').each (function(){
        this.reset();
    });
    $('#nome-header').text("Cadastro de pessoas");
    $('.cd_pessoa').val('');
})

$(document).on('click', '.nova_pessoa', function(){
    $('#form-modal-pessoas').each (function(){
        this.reset();
        $('#cd_pessoa').val('');
    });
    $('#nome-header').text("Cadastro de pessoas");
})

$('#atualiza-lista').click(function(){
    window.location.reload();
});

$('#open').click(function () {
    $('#painel_cadastro').hide();
    $('#painel_pesquisa').show();
    document.getElementById('nova_pesquisa').style.display = 'none';
    if($('#cd_pessoa_disabled').val() != null  && $('#cd_pessoa_disabled').val() != ''){
        $('#painel_cadastro').show();
        $('#painel_pesquisa').hide();
        preenche_pessoa($('#cd_pessoa_disabled').val());
        if($('#novo_prontuario').val() == 0)
            document.getElementById('seleciona-pesssoa').style.display = 'block';
        else {
            document.getElementById('painel-planos').style.display = 'block';
        }
    }
})


$('#nova_pesquisa').click(function () {
    document.getElementById('nova_pesquisa').style.display = 'none';
    $('#painel_cadastro').hide();
    $('#painel_pesquisa').show();
})

$('#novo_cadastro').click(function () {
    document.getElementById('nova_pesquisa').style.display = 'block';
    $('#painel_cadastro').show();
    $('#painel_pesquisa').hide();
})


/*-------------------------------------------------------------------------
|                                PESSOAS
|--------------------------------------------------------------------------*/

$('.ver_pessoa').click(function(){
    //Configura_PF_ou_PJ();
    if($(this).val() != "")
        preenche_pessoa($(this).val());
    else {
        preenche_pessoa($('#cd_pessoa_disabled').val());
    }

})

$('.nova_pessoa').click(function(){
    document.getElementById('form-modal-pessoas').reset();
    Configura_PF_ou_PJ();
})

/*-------------------------------------------------------------------------
|                                OPERADORES
|--------------------------------------------------------------------------*/
$('.remover-operador').click(function () {
    $('#id_operador').val($(this).val());
})

$('.remover-grupo').click(function () {
    $('#cd_grupo_op').val($(this).val());
})

/*-------------------------------------------------------------------------
|                                ATENDIMENTO
|--------------------------------------------------------------------------*/
$(document).on('click', '.remover-atendimento', function(){
    $.ajax({
        type: 'POST',
        url: dir + 'ajax/remover-atendimento',
        data: {"cd_atendimento": $(this).attr('data-atendimento'), "_token": token},
        dataType: 'json',
        success: function (data) {
            if (data.success == true) {
                window.location.reload();
            }
        }
    });
});

$(document).on('click', '#cadastrar-cartao-sus', function(){
    preenche_pessoa($(this).attr('data-pessoa'));
    $('#painel_cadastro').show();
    $('#painel_pesquisa').hide();
    document.getElementById('nova_pesquisa').style.display = 'block';
});

$(document).on('click', '#chama-painel', function(){
    $.ajax({
        type: 'POST',
        url: dir + 'ajax/chamar-painel',
        data: {"cd_prontuario": $('#cd_prontuario').val(), "cd_sala": $('#salas').val(), "cd_painel": $('#paineis').val(),"_token": token},
        dataType: 'json',
        success: function (data) {
            if (data.success == true) {
                $('#escolhe-sala').modal('hide');
                $('#sala-rodape').html($('#salas option:selected').text());
            }
        }
    });
});

/*$(".validar").blur(function() {
    var valor = $(this).val().replace(/\D/g,'');
    var retorno = true;

    if(/^(.)\1+$/.test(valor)){
        retorno = false;
    }
    if(retorno) {
        if ($('.id_pessoa').val() == 'F') {
            if(valor.length!=11){
                retorno = false;
            }
            else {
                retorno = validaCPF(valor);
            }
        }
        else {
            if(valor.length!=14){
                retorno = false;
            }
            else {
                retorno = validaCNPJ(valor);
            }
        }
    }
    if(!retorno){
        $(this).addClass("is-invalid");
    }
    if(retorno){
        $(this).removeClass("is-invalid");
    }
});*/

$('.form-no-submit').keypress(function(event) {
    if((event.which== 13) && ($(event.target)[0].localName != "textarea")) {
        event.preventDefault();
        return false;
    }
});

$('#cadastra-dependente').click(function() {
    $('#painel_cadastro').hide();
    $('#painel_pesquisa').show();
    document.getElementById('nova_pesquisa').style.display = 'none';
    $('#id').val('');
    $('#cd_pessoa').val('');
    $('#nm_pessoa').val('');
    $('#cd_beneficiario').val('');
    $('#modal-pesquisa').modal('show');
    document.getElementById('salvar').style.display='none';
    document.getElementById('cadastra-dependente').style.display='none';
    document.getElementById('salvar-dependente').style.display='block';
    document.getElementById('cd_plano').disabled = true;
});

$('#salvar-dependente').click(function() {
    $.ajax({
        type: 'POST',
        url: dir + 'ajax/salvar-dependente',
        data: {"cd_contrato": $('#cd_contrato').val(),"cd_pessoa": $('#cd_pessoa').val(),"cd_beneficiario": $('#cd_beneficiario').val(),"parentesco": $('#parentesco').val(), "_token": token},
        dataType: 'json',
        success: function (data) {
            if (data.success == true) {
                $('#mensagem').html("<div class='alert alert-info'><p>"+data.mensagem+"</p></div>");
                $("#mensagem").show();
                window.setTimeout(function () {
                    $("#mensagem").hide();
                    window.location.href = data.id_beneficiario;
                }, 3000);
            }
            else
            {
                $('#mensagem').html("<div class='alert alert-danger'><p>"+data.mensagem+"</p></div>");
                $("#mensagem").show();
                window.setTimeout(function () {
                    $("#mensagem").hide();
                }, 3000);
            }
        }
    });
});

function pesquisa_ocupacao(pesquisa) {
    $.ajax({
        type: 'POST',
        url: dir + 'ajax/profissionais/pesquisa-ocupacao',
        data: {"pesquisa":pesquisa, "_token": token},
        dataType: 'json',
        success: function (data) {
            var html = '';
            if (data.success) {
                for (var x = 0; x < data.retorno.length; x++) {
                    html += "<tr><td>" + data.retorno[x].cd_ocupacao + " - " +data.retorno[x].nm_ocupacao + "<button id='selecionar-ocupacao' data-nome='"+data.retorno[x].nm_ocupacao + "' value="+ data.retorno[x].cd_ocupacao + " class='btn btn-primary btn-sm pull-right'><span class='fa fa-check'></span></button></td></tr>";
                }
            }
            $('#retorno-search').html(html);
        }
    });
}
$('#ver-historico').click(function() {
    ver_historico($(this).val());
});

function ver_historico(cdPessoa) {
    $.ajax({
        type: 'POST',
        url: dir + 'ajax/atendimentos/ver-historico',
        data: {"cd_pessoa":cdPessoa, "_token": token},
        dataType: 'json',
        success: function (data) {
            var html = "";
            if(data.success==true) {
                for(var x=0;x<data.retorno.length;x++){
                    if(data.retorno[x].cd_procedimento !== null) {
                        html +=
                            "<tr>" +
                            "<td>" + (data.retorno[x].criacao != null ? convertDate(data.retorno[x].criacao.substring(0, 10))+" "+ data.retorno[x].criacao.substring(11, data.retorno[x].criacao.length): '') + "</td>" +
                            "<td>" + data.retorno[x].cd_procedimento + " - " + data.retorno[x].nm_procedimento + "</td>" +
                            "<td><a type='button' class='btn btn-primary btn-sm' target='_blank' href='" + dir + "relatorios/prontuario/" + data.retorno[x].cd_prontuario + "'><span class='fa fa-eye'></span></a></td>" +
                            "<td>" + data.retorno[x].responsavel + "</td>" +
                            "<td>" + (data.retorno[x].ocupacao != null ? data.retorno[x].ocupacao : '') + "</td>" +
                            "</tr>";
                    }
                }
                $('#corpo-historico').html(html);
            }
            else{
                $('#corpo-historico').html('');
            }
        }
    });
}
