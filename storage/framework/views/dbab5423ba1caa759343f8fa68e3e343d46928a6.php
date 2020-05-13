<?php $__env->startSection('content'); ?>
    <?php if(!empty(Session::get('status'))): ?>
        <script>
            swal({
                type: 'success',
                title: '<?php echo e(Session::get('status')); ?>',
                showConfirmButton: false,
                timer: 1500
            })
        </script>
    <?php endif; ?>
    <?php if(!empty(Session::get('confirmation'))): ?>
        <script>
            swal({
                type: 'warning',
                title: 'Atenção',
                html: '<?php echo Session::get('confirmation'); ?>',
                showConfirmButton: true
            })
        </script>
    <?php endif; ?>

    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a href="<?php echo e(route('home')); ?>">
                    <img src="<?php echo e(asset('public/images/logo3.png')); ?>" alt="" class="img-responsive" />
                </a>
            </div>
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-center" style="padding-left: 50px">
                    <h3><?php echo e(session()->get('nm_estabelecimento')." - ".(!empty($headerText) ? $headerText : '')); ?></h3>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo e(session('nome')); ?> <span class="caret"></span></a>
                        <ul class="dropdown-menu">

                            <li><a href="<?php echo e(route('operadores/meus-dados')); ?>">Meus Dados</a></li>
                            <li><a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Sair</a></li>
                            <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" style="display: none;">
                                <?php echo csrf_field(); ?>
                            </form>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container-fluid recuo-top">
        <div class="row">
            <?php if(isset($breadcrumbs)): ?>
                <h4 class="breadcrumb">
                    <?php $__currentLoopData = $breadcrumbs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $crumbs): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if($loop->last): ?>
                            <li class="breadcrumb-item "><?php echo e($crumbs['titulo']); ?></li>
                            <?php if(isset($extracrumbinfo)): ?>
                                <li class="breadcrumb-item "><?php echo e($extracrumbinfo); ?></li>
                            <?php endif; ?>
                        <?php else: ?>
                            <li class="breadcrumb-item "><a href="<?php echo e($crumbs['href']); ?>"><?php echo e($crumbs['titulo']); ?></a></li>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </h4>
            <?php endif; ?>

            <div class="col-sm-offset-2 col-sm-6">
                <?php echo $__env->yieldContent('conteudo-small'); ?>
            </div>

            <div class="col-sm-offset-1 col-sm-10">
                <?php echo $__env->yieldContent('conteudo'); ?>
            </div>

            <div class="col-sm-12">
                <?php echo $__env->yieldContent('conteudo-full'); ?>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js_variaveis'); ?>
    <script>
        var token = '<?php echo e(csrf_token()); ?>';
        var dir = '<?php echo e(env('APP_DIR', '/')); ?>';
    </script>
    <script src="<?php echo e(js_versionado('app.js')); ?>" defer></script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>