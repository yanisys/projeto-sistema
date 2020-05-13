<div class="modal" id="modal-cirurgias-previas"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" id="dialog-cirurgias-previas">
        <div class="modal-content" id="content-cirurgias-previas">
            <div class="modal-header">
                <h3 class="modal-title" id="myModalLabel">Cadastro de cirurgias prévias</h3>
            </div>
            <div class="modal-body">
                <div class="panel-body">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="sala">Data da cirurgia</label>
                            <input type="text" id="data_cirurgia" class="form-control mask-data">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="painel">Descrição da cirurgia</label>
                            <textarea rows="2" id="descricao_cirurgia" maxlength="200" class="form-control"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel-footer" style="text-align:left !important;">
                <button type="button" class="btn btn-danger margin-top-zero" data-dismiss="modal">Cancelar</button>
                <button id="btn-cadastra-cirurgia-previa" class="btn btn-primary  pull-right">Cadastrar</button>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo e(js_versionado('modal_cirurgias_previas.js')); ?>" defer></script>