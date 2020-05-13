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
            <p ><a class="btn btn-primary" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="true" aria-controls="collapseExample">
                    Fechar
                </a>
            </p>
        </div>
    <?php endif; ?>

    <div class="panel panel-primary">
        <div class="panel-heading">
            <button id='chamar-painel' data-toggle='modal' data-target="#escolhe-sala" class="btn btn-primary pull-right">Chamar painel</button>
            <button class="btn btn-primary pull pull-right ver_pessoa" type="button" data-toggle="modal" data-target="#modal-pesquisa">Ver cadastro</button>
            <?php echo e(Form::open(['id' => 'form_realiza_procedimento', 'class' => 'form-no-submit'])); ?>

            <?php echo e(Form::hidden('cd_pessoa',(isset($lista[0]->cd_pessoa) ? $lista[0]->cd_pessoa : ""),["name" => "cd_pessoa", "id" => "cd_pessoa_disabled"  ])); ?>

            <?php echo e(Form::hidden('cd_prontuario',(isset($lista[0]->cd_prontuario) ? $lista[0]->cd_prontuario : ""),["name" => "cd_prontuario", "id" => "cd_prontuario"])); ?>

            <h4>Procedimento</h4>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="status">Procedimento</label>
                        <?php echo e(Form::text('nm_procedimento',(isset($procedimento[0]->nm_procedimento) ? ($procedimento[0]->cd_procedimento." - ".$procedimento[0]->nm_procedimento) : ""),["disabled"=>"disabled", 'class'=> ($errors->has("id") ? "form-control is-invalid" : "form-control")])); ?>

                        <?php echo e(Form::hidden('nm_procedimento',(isset($procedimento[0]->nm_procedimento) ? $procedimento[0]->nm_procedimento : ""),["name" => "nm_procedimento", "id" => "nm_procedimento"])); ?>

                        <?php echo e(Form::hidden('cd_procedimento',(isset($procedimento[0]->cd_procedimento) ? $procedimento[0]->cd_procedimento : ""),["name" => "cd_procedimento", "id" => "cd_procedimento"])); ?>

                        <?php echo e(Form::hidden('id_status','A',["name" => "id_status", "id" => "id_status"])); ?>

                        <?php echo e(Form::hidden('id_atendimento_procedimento',(isset($procedimento[0]->id_atendimento_procedimento) ? $procedimento[0]->id_atendimento_procedimento : ""),["name" => "id_atendimento_procedimento", "id" => "id_atendimento_procedimento"])); ?>

                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="status">Solicitante</label>
                        <?php echo e(Form::text('nm_ocupacao',isset($procedimento[0]->nm_ocupacao) ? (trim($procedimento[0]->nm_ocupacao)." ".$procedimento[0]->nm_pessoa) : "",["disabled"=>"disabled", 'class'=> ($errors->has("id") ? "form-control is-invalid" : "form-control")])); ?>

                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="status">Data/ Hora Solicitação</label>
                        <?php echo e(Form::text('dt_hr_solicitacao',isset($procedimento[0]->dt_hr_solicitacao) ? formata_data_hora($procedimento[0]->dt_hr_solicitacao) : "",["name" => "dt_hr_solicitacao", "id" => "dt_hr_solicitacao", "disabled"=>"disabled", 'class'=> "form-control"])); ?>

                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="descricao_solicitacao">Detalhes da solicitação</label>
                        <?php echo e(Form::textarea('descricao_solicitacao',(isset($procedimento[0]->descricao_solicitacao) ? $procedimento[0]->descricao_solicitacao : ""),["name" => "descricao_solicitacao", "maxlength" => "1000", "rows"=>"2", "id" => "descricao_solicitacao", "disabled"=>"disabled", 'class'=> ($errors->has("descricao_solicitacao") ? "form-control is-invalid" : "form-control")])); ?>

                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="descricao_execucao">Detalhes da execução</label>
                        <?php echo e(Form::textarea('descricao_execucao',(isset($procedimento[0]->descricao_execucao) ? $procedimento[0]->descricao_execucao : ""),["name" => "descricao_execucao", "maxlength" => "1000", "rows"=>"2", "id" => "descricao_execucao", 'class'=> ($errors->has("descricao_execucao") ? "form-control is-invalid" : "form-control")])); ?>

                    </div>
                </div>
            </div>
            <div class="panel-footer">
                <div class="pull-right"><?php echo e("Responsável: ".title_case(Session()->get('profissional')).' ' .Session()->get('nome')); ?>

                    <br>
                    <?php $__currentLoopData = $salas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div id="sala-rodape"><?php echo e(($s->cd_sala == Session()->get('cd_sala')) ? title_case($s->ds_sala) : ''); ?></div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
    </div>

    <?php if((session()->get('recurso.operadores-adicionar'))): ?>
        <?php echo e(Form::submit('Salvar',['class'=>"btn btn-success pull-left"])); ?>

        <?php echo e(Form::submit('Finalizar',['id'=>'finalizar-procedimento', 'class'=>"btn btn-success pull-right"])); ?>

    <?php endif; ?>
    <?php echo e(Form::close()); ?>


    <?php if(!empty(Session::get('status'))): ?>
        <div class="alert alert-info" id="msg">
            <?php echo e(Session::get('status')); ?>

        </div>
    <?php endif; ?>
    <?php echo $__env->make('pessoas/modal-pessoas', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('painel-modal'); ?>
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
                                <select id="salas" class="form-control" style="color:#287da1;">
                                    <?php $__currentLoopData = $salas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value='<?php echo e($s->cd_sala); ?>' <?php echo e((session()->get('cd_sala')==$s->cd_sala) ? "selected" : ""); ?>><?php echo e($s->ds_sala); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="painel"><h4>Selecione um painel</h4></label>
                                <select id="paineis" class="form-control" style="color:#287da1;">
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
    <?php echo $__env->make('pessoas/modal-pessoas', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.default', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>