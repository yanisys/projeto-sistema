var player = document.getElementById('player');
var snapshotCanvas = document.getElementById('snapshot');
var captureButton = document.getElementById('capture');
var videoTracks;

if (player.hasAttribute("controls")) {
    player.removeAttribute("controls")
}

$('#modal-capturar-foto').on('show.bs.modal', function (e) {
    document.getElementById('mostra-camera-tempo-real').style.display = 'block';
    document.getElementById('mostra-imagem-capturada').style.display = 'none';
    var handleSuccess = function(stream) {
        player.srcObject = stream;
        videoTracks = stream.getVideoTracks();
    };

    captureButton.addEventListener('click', function() {
        var context = snapshot.getContext('2d');
        context.drawImage(player, 0, 0, snapshotCanvas.width, snapshotCanvas.height);
    });

    navigator.mediaDevices.getUserMedia({video: true})
        .then(handleSuccess);
})

$('#modal-capturar-foto').on('hidden.bs.modal', function (e) {
    videoTracks.forEach(function(track) {track.stop()});
})

$('#capturar-imagem').on('click', function (e) {
    var snapshot = document.getElementById("snapshot");
    var imagem = snapshot.toDataURL();
    $('#foto-pessoa').attr('src', imagem);
    $('#arquivo-foto-pessoa').val(imagem);
})

$('#capture').on('click', function (e) {
    document.getElementById('mostra-camera-tempo-real').style.display = 'none';
    document.getElementById('mostra-imagem-capturada').style.display = 'block';
})

$('#limpar-capture').on('click', function (e) {
    document.getElementById('mostra-camera-tempo-real').style.display = 'block';
    document.getElementById('mostra-imagem-capturada').style.display = 'none';
})