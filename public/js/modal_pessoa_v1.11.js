/*-------------------------------------------------------------------------
| Botão padrão para abrir o modal de cadastro/pesquisa de pessoas
|    PARAMETROS:
     - data-modo:       pesquisar - abre o modal na pesqusia,
                        editar    - abre o modal no cadastro
                        novo      - abre o modal no cadastro e limpa o form
     - data-cd-pessoa:  pessoa.cd_pessoa a ser carregado na parte de cadastro do modal
     - data-nao-validar-endereco: não precisa informar valor para este parametro,
                                  se estiver informado o endereço não será validado

    EXEMPLO:
    <button class="btn-modal-pessoa"
        data-modo="editar"
        data-nao-validar-endereco
        data-cd-pessoa=123

    >botao</button>
|--------------------------------------------------------------------------*/
$('.btn-modal-pessoa').click(function(){
    var modo =($(this).attr('data-modo'));
    var cd_pessoa =($(this).attr('data-cd-pessoa'));
    var nao_validar_endereco =($(this).attr('data-nao-validar-endereco'));

    if (nao_validar_endereco != null) {
        $('#nao_validar_endereco').val('1');
    } else {
        $('#nao_validar_endereco').val('0');
    }

    $('#form-modal-pessoas').each (function(){
        this.reset();
    });
    $('.cd_pessoa').val('');
    if (modo == 'pesquisar') {
        $('#painel_cadastro').hide();
        $('#painel_pesquisa').show();
    } else if(modo == 'editar' || modo == 'novo') {
        $('#painel_cadastro').show();
        $('#painel_pesquisa').hide();
    } else {
        alert('Parâmetro data-modo do modal de pessoas está incorreto! ');
    }
    Configura_PF_ou_PJ();
    if (modo == 'editar' && cd_pessoa > 0) {
        preenche_pessoa(cd_pessoa);
    }

    $('#modal-pesquisa').modal({
        backdrop: 'static'
    })
    document.getElementById("nome_pesquisa").focus();
})

$('#nome_pesquisa').on('keydown', function(e) {
    if (e.which == 13) {
        Pesquisar_Pessoa($('#nome_pesquisa').val(),'F');
    }
});

function Configura_PF_ou_PJ() {
    ConfiguraPFouPJ ($('#p_id_pessoa').val());
}

function ConfiguraPFouPJ($idPessoa) {
    if ($idPessoa == 'F') {
        $('.label_cnpj_cpf').html("CPF<span style='color:red'>*</span>");
        $('.cnpj_cpf').mask('000.000.000-00');
        $('#tab_titulo_cpf_cnpj').html("Pessoa Física");
        $('#tab_titulo_cpf_cnpj').attr('href', "#tab_pfisica");
        $('#usar_tab_biometria').show();
        document.getElementById('responsaveis').style.display = 'block';
    } else {
        $('.label_cnpj_cpf').html("CNPJ<span style='color:red'>*</span>");
        $('.cnpj_cpf').mask('00.000.000/0000-00');
        $('#tab_titulo_cpf_cnpj').html("Pessoa Jurídica");
        $('#tab_titulo_cpf_cnpj').attr('href', "#tab_pjuridica");
        $('#usar_tab_biometria').hide();
        document.getElementById('responsaveis').style.display = 'none';
    }
}

