<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('public/css/relatorios/prontuario_v1.0.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('header'); ?>
        <?php if($estabelecimento->tp_estabelecimento === 'U'): ?>
            <div id="aviso">Esta conta será paga com recursos públicos</div>
        <?php endif; ?>
        <div class="font-12px">
            <h3><?php echo e('PREFEITURA MUNICIPAL DE '.session('cidade_estabelecimento')); ?></h3>
            <h3><?php echo e(session('nm_estabelecimento').' ('.session('cnes_estabelecimento').')'); ?></h3>
        </div>
    <h2>Consulta</h2>
    <hr>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('conteudo'); ?>
    <div class="pagina">
        <div class="recuo font-10px" style="display: inline-block;">
            <b>Data do Atendimento:</b> <?php echo e(formata_data_hora($prontuario->prontuario_created_at)); ?><br>
            <b>Prontuário N°:</b> <?php echo e($prontuario->cd_prontuario); ?><br>
            <b>Nome:</b> <?php echo e($prontuario->nm_pessoa); ?> - <?php echo e(( ($prontuario->id_sexo == 'M') ? 'Masculino' : 'Feminino')); ?> - <?php echo e(formata_data($prontuario->dt_nasc)); ?> (<?php echo e(calcula_idade($prontuario->dt_nasc)); ?>)<br>
            <b>Cartão Nacional:</b> <?php echo e($prontuario->cd_beneficiario); ?><br>
            <b>Nome da Mãe:</b> <?php echo e($prontuario->nm_mae); ?><br>
        </div>
        <div class="foto-pessoa" style="display: inline-block;">
            <img src="<?php echo e((isset($l->nm_arquivo) ?  asset("storage/app/images/pessoas/".$prontuario->cd_pessoa."/principal/".$prontuario->cd_pessoa.'.png'): asset('public/images/branco.png'))); ?>">
        </div>
        <div class="div-titulo">Consulta de enfermagem</div>
        <table width="100%" class="pure-table" border="0">
            <tr>
                <td class="maiusculas" colspan="12">
                    <b>
                        Profissional:
                        <?php echo e($prontuario->nm_ocupacao." ".$prontuario->nm_profissional."  -  ".$prontuario->conselho.": ".$prontuario->nr_conselho." - "); ?>

                        <?php echo e((!empty($prontuario->created_at) ? formata_data_hora($prontuario->created_at) : '' )); ?>

                    </b>
                </td>
            </tr>
            <tr>
                <td colspan="12"><b>Motivo da consulta(Queixa principal)</b></td>
            </tr>
            <tr>
                <td colspan="12"><?php echo e($prontuario->avaliacao); ?></td>
            </tr>
            <tr>
                <td colspan="2"><b>Cintura</b></td>
                <td colspan="2"><b>Quadril</b></td>
                <td colspan="2"><b>Índice Cintura/Quadril</b></td>
                <td colspan="2"><b>Peso</b></td>
                <td colspan="2"><b>Altura</b></td>
                <td colspan="2"><b>Massa corporal</b></td>
            </tr>
            <tr>
                <td colspan="2"><?php echo e($prontuario->cintura); ?></td>
                <td colspan="2"><?php echo e($prontuario->quadril); ?></td>
                <td colspan="2"><?php echo e($prontuario->indice_cintura_quadril); ?></td>
                <td colspan="2"><?php echo e($prontuario->peso); ?></td>
                <td colspan="2"><?php echo e($prontuario->altura); ?></td>
                <td colspan="2"><?php echo e($prontuario->massa_corporal); ?></td>
            </tr>
            <tr>
                <td colspan="2"><b>Pressão arterial</b></td>
                <td colspan="2"><b>Temperatura</b></td>
                <td colspan="2"><b>Freq. cardíaca/ pulso</b></td>
                <td colspan="2"><b>Freq. respiratória</b></td>
                <td colspan="2"><b>Estado nutricional</b></td>
                <td colspan="2"><b>Risco Associado Cintura</b></td>
            </tr>
            <tr>
                <td colspan="2"><?php echo e($prontuario->pressao_arterial); ?></td>
                <td colspan="2"><?php echo e($prontuario->temperatura); ?></td>
                <td colspan="2"><?php echo e($prontuario->freq_cardiaca); ?></td>
                <td colspan="2"><?php echo e($prontuario->freq_respiratoria); ?></td>
                <td colspan="2"><?php echo e($prontuario->estado_nutricional); ?></td>
                <td colspan="2"><?php echo e($prontuario->risco_cintura); ?></td>
            </tr>
            <tr>
                <td colspan="2"><b>Glicemia Capilar (mg/dil)</b></td>
                <td colspan="2"><b>Saturação</b></td>
                <td colspan="2"><b>Abertura ocular</b></td>
                <td colspan="2"><b>Resposta verbal</b></td>
                <td colspan="2"><b>Resposta motora</b></td>
                <td colspan="2"><b>Escala de Glasgow</b></td>

            </tr>
            <tr>
                <td colspan="2"><?php echo e((!empty($prontuario->glicemia_capilar) ? $prontuario->glicemia_capilar : 'Não Avaliado' )); ?></td>
                <td colspan="2"><?php echo e($prontuario->saturacao); ?></td>
                <td colspan="2"><?php echo e((!empty($prontuario->abertura_ocular) ? arrayPadrao('abertura_ocular')[$prontuario->abertura_ocular] : '')); ?></td>
                <td colspan="2"><?php echo e((!empty($prontuario->resposta_verbal) ? arrayPadrao('resposta_verbal')[$prontuario->resposta_verbal] : '')); ?></td>
                <td colspan="2"><?php echo e((!empty($prontuario->resposta_motora) ? arrayPadrao('resposta_motora')[$prontuario->resposta_motora] : '')); ?></td>
                <td colspan="2"><?php echo e((!empty($prontuario->escore_glasgow) ? escala_de_coma_glasgow($prontuario->escore_glasgow) : 'Não Avaliado' )); ?></td>
            </tr>
            <tr>
                <td colspan="4"><b>Exames apresentados</b></td>
                <td colspan="4"><b>Escala de dor</b></td>
                <td colspan="4"><b>Medicamentos em uso</b></td>
            </tr>
            <tr>
                <td colspan="4"><?php echo e($prontuario->exames_apresentados); ?></td>
                <td colspan="4"><?php echo e(($prontuario->nivel_de_dor )); ?></td>
                <td colspan="4">
                    <?php $__currentLoopData = $medicamentos_em_uso; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $mu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php echo e(($k > 0) ? ', ': ''); ?>

                        <?php echo e($mu->descricao_medicamento); ?>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </td>
            </tr>
            <tr>
                <td colspan="4"><b>Alergias</b></td>
                <td colspan="4"><b>História médica pregressa</b></td>
                <td colspan="4"><b>Cirurgias prévias</b></td>
            </tr>
            <tr>
                <td colspan="4">
                    <?php $__currentLoopData = $alergias; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php echo e(($k > 0) ? ', ': ''); ?>

                        <?php echo e($a->nm_alergia); ?>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </td>
                <td class="maiusculas" colspan="4">
                    <?php $__currentLoopData = $historia_medica; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $hm): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php echo e(($k > 0) ? ', ': ''); ?>

                        <?php echo e($hm->nm_cid."(".$hm->cd_cid.")"); ?>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </td>
                <td class="maiusculas" colspan="4">
                    <?php $__currentLoopData = $cirurgias_previas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $cp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php echo e(($k > 0) ? ', ': ''); ?>

                        <?php echo e(formata_data($cp->dt_cirurgia).": ".$cp->descricao_cirurgia); ?>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </td>
            </tr>
            <tr>
                <td colspan="12"><b>Conduta</b></td>
            </tr>
            <tr>
                <?php if(isset($prontuario->classificacao)): ?>
                    <td colspan="12"><?php echo e(($prontuario->classificacao <= 6 ? "CLASSIFICAÇÃO DE RISCO: " : ""). arrayPadrao('classificar_risco')[$prontuario->classificacao]); ?>

                        <?php endif; ?>
                        <?php if(!empty($prontuario->classificacao_nova)): ?>
                            (RECLASSIFICADO ÀS <?php echo e(formata_hora($prontuario->hora_alteracao)); ?>

                            DE <?php echo e(arrayPadrao('classificar_risco')[$prontuario->classificacao_anterior]); ?>

                            PARA <?php echo e(arrayPadrao('classificar_risco')[$prontuario->classificacao_nova]); ?>.
                            <?php echo e(strtoupper($prontuario->motivo)); ?>)
                        <?php endif; ?>
                    </td>
            </tr>
        </table>

        <div class="div-titulo">Consulta Médica</div>
        <?php if(isset($atendimento_medico)): ?>
        <table width="100%" class="pure-table" border="0">
            <tr>
                <td class="maiusculas" colspan="12">
                    <b>
                        Profissional:
                        <?php echo e($atendimento_medico->nm_ocupacao." ".$atendimento_medico->nm_pessoa."  -  ".$atendimento_medico->conselho.": ".$atendimento_medico->nr_conselho." - "); ?>

                        <?php echo e((!empty($atendimento_medico->created_at) ? formata_data_hora($atendimento_medico->created_at) : '' )); ?>

                    </b>
                </td>
            </tr>
            <tr>
                <td colspan="12" style="padding-left: 20px;"><b>SUBJETIVO</b></td>
            </tr>
            <tr>
                <td colspan="12"><b>Motivo da consulta(Queixa principal)</b></td>
            </tr>
            <tr>
                <td colspan="12">
                <?php $__currentLoopData = $atendimento_subjetivo_descricao; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $asd): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php echo e(($k > 0) ? ' - ': ''); ?>

                    <?php echo e($asd->descricao_subjetivo); ?>

                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </td>
            </tr>
            <tr>
                <td colspan="12" style="padding-left: 20px;"><b>OBJETIVO</b></td>
            </tr>
            <tr>
                <td colspan="12"><b>Avaliação médica</b></td>
            </tr>
            <tr>
                <td colspan="12">
                    <?php echo e($atendimento_medico->descricao_objetivo); ?>

                </td>
            </tr>
            <tr>
                <td colspan="12" style="padding-left: 20px;"><b>AVALIAÇÃO</b></td>
            </tr>
            <tr>
                <td colspan="12"><b>Descrição</b></td>
            </tr>
            <tr>
                <td colspan="12">
                    <?php $__currentLoopData = $avaliacao_descricao; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $ad): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php echo e(($k > 0) ? ' - ': ''); ?>

                        <?php echo e($ad->descricao_avaliacao); ?>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </td>
            </tr>
            <tr>
                <td colspan="12"><b>Disgnóstico</b></td>
            </tr>
            <tr>
                <td colspan="2"><b>Tipo</b></td>
                <td colspan="2"><b>CID</b></td>
                <td colspan="2"><b>Acidente</b></td>
                <td colspan="2"><b>Data do Primeiro Sintoma</b></td>
                <td colspan="2"><b>Profissional</b></td>
                <td colspan="2"><b>Data/Hora</b></td>
            </tr>
            <?php $__currentLoopData = $atendimento_avaliacao_cid; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cid): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td colspan="2"><?php echo e(($cid->cid_principal == 'S' ? 'PRIMÁRIO' : 'SECUNDÁRIO')); ?></td>
                    <td colspan="2"><?php echo e($cid->cd_cid); ?> - <?php echo e($cid->nm_cid); ?></td>
                    <td colspan="2">Trabalho:
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
                    <td colspan="2"><?php echo e(formata_data($cid->dt_primeiros_sintomas)); ?></td>
                    <td colspan="2"><?php echo e($cid->nm_ocupacao); ?> <?php echo e($cid->nm_pessoa); ?> - <?php echo e($cid->conselho.": ". $cid->nr_conselho); ?> </td>
                    <td colspan="2"><?php echo e(formata_data_hora($cid->created_at)); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td colspan="12" style="padding-left: 20px;"><b>PLANO</b></td>
            </tr>
            <tr>
                <td colspan="12"><b>Intervenção/ Procedimentos</b></td>
            </tr>
            <tr>
                <td colspan="12"><?php echo e($atendimento_medico->descricao_plano); ?></td>
            </tr>

            <tr>
                <td colspan="12" style="padding-left: 20px;"><b>CONDUTA</b></td>
            </tr>

            <?php if(isset($prescricao)): ?>
                <?php $__currentLoopData = $prescricao; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tipo_prescricao): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if(isset($tipo_prescricao)): ?>
                        <?php $__currentLoopData = $tipo_prescricao; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td colspan="12"><b><?php echo e($p->tp_prescricao); ?></b></td>
                            </tr>
                            <?php if($p->tp_prescricao == 'PRESCRICAO_AMBULATORIAL'): ?>
                            <tr>
                                <td colspan="12"><b>Prescrição Nº<?php echo e($p->cd_prescricao); ?></b></td>
                            </tr>
                            <?php endif; ?>
                            <?php if(isset($p->itens)): ?>
                                <?php $contador=0; ?>
                                <?php if(isset($p->itens['exame_laboratorial'][0])): ?>
                                    <tr>
                                        <td colspan="12"><b>Exames laboratoriais</b></td>
                                    </tr>
                                    <?php $__currentLoopData = $p->itens['exame_laboratorial']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exame): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td colspan="12"><?php echo e(arrayPadrao('exames_laboratoriais')[$exame->cd_exame_laboratorial].(isset($exame->observacao_exame_laboratorial) ? ' - '.$exame->observacao_exame_laboratorial : '')); ?></td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                                <?php if(isset($p->itens['dieta'][0])): ?>
                                    <?php $__currentLoopData = $p->itens['dieta']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$pd): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php if($key===0): ?>
                                            <tr><td colspan="12"><b>Dieta</b></td></tr>
                                        <?php endif; ?>
                                            <?php echo e($contador++); ?>

                                        <tr>
                                            <td colspan="5"><?php echo e($contador.") ".arrayPadrao('dieta')[$pd->dieta]." - ".arrayPadrao('via')[$pd->via_dieta]); ?></td>
                                            <td colspan="3"><?php echo e("De ".$pd->intervalo_dieta."/ ".$pd->intervalo_dieta." ".arrayPadrao('periodo')[$pd->tp_intervalo_dieta].", durante ".$pd->prazo_dieta." ".arrayPadrao('periodo')[$pd->tp_prazo_dieta]); ?></td>
                                            <td colspan="4"><?php echo e($pd->aprazamento); ?></td>
                                            <?php if(isset($pd->descricao_dieta)): ?>
                                                <tr><td colspan="12"><?php echo e($pd->descricao_dieta); ?></td></tr>
                                            <?php endif; ?>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                                <?php if(isset($p->itens['csv'][0])): ?>
                                    <?php $__currentLoopData = $p->itens['csv']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$pc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php if($key===0): ?>
                                            <tr><td colspan="12"><b>CSV</b></td></tr>
                                        <?php endif; ?>
                                        <?php echo e($contador++); ?>

                                        <tr>
                                            <td colspan="5"><?php echo e($contador.") ".$pc->descricao_csv); ?></td>
                                            <td colspan="3"><?php echo e("De ".$pc->intervalo_csv."/ ".$pc->intervalo_csv." ".arrayPadrao('periodo')[$pc->tp_intervalo_csv].", durante ".$pc->prazo_csv." ".arrayPadrao('periodo')[$pc->tp_prazo_csv]); ?></td>
                                            <td colspan="4"><?php echo e($pc->aprazamento); ?></td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                                <?php if(isset($p->itens['outros_cuidados'][0])): ?>
                                    <?php $__currentLoopData = $p->itens['outros_cuidados']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$poc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php if($key===0): ?>
                                            <tr><td colspan="12"><b>Outros cuidados</b></td></tr>
                                        <?php endif; ?>
                                        <?php echo e($contador++); ?>

                                        <tr>
                                            <td colspan="12"><?php echo e($contador.") ".arrayPadrao('posicoes_enfermagem')[$poc->posicao]." - ".$poc->descricao_posicao); ?></td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                                <?php if(isset($p->itens['oxigenoterapia'][0])): ?>
                                    <?php $__currentLoopData = $p->itens['oxigenoterapia']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$pox): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php if($key===0): ?>
                                            <tr><td colspan="12"><b>Oxigenoterapia</b></td></tr>
                                        <?php endif; ?>
                                        <?php echo e($contador++); ?>

                                        <tr>
                                            <td colspan="5"><?php echo e($contador.") ".$pox->qtde_oxigenio."L/min, ".arrayPadrao('administracao_oxigenio')[$pox->administracao_oxigenio]." - ".$pox->descricao_oxigenio); ?></td>
                                            <td colspan="3"><?php echo e("De ".$pox->intervalo_oxigenoterapia."/ ".$pox->intervalo_oxigenoterapia." ".arrayPadrao('periodo')[$pox->tp_intervalo_oxigenoterapia].", durante ".$pox->prazo_oxigenoterapia." ".arrayPadrao('periodo')[$pox->tp_prazo_oxigenoterapia]); ?></td>
                                            <td colspan="4"><?php echo e($pox->aprazamento); ?></td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                                <?php if(isset($p->itens['medicacao'][0])): ?>
                                    <?php $__currentLoopData = $p->itens['medicacao']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$med): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php if($key===0): ?>
                                            <tr><td colspan="12"><b>Medicação</b></td></tr>
                                        <?php endif; ?>
                                        <?php echo e($contador++); ?>

                                        <tr>
                                            <td colspan="5"><?php echo e($contador.") ".$med->nm_produto." - ".$med->dose." ".$med->abreviacao.", ".arrayPadrao('via')[$med->tp_via]); ?></td>
                                            <td colspan="3"><?php echo e("De ".$med->intervalo."/ ".$med->intervalo." ".arrayPadrao('periodo')[$med->tp_intervalo].", durante ".$med->prazo." ".arrayPadrao('periodo')[$med->tp_prazo]); ?></td>
                                            <td colspan="4"><?php echo e($med->aprazamento); ?></td>
                                        <?php if(isset($med->observacao_medicamento)): ?>
                                            <tr><td colspan="12"><?php echo e($med->observacao_medicamento); ?></td></tr>
                                        <?php endif; ?>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>

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
                        <td><?php echo e($ap->nm_ocupacao_solicitante); ?> <?php echo e($ap->nm_solicitante); ?> - <?php echo e($ap->conselho_solicitante); ?>: <?php echo e($ap->nr_conselho_solicitante); ?> </td>
                        <td><?php echo e(formata_data_hora($ap->dt_hr_execucao)); ?></td>
                        <td><?php if(isset($ap->nm_executante)): ?><?php echo e($ap->nm_ocupacao_executante); ?> <?php echo e($ap->nm_executante); ?> - <?php echo e($ap->conselho_executante); ?>: <?php echo e($ap->nr_conselho_executante); ?><?php endif; ?></td>
                    </tr>
                    <?php if(isset($ap->descricao_solicitacao)): ?>
                    <tr>
                        <td colspan="5">
                            <b>Descrição da solicitação:</b> <?php echo e($ap->descricao_solicitacao); ?>

                        </td>
                    </tr>
                    <?php endif; ?>
                    <?php if(isset($ap->descricao_execucao)): ?>
                    <tr>
                        <td colspan="5">
                            <b>Descrição da execução:</b> <?php echo e($ap->descricao_execucao); ?>

                        </td>
                    </tr>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </table>
        <?php endif; ?>

        <?php if(count($atendimento_evolucao) > 0): ?>
            <div class="div-titulo">Evolução Clínica</div>
            <table width="100%" class="pure-table" border="0">
                <tr>
                    <td><b>Data/ Hora</b></td>
                    <td><b>Sala/ Leito</b></td>
                    <td><b>Profissional</b></td>
                    <td><b>Descrição</b></td>
                </tr>
                <?php $__currentLoopData = $atendimento_evolucao; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ae): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e(formata_data_hora($ae->created_at)); ?></td>
                        <td><?php echo e($ae->nm_sala); ?> <?php echo e(((isset($ae->cd_leito) && $ae->cd_leito > 0) ? "/ ".arrayPadrao('leitos')[$ae->cd_leito] : '')); ?></td>
                        <td><?php echo e($ae->nm_ocupacao); ?> <?php echo e($ae->nm_pessoa); ?> - <?php echo e($ae->conselho); ?>: <?php echo e($ae->nr_conselho); ?></td>
                        <td><?php echo e($ae->descricao_evolucao); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </table>
        <?php endif; ?>

        <?php if((isset($atendimento_medico)) && $atendimento_medico->motivo_alta > 0): ?>
            <div class="div-titulo">Alta</div>
            <div class="recuo">
                <b>Motivo da Alta:</b> <?php echo e(arrayPadrao('motivo_alta')[$atendimento_medico->motivo_alta]); ?><br>
                <b>Descrição:</b> <?php echo e($atendimento_medico->descricao_alta); ?><br>
                <b>Data/ Hora: </b><?php echo e(formata_data_hora($atendimento_medico->finished_at)); ?>

            </div>
        <?php endif; ?>

    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('footer'); ?>
    <hr>
    <small>Impresso em <?php echo e(date("d/m/Y - H:m:s")); ?></small>
    <br>
    <?php if($estabelecimento->tp_estabelecimento === 'U'): ?>
        <small><i class="text-right">Esta conta será paga com recursos públicos</i></small>
    <?php endif; ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.relatorio', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>