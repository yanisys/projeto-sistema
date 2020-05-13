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

    <?php echo e(Form::open(['id' => 'meus-dados'])); ?>

    <div class="panel panel-primary">
        <div class="panel-heading">Meus dados</div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="id">Código de Usuário</label>
                        <?php echo e(Form::text('id',(isset($user['id']) ? $user['id'] : ""),[ "id" => "id", "disabled", 'class'=> "form-control"])); ?>

                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="cd_pessoa">Pessoa</label>
                        <?php echo e(Form::text('cd_pessoa',(isset($user['cd_pessoa']) ? $user['cd_pessoa'] : ""),[ 'disabled', "id" => "cd_pessoa",  'class'=> "form-control"])); ?>

                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="nm_pessoa">Nome</label>
                        <?php echo e(Form::text('nm_pessoa', (isset($user['nm_pessoa']) ? $user['nm_pessoa'] : "") ,["disabled", "id" => "nm_pessoa", 'class'=> "form-control" ])); ?>

                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="Grupo">Grupo</label>
                        <?php echo e(Form::text('cd_grupo_op', (isset($user['nm_grupo_op']) ? $user['nm_grupo_op'] : "") ,[ "disabled", "id" => "nm_grupo_op", 'class'=> "form-control" ])); ?>

                    </div>
                </div>
            </div>
        </div>

    </div>
    <div class="panel panel-primary">
        <div class="panel-heading">Alterar E-mail</div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-6">
                    <?php echo e(Form::checkbox('trocar_email','trocar_email',(isset($_POST['trocar_email']) ? $_POST['trocar_email'] : false),["class" => 'form-check-input'])); ?>

                    <label class="form-check-label" for="trocar_email">Quero alterar meu E-Mail</label>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label id="mail" for="email">E-Mail de login</label>
                        <?php echo e(Form::text('email',(isset($user['email']) ? $user['email'] : "") ,["maxlength" => "60", "id" => "email", 'class'=> ($errors->has("email") ? "form-control is-invalid" : "form-control")])); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="panel panel-primary">
        <div class="panel-heading">Alterar Senha</div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-6">
                    <?php echo e(Form::checkbox('trocar_senha','trocar_senha',(isset($_POST['trocar_senha']) ? $_POST['trocar_senha'] : false),["class" => 'form-check-input'])); ?>

                    <label class="form-check-label" for="trocar_senha">Quero alterar minha senha</label>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label id="password" for="password">Nova Senha</label>
                        <?php echo e(Form::password('nova_senha',["maxlength" => "20", "id" => "nova_senha", 'class'=> ($errors->has("nova_senha") ? "form-control is-invalid" : "form-control")])); ?>

                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label id="password-confirm" for="password-confirm">Confirmar Nova Senha</label>
                        <?php echo e(Form::password('nova_senha_confirmation',["maxlength" => "20", "id" => "nova_senha-confirm", 'class'=> ($errors->has("NOVA_SENHA_confirmation") ? "form-control is-invalid" : "form-control")])); ?>

                    </div>
                </div>
            </div>

        </div>

    </div>
    <?php echo e(Form::submit('Salvar',['class'=>"btn btn-success"])); ?>

    <?php echo e(Form::close()); ?>


    <?php if(!empty(Session::get('status'))): ?>
        <div class="alert alert-info" id="msg">
            <?php echo e(Session::get('status')); ?>

        </div>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.default', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>