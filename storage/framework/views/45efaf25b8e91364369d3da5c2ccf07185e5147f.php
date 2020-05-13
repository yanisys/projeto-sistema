<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('public/css/relatorios/prescricao.css')); ?>">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('header'); ?>
    <div class="font-12px">
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
        <b><?php echo e($titulo); ?></b>
    </div>
    <hr>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('conteudo'); ?>
    <div class="pagina">
        <?php $__currentLoopData = $status; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $st): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if($st=='C'): ?>
                <table class="pure-table-bordered cinza" border="0">
            <?php else: ?>
                <table class="pure-table-bordered" border="0">
            <?php endif; ?>
            <?php if(isset($prescricao_dieta)): ?>
                <?php $__currentLoopData = $prescricao_dieta; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$pd): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($st == $pd->status): ?>
                        <?php if($key===0): ?>
                            <tr><th>Dieta</th></tr>
                        <?php endif; ?>
                        <?php echo e($contador++); ?>

                        <tr>
                            <td style="padding-left: 20px; width: 500px;"><?php echo e($contador.") ".arrayPadrao('dieta')[$pd->dieta]." - ".arrayPadrao('via')[$pd->via_dieta].(isset($pd->descricao_dieta) ? " - Obs: ".$pd->descricao_dieta : "")); ?></td>
                            <td style="width: 175px"><?php echo e($pd->aprazamento); ?><br><br></td>
                        </tr>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
            <?php if(isset($prescricao_csv)): ?>
                <?php $__currentLoopData = $prescricao_csv; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$pc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($st == $pc->status): ?>
                        <?php if($key===0): ?>
                            <tr><th>CSV</th></tr>
                        <?php endif; ?>
                        <?php echo e($contador++); ?>

                        <tr>
                            <td style="padding-left: 20px; width: 500px;"><?php echo e($contador.") De ".$pc->intervalo_csv." em ".$pc->intervalo_csv." ".($pc->intervalo_csv == 1 ? arrayPadrao('periodo')[$pc->tp_intervalo_csv] : arrayPadrao('periodo_plural')[$pc->tp_intervalo_csv]).(isset($pc->intervalo_csv) ? " - Obs: ".$pc->descricao_csv : "")); ?></td>
                            <td style="width: 175px">
                            <?php if(isset($pc->aprazamento)): ?>
                                 <?php echo e($pd->aprazamento); ?>

                            <?php endif; ?>
                            </td>
                        </tr>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
            <?php if(isset($prescricao_outros_cuidados)): ?>
                <?php $__currentLoopData = $prescricao_outros_cuidados; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$poc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($st == $poc->status): ?>
                        <?php if($key===0): ?>
                            <tr><th>Outros Cuidados</th></tr>
                        <?php endif; ?>
                        <?php echo e($contador++); ?>

                        <tr>
                            <td style="padding-left: 20px; width: 500px;"><?php echo e($contador.") ".arrayPadrao('posicoes_enfermagem')[$poc->posicao].(isset($poc->descricao_posicao) ? " - Obs: ".$poc->descricao_posicao : "")); ?></td>
                        </tr>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
            <?php if(isset($prescricao_oxigenoterapia)): ?>
                <?php $__currentLoopData = $prescricao_oxigenoterapia; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$po): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($st == $po->status): ?>
                        <?php if($key===0): ?>
                            <tr><th>Oxigenoterapia</th></tr>
                        <?php endif; ?>
                        <?php echo e($contador++); ?>

                        <tr>
                            <td style="padding-left: 20px; width: 500px;"><?php echo e($contador.") ".$po->qtde_oxigenio." L/min - ".arrayPadrao('administracao_oxigenio')[$po->administracao_oxigenio].(isset($po->descricao_oxigenio) ? " - Obs: ".$po->descricao_oxigenio : "")); ?></td>
                            <td style="width: 175px"><?php echo e($po->aprazamento); ?><br><br></td>
                        </tr>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
            <?php if(isset($prescricao_medicacao)): ?>
                <?php $__currentLoopData = $prescricao_medicacao; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$pm): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($st == $pm->status): ?>
                        <?php if($key===0): ?>
                            <tr><th>Medicação</th></tr>
                        <?php endif; ?>
                        <?php echo e($contador++); ?>

                        <tr>
                            <td style="padding-left: 20px; border-bottom: none"><?php echo e($contador.") ".$pm->nm_produto." - ".$pm->dose." ".$pm->abreviacao.", ".arrayPadrao('via')[$pm->tp_via].", de ".$pm->intervalo." em ".$pm->intervalo." ".($pm->intervalo > 1 ? arrayPadrao('periodo_plural')[$pm->tp_intervalo] : arrayPadrao('periodo')[$pm->tp_intervalo]).", durante ".$pm->prazo." ".($pm->prazo > 1 ? arrayPadrao('periodo_plural')[$pm->tp_prazo] : arrayPadrao('periodo')[$pm->tp_prazo])); ?></td>
                            <td style="width: 175px; border-bottom: none">
                                <?php if(isset($pm->aprazamento)): ?>
                                    <?php echo e($pm->aprazamento); ?>

                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" style="padding-left: 20px;">
                                <?php if(isset($pm->observacao_medicamento)): ?>
                                    Obs: <?php echo e($pm->observacao_medicamento); ?>

                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
            </table>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('footer'); ?>

    <hr>
    <div>
        <b>MÉDICO RESPONSÁVEL: <?php echo e($medico->nm_medico); ?> - <?php echo e($medico->conselho." ".$medico->nr_conselho); ?></b><br>
        <small>Impresso em <?php echo e(date("d/m/Y - H:m:s")); ?></small>
    </div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.relatorio', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>