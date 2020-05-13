<?php $__env->startSection('conteudo'); ?>
    <div class="col-sm-offset-1 col-sm-10">

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

        <?php echo e(Form::open(['id' => 'form-cadastra-grupo', 'class' => 'form-no-submit'])); ?>

        <div class="panel panel-primary">
            <div class="panel-heading">
                <?php if((session()->get('recurso.grupos-editar'))): ?>
                    <a href="<?php echo e(route('grupos/cadastro')); ?>" class="btn btn-primary pull-right margin-top-10">Novo</a>
                <?php endif; ?>
                <h4>Dados do Grupo de Operadores</h4>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="cd_grupo_op">Código<span style="color:red">*</span></label>
                            <?php echo e(Form::text('cd_grupo_op',(isset($grupo['cd_grupo_op']) ? $grupo['cd_grupo_op'] : ""),["name" => "cd_grupo_op", "maxlength" => "10", "id" => "cd_grupo_op",  "disabled" => "disabled", 'class'=> ($errors->has("cd_grupo_op") ? "form-control is-invalid" : "form-control")])); ?>

                            <?php echo e(Form::hidden('cd_grupo_op',(isset($grupo['cd_grupo_op']) ? $grupo['cd_grupo_op'] : ""),["name" => "cd_grupo_op", "id" => "cd_grupo_op"])); ?>

                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="form-group">
                            <label for="nm_grupo_op">Nome<span style="color:red">*</span></label>
                            <?php echo e(Form::text('nm_grupo_op', (isset($grupo['nm_grupo_op']) ? $grupo['nm_grupo_op'] : "") ,["maxlength" => "40", "id" => "nm_grupo_op", 'class'=> ($errors->has("nm_grupo_op") ? "maiusculas form-control is-invalid" : "maiusculas form-control") ])); ?>

                        </div>
                    </div>
                </div>

                <?php if(!isset($permissoes) || $permissoes->IsEmpty()): ?>
                    <p>Sem resultados</p>
                <?php else: ?>
                        <div class="panel-primary" id="accordion">
                            <h3>Permissões</h3>
                            <div class="panel panel-default">
                                <?php $__currentLoopData = $grupos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $g): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapse-<?php echo e($g); ?>">
                                                <b><?php echo e(title_case($g)); ?></b>
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapse-<?php echo e($g); ?>" class="panel-collapse collapse">
                                        <div class="panel-body">
                                            <?php $__currentLoopData = $permissoes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php $igual = strpos( $p->obj_recurso, $g ); ?>
                                                <?php if($igual===0): ?>
                                                    <li class="list-group-item">
                                                    <?php echo e(title_case($p->ds_recurso)); ?>

                                                        <div class="material-switch pull-right">
                                                            <input id='checkbox-<?php echo e($p->cd_recurso); ?>' type="checkbox" name='checkbox-<?php echo e($p->cd_recurso); ?>' value='<?php echo e($p->cd_recurso); ?>' <?php echo e(($p->permitido > 0) ? 'checked' : ''); ?>/>
                                                            <label for='checkbox-<?php echo e($p->cd_recurso); ?>' class="label-primary"></label>
                                                        </div>
                                                    </li>
                                                <?php endif; ?>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>

                <?php endif; ?>
            </div>
            <div class="panel-footer fixed-panel-footer" >
                <?php if((session()->get('recurso.grupos-editar'))): ?>
                    <?php echo e(Form::submit('Salvar',['class'=>"btn btn-success pull-right"])); ?>

                <?php endif; ?>
            </div>
        </div>

        <?php echo e(Form::close()); ?>


    </div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.default', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>