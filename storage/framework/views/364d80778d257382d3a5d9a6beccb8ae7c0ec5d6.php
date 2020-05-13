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
            <p><a class="btn btn-primary" data-toggle="collapse" href="#collapseExample" role="button"
                  aria-expanded="true" aria-controls="collapseExample">
                    Fechar
                </a></p>
        </div>
    <?php endif; ?>

    <?php echo e(Form::open(['id' => 'cadastra-movimento', 'class' => 'form-no-submit'])); ?>

    <div class="panel panel-primary">
        <div class="panel-heading">
            <?php if((session()->get('recurso.materiais/movimento-editar'))): ?>
                <a href="<?php echo e(route('materiais/movimento/cadastro')); ?>" class="btn btn-primary pull-right margin-top-10">Novo</a>
            <?php endif; ?>
            <h4>Parâmetros do Movimento</h4>
        </div>
        <div class="panel-body">
            <div class="tab-content">
                <div class="tab-pane fade in active" id="tab_info">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="cd_movimento">Código<span style="color:red">*</span></label>
                                <?php echo e(Form::text('cd_movimento',(isset($movimento['cd_movimento']) ? $movimento['cd_movimento'] : ""),["name" => "cd_movimento", "maxlength" => "10", "id" => "cd_movimento",  "disabled" => "disabled", 'class'=> ($errors->has("cd_movimento") ? "form-control is-invalid" : "form-control")])); ?>

                                <?php echo e(Form::hidden('cd_movimento',(isset($movimento['cd_movimento']) ? $movimento['cd_movimento'] : ""),["name" => "cd_movimento", "id" => "cd_movimento"])); ?>

                            </div>
                        </div>
                        <div class="col-md-2 pull-right">
                            <div class="form-group">
                                <label for="situacao">Situação</label>
                                <?php echo e(Form::select('situacao', ['A' => 'Ativo', 'I' => 'Inativo'], (isset($movimento['situacao']) ? $movimento['situacao'] : "A"),['class'=> ($errors->has("situacao") ? "form-control is-invalid" : "form-control"), 'id' => 'situacao'])); ?>

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="nm_movimento">Nome<span
                                style="color:red">*</span></label>
                                <?php echo e(Form::text('nm_movimento', (isset($movimento['nm_movimento']) ? $movimento['nm_movimento'] : "") ,["maxlength" => "100", "name" => "nm_movimento", 'class'=> ($errors->has("nm_movimento") ? "form-control is-invalid maiusculas" : "form-control maiusculas") ])); ?>

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="tp_nf">Tipo de documento</label>
                                <?php echo e(Form::select('tp_nf', arrayPadrao('tipo_nota'), (isset($movimento['tp_nf']) ? $movimento['tp_nf'] : '0'),['class'=> "form-control"])); ?>

                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="tp_movimento">Tipo de movimento</label>
                                <?php echo e(Form::select('tp_movimento', arrayPadrao('tipo_movimento'), (isset($movimento['tp_movimento']) ? $movimento['tp_movimento'] : 'C'),['class'=> "form-control", 'id' => 'tp_movimento'])); ?>

                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="tp_conta">Tipo de conta</label>
                                <?php echo e(Form::select('tp_conta', arrayPadrao('tipo_conta'), (isset($movimento['tp_conta']) ? $movimento['tp_conta'] : 'N'),['class'=> "form-control"])); ?>

                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="tp_conta">Ação sobre o estoque</label>
                                <?php echo e(Form::select('tp_saldo', arrayPadrao('tipo_saldo'), (isset($movimento['tp_saldo']) ? $movimento['tp_saldo'] : 'N'),['class'=> "form-control"])); ?>

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="input-group">
                                <label for="cd_cfop">Cfop padrão<button type="button" id="limpa-cfop" class="btn btn-warning btn-xs"><span class="fas fa-broom"></span></button><br></label>
                                <?php echo e(Form::text('cd_cfop', (isset($movimento['ds_cfop']) ? $movimento['cd_cfop']." - ".$movimento['ds_cfop'] : "") ,["name" => "cd_cfop", "id" => "cd_cfop", "disabled", 'class'=> 'form-control'])); ?>

                                <?php echo e(Form::hidden('cd_cfop', (isset($movimento['cd_cfop']) ? $movimento['cd_cfop'] : 0) ,["name" => "cd_cfop", 'id' => 'cd_cfop_hidden', 'class'=> 'form-control'])); ?>

                                <span class="input-group-btn">
                                    <button type="button" data-toggle="modal" class="btn btn-info margin-top-25" id="btn-modal-pesquisa-cfop"><span class="fa fa-search"></span></button>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel-footer fixed-panel-footer" >
            <?php if((session()->get('recurso.materiais/movimento-editar'))): ?>
                <?php echo e(Form::submit('Salvar',['class'=>"btn btn-success pull-right", 'id' => 'salvar-movimento'])); ?>

            <?php endif; ?>
        </div>

    </div>

    <?php echo e(Form::close()); ?>


<?php $__env->stopSection(); ?>

<?php $__env->startSection('painel-modal'); ?>
    <?php echo $__env->make('materiais.movimento.modal-pesquisa-cfop', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.default', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>