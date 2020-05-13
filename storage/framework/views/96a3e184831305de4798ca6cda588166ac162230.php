<?php $__env->startSection('conteudo-full'); ?>

    <div class="panel panel-primary" id="accordion">
        <div class="panel-heading">
            <button class="btn btn-primary pull-right margin-top-10" id="atualiza-lista">Atualizar <span class="fa fa-refresh"></span></button>
            <!-- <button class="btn btn-primary pull-right margin-top-10" data-toggle="modal" data-target="#modal-pesquisa" id="open">Novo atendimento</button> -->
            <button class="btn-modal-pessoa btn btn-primary pull-right margin-top-10 <?php echo e(verficaPermissaoBotao('recurso.atendimentos/novo-atendimento')); ?>"
                    data-modo="pesquisar" data-nao-validar-endereco >
                Novo atendimento
            </button>
            <h4>Fila de atendimentos</h4>
        </div>
        <div class="panel-body" style="font-size: 9pt">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title text-center">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse-atendimento">
                            <b>Aguardando Consulta de Enfermagem/ Acolhimento (<?php echo e(count($prontuario)); ?>)</b>
                        </a>
                    </h4>
                </div>
                <div id="collapse-atendimento" class="panel-collapse collapse">
                    <div class="panel-body panel-body-no-margin" id="fila-atendimentos">
                        <table class="table table-bordered table-hover table-striped table-fila" >
                            <?php $__currentLoopData = $prontuario; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td width="135"><?php echo e(formata_data_hora($p->created_at)); ?></td>
                                    <td align="center" width="30">
                                        <?php if(($p->id_sexo == 'F')): ?>
                                            <span class='fa fa-female' style='color:deeppink;'></span>
                                        <?php else: ?>
                                            <span class='fa fa-male' style='color:blue;'></span>
                                        <?php endif; ?>
                                    </td>
                                    <td> <b><?php echo e($p->nm_pessoa); ?></b><?php if(isset($p->nm_sala)): ?><span style="background-color: #ddd32f"> - CHAMADO NO PAINEL: <?php echo e($p->nm_sala); ?></span><?php endif; ?></td>
                                    <td width="10%"> <?php echo e(isset($p->dt_nasc) ? calcula_idade($p->dt_nasc) : ""); ?> </td>
                                    <td width="20%"><?php echo e($p->recepcionista); ?></td>
                                    <td align="center" width="9%">
                                        <a href="<?php echo e(route('atendimentos/acolhimento').'/'.$p->cd_prontuario); ?>" title='Ir para acolhimento' class="btn btn-primary fa fa-stethoscope <?php echo e(verficaPermissaoBotao('recurso.atendimentos/acolhimento')); ?>"></a>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </table>
                    </div>
                </div>
                <div class="panel-heading">
                    <h4 class="panel-title text-center">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse-acolhimento">
                            <b>Aguardando Atendimento Médico (<?php echo e(count($acolhimento)); ?>)</b>
                        </a>
                    </h4>
                </div>
                <div id="collapse-acolhimento" class="panel-collapse collapse">
                    <div class="panel-body panel-body-no-margin" id="fila-atendimentos">
                        <table class="table table-bordered table-hover table-striped table-fila" >
                            <?php $__currentLoopData = $acolhimento; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr id="classificacao-risco-<?php echo e($a->classificacao); ?>">
                                    <td width="135"><?php echo e(formata_data_hora($a->created_at)); ?></td>
                                    <td align="center" width="30">
                                        <?php if(($a->id_sexo == 'F')): ?>
                                            <span class='fa fa-female' style='color:deeppink;'></span>
                                        <?php else: ?>
                                            <span class='fa fa-male' style='color:blue;'></span>
                                        <?php endif; ?>
                                    </td>
                                    <td id="aguardando-atendimento-<?php echo e($a->cd_prontuario); ?>"> <b><?php echo e($a->nm_pessoa); ?></b> <?php if(isset($a->nm_sala) && ($a->horario > $a->created_at)): ?><span style="background-color: #ddd32f"> - CHAMADO NO PAINEL: <?php echo e($a->nm_sala); ?></span><?php endif; ?></td>
                                    <td width="10%"> <?php echo e(isset($a->dt_nasc) ? calcula_idade($a->dt_nasc) : ""); ?> </td>
                                    <td width="20%"><?php echo e($a->enfermeiro); ?></td>
                                    <td align="center" width="9%">
                                        <div class="btn-group" role="group">
                                            <a href="<?php echo e(route('atendimentos/atendimento-medico').'/'.$a->cd_prontuario); ?>" title='Ir para o prontuário' class='btn btn-primary fa fa-stethoscope <?php echo e(verficaPermissaoBotao('recurso.atendimentos/atendimento-medico')); ?>'></a>
                                            <a data-classificacao-atual='<?php echo e($a->classificacao); ?>' data-cd-prontuario='<?php echo e($a->cd_prontuario); ?>' title='Reclassificar risco' class="btn btn-warning fas fa-sync-alt abre-modal-reclassificar <?php echo e(verficaPermissaoBotao('recurso.atendimentos-acolhimento-salvar')); ?>"></a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </table>

                    </div>
                </div>
                <div class="panel-heading">
                    <h4 class="panel-title text-center">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse-atendidos">
                            <b>Pacientes Atendidos/ Medicina Interna (<?php echo e(count($atendimento_interno)+count($atendimentos_direto_procedimentos)); ?>)</b>
                        </a>
                    </h4>
                </div>
                <div id="collapse-atendidos" class="panel-collapse collapse">
                    <div class="panel-body panel-body-no-margin" id="fila-atendidos">
                        <table class="table table-bordered table-hover table-striped table-fila" >
                            <?php $__currentLoopData = $atendimento_interno; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ai): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td width="135"><?php echo e(formata_data_hora($ai->created_at)); ?></td>
                                    <td align="center" width="30">
                                        <?php if(($ai->id_sexo == 'F')): ?>
                                            <span class='fa fa-female' style='color:deeppink;'></span>
                                        <?php else: ?>
                                            <span class='fa fa-male' style='color:blue;'></span>
                                        <?php endif; ?>
                                    </td>
                                    <td> <b><?php echo e($ai->nm_pessoa); ?></b></td>
                                    <td width="10%"> <?php echo e(isset($ai->dt_nasc) ? calcula_idade($ai->dt_nasc) : ""); ?> </td>
                                    <td width="20%"><?php echo e($ai->medico); ?></td>
                                    <td align="center" width="9%">
                                        <div class="btn-group" role="group">
                                            <a href="<?php echo e(route('atendimentos/atendimento-medico').'/'.$ai->cd_prontuario); ?>" title='Ir para o prontuário' class='btn btn-primary fa fa-stethoscope <?php echo e(verficaPermissaoBotao('recurso.atendimentos/atendimento-medico')); ?>'></a>
                                            <a href="<?php echo e(route('atendimentos/procedimentos').'/'.$ai->cd_prontuario); ?>" title='Ir para procedimentos/ Evolução clínica' class='btn btn-success fa fa-plus-square <?php echo e(verficaPermissaoBotao('recurso.atendimentos/procedimentos')); ?>'></a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php $__currentLoopData = $atendimentos_direto_procedimentos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $adp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td width="135"><?php echo e(formata_data_hora($adp->created_at)); ?></td>
                                    <td align="center" width="30">
                                        <?php if(($adp->id_sexo == 'F')): ?>
                                            <span class='fa fa-female' style='color:deeppink;'></span>
                                        <?php else: ?>
                                            <span class='fa fa-male' style='color:blue;'></span>
                                        <?php endif; ?>
                                    </td>
                                    <td> <b><?php echo e($adp->nm_pessoa); ?></b></td>
                                    <td width="10%"> <?php echo e(isset($adp->dt_nasc) ? calcula_idade($adp->dt_nasc) : ""); ?> </td>
                                    <td width="20%"><?php echo e($adp->medico); ?></td>
                                    <td align="center" width="9%">
                                        <a href="<?php echo e(route('atendimentos/procedimentos').'/'.$adp->cd_prontuario); ?>" title='Ir para procedimentos/ Evolução clínica' class='btn btn-success fa fa-plus-square <?php echo e(verficaPermissaoBotao('recurso.atendimentos/procedimentos')); ?>'></a>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </table>
                    </div>
                </div>
                <div class="panel-heading">
                    <h4 class="panel-title text-center">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse-procedimentos">
                            <b>Aguardando Realização de Procedimentos  (<?php echo e(count($procedimentos)); ?>)</b>
                        </a>
                    </h4>
                </div>
                <div id="collapse-procedimentos" class="panel-collapse collapse ">
                    <div class="panel-body panel-body-no-margin" id="total-procedimentos">
                        <table class="table table-bordered table-hover table-striped table-fila" >
                            <?php $__currentLoopData = $procedimentos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td width="135"> <?php echo e(formata_data_hora($p->dt_hr_solicitacao)); ?></td>
                                    <td align="center" width="30">
                                        <?php if(($p->id_sexo == 'F')): ?>
                                            <span class='fa fa-female' style='color:deeppink;'></span>
                                        <?php else: ?>
                                            <span class='fa fa-male' style='color:blue;'></span>
                                        <?php endif; ?>
                                    </td>
                                    <td> <b><?php echo e($p->nm_pessoa); ?></b></td>
                                    <td width="30%"> <?php echo e($p->nm_procedimento); ?></td>
                                    <td  width="20%"> <?php echo e($p->nm_medico); ?></td>
                                    <td align="center" width="9%"><a href="<?php echo e(route('atendimentos/procedimentos').'/'.$p->cd_prontuario); ?>" title='Ir para procedimentos/ Evolução clínica' class='btn btn-success  fa fa-plus-square <?php echo e(verficaPermissaoBotao('recurso.atendimentos/procedimentos')); ?>'></a></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </table>
                    </div>
                </div>
                <div class="panel-heading">
                    <h4 class="panel-title text-center">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse-procedimentos-radiologicos">
                            <b>Aguardando Realização de Procedimentos Radiológicos (<?php echo e(count($procedimentos_radiologicos)); ?>)</b>
                        </a>
                    </h4>
                </div>
                <div id="collapse-procedimentos-radiologicos" class="panel-collapse collapse">
                    <div class="panel-body panel-body-no-margin" id="total-procedimentos">
                        <table class="table table-bordered table-hover table-striped table-fila" >
                            <?php $__currentLoopData = $procedimentos_radiologicos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td width="135"> <?php echo e(formata_data_hora($p->dt_hr_solicitacao)); ?></td>
                                    <td align="center" width="30">
                                        <?php if(($p->id_sexo == 'F')): ?>
                                            <span class='fa fa-female' style='color:deeppink;'></span>
                                        <?php else: ?>
                                            <span class='fa fa-male' style='color:blue;'></span>
                                        <?php endif; ?>
                                    </td>
                                    <td> <b><?php echo e($p->nm_pessoa); ?></b></td>
                                    <td width="30%"> <?php echo e($p->nm_procedimento); ?></td>
                                    <td width="20%"> <?php echo e($p->nm_medico); ?></td>
                                    <td align="center" width="9%"><a href="<?php echo e(route('atendimentos/procedimentos').'/'.$p->cd_prontuario); ?>" title='Ir para procedimentos/ Evolução clínica' class='btn btn-success fa fa-plus-square <?php echo e(verficaPermissaoBotao('recurso.atendimentos/procedimentos')); ?>'></a></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </table>
                    </div>
                </div>
                <div class="panel-heading">
                    <h4 class="panel-title text-center">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse-concluidos">
                            <b>Pacientes Atendidos/ Atendimentos Finalizados (<?php echo e(count($atendimento_concluido)); ?>)</b>
                        </a>
                    </h4>
                </div>
                <div id="collapse-concluidos" class="panel-collapse collapse">
                    <div class="panel-body panel-body-no-margin" id="total-concluidos">
                        <table class="table table-bordered table-hover table-striped table-fila" >
                            <?php $__currentLoopData = $atendimento_concluido; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ac): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td width="135"><b>Entrada: </b><?php echo e(formata_hora($ac->created_at)); ?> <b>Conclusão: </b> <?php echo e(formata_hora($ac->finished_at)); ?></td>
                                    <td align="center" width="30">
                                        <?php if(($ac->id_sexo == 'F')): ?>
                                            <span class='fa fa-female' style='color:deeppink;'></span>
                                        <?php else: ?>
                                            <span class='fa fa-male' style='color:blue;'></span>
                                        <?php endif; ?>
                                    </td>
                                    <td> <b><?php echo e($ac->nm_pessoa); ?></b></td>
                                    <td width="20%"> <?php echo e(isset($ac->motivo_alta) ? arrayPadrao('motivo_alta')[$ac->motivo_alta] : ''); ?></td>
                                    <td width="10%"> <?php echo e(isset($ac->dt_nasc) ? calcula_idade($ac->dt_nasc) : ""); ?> </td>
                                    <td width="29%"> <?php echo e($ac->medico); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('painel-modal'); ?>
    <?php echo $__env->make('pessoas.modal-pessoas', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <div class="modal fade" id="novo-atendimento"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Fechar</span></button>
                    <h4 class="modal-title" id="label_modal_atendimento">Selecione o tipo de atendimento</h4>
                </div>
                <div class="modal-body" id="planos_usuario">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-striped">
                            <thead>
                            <tr>
                                <th>Plano</th>
                                <th>Nº cartão</th>
                                <th class='text-center' width="190px">Ação</th>
                            </tr>
                            </thead>
                            <tbody id="tabela_plano">
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-body" id="origem_usuario" style="display: none">
                    <div class="panel-body">
                        <div class="col-md-12">
                            <div class="col-md-9">
                                <label for="Grupo">Origem</label>
                                <?php echo e(Form::select('cd_origem', isset($origem) ? $origem : [0 => "Nenhuma origem cadastrada"], "1",['class'=> "form-control", 'id' => 'cd_origem_usuario'])); ?>

                            </div>
                            <div class="col-md-3">
                                <label for="btn_ir_para_origem">&nbsp</label>
                                <button id="btn_ir_para_origem" type="button" class="btn btn-default" data-dismiss="modal">Novo atendimento</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="text-align:left !important;">
                    <div id="mensagem-novo-atendimento"></div>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal-reclassificar"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Fechar</span></button>
                    <h3 class="modal-title" id="myModalLabel">Reclassificação de risco</h3>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="avaliacao">Motivo da reclassificação</label>
                                <textarea id="motivo-reclassificacao" placeholder="Informe o motivo da troca de classificação de risco. Mínimo de 10 caracteres." class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="avaliacao">Nova classificação</label>
                                <select id="classifica" class="form-control">
                                    <option value='1' style=background-color:blue;><?php echo e(arrayPadrao('classificar_risco')[1]); ?></option>
                                    <option value='2' style=background-color:forestgreen;><?php echo e(arrayPadrao('classificar_risco')[2]); ?></option>
                                    <option value='3' style=background-color:yellow;><?php echo e(arrayPadrao('classificar_risco')[3]); ?></option>
                                    <option value='4' style=background-color:orange;><?php echo e(arrayPadrao('classificar_risco')[4]); ?></option>
                                    <option value='5' style=background-color:red;><?php echo e(arrayPadrao('classificar_risco')[5]); ?></option>
                                    <option value='6' style=background-color:black;><?php echo e(arrayPadrao('classificar_risco')[6]); ?></option>
                                </select>
                                <input type="hidden" id="classificacao_atual">
                                <input type="hidden" id="cd_prontuario_reclassificacao">
                            </div>
                        </div>
                    </div>
                </div>
                <div id="mensagem"></div>
                <div class="modal-footer" style="text-align:left !important;">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-default pull-right" id="salvar-reclassificacao">Salvar</button>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('custom_js'); ?>
    <script src=" <?php echo e(asset('public/js/jquery.cookie.js')); ?>"></script>
    <script src="<?php echo e(js_versionado('fila.js')); ?>"></script>
    <script src="<?php echo e(js_versionado('prontuario.js')); ?>"></script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.default', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>