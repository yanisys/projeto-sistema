<div id="modal-detalhes-evolucao" class="modal fade"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div id="dialog-detalhes-evolucao" class="modal-dialog">
        <div  class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="myModalLabel">Evolução</h3>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="descricao_solicitacao">Detalhes</label>
                            <textarea class="form-control input-sm" maxlength="1950" rows="6" <?php echo e($lista[0]->status == 'C' ? 'disabled' : ''); ?> id="descricao_evolucao"></textarea>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="sala">Selecione uma sala</label>
                    <select id="cd_sala" class="form-control">
                        <option value='0'>NÃO INFORMADO</option>
                        <?php $__currentLoopData = $salas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value='<?php echo e($s->cd_sala); ?>' <?php echo e((session()->get('cd_sala')==$s->cd_sala) ? "selected" : 0); ?>><?php echo e($s->nm_sala); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="cd_leito">Selecione um leito</label>
                    <select id="cd_leito" class="form-control">
                        <?php $__currentLoopData = arrayPadrao('leitos'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $l => $n): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value='<?php echo e($l); ?>' <?php echo e((session()->get('cd_sala')==$n) ? "selected" : ""); ?>><?php echo e($n); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger margin-top-zero pull-left" data-dismiss="modal">Sair</button>
                <?php if((session()->get('recurso.atendimentos-evolucao-salvar'))): ?>
                    <button type="button" id="salvar-evolucao" class="btn btn-success pull-right">Salvar</button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo e(js_versionado('modal_evolucao.js')); ?>" defer></script>
