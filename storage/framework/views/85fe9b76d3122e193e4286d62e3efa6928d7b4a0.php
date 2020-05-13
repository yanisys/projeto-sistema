<?php $__env->startSection('conteudo'); ?>
    <?php if($errors->any()): ?>
        <div class="alert alert-danger collapse in" id="collapseExample">
            <h4 class="alert-heading">Os seguintes erros foram encontrados:</h4>
            <ul>
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li> <?php echo e($error); ?> </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
            <hr>
            <p class="mb-0">Por favor, verifique e tente novamente.</p>
            <p><a class="btn btn-primary" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="true" aria-controls="collapseExample">Fechar</a></p>
        </div>
    <?php endif; ?>

    <div class="panel panel-primary">
        <div class="panel-heading">
            <?php if((session()->get('recurso.atendimentos-procedimentos-salvar'))): ?>
                <button id='chamar-painel' data-toggle='modal' data-target="#escolhe-sala" class="btn btn-primary pull-right">Chamar painel</button>
            <?php endif; ?>
            <button class="btn btn-primary pull pull-right ver_pessoa" type="button" data-toggle="modal" value="<?php echo e($lista[0]->cd_pessoa); ?>"  data-target="#modal-pesquisa">Ver cadastro</button>
            <button type="button" class="btn btn-primary pull pull-right" id="ver-historico" value='<?php echo e((isset($lista[0]->cd_pessoa) ? $lista[0]->cd_pessoa : "")); ?>' data-toggle="modal" data-target="#modal-historico">Histórico</button>
            <a href="<?php echo e(route('atendimentos/atendimento-medico').'/'.$lista[0]->cd_prontuario); ?>" title='Ir para o prontuário' class='btn btn-primary pull-right <?php echo e(verficaPermissaoBotao('recurso.atendimentos/atendimento-medico')); ?>'>Prontuário</a>
            <a href="<?php echo e(route('relatorios/prontuario').'/'.$lista[0]->cd_prontuario); ?>" target="_blank" class="btn btn-primary pull-right"><span class="fa fa-print"></span></a>
            <input id='cd_prontuario' type="hidden" value="<?php echo e($lista[0]->cd_prontuario); ?>">
            <h4>Procedimentos</h4>
        </div>
        <div class="panel-body">
            <div class="panel-heading">Procedimentos</div>
            <div class="panel-body panel-acolhimento">
                <?php if((session()->get('recurso.atendimentos-procedimentos-adicionar'))): ?>
                    <input data-toggle="modal" class="btn btn-info btn-sm" data-target="#modal-procedimentos" data-permissoes="true" value="Adicionar" type="button">
                <?php endif; ?>

                <div class="panel-body">
                    <div class="row">
                        <div class='table-responsive'>
                            <table class='table table-bordered table-hover table-striped'>
                                <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Nome procedimento</th>
                                    <th>Situação</th>
                                    <th class='text-center' width="100px">Ação</th>
                                </tr>
                                </thead>
                                <tbody id='lista-procedimentos' data-permissoes="true" class="lista-procedimentos">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel-footer fixed-panel-footer">
            <div class="pull-left"><?php echo e("Responsável: ".title_case(Session()->get('profissional')).' ' .Session()->get('nome')); ?>

                <br>
                <?php $__currentLoopData = $salas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div id="sala-rodape"><?php echo e(($s->cd_sala == Session()->get('cd_sala')) ? title_case($s->ds_sala) : ''); ?></div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>

    <?php echo $__env->make('pessoas/modal-pessoas', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make('atendimentos/modal-procedimentos', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make('atendimentos/modal-pesquisa', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make('atendimentos/modal-historico', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('painel-modal'); ?>
    <div id="modal-detalhes-procedimento" class="modal fade"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div id="dialog-detalhes-procedimento" class="modal-dialog">
            <div  class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="myModalLabel">Detalhes do procedimento</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="status">Procedimento</label>
                                <input type="text" class="form-control input-sm" disabled="disabled" id="d_procedimento">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="status">Solicitante</label>
                                <input type="text" class="form-control input-sm" disabled="disabled" id="d_user_solicitacao">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="status">Data/ Hora Solicitação</label>
                                <input type="text" class="form-control input-sm" disabled="disabled" id="d_hr_solicitacao">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="descricao_solicitacao">Detalhes da solicitação</label>
                                <textarea class="form-control input-sm" disabled="disabled" id="d_solicitacao"></textarea>
                            </div>
                        </div>
                    </div>
                    <?php echo e(Form::open(['id' => 'form-detalhes-procedimento', 'class' => 'form-no-submit'])); ?>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="descricao_execucao">Detalhes da execução</label>
                                <?php echo e(Form::textarea('d_execucao',"",["name" => "descricao_execucao", "maxlength" => "1000", "rows"=>"3", "id" => "d_execucao",  'class'=> ($errors->has("descricao_execucao") ? "form-control input-sm is-invalid" : "form-control input-sm")])); ?>

                                <?php echo e(Form::hidden('d_id_atendimento_procedimento','',["name" => "id_atendimento_procedimento", "id" => "d_id_atendimento_procedimento"])); ?>

                                <?php echo e(Form::hidden('id_status','A',["name" => "id_status", "id" => "id_status"])); ?>

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="status">Realizado por</label>
                                <input class="form-control input-sm" type="text" disabled="disabled" id="d_user_execucao">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="status">Data/ Hora execução</label>
                                <input class="form-control input-sm" type="text" disabled="disabled" id="d_hr_execucao">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-footer" style="text-align:left !important;">
                    <button type="button" class="btn btn-danger margin-top-zero" data-dismiss="modal">Sair</button>
                    <?php if((session()->get('recurso.atendimentos-procedimentos-salvar'))): ?>
                        <?php echo e(Form::submit('Salvar',['id'=>'salvar-procedimento', 'class'=>"btn btn-success pull-right"])); ?>

                        <?php echo e(Form::submit('Encerrar',['id'=>'finalizar-procedimento', 'class'=>"btn btn-success pull-right"])); ?>

                    <?php endif; ?>
                </div>
                <?php echo e(Form::close()); ?>

            </div>
        </div>
    </div>

    <div class="modal fade" id="escolhe-sala"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="myModalLabel">Chamar painel</h3>
                </div>
                <div class="modal-body">
                    <div class="panel-body">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="sala"><h4>Selecione uma sala</h4></label>
                                <select id="salas" class="form-control input-sm" style="color:#287da1;">
                                    <?php $__currentLoopData = $salas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value='<?php echo e($s->cd_sala); ?>' <?php echo e((session()->get('cd_sala')==$s->cd_sala) ? "selected" : ""); ?>><?php echo e($s->ds_sala); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="painel"><h4>Selecione um painel</h4></label>
                                <select id="paineis" class="form-control input-sm" style="color:#287da1;">
                                    <?php $__currentLoopData = arrayPadrao('paineis'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p => $key): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value='<?php echo e($p); ?>'  <?php echo e((session()->get('cd_painel')==$p) ? "selected" : ""); ?>><?php echo e($key); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-footer" style="text-align:left !important;">
                    <button type="button" class="btn btn-danger margin-top-zero" data-dismiss="modal">Cancelar</button>
                    <button id="chama-painel" class="btn btn-primary  pull-right">Chamar</button>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('custom_js'); ?>
    <script src="<?php echo e(js_versionado('prontuario.js')); ?>"></script>
    <script src="<?php echo e(js_versionado('atendimento_medico.js')); ?>"></script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.default', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>