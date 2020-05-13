<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('public/css/relatorios/prontuario_v1.0.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('header'); ?>


<?php $__env->stopSection(); ?>

<?php $__env->startSection('conteudo'); ?>
    <div class="pagina">
        <div class="div-titulo text-center" style="font-size: 16px;"><?php echo e(session('nm_estabelecimento').' ('.session('cnes_estabelecimento').')'); ?> - Relatório de prontuários
        </div>
        <div style="font-size: 12px">
            <br><?php echo e("Período: " . $filtros['inicio'] . " a " . $filtros['final']); ?>

        </div>
        <table width="100%" class="pure-table" border="0">
            <tr>
                <td><b>Data/ Hora</b></td>
                <td><b>Paciente</b></td>
                <td><b>Sexo</b></td>
                <td><b>Dt. nasc</b></td>
                <td><b>Médico</b></td>
                <td><b>Cid</b></td>
                <td><b>Motivo alta</b></td>
            </tr>
            <?php if(!isset($lista) || $lista->IsEmpty()): ?>
                <tr><td colspan="6">Sem resultados</td></tr>
            <?php else: ?>
                <?php $__currentLoopData = $lista; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prontuario): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e(formata_data_hora($prontuario->created_at)); ?></td>
                        <td><?php echo e($prontuario->nm_pessoa); ?></td>
                        <td class='text-center'><?php echo e($prontuario->id_sexo); ?></td>
                        <td><?php echo e(isset($prontuario->dt_nasc) ? formata_data($prontuario->dt_nasc) : ''); ?></td>
                        <td><?php echo e($prontuario->nm_medico); ?></td>
                        <td><?php echo e((isset($prontuario->nm_cid)) ? $prontuario->nm_cid : ""); ?></td>
                        <td><?php echo e((isset($prontuario->motivo_alta) && $prontuario->motivo_alta !== 0) ? arrayPadrao('motivo_alta')[$prontuario->motivo_alta] : ''); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td colspan="7">Total: <?php echo e(count($lista)); ?></td>
                    </tr>
            <?php endif; ?>
        </table>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('footer'); ?>
    <hr>
    <small>Impresso em <?php echo e(date("d/m/Y - H:m:s")); ?></small>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.relatorio', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>