function preenche_pessoa(cdPessoa){
    $.ajax({
        type: 'POST',
        url: dir + 'ajax/pessoas/preenche-pessoa',
        data: {"cd_pessoa": cdPessoa, "_token": token},
        dataType: 'json',
        success: function (data) {
            if (data.success == true) {
                var chaves = Object.keys(data.pessoa).toString();
                chaves = chaves.split(',');
                for(var x=0;x<chaves.length;x++){
                    $('#p_'+chaves[x]).val(data.pessoa[chaves[x]]);
                }

                $('.cd_pessoa').val(cdPessoa);
                $('#painel-planos').val(cdPessoa);
                $('#nome-header').text(data.pessoa['nm_pessoa']);
                $('#p_dt_nasc').val(convertDate(data.pessoa['dt_nasc']));
                $('#p_dt_nasc_contato').val(convertDate(data.pessoa['dt_nasc_contato']));
                if($('#p_id_raca_cor').val() == 5) {
                    document.getElementById('div_etnia').style.display = 'block';
                }
                else {
                    document.getElementById('div_etnia').style.display = 'none';
                }

                ConfiguraPFouPJ(data.pessoa['id_pessoa']);
                if(data.pessoa['nm_arquivo'] != null)
                    $('#foto-pessoa').attr('src',dir+"storage/app/images/pessoas/"+data.pessoa['cd_pessoa']+"/principal/"+data.pessoa['nm_arquivo']);
                else
                    $('#foto-pessoa').attr('src',dir+'public/images/sem_foto.jpg');
                if(data.pessoa['impressao_digital'] != null && data.pessoa['impressao_digital'] != '')
                    $('#imagem_digital').attr('src',dir+'public/images/digital.jpg');
                else
                    $('#imagem_digital').attr('src',dir+'public/images/sem_digital.jpg');
                //Configura_PF_ou_PJ();
                //document.getElementById('header_nome').innerText = data.pessoa['nm_pessoa'];
                //$('#nm_estabelecimento').val(cdPessoa);
            }
        }
    });
}
$('#p_id_raca_cor').on('change', function(e){
    if($(this).val() == 5) {
        document.getElementById('div_etnia').style.display = 'block';
    }
    else {
        document.getElementById('div_etnia').style.display = 'none';
        $('#p_cd_etnia').val(null);
    }
});
$('#id_pessoa').on('change', function(e){
    Configura_PF_ou_PJ();
});
$('#p_id_pessoa').on('change', function(e){
    Configura_PF_ou_PJ();
});

$("#cep").blur(function() {
    Preenche_Endereco($("#cep").val(),'#endereco','#bairro','#localidade','#uf');
});

$("#p_cep").blur(function() {
    Preenche_Endereco($("#p_cep").val(),'#p_endereco','#p_bairro','#p_localidade','#p_uf');
});

$("#cep_aux").blur(function() {
    Preenche_Endereco($("#cep_aux").val(),'#end_aux','#bairro_aux','#localidade_aux','#uf_aux');
});

/*$("#p_cd_beneficiario").blur(function() {
    alert(validaCns($("#p_cd_beneficiario").val()));
});
*/
function Preenche_Endereco(id_cep, id_endereco, id_bairro, id_localidade, id_uf){
    if ($(id_localidade).val() == '' && id_cep != '') {
        var cep = id_cep.replace(/[^0-9]/g, '');
        $(id_endereco).attr('disabled', 'disabled');
        $(id_bairro).attr('disabled', 'disabled');
        $(id_localidade).attr('disabled', 'disabled');
        $(id_uf).attr('disabled', 'disabled');
        $('#bt-salvar').attr('disabled', 'disabled');
        $.ajax({
            type: 'POST',
            url: dir + 'ajax/auto-preencher-endereco',
            data: {"cep": cep, "_token": token},
            dataType: 'json',
            success: function (data) {

                if (data.success == true) {
                    $(id_endereco).val(data.dados.logradouro);
                    $(id_bairro).val(data.dados.bairro);
                    $(id_localidade).val(data.dados.localidade);
                    $(id_uf).val(data.dados.uf);
                }
                $(id_endereco).removeAttr('disabled');
                $(id_bairro).removeAttr('disabled');
                $(id_localidade).removeAttr('disabled');
                $(id_uf).removeAttr('disabled');
                $('#bt-salvar').removeAttr('disabled');
            },
            error: function (xhr, ajaxOptions, thrownError) {
                $(id_endereco).removeAttr('disabled');
                $(id_bairro).removeAttr('disabled');
                $(id_localidade).removeAttr('disabled');
                $(id_uf).removeAttr('disabled');
                $('#bt-salvar').removeAttr('disabled');
            }
        });
    }
}

