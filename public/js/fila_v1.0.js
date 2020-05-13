/*-------------------------------------------------------------------------
          MODAL DE RECLASSIFICAÇÃO DE RISCO
|--------------------------------------------------------------------------*/
$(document).on('click', '.abre-modal-reclassificar', function() {
    $('#classificacao_atual').val($(this).attr('data-classificacao-atual'));
    $('#cd_prontuario_reclassificacao').val($(this).attr('data-cd-prontuario'));
    $('#motivo-reclassificacao').val('')

    $('#modal-reclassificar').modal({
        backdrop: 'static'
    });

    //document.getElementById("motivo-reclassificacao").focus();
})

$(document).on('click', '#salvar-reclassificacao', function(){
    motivo = $('#motivo-reclassificacao').val();
    classificacao_nova = $('#classifica').val();
    classificacao_atual = $('#classificacao_atual').val();
    cd_prontuario = $('#cd_prontuario_reclassificacao').val();

    var erro = '';
    if (motivo.length < 10) {
        erro += 'Motivo da Reclassificação deve ter no mínimo 10 caracteres!<br>';
    }
    if (classificacao_nova == classificacao_atual) {
        erro += 'Escolha uma classificação diferente da atual!<br>';
    }

    if (erro != '') {
        Swal('Atenção!',erro,'warning')
    } else {
        $.ajax({
            type: 'POST',
            url: dir + 'ajax/atendimentos/reclassificar',
            data: {
                "motivo": motivo,
                "classificacao_anterior": classificacao_atual,
                "classificacao_nova": classificacao_nova,
                'motivo': motivo,
                "cd_prontuario": cd_prontuario,
                "_token": token
            },
            dataType: 'json',
            success: function (data) {
                if (data.success == true) {
                    /*lista_atendimento_procedimento($('#cd_prontuario').val());
                    $('#modal-procedimentos').modal('hide');*/
                    location.reload();
                } else {
                    Swal('Atenção!',data.mensagem,'warning');
                }
            }
        });
    }
})
/*-------------------------------------------------------------------------
      LEMBRAR QUAIS GRUPOS ESTAVAM ABERTOS NA FILA DE ATENDIMENTO
|--------------------------------------------------------------------------*/
$(document).ready(function () {
    //carrega os abertos de um cookie quando carrega a pagina
    var gruposAbertos = $.cookie('gruposAbertos');
    if (gruposAbertos == null) {
        var gruposAbertos = [];
    } else {
        gruposAbertos = JSON.parse(gruposAbertos);
    }

    //adiciona no array quando abre
    $("#accordion").on('shown.bs.collapse', function (e) {
        var active = e.target.id;

        if(gruposAbertos.indexOf(active) == -1) {
            gruposAbertos.push(active);
        }

        $.cookie('gruposAbertos', JSON.stringify(gruposAbertos));
    });
    //remove do array quando abre
    $('#accordion').on('hidden.bs.collapse', function (e) {
        var active = e.target.id;

        for( var i = 0; i < gruposAbertos.length; i++){
            if ( gruposAbertos[i] == active) {
                gruposAbertos.splice(i, 1);
            }
        }
        $.cookie('gruposAbertos', JSON.stringify(gruposAbertos));
    })
    for (index = 0; index < gruposAbertos.length; ++index) {
        $("#" + gruposAbertos[index]).addClass("in");
    }
});