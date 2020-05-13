<?php $__env->startSection('content'); ?>
    <div class="container">
        <header><img src="<?php echo e(asset('public/images/logo.png')); ?>" alt="Nicola - Planos de Saúde" class="img-responsive" /></header>
        <div class="row">
            <div class="col-sm-4 col-sm-offset-4 border">
                <form class='form-signin' method="POST" action="<?php echo e(route('login')); ?>" aria-label="<?php echo e(__('Login')); ?>">
                    <?php echo csrf_field(); ?>
                    <div class="form-group">
                        <input type="text" id='email' name='email' class="form-control<?php echo e($errors->has('email') ? ' is-invalid' : ''); ?>" placeholder='Login' value="<?php echo e(old('email')); ?>" required autofocus >
                    </div>
                    <div class="form-group">
                        <input id="password" type="password" class="form-control<?php echo e($errors->has('password') ? ' is-invalid' : ''); ?>" placeholder='Senha' name="password" required>
                        <a class="btn btn-link" href="<?php echo e(route('password.request')); ?>">Esqueceu a sua senha?</a>
                    </div>
                    <?php if($errors->any()): ?>
                        <div class="alert alert-danger">
                            <ul>
                                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php echo e($error); ?>

                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    <div class="form-group row">
                        <div class="col-md-6 offset-md-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" <?php echo e(old('remember') ? 'checked' : ''); ?>>
                                <label class="form-check-label" for="remember">
                                    Lembrar de mim
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row mb-0">
                        <div class="col-md-8 offset-md-4">
                            <button type="submit" class="btn btn-primary">
                                Entrar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>