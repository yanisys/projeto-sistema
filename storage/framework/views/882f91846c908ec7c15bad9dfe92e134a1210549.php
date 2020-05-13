<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('public/css/relatorios/prontuario_v1.0.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('header'); ?>
    <div class="font-12px">
        <h3><?php echo e(session('nm_estabelecimento').' ('.session('cnes_estabelecimento').')'); ?></h3>
    </div>
    <hr>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('conteudo'); ?>
    <div class="pagina">
        <div class="div-titulo">Relatório de estoque</div>
        <table width="100%" class="pure-table" border="0">
            <tr>
                <td><b>Produto</b></td>
                <?php if(isset($estoque[0]->nm_sala)): ?>
                    <td><b>Local de estoque</b></td>
                <?php endif; ?>
                <?php if(isset($estoque[0]->lote)): ?>
                    <td><b>Lote</b></td>
                <?php endif; ?>
                <?php if(isset($estoque[0]->dt_validade)): ?>
                <td><b>Validade</b></td>
                <?php endif; ?>
                <td><b>Quantidade</b></td>
            </tr>
            <?php if(isset($estoque[0])): ?>
                <?php $__currentLoopData = $estoque; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($e->quantidade > 0): ?>
                    <tr>
                        <td><?php echo e($e->nm_produto.(isset($e->ds_produto) ? " - " : "").$e->ds_produto); ?></td>
                        <?php if(isset($e->nm_sala)): ?>
                            <td><?php echo e($e->nm_sala); ?></td>
                        <?php endif; ?>
                        <?php if(isset($e->lote)): ?>
                            <td><?php echo e($e->lote); ?></td>
                        <?php endif; ?>
                        <?php if(isset($estoque[0]->dt_validade)): ?>
                            <td><?php echo e(isset($e->dt_validade) ? formata_data($e->dt_validade) : ""); ?></td>
                        <?php endif; ?>
                        <td><?php echo e($e->quantidade); ?></td>
                    </tr>
                    <?php endif; ?>
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