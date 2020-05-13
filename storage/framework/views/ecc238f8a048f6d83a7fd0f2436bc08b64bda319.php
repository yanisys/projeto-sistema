<div id="modal-capturar-foto" class="modal fade"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div id="dialog-capturar-foto" class="modal-dialog">
        <div  class="modal-content">
            <div class="modal-header">
                <button class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Fechar</span></button>
                <h3 class="modal-title" id="myModalLabel">Capturar imagem</h3>
            </div>
            <div class="panel-body">
                <div id='mostra-camera-tempo-real' style="display: block" class="col-md-10">
                    <video id="player" controls autoplay></video>
                </div>
                <div id='mostra-imagem-capturada' style="display: none" class="col-md-10">
                    <canvas class="pull-left" id="snapshot"></canvas>
                </div>
            </div>
            <div class="panel-footer" style="text-align:left !important;">
                <button type="button" class="btn btn-primary" title="Capturar imagem" id="capture"><span class="fa fa-camera"></span></button>
                <button type="button" class="btn btn-warning" title="Limpar imagem" id="limpar-capture"><span class="fas fa-trash"></span></button>
                <button type="button" id="capturar-imagem" class="btn btn-success margin-top-zero pull-right" data-dismiss="modal">Concluído</button>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo e(js_versionado('webcam.js')); ?>" defer></script>
