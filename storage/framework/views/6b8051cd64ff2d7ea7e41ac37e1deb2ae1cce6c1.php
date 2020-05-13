<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('public/css/relatorios/prontuario_v1.0.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('header'); ?>
    <div class="font-12px">
        <h3><?php echo e(session('nm_estabelecimento').' ('.session('cnes_estabelecimento').')'); ?></h3>
        <?php if(isset($dados[0])): ?>
            <h3>COMPETÊNCIA - <?php echo e(arrayPadrao('mes')[substr($dados[0]['competencia'],4,6)]."/".substr($dados[0]['competencia'],0,4)); ?></h3>
        <?php endif; ?>
    </div>
    <hr>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('conteudo'); ?>
    <div class="pagina">
        <div class="div-titulo">Relatório BPA</div>
        <table width="100%" class="pure-table" border="0">
            <tr>
                <td><b>Ocupação</b></td>
                <td><b>Procedimento</b></td>
                <td><b>Quantidade</b></td>
            </tr>
            <?php if(isset($dados[0])): ?>
                <?php $__currentLoopData = $dados; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($d['cbo'] ." - ". $d['nm_ocupacao']); ?></td>
                    <td><?php echo e($d['cd_procedimento'] ." - ". $d['nm_procedimento']); ?></td>
                    <td><?php echo e($d['quantidade']); ?></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
        </table>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('footer'); ?>
    <hr>
    <small>Impresso em <?php echo e(date("d/m/Y - H:m:s")); ?></small>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.relatorio', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>