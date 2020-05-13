window.setInterval('atualiza_painel()', 10001);

function atualiza_painel(){
    $.ajax({
        type: 'POST',
        url: dir + 'ajax/atualizar-painel',
        data: {"cd_painel": $('#cd_painel').val(),"_token": token},
        dataType: 'json',
        success: function (data) {
            var html ="";
            if (data.success == true) {
                for(var x=0;x<data.retorno.length;x++)
                {
                    if(x==0)
                        html += "<tr class='chamados chamado_atual'>";
                    else
                        html += "<tr class='chamados'>";

                    html += "<td class='text-center'>" + data.retorno[x]['nm_pessoa'] + "</td>";
                    html += "<td class='text-center'>" + data.retorno[x]['nm_sala'] + "</td>";
                    html += "<td class='text-center'>" + data.retorno[x]['horario'] + "</td>";
                    html += "</tr>";
                }
                if(data.retorno[0]['chamado_voz']==0){
                    var nome = data.retorno[0]['nm_pessoa'];
                    var sala = data.retorno[0]['nm_sala'];
                    TocarMusica();
                    if(data.chamado_voz == 'voz') {
                        setTimeout(function () {
                            chamado_voz(nome, sala);
                        }, 4000);
                    }
                }

            }
            $('#painel').html(html);
        }
    });
}

function TocarMusica(){
    var audio1 = new Audio();
    audio1.src = dir+"public/sounds/bip.mp3";
    audio1.play();
}

function chamado_voz(nome, sala) {
    var text;
    var voices;
    text = new SpeechSynthesisUtterance();
	
	var synth = window.speechSynthesis;
	var voices = synth.getVoices();
	//alert('teste '  + voices.length);
	
    //text.voiceURI = 'Google português do Brasil-pt-BR'; //discovered after dumping getVoices()
    //text.lang = "pt-BR";
    //voices = window.speechSynthesis.getVoices();
    //alert(voices.length);
    for(var x=0;x<voices.length;x++) {
       // alert(voices[x].name + " " + voices[x].lang + " " + x);
        if (text.voice == null && voices[x].lang == 'pt-BR'){
            text.voice = voices[x];
            //alert('setando: ' + voices[x].name );

        }
    }

    text.localService = true;
    //text.voice = voices[15]; //index to the voiceURI. This index number is not static.
    text.text = nome+", você será atendido na "+sala;

    window.speechSynthesis.speak(text);

    $('#resposta').html('');

    //responsiveVoice.speak('Senhor Felipe Tassinari, por favor compareça a sala 2', 'Brazilian Portuguese Female');
}

//
//var elem = document.documentElement;
var elem = document.getElementById('chamar');
function openFullscreen() {
    if (elem.requestFullscreen) {
        elem.requestFullscreen();
    } else if (elem.mozRequestFullScreen) { /* Firefox */
        elem.mozRequestFullScreen();
    } else if (elem.webkitRequestFullscreen) { /* Chrome, Safari & Opera */
        elem.webkitRequestFullscreen();
    } else if (elem.msRequestFullscreen) { /* IE/Edge */
        elem.msRequestFullscreen();
    }
}



