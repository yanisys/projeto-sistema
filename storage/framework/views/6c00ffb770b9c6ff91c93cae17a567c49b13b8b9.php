<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('public/css/relatorios/receituario_v1.0.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('header'); ?>
    <?php for($x=0;$x<2;$x++): ?>
    <div class="header" style="width: 49%; display: inline-block; font-size: 10px">
        <h3><?php echo e(session('nm_estabelecimento')); ?></h3>
        <div style="text-align: left">
            <h4>IDENTIFICAÇÃO DO EMITENTE</h4>
            <b><?php echo e($medico->nm_medico); ?></b><br>
            <b><?php echo e($medico->nm_ocupacao ." | ".$medico->conselho." ".$medico->nr_conselho); ?></b><br>
            <?php echo e($estabelecimento->endereco. " ".$estabelecimento->endereco_nro ." | ".$estabelecimento->bairro); ?><br>
            <?php echo e($estabelecimento->localidade." | ".$estabelecimento->uf); ?><br>
            <?php echo e((isset($estabelecimento->nr_fone1) && $estabelecimento->nr_fone1 !== "") ? "Fone: ".$estabelecimento->nr_fone1." - " : ""); ?>

            <?php echo e((isset($estabelecimento->ds_email) && $estabelecimento->ds_email !== "") ? "E-mail: ".$estabelecimento->ds_email : ""); ?>

        </div>
        <br><h3><?php echo e($paciente->nm_paciente); ?></h3><br>
        PRESCRIÇÃO: <?php echo e($titulo); ?>

    </div>
    <?php endfor; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('conteudo'); ?>
    <div class="pagina" style="padding-top: 10px">
        <table width="100%" class="pure-table" style="font-size: 10px" border="0">
        <?php if(isset($receita_medicacao)): ?>
            <?php $__currentLoopData = $receita_medicacao; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td width="50%"><?php echo e(($key + 1).". ".$r->nm_produto."-----"); ?></td>
                    <td width="50%"><?php echo e(($key + 1).". ".$r->nm_produto."-----"); ?></td>
                </tr>
                <tr>
                    <td width="50%" style="padding-left: 20px"><?php echo e($r->dose." ".$r->abreviacao.", ".arrayPadrao('via')[$r->tp_via].", de ".$r->intervalo." em ".$r->intervalo." ".($r->intervalo > 1 ? arrayPadrao('periodo_plural')[$r->tp_intervalo] : arrayPadrao('periodo')[$r->tp_intervalo]).", durante ".$r->prazo." ".($r->prazo > 1 ? arrayPadrao('periodo_plural')[$r->tp_prazo] : arrayPadrao('periodo')[$r->tp_prazo])); ?></td>
                    <td width="50%" style="padding-left: 20px"><?php echo e($r->dose." ".$r->abreviacao.", ".arrayPadrao('via')[$r->tp_via].", de ".$r->intervalo." em ".$r->intervalo." ".($r->intervalo > 1 ? arrayPadrao('periodo_plural')[$r->tp_intervalo] : arrayPadrao('periodo')[$r->tp_intervalo]).", durante ".$r->prazo." ".($r->prazo > 1 ? arrayPadrao('periodo_plural')[$r->tp_prazo] : arrayPadrao('periodo')[$r->tp_prazo])); ?></td>
                </tr>
                    <tr>
                        <td width="50%"  style="padding-left: 20px">
                            <?php if(isset($r->observacao_medicamento)): ?>
                                Obs: <?php echo e($r->observacao_medicamento); ?>

                            <?php endif; ?>
                        </td>
                        <td width="50%"  style="padding-left: 20px">
                            <?php if(isset($r->observacao_medicamento)): ?>
                                Obs: <?php echo e($r->observacao_medicamento); ?>

                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <td width="50%"  style="padding-left: 20px">
                            <?php if(isset($r->aprazamento)): ?>
                                Horários: <?php echo e($r->aprazamento); ?>

                            <?php endif; ?>
                        </td>
                        <td width="50%"  style="padding-left: 20px">
                            <?php if(isset($r->aprazamento)): ?>
                                Horários: <?php echo e($r->aprazamento); ?>

                            <?php endif; ?>
                        </td>
                    </tr>
                <tr><td width="50%"></td><td width="50%"></td></tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php endif; ?>
        </table>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('footer'); ?>
    <?php for($x=0;$x<2;$x++): ?>
        <div class="footer" style="width: 49%; display: inline-block; font-size: 10px">
            <hr>
            <small>Impresso em <?php echo e(date("d/m/Y - H:m:s")); ?></small>
            <br>
        </div>
    <?php endfor; ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.receituario', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>