$(".salvar-pessoa").click(function() {
    $(".salvar-pessoa").attr('disabled', 'disabled');
    $.ajax({
        type: 'POST',
        url: dir + 'ajax/pessoas/cadastrar',
        data: $('#form-modal-pessoas').serialize(),
        dataType: 'json',
        success: function (data) {
            var mensagem = "";
            var class_alert="";
            var titulo="";
            if (data.success == true) {
                class_alert = "info";
                mensagem = data.status;
                titulo = "Sucesso !";
                $('.cd_pessoa').val(data.pessoa);
                $('#nm_pessoa').val($('#p_nm_pessoa').val());
                $('#nm_estabelecimento').val($('#p_nm_pessoa').val());
                if (document.getElementById('novo_prontuario') != null) {
                    if ($('#novo_prontuario').val() == 0) {
                        //document.getElementById('seleciona-pesssoa').style.display = 'block';
                    } else {
                        document.getElementById('painel-planos').style.display = 'block';
                    }
                } else {
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                }
            }
            else {
                class_alert = "error";
                titulo = "Erro!";
                $.each(data.erros, function(index,value) {
                    mensagem += "<li>"+value+"</li>";
                });
            }
            swal({
                title: titulo,
                html: mensagem,
                type: class_alert,
                confirmButtonText: 'OK'
            })
            $(".salvar-pessoa").removeAttr('disabled');
        }
    });

});

function Pesquisar_Pessoa(nome, tp_pessoa){
    $.ajax({
        type: 'POST',
        url: dir + 'ajax/buscar-pessoa',
        data: {"nome": nome, "tp_pessoa":tp_pessoa, "_token": token},
        dataType: 'json',
        success: function (data) {
            if (data.success == true) {
                var html;
                var html_head=
                    "<tr>" +
                    "<th>Nome</th>";
                if(tp_pessoa == "F") {
                    html_head += "<th>CPF</th>" +
                        "<th>Data de nascimento</th>" +
                        /*"<th>Foto</th>"+*/
                        "<th>Nome da mãe</th>";
                }
                else{
                    html_head += "<th>CNPJ</th>" +
                        "<th>Cidade</th>" +
                        "<th>Telefone</th>";
                }
                html_head += "<th>#</th>" +
                    "</tr>";
                for(var x=0;x<data.dados.length;x++){
                    var dt_nasc = (data.dados[x].dt_nasc != null ? convertDate(data.dados[x].dt_nasc) : '');

                    html +="<tr>" +
                        "<td class='text-center'>"+data.dados[x].nm_pessoa+"</td>"+
                        "<td class='text-center'>"+data.dados[x].cnpj_cpf+"</td>";
                    if(tp_pessoa == "F") {
                        html += "<td class='text-center'>" + dt_nasc + "</td>" +
                      /*  "<td class='text-center'>";
                        if(data.dados[x]['nm_arquivo'] != null) {
                            html += "<img src='"+dir + "storage/app/images/pessoas/" + data.dados[x].cd_pessoa + "/principal/" + data.dados[x].cd_pessoa+"_thumb.png'>";
                        }
                        else {
                            html += "<img src='"+dir+"public/images/sem_foto_thumb.jpg'>";
                        }
                        html += "</td>" +  */
                        "<td class='text-center'>" + data.dados[x].nm_mae + "</td>";
                    }
                    else{
                        html += "<td class='text-center'>" + data.dados[x].localidade + "</td>" +
                            "<td class='text-center'>" + data.dados[x].nr_fone1 + "</td>";
                    }
                    html += "<td class='text-center'>" +
                        "<button data-pessoa='"+data.dados[x].nm_pessoa+"' data-email='"+data.dados[x].ds_email+"' value="+data.dados[x].cd_pessoa+ " class='btn btn-primary btn-sm selecionar'>Detalhes</button>";
                    if($('#novo_prontuario').val() == 0) {
                        html += "<button data-pessoa='"+data.dados[x].nm_pessoa+"' data-email='"+data.dados[x].ds_email+"' value="+data.dados[x].cd_pessoa+ " class='btn btn-primary btn-sm selecionar-pessoa'>Selecionar</button>";
                    }
                    else{
                        html += "<button id='abrir-painel-planos' data-toggle='modal' data-atendimento='true' value='"+data.dados[x].cd_pessoa+"' data-target='#novo-atendimento' class='btn btn-primary btn-sm'>Novo atendimento</button></td>";
                    }
                    "</tr>";
                }
                $('#head-table-pessoas').html(html_head);
                $('#table-pessoas').html(html);
            }
        }
    });
}

