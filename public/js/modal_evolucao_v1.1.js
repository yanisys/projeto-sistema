$('#salvar-evolucao').click(function() {
    add_atendimento_evolucao($('#cd_prontuario').val());
});

function add_atendimento_evolucao() {
    if($('#descricao_evolucao').val() != '');
    {
        if ($('#cd_sala').val() > 0) {
            document.getElementById('salvar-evolucao').disabled = true;
            $.ajax({
                type: 'POST',
                url: dir + 'ajax/atendimentos/add-atendimento-evolucao',
                data: {
                    "cd_prontuario": $('#cd_prontuario').val(),
                    "cd_sala": $('#cd_sala').val(),
                    "cd_leito": $('#cd_leito').val(),
                    'descricao_evolucao': $('#descricao_evolucao').val(),
                    "_token": token
                },
                dataType: 'json',
                success: function (data) {
                    if (data.success) {
                        document.getElementById('salvar-evolucao').disabled = false;
                        lista_atendimento_evolucao($('#cd_prontuario').val());
                    }
                    $('#descricao_evolucao').val('');
                    $('#modal-detalhes-evolucao').modal('hide');
                },
                error: function () {
                    swal({
                        type: 'error',
                        title: 'Atenção',
                        text: "Ocorreu um erro ao inserir a evolução. Verifique os dados e tente novamente.",
                        showConfirmButton: true,
                    })
                    document.getElementById('salvar-evolucao').disabled = false;
                }
            });
        }
        else {
            swal({
                type: 'error',
                title: 'Atenção',
                text: "Você deve informar a sala e o leito(quando houver)",
                showConfirmButton: true,
            })
        }
    }
}
