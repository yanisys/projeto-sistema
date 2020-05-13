<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('public/css/relatorios/prontuario_v1.0.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('header'); ?>
    <div class="font-12px">
        <h3><?php echo e('PREFEITURA MUNICIPAL DE '.session('cidade_estabelecimento')); ?></h3>
        <h3><?php echo e(session('nm_estabelecimento').' ('.session('cnes_estabelecimento').')'); ?></h3>
    </div>
    <h2><?php echo e((isset($titulo) ? $titulo : 'Sem Título')); ?></h2>
    <hr>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('conteudo'); ?>
    <div class="pagina">
    <?php $__currentLoopData = $relatorio; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php if($rel->quebrar_pagina && $loop->iteration != 1): ?>
            </div>
            <div class="pagina">
        <?php endif; ?>

        <!-- INICIALIZANDO VARIAVEIS -->
        <?php
            $totalizar = null;
            $limite_campos = null;
            foreach ($rel->totalizar as $tot) {
                $totalizar[$tot] = true;
                $totais[$tot] = 0;
            }
            foreach ($rel->limite_campos as $tot){
                $limite_campos[$tot] = true;
                $limite_totais[$tot] = 0;
            }
        ?>

        <?php if(!empty($rel->titulo)): ?>
            <h2><?php echo e($rel->titulo); ?></h2>
        <?php endif; ?>

        <!-- TABELA DOS DADOS -->
        <table width="100%" class="pure-table" border="0">
            <?php $__currentLoopData = $rel->dados; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dados): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <!-- CABECALHO -->
                <?php if($loop->iteration == 1): ?>
                    <tr>
                    <?php $__currentLoopData = $dados; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <td><b><?php echo e((($key !== $loop->iteration-1) ? title_case($key) : '')); ?> </b></td>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tr>
                <?php endif; ?>
                <!-- LIMITE DE LINHAS -->
                <?php if($rel->limite > 0 && $loop->iteration > $rel->limite): ?>
                    <?php $__currentLoopData = $dados; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if(isset($limite_campos[$key])): ?>
                            <?php ($limite_totais[$key] += $value); ?>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                    <tr>
                    <!-- CELULAS DOS DADOS -->
                    <?php $__currentLoopData = $dados; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <td><?php echo e($value); ?> </td>
                        <?php if(isset($totalizar[$key])): ?>
                            <?php ($totais[$key] += $value); ?>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tr>
                <?php endif; ?>

                <!-- RODAPE - TOTALIZANDO LINHAS EXCEDENTE DO LIMITE-->
                <?php if($loop->last && isset($limite_campos) && $loop->iteration > $rel->limite ): ?>
                    <tr>
                    <?php $__currentLoopData = $dados; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if($loop->iteration == 1 && $rel->limite_nome != ''): ?>
                            <td>Outros</td>
                        <?php elseif(isset($limite_campos[$key])): ?>
                            <td><?php echo e(round($limite_totais[$key],2)); ?></td>
                        <?php else: ?>
                            <td></td>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tr>
                <?php endif; ?>
                <!-- RODAPE - TOTALIZANDO COLUNAS-->
                <?php if($loop->last && isset($totalizar) ): ?>
                    <tr>
                    <?php $__currentLoopData = $dados; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if($loop->iteration == 1 && $rel->totalizar_nome != ''): ?>
                            <td><b>Total</b></td>
                        <?php elseif(isset($totalizar[$key])): ?>
                            <?php if(isset($limite_totais[$key])): ?>
                                    <?php ($totais[$key] += $limite_totais[$key]); ?>
                                    <?php ($limite_totais[$key] = 0); ?>
                            <?php endif; ?>

                            <td><b><?php echo e(round($totais[$key],2)); ?></b></td>
                        <?php else: ?>
                            <td></td>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tr>
                <?php endif; ?>

            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </table>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('footer'); ?>
    <hr>
    <small>Impresso em <?php echo e(date("d/m/Y - H:m:s")); ?></small>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.relatorio', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>