$('#realizar-pesquisa').click(function () {
    if(($('#nome_pesquisa').val().length > 3)) {
        document.getElementById('realizar-pesquisa').disabled=true;
        Pesquisar_Pessoa($('#nome_pesquisa').val(), $('#p_id_pessoa').val());
        document.getElementById('realizar-pesquisa').disabled=false;
    }
});

$('#btn-pesquisar-cep').click(function(){
    var uf = $('#uf_pesquisa_cep').val();
    var cidade = $('#cidade_pesquisa_cep').val();
    var endereco = $('#endereco_pesquisa_cep').val();
    var erro = '';

    if (uf.trim().length != 2) {
      erro += 'Informe uma UF<br>';
    }
    if (cidade.trim().length < 3) {
      erro += 'Campo Cidade deve ter no mínimo 3 caracteres!<br>';
    }
    if (endereco.trim().length < 3) {
        if(endereco == '')
            endereco = 'localidade';
        else
            erro += 'Campo Endereço deve ter no mínimo 3 caracteres!';
    }
    if (erro != '') {
        Swal('Atenção!',erro,'warning')
    } else {
        $("#btn-pesquisar-cep").attr('disabled', 'disabled');
        $('#table-cep').html('<tr><td colspan="6">Pesquisando CEP, por favor aguarde...</td></tr>');
        $.ajax({
            type: 'POST',
            url: dir + 'ajax/pesquisar-cep',
            data: {"uf": uf, "cidade": cidade, "endereco": endereco, "_token": token},
            dataType: 'json',
            success: function (data) {
                if (data.success == true) {
                    var html = '';
                    if (data.dados.length == 0){
                        html='<tr><td colspan="6">Sem resultados para exibir....</td></tr>';
                    } else {
                        for (var x = 0; x < data.dados.length; x++) {
                            html += "<tr>" +
                                "<td>" + data.dados[x].cep + "</td>" +
                                "<td>" + data.dados[x].logradouro + "</td>" +
                                "<td>" + data.dados[x].complemento + "</td>" +
                                "<td>" + data.dados[x].bairro + "</td>" +
                                "<td>" + data.dados[x].localidade + '-' + data.dados[x].uf + "</td>" +
                                "<td> <button data-cep= " + data.dados[x].cep +
                                " data-logradouro= '" + data.dados[x].logradouro +
                                "' data-bairro= '" + data.dados[x].bairro +
                                "' data-localidade= '" + data.dados[x].localidade +
                                "' data-uf= '" + data.dados[x].uf +
                                "' data-complemento= '" + data.dados[x].complemento +
                                "' class='btn btn-primary btn-sm selecionar-cep'><span class='fa fa-check'></span></button> " +
                                "</td>" +
                                "</tr>";
                        }
                    }
                    $('#table-cep').html(html);
                }
                $("#btn-pesquisar-cep").removeAttr('disabled');
            },
            error: function (xhr, ajaxOptions, thrownError) {
                $('#table-cep').html('<tr><td colspan="6">Ocorreu um erro ao pesquisar por este endereço. Verifique os dados informadoes e tente novamente.</td></tr>');
                $("#btn-pesquisar-cep").removeAttr('disabled');
            }
        });
    }

})

