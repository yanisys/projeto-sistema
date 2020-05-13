<?php $__env->startSection('header'); ?>
    <div class="font-12px">
        <h3>PREFEITURA MUNICIPAL DE URUGUAIANA</h3>
        <h3>UPA - UNIDADE DE PRONTO ATENDIMENTO (9315292)</h3>
    </div>
    <h2>Consulta</h2>
    <hr>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('conteudo'); ?>
    <div class="pagina">
        <div class="recuo font-10px">
            <div class="text-right"><b>Data do Atendimento:</b> <?php echo e(formata_data_hora($prontuario->created_at)); ?><br></div>
            <b>Prontuário N°:</b> <?php echo e($prontuario->cd_prontuario); ?><br>
            <b>Nome:</b> <?php echo e($prontuario->nm_pessoa); ?> - <?php echo e(( ($prontuario->id_sexo == 'M') ? 'Masculino' : 'Feminino')); ?> - <?php echo e(formata_data($prontuario->dt_nasc)); ?> (<?php echo e(calcula_idade($prontuario->dt_nasc)); ?>)<br>
            <b>Cartão Nacional:</b> <?php echo e($prontuario->cd_beneficiario); ?><br>
            <b>Nome da Mãe:</b> <?php echo e($prontuario->nm_mae); ?><br>
        </div>
        <div class="div-titulo">Dados Vitais e Antropomórficos</div>
        <table width="100%" class="pure-table" border="0">
            <tr>
                <td><b>P.A.</b></td>
                <td><b>Temperatura</b></td>
                <td><b>Peso</b></td>
                <td><b>Altura</b></td>
                <td><b>Massa Corporal</b></td>
                <td colspan="2"><b>Estado Nutricional</b></td>
            </tr>
            <tr>
                <td><?php echo e($prontuario->pressao_arterial); ?></td>
                <td><?php echo e($prontuario->temperatura); ?></td>
                <td><?php echo e($prontuario->peso); ?></td>
                <td><?php echo e($prontuario->altura); ?></td>
                <td><?php echo e($prontuario->massa_corporal); ?></td>
                <td colspan="2"><?php echo e($prontuario->estado_nutricional); ?>o</td>
            </tr>
            <tr>
                <td><b>Pulso</b></td>
                <td><b>F.R.</b></td>
                <td><b>Cintura</b></td>
                <td><b>Quadril</b></td>
                <td><b>Índice Cintura/Quadril</b></td>
                <td><b>Risco Associado Cintura</b></td>
                <td><b>Saturação</b></td>
            </tr>
            <tr>
                <td><?php echo e($prontuario->freq_cardiaca); ?></td>
                <td><?php echo e($prontuario->freq_respiratoria); ?></td>
                <td><?php echo e($prontuario->cintura); ?></td>
                <td><?php echo e($prontuario->quadril); ?></td>
                <td><?php echo e($prontuario->indice_cintura_quadril); ?></td>
                <td><?php echo e($prontuario->risco_cintura); ?></td>
                <td></td>
            </tr>
            <tr>
                <td colspan="2"><b>Glicemia Capilar (mg/dil)</b></td>
                <td colspan="2"><b>Escala de Coma de Glasgow</b></td>
                <td colspan="3"><b></b></td>
            </tr>
            <tr>
                <td colspan="2"><?php echo e((!empty($prontuario->glicemia_capilar) ? $prontuario->glicemia_capilar : 'Não Avaliado' )); ?></td>
                <td colspan="2"><?php echo e((!empty($prontuario->escore_glasgow) ? escala_de_coma_glasgow($prontuario->escore_glasgow) : 'Não Avaliado' )); ?></td>
                <td colspan="3"></td>
            </tr>
        </table>

        <?php if(count($atendimento_subjetivo_descricao) > 0): ?>
            <div class="div-titulo">Informações do Paciente</div>
            <div class="recuo recuo-top">
                <p><b>Queixa / Exame Físico:</b>  </p>
            </div>
            <div>
                <table width="100%" class="pure-table" border="0">
                    <tr>
                        <td><b>Descrição</b></td>
                        <td><b>Profissional</b></td>
                        <td><b>Data/Hora</b></td>
                    </tr>
                    <?php $__currentLoopData = $atendimento_subjetivo_descricao; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $asd): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($asd->descricao_subjetivo); ?></td>
                            <td><?php echo e($asd->nm_pessoa); ?> - CRM <?php echo e($asd->cd_ocupacao); ?> - <?php echo e($asd->nm_ocupacao); ?> </td>
                            <td><?php echo e(formata_data_hora($asd->created_at)); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </table>
            </div>
        <?php endif; ?>
        <div class="recuo">
            <?php if(isset($atendimento_medico)): ?>
                <p class="recuo"> </p>
                <p><b>História Clínica:</b> </p>
                <p class="recuo"><?php echo e($atendimento_medico->historia_clinica); ?></p>
                <p><b>Adesão a Tratamentos Prescritos:</b> </p>
                <p class="recuo"><?php echo e($atendimento_medico->adesao_tratamentos); ?></p>
                <p><b>Acompanhante/Cuidador:</b> <?php echo e($atendimento_medico->acompanhante); ?></p>
                <b>Profissional:</b> <?php echo e($atendimento_medico->nm_pessoa); ?> - CRM <?php echo e($atendimento_medico->cd_ocupacao); ?> - <?php echo e($atendimento_medico->nm_ocupacao); ?> <br>
                <b>Data/Hora:</b> <?php echo e(formata_data_hora($atendimento_medico->created_at)); ?> <br>
            <?php endif; ?>
        </div>

        <?php if(count($avaliacao_descricao) > 0): ?>
            <div class="div-titulo">Avaliação Médica</div>
            <div class="recuo recuo-top">
                <?php $__currentLoopData = $avaliacao_descricao; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $av): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <p>
                        <b>Avaliação:</b> <?php echo e($av->descricao_avaliacao); ?> <br>
                        <b>Profissional:</b> <?php echo e($av->nm_pessoa); ?> - CRM <?php echo e($av->cd_ocupacao); ?> - <?php echo e($av->nm_ocupacao); ?> <br>
                        <b>Data/Hora:</b> <?php echo e(formata_data_hora($av->created_at)); ?>

                    </p>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <table width="100%" class="pure-table" border="0">
                <tr>
                    <td><b>Tipo</b></td>
                    <td><b>CID</b></td>
                    <td><b>Acidente</b></td>
                    <td><b>Data do Primeiro Sintoma</b></td>
                    <td><b>Profissional</b></td>
                    <td><b>Data/Hora</b></td>
                </tr>
                <?php $__currentLoopData = $atendimento_avaliacao_cid; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cid): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e(($cid->cid_principal == 'S' ? 'PRIMÁRIO' : 'SECUNDÁRIO')); ?></td>
                        <td><?php echo e($cid->cd_cid); ?> - <?php echo e($cid->nm_cid); ?></td>
                        <td>Trabalho:
                            <?php switch($cid->diagnostico_trabalho):
                                case ('S'): ?>
                                    Sim
                                <?php break; ?>
                                <?php case ('N'): ?>
                                    Não
                                <?php break; ?>
                                <?php default: ?>
                                    Não Informado
                            <?php endswitch; ?>
                            Trânsito:
                            <?php switch($cid->diagnostico_transito):
                                case ('S'): ?>
                                    Sim
                                <?php break; ?>
                                <?php case ('N'): ?>
                                    Não
                                <?php break; ?>
                                <?php default: ?>
                                    Não Informado
                            <?php endswitch; ?> </td>
                        <td><?php echo e(formata_data($cid->dt_primeiros_sintomas)); ?></td>
                        <td><?php echo e($cid->nm_pessoa); ?> - CRM <?php echo e($cid->cd_ocupacao); ?> - <?php echo e($cid->nm_ocupacao); ?> </td>
                        <td><?php echo e(formata_data_hora($cid->created_at)); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </table>
        <?php endif; ?>

        <?php if(count($atendimento_procedimento) > 0): ?>

            <div class="div-titulo">Procedimentos</div>
            <table width="100%" class="pure-table" border="0">
                <tr>
                    <td><b>Procedimento</b></td>
                    <td><b>Data/Hora Solicitação</b></td>
                    <td><b>Profissional Solicitante</b></td>
                    <td><b>Data/Hora Realização</b></td>
                    <td><b>Realizador</b></td>
                </tr>
                <?php $__currentLoopData = $atendimento_procedimento; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ap): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($ap->cd_procedimento." - ".$ap->nm_procedimento); ?></td>
                        <td><?php echo e(formata_data_hora($ap->dt_hr_solicitacao)); ?></td>
                        <td><?php echo e($ap->nm_solicitante); ?> - CRM <?php echo e($ap->cd_ocupacao_solicitante); ?> - <?php echo e($ap->nm_ocupacao_solicitante); ?></td>
                        <td><?php echo e(formata_data_hora($ap->dt_hr_execucao)); ?></td>
                        <td><?php echo e($ap->nm_executante); ?> - CRM <?php echo e($ap->cd_ocupacao_executante); ?> - <?php echo e($ap->nm_ocupacao_executante); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </table>
        <?php endif; ?>

        <?php if((isset($atendimento_medico)) && $atendimento_medico->motivo_alta > 0): ?>
            <div class="div-titulo">Alta</div>
            <div class="recuo">
                <b>Motivo da Alta:</b> <?php echo e(arrayPadrao('motivo_alta')[$atendimento_medico->motivo_alta]); ?><br>
                <b>Descricao:</b> <?php echo e($atendimento_medico->descricao_alta); ?><br>
            </div>
        <?php endif; ?>

    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('footer'); ?>
    <hr>
    <small>Impresso em <?php echo e(date("d/m/Y - H:m:s")); ?></small>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.relatorio', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>