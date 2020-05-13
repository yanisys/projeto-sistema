$('#bt-capturar-biometria').click(function() {
    CapturarDigital();
});

$('#bt-comparar-biometria').click(function() {
    CompararDigital();
});

function CapturarDigital() {
    //jQuery.support.cors = true;
    $.ajax({
        url: 'http://localhost:9000/api/public/v1/captura/Enroll/1',
        type: 'GET',
        success: function (data) {
            if (data !== "") {
                $('#imagem_digital').attr('src',dir+'public/images/digital.jpg');
                document.getElementById('p_impressao_digital').value = data;
            }
        }
    })
}

function CompararDigital() {
    //jQuery.support.cors = true;
    var digital = document.getElementById('p_impressao_digital').value;
    $.ajax({
        url: 'http://localhost:9000/api/public/v1/captura?Digital=' + digital,
        type: 'GET',
        success: function (data) {
            if(data == "OK"){
                swal({
                    type: 'success',
                    title: 'Confirmado',
                    text: 'Digital confirmada com sucesso!',
                    showConfirmButton: true,
                    timer: 2000,
                })
            }
            else {
                swal({
                    type: 'error',
                    title: 'Erro',
                    text: 'Digital não confirmada!',
                    showConfirmButton: true,
                    timer: 2000,
                })
            }
        }
    });
}