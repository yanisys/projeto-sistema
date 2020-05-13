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
                </a></p>
        </div>
    <?php endif; ?>

    <?php echo e(Form::open(['id' => 'cadastra-estabelecimento', 'class' => 'form-no-submit'])); ?>

    <div class="panel panel-primary">
        <div class="panel-heading">
            <?php if((session()->get('recurso.estabelecimentos-editar'))): ?>
                <a href="<?php echo e(route('estabelecimentos/cadastro')); ?>" class="btn btn-primary pull-right margin-top-10">Novo</a>
            <?php endif; ?>
                <h4>Dados do estabelecimento</h4>
        </div>
        <div class="panel-body">
            <div class="tab-content">
                <div class="tab-pane fade in active" id="tab_info">
                    <div class="row">
                        <div class="form-group">
                            <?php echo e(Form::hidden('cd_estabelecimento',(isset($estabelecimento['cd_estabelecimento']) ? $estabelecimento['cd_estabelecimento'] : ""),["name" => "cd_estabelecimento", "id" => "cd_estabelecimento"])); ?>

                        </div>
                        <div class="col-md-2">
                            <div class="input-group">
                                <label for="cd_pessoa">Pessoa<span style="color:red">*</span></label>
                                <?php echo e(Form::text('cd_pessoa',(isset($estabelecimento['cd_pessoa']) ? $estabelecimento['cd_pessoa'] : ""),["name" => "cd_pessoa", 'disabled', "maxlength" => "10", "id" => "cd_pessoa", 'class'=> ($errors->has("cd_pessoa") ? "form-control is-invalid" : "form-control cd_pessoa")])); ?>

                                <?php echo e(Form::hidden('cd_pessoa',(isset($estabelecimento['cd_pessoa']) ? $estabelecimento['cd_pessoa'] : ""),["name" => "cd_pessoa", "id" => "cd_pessoa_disabled", "class" => "cd_pessoa" ])); ?>

                                <span class="input-group-btn">
                            <button type="button" class="btn btn-info margin-top-25 btn-modal-pessoa"
                                <?php echo e((isset($estabelecimento['cd_pessoa']) ? 'data-modo=editar data-cd-pessoa='.$estabelecimento['cd_pessoa'] : "data-modo=pesquisar")); ?>>
                                <span class="fa fa-search"></span> </button>
                        </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nm_estabelecimento">Nome do Estabelecimento<span style="color:red">*</span></label>
                                <?php echo e(Form::text('nm_estabelecimento', (isset($estabelecimento['nm_estabelecimento']) ? $estabelecimento['nm_estabelecimento'] : "") ,["maxlength" => "60", "disabled", "id" => "nm_estabelecimento", 'class'=> ($errors->has("nm_estabelecimento") ? "form-control is-invalid" : "form-control") ])); ?>

                                <?php echo e(Form::hidden('nm_estabelecimento', (isset($estabelecimento['nm_estabelecimento']) ? $estabelecimento['nm_estabelecimento'] : "") ,["id" => "nm_estabelecimento_disabled" ])); ?>

                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="tp_estabelecimento">Tipo<span style="color:red">*</span></label>
                                <?php echo e(Form::select('tp_estabelecimento', arrayPadrao('tipo_estabelecimento'), (isset($estabelecimento['tp_estabelecimento']) ? trim($estabelecimento['tp_estabelecimento']) : ""),['class'=>  "form-control", 'id' => 'tp_estabelecimento'])); ?>

                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="status">Situação<span style="color:red">*</span></label>
                                <?php echo e(Form::select('status', arrayPadrao('situacao'), (isset($estabelecimento['status']) ? $estabelecimento['status'] : "A"),['class'=>  "form-control", 'id' => 'status'])); ?>

                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="cnes">Cnes<span style="color:red">*</span></label>
                                <?php echo e(Form::text('cnes', (isset($estabelecimento['cnes']) ? trim($estabelecimento['cnes']) : ""),['maxlength' => '9','class'=>  "form-control mask-numeros-11", 'id' => 'cnes'])); ?>

                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <div class="panel panel-default">
                            <div class="panel-heading"><h4>Selecione os tipos de planos permitidos para o estabelecimento</h4></div>
                            <div class="panel-body">
                                <?php if(isset($estabelecimento['tp_plano']))
                                    $planos_permitidos = str_split($estabelecimento['tp_plano']) ?>
                                <?php $__currentLoopData = arrayPadrao('tipo_plano'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php $existe = false; ?>
                                    <?php if(isset($planos_permitidos)): ?>
                                        <?php if(in_array($k,$planos_permitidos)): ?>
                                            <?php $existe = true?>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                    <div class="col-md-3">
                                        <input id='checkbox-<?php echo e($k); ?>' type="checkbox" name='checkbox-<?php echo e($k); ?>' value='<?php echo e($k); ?>'<?php echo e(($existe) ? ' checked' : ''); ?>/>
                                        <label for='checkbox-<?php echo e($k); ?>'><?php echo e($r); ?></label>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel-footer fixed-panel-footer" >
            <?php if((session()->get('recurso.estabelecimentos-editar'))): ?>
                <?php echo e(Form::submit('Salvar',['class'=>"btn btn-success pull-right", 'id' => 'salvar-estabelecimento'])); ?>

            <?php endif; ?>
        </div>

    </div>

    <?php echo e(Form::close()); ?>

    <?php echo $__env->make('pessoas.modal-pessoas', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.default', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>