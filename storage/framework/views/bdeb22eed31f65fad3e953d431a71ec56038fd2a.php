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
            <p><a class="btn btn-primary" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="true" aria-controls="collapseExample">
                    Fechar
                </a>
            </p>
        </div>
    <?php endif; ?>

    <?php echo e(Form::open(['id' => 'cadastraoperador', 'class' => 'form-no-submit'])); ?>
    <div class="panel panel-primary">
        <div class="panel-heading">
            <?php if((session()->get('recurso.operadores-editar'))): ?>
                <a href="<?php echo e(route('operadores/cadastro')); ?>" class="btn btn-primary pull-right margin-top-10">Novo</a>
            <?php endif; ?>
            <h4>Dados do Operador</h4>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="id">Código<span style="color:red">*</span></label>
                        <?php echo e(Form::text('id',(isset($user['id']) ? $user['id'] : ""),["name" => "id", "maxlength" => "10", "id" => "id", "disabled"=>"disabled", 'class'=> ($errors->has("id") ? "form-control is-invalid" : "form-control")])); ?>
                        <?php echo e(Form::hidden('id',(isset($user['id']) ? $user['id'] : ""),["name" => "id", "id" => "id"])); ?>
                    </div>
                </div>
                <div class="col-md-2 pull-right">
                    <div class="form-group">
                        <label for="id_situacao">Situação</label>
                        <?php echo e(Form::select('id_situacao', ['A' => 'Ativo', 'I' => 'Inativo'], (isset($user['id_situacao']) ? $user['id_situacao'] : "A"),['class'=> ($errors->has("id_situacao") ? "form-control is-invalid" : "form-control"), 'id' => 'id_situacao'])); ?>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-2">
                    <div class="input-group">
                        <label for="cd_pessoa">Pessoa<span style="color:red">*</span></label>
                        <?php echo e(Form::text('cd_pessoa',(isset($user['cd_pessoa']) ? $user['cd_pessoa'] : ""),["name" => "cd_pessoa", 'disabled', "maxlength" => "10", "id" => "cd_pessoa", 'class'=> ($errors->has("cd_pessoa") ? "form-control is-invalid" : "cd_pessoa form-control")])); ?>
                        <?php echo e(Form::hidden('cd_pessoa',(isset($user['cd_pessoa']) ? $user['cd_pessoa'] : ""),["name" => "cd_pessoa", "id" => "cd_pessoa_disabled", "class"=>"cd_pessoa" ])); ?>
                        <span class="input-group-btn">
                            <!-- <button class="btn btn-info margin-top-25" type="button" data-toggle="modal" data-target="#modal-pesquisa" id="open"><span class="fa fa-search"></span> </button> -->
                            <button class="btn-modal-pessoa btn btn-info margin-top-25 <?php echo e(verficaPermissaoBotao('recurso.pessoas-editar')); ?>" type="button"
                                <?php echo e((isset($user['cd_pessoa']) ? 'data-modo=editar data-cd-pessoa='.$user['cd_pessoa'] : "data-modo=pesquisar")); ?>>
                                <span class="fa fa-search"></span>
                            </button>
                        </span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="nm_pessoa">Nome<span style="color:red">*</span></label>
                        <?php echo e(Form::text('nm_pessoa', (isset($user['nm_pessoa']) ? $user['nm_pessoa'] : "") ,["maxlength" => "60", "disabled", "id" => "nm_pessoa", 'class'=> ($errors->has("nm_pessoa") ? "form-control is-invalid" : "maiusculas form-control") ])); ?>
                        <?php echo e(Form::hidden('nm_pessoa', (isset($user['nm_pessoa']) ? $user['nm_pessoa'] : "") ,["id" => "nm_pessoa_disabled" ])); ?>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="Grupo">Grupo</label>
                        <?php echo e(Form::select('cd_grupo_op', $grupos, (isset($user['cd_grupo_op']) ? $user['cd_grupo_op'] : "USER"),['class'=> ($errors->has("cd_grupo_op") ? "form-control is-invalid" : "form-control"), 'id' => 'cd_grupo_op'])); ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label id="mail" for="email">E-Mail<span style="color:red">*</span></label>
                        <?php echo e(Form::text('email',(isset($user['email']) ? $user['email'] : "") ,["maxlength" => "60", "id" => "email", 'class'=> ($errors->has("email") ? "form-control is-invalid" : "form-control")])); ?>
                    </div>
                </div>
                <?php if(empty($user['id']) ): ?>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label id="password" for="password">Senha<span style="color:red">*</span></label>
                            <?php echo e(Form::password('password',["maxlength" => "20", "id" => "password", 'class'=> ($errors->has("password") ? "form-control is-invalid" : "form-control")])); ?>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label id="password-confirm" for="password-confirm">Confirmar Senha<span style="color:red">*</span></label>
                            <?php echo e(Form::password('password_confirmation',["maxlength" => "20", "id" => "password-confirm", 'class'=> ($errors->has("password_confirmation") ? "form-control is-invalid" : "form-control")])); ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <div class="panel panel-default">
                            <div class="panel-heading"><h4>Selecione os estabelecimentos permitidos para o operador</h4></div>
                            <div class="panel-body">
                                <?php $__currentLoopData = $estabelecimentos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                     <?php $existe = false;
                                     if(isset($estabelecimentos_permitidos)) {
                                          if(in_array($e->cd_estabelecimento,$estabelecimentos_permitidos)){
                                          $existe = true;
                                          }
                                     }?>
                                <div class="col-md-4">
                                    <input id='checkbox-<?php echo e($e->cd_estabelecimento); ?>' type="checkbox" name='checkbox-<?php echo e($e->cd_estabelecimento); ?>' value='<?php echo e($e->cd_estabelecimento); ?>'<?php echo e(($existe) ? ' checked' : ''); ?>/>
                                    <label for='checkbox-<?php echo e($e->cd_estabelecimento); ?>'><?php echo e($e->nm_estabelecimento); ?></label>
                                </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel-footer fixed-panel-footer" >
            <?php if((session()->get('recurso.operadores-editar'))): ?>
                <?php echo e(Form::submit('Salvar',['class'=>"btn btn-success pull-right"])); ?>
            <?php endif; ?>
        </div>
    </div>
    <?php echo e(Form::close()); ?>

    <?php echo $__env->make('pessoas.modal-pessoas', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.default', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>