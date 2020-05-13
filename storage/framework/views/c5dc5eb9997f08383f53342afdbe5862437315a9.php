<div class="modal" id="modal-medicamentos-em-uso"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" id="dialog-medicamentos-em-uso">
        <div class="modal-content" id="content-medicamentos-em-uso">
            <div class="modal-header">
                <h3 class="modal-title" id="myModalLabel">Cadastro de medicamentos em uso</h3>
            </div>
            <div class="modal-body">
                <div class="panel-body">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="painel">Descrição do medicamento</label>
                            <textarea rows="2" id="descricao_medicamento_em_uso" maxlength="200" class="form-control"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel-footer" style="text-align:left !important;">
                <button type="button" class="btn btn-danger margin-top-zero" data-dismiss="modal">Cancelar</button>
                <button id="btn-cadastra-medicamento-em-uso" class="btn btn-primary  pull-right">Cadastrar</button>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo e(js_versionado('modal_medicamentos_em_uso.js')); ?>" defer></script>