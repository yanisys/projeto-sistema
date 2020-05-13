<?php $__env->startSection('conteudo'); ?>

    <div class="panel panel-default ">
        <div class="panel-body">
            <?php echo Form::open(["method" => "GET", "class" => "form-search"]); ?>

            <div class="container-fluid">
                <div class="col-sm-4">
                    <label>Paciente</label>
                    <?php echo Form::text('paciente',(!empty($_REQUEST['paciente']) ? $_REQUEST['paciente'] : ""),["name" => "paciente", "placeholder" => "Nome do paciente",'class'=>'form-control']); ?>

                </div>
                <div class="col-sm-4">
                    <label>Responsável</label>
                    <?php echo Form::text('responsavel',(!empty($_REQUEST['responsavel']) ? $_REQUEST['responsavel'] : ""),["name" => "responsavel", "id" => "responsavel", "placeholder" => "Nome do responsável",'class'=>'form-control']); ?>

                </div>
                <?php echo Form::submit('Buscar',['class'=>"btn btn-default margin-top-25"]); ?>

            </div>
            <?php echo Form::close(); ?>

        </div>
    </div>

    <div class="table-responsive font-12px">
        <table class="table table-bordered table-hover table-striped">
            <thead>
            <tr>
                <th class="text-center">Data/ hora chegada</th>
                <th class="text-center">Sexo</th>
                <th class="text-center">Paciente</th>
                <th class="text-center">Idade</th>
                <th class="text-center">Responsável</th>
                <th class='text-center' width="190px">Ação</th>
            </tr>
            </thead>
            <tbody>
            <?php if(!isset($prontuario) || $prontuario->IsEmpty()): ?>
                <tr><td colspan="5">Sem resultados</td></tr>
            <?php else: ?>
                <?php $__currentLoopData = $prontuario; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr id="classificacao-risco-<?php echo e($p->classificacao); ?>">
                        <td class='text-center'>
                            <?php echo e(formata_data_hora($p->created_at)); ?>

                        </td>
                        <td class='text-center'>
                            <?php if(($p->id_sexo == 'F')): ?>
                                <span class='fa fa-female' style='color:deeppink;'></span>
                            <?php else: ?>
                                <span class='fa fa-male' style='color:blue;'></span>
                            <?php endif; ?>
                        </td>
                        <td class='text-center'>
                            <?php echo e($p->nm_pessoa); ?>

                            <?php if(isset($p->nm_sala)): ?><span style="background-color: #ddd32f"> - CHAMADO NO PAINEL: <?php echo e($p->nm_sala); ?></span><?php endif; ?>
                        </td>
                        <td class='text-center'>
                            <?php echo e(isset($p->dt_nasc) ? calcula_idade($p->dt_nasc) : ""); ?>

                        </td>
                        <td class='text-center'>
                            <?php echo e($p->enfermeiro); ?>

                        </td>
                        <td align="center" width="9%">
                            <div class="btn-group" role="group">
                                <a href="<?php echo e(route('atendimentos/atendimento-medico').'/'.$p->cd_prontuario); ?>" title='Ir para o prontuário' class='btn btn-primary fa fa-stethoscope <?php echo e(verficaPermissaoBotao('recurso.atendimentos/atendimento-medico')); ?>'></a>
                                <a data-classificacao-atual='<?php echo e($p->classificacao); ?>' data-cd-prontuario='<?php echo e($p->cd_prontuario); ?>' title='Reclassificar risco' class="btn btn-warning fas fa-sync-alt abre-modal-reclassificar <?php echo e(verficaPermissaoBotao('recurso.atendimentos-acolhimento-salvar')); ?>"></a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('painel-modal'); ?>

    <div class="modal fade" id="modal-reclassificar"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Fechar</span></button>
                    <h3 class="modal-title" id="myModalLabel">Reclassificação de risco</h3>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="avaliacao">Motivo da reclassificação</label>
                                <textarea id="motivo-reclassificacao" placeholder="Informe o motivo da troca de classificação de risco. Mínimo de 10 caracteres." class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="avaliacao">Nova classificação</label>
                                <select id="classifica" class="form-control">
                                    <option value='1' style=background-color:blue;><?php echo e(arrayPadrao('classificar_risco')[1]); ?></option>
                                    <option value='2' style=background-color:forestgreen;><?php echo e(arrayPadrao('classificar_risco')[2]); ?></option>
                                    <option value='3' style=background-color:yellow;><?php echo e(arrayPadrao('classificar_risco')[3]); ?></option>
                                    <option value='4' style=background-color:orange;><?php echo e(arrayPadrao('classificar_risco')[4]); ?></option>
                                    <option value='5' style=background-color:red;><?php echo e(arrayPadrao('classificar_risco')[5]); ?></option>
                                    <option value='6' style=background-color:black;><?php echo e(arrayPadrao('classificar_risco')[6]); ?></option>
                                </select>
                                <input type="hidden" id="classificacao_atual">
                                <input type="hidden" id="cd_prontuario_reclassificacao">
                            </div>
                        </div>
                    </div>
                </div>
                <div id="mensagem"></div>
                <div class="modal-footer" style="text-align:left !important;">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-default pull-right" id="salvar-reclassificacao">Salvar</button>
                </div>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>



<?php $__env->startSection('custom_js'); ?>

    <script src=" <?php echo e(asset('public/js/jquery.cookie.js')); ?>"></script>

    <script src="<?php echo e(js_versionado('fila.js')); ?>"></script>

    <script src="<?php echo e(js_versionado('prontuario.js')); ?>"></script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.default', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>