$(document).on('click', '.selecionar-cep', function(){
    $('#modal-pesquisa-cep').modal('hide');
    $('#p_cep').val($(this).attr('data-cep'));
    $('#p_endereco').val($(this).attr('data-logradouro'));
    $('#p_endereco_compl').val($(this).attr('data-complemento'));
    $('#p_bairro').val($(this).attr('data-bairro'));
    $('#p_localidade').val($(this).attr('data-localidade'));
    $('#p_uf').val($(this).attr('data-uf'));
});


/* DAQUI PRA BAIXO NAO FORAM ORGANIZADAS AINDA */

$(document).on('click', '.selecionar', function(){
    document.getElementById('nova_pesquisa').style.display = 'block';
    $('#painel_pesquisa').hide();
    $('#painel_cadastro').show();
    if($('#novo_prontuario').val() == 0) {
        var email = ($(this).attr('data-email'));
        if (email == 'null')
            email = "";
        $('#cd_pessoa').val($(this).val());
        $('#cd_pessoa_disabled').val($(this).val());
        $('#nm_pessoa').val($(this).attr('data-pessoa'));
        $('#nm_pessoa_disabled').val($(this).attr('data-pessoa'));
        $('#email').val(email);
        $('#nm_estabelecimento').val($(this).attr('data-pessoa'));
        $('#nm_estabelecimento_disabled').val($(this).attr('data-pessoa'));
        document.getElementById('seleciona-pesssoa').style.display = 'block';
    }
    else{
        document.getElementById('painel-planos').style.display = 'block';
    }
    preenche_pessoa($(this).val());
});

$('.seleciona-pessoa').click(function () {
    $('#modal-pesquisa').modal('hide');
});

$(document).on('click', '.selecionar-pessoa', function(){
    $('#modal-pesquisa').modal('hide');
    var email = ($(this).attr('data-email'));
    if (email == 'null')
        email = "";
    $('.cd_pessoa').val($(this).val());
    $('#cd_pessoa').val($(this).val());
    $('#cd_pessoa_disabled').val($(this).val());
    $('#nm_pessoa').val($(this).attr('data-pessoa'));
    $('#nm_pessoa_disabled').val($(this).attr('data-pessoa'));
    $('#email').val(email);
    $('#nm_estabelecimento').val($(this).attr('data-pessoa'));
    $('#nm_estabelecimento_disabled').val($(this).attr('data-pessoa'));
});



function validaCns(cns)
{
    cns = cns.trim();
    var soma = 0;
    if (cns == null || cns.length != 15 || (parseInt(cns.substring(0,1)) > 2 && parseInt(cns.substring(0,1)) < 7))
        return false;

    if (cns.substring(0,1) == 1 || cns.substring(0,1) == 2)
    {
        var resto, dv;
        var resultado = "";
        var pis = cns.substring(0, 11);
        for(var x=0;x<11;x++)
            soma = soma + (15 - x) * parseInt(pis.substring(x,x+1))

        resto = parseFloat(soma % 11);
        dv = 11 - resto;
        if (dv == 11)
            dv = 0;

        if (dv == 10){
            soma += 2;
            resto = soma % 11;
            dv = 11 - resto;
            resultado = pis + "001" + dv.toString();
        }
        else
            resultado = pis + "000" + dv.toString();

        return (cns === resultado ? true : false);
    }

    if (cns.substring(0,1) == 7 || cns.substring(0,1) == 8 || cns.substring(0,1) == 9){
        for(var x=0;x<15;x++)
            soma = soma + (15 - x) * parseInt(cns.substring(x,x+1));

        return (parseFloat(soma % 11) == 0 ? true : false);
    }
}