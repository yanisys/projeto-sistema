<?php $__env->startSection('conteudo-full'); ?>

    <div class="panel panel-primary" id="accordion">
        <div class="panel-heading">
            <button class="btn btn-primary pull-right margin-top-10" id="atualiza-lista">Atualizar <span class="fa fa-refresh"></span></button>
            <button class="btn btn-primary pull-right margin-top-10" data-toggle="modal" data-target="#modal-pesquisa" id="open">Novo atendimento</button>
            <h4>Fila de atendimentos</h4>
        </div>
        <div class="panel-body" style="font-size: 9pt">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse-atendimento">
                            <b id="total-atendimentos">Aguardando acolhimento/ classificação de risco (<?php echo e(count($prontuario)); ?>)</b>
                        </a>
                    </h4>
                </div>
                <div id="collapse-atendimento" class="panel-collapse collapse">
                    <div class="panel-body panel-body-no-margin" id="fila-atendimentos">
                        <table class="table table-bordered table-hover table-striped table-fila" >
                            <?php $__currentLoopData = $prontuario; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td> <?php echo e($p->nm_pessoa); ?></td>
                                    <td> <?php echo e(isset($p->dt_nasc) ? calcula_idade($p->dt_nasc) : ""); ?> </td>
                                    <?php if(($p->id_sexo == 'F')): ?>
                                        <td align="center"><span class='fa fa-female' style='color:deeppink;'></span></td>
                                    <?php else: ?>
                                        <td align="center"><span class='fa fa-male' style='color:blue;'></span></td>
                                    <?php endif; ?>
                                    <td><?php echo e(formata_data_hora($p->created_at)); ?></td>
                                    <td  align="center" width="110"> <a href="<?php echo e(route('atendimentos/acolhimento').'/'.$p->cd_prontuario); ?>" title='Ir para acolhimento' class="btn btn-primary fa fa-stethoscope <?php echo e(verficaPermissaoBotao('recurso.atendimentos/acolhimento')); ?>"></a></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </table>
                    </div>
                </div>
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse-acolhimento">
                            <b id="total-acolhimentos">Aguardando atendimento médico (<?php echo e(count($acolhimento)); ?>)</b>
                        </a>
                    </h4>
                </div>
                <div id="collapse-acolhimento" class="panel-collapse collapse">
                    <div class="panel-body panel-body-no-margin" id="fila-atendimentos">
                        <table class="table table-bordered table-hover table-striped table-fila" >
                        <?php $__currentLoopData = $acolhimento; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr id="classificacao-risco-<?php echo e($a->classificacao); ?>">
                                <td id="aguardando-atendimento-<?php echo e($a->cd_prontuario); ?>" class='col-sm-5'> <?php echo e($a->nm_pessoa); ?><!-- <?php if(isset($a->ds_sala)): ?><span style="background-color: #dd1ac3"> - Chamado na sala <?php echo e($a->ds_sala); ?></span><?php endif; ?>--></td>
                                <td > <?php echo e(isset($a->dt_nasc) ? calcula_idade($a->dt_nasc) : ""); ?> </td>
                                <?php if(($a->id_sexo == 'F')): ?>
                                    <td align="center"><span class='fa fa-female' style='color:deeppink;'></span></td>
                                <?php else: ?>
                                    <td align="center"><span class='fa fa-male' style='color:blue;'></span></td>
                                <?php endif; ?>
                                <td><?php echo e(formata_data_hora($a->created_at)); ?></td>
                                <td width="110">
                                    <a href="<?php echo e(route('atendimentos/atendimento-medico').'/'.$a->cd_prontuario); ?>" title='Ir para o prontuário' class='btn btn-primary fa fa-stethoscope <?php echo e(verficaPermissaoBotao('recurso.atendimentos/atendimento-medico')); ?>'></a>
                                    <button type='button' style="font-size: 7pt" data-classificacao-atual='<?php echo e($a->classificacao); ?>' data-cd-prontuario='<?php echo e($a->cd_prontuario); ?>' title='Reclassificar risco' class="btn btn-warning btn-sm abre-modal-reclassificar <?php echo e(verficaPermissaoBotao('recurso.atendimentos-acolhimento-salvar')); ?>"><span class="fa fa-retweet"></span></button>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </table>

                    </div>
                </div>
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse-atendidos">
                            <b id="total-atendidos">Pacientes atendidos/ Medicina Interna (<?php echo e(count($atendimento_interno)); ?>)</b>
                        </a>
                    </h4>
                </div>
                <div id="collapse-atendidos" class="panel-collapse collapse">
                    <div class="panel-body panel-body-no-margin" id="fila-atendidos">
                        <table class="table table-bordered table-hover table-striped table-fila" >
                        <?php $__currentLoopData = $atendimento_interno; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ai): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td> <?php echo e($ai->nm_pessoa); ?></td>
                                <td> <?php echo e(isset($ai->dt_nasc) ? calcula_idade($ai->dt_nasc) : ""); ?> </td>
                                <?php if(($ai->id_sexo == 'F')): ?>
                                    <td align="center"><span class='fa fa-female' style='color:deeppink;'></span></td>
                                <?php else: ?>
                                    <td align="center"><span class='fa fa-male' style='color:blue;'></span></td>
                                <?php endif; ?>
                                <td><?php echo e(formata_data_hora($ai->created_at)); ?></td>
                                <td align="center" width="110">
                                    <a href="<?php echo e(route('atendimentos/atendimento-medico').'/'.$ai->cd_prontuario); ?>" title='Ir para o prontuário' class='btn btn-primary fa fa-stethoscope <?php echo e(verficaPermissaoBotao('recurso.atendimentos/atendimento-medico')); ?>'></a>
                                    <a href="<?php echo e(route('atendimentos/procedimentos').'/'.$ai->cd_prontuario); ?>" title='Ir para procedimentos' class='btn btn-success fa fa-plus-square <?php echo e(verficaPermissaoBotao('recurso.atendimentos/procedimentos')); ?>'></a>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </table>
                    </div>
                </div>
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse-procedimentos">
                            <b id="total-procedimentos">Aguardando realização de procedimentos  (<?php echo e(count($procedimentos)); ?>)</b>
                        </a>
                    </h4>
                </div>
                <div id="collapse-procedimentos" class="panel-collapse collapse ">
                    <div class="panel-body panel-body-no-margin" id="total-procedimentos">
                        <table class="table table-bordered table-hover table-striped table-fila" >
                            <?php $__currentLoopData = $procedimentos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td> <?php echo e($p->nm_pessoa); ?></td>
                                    <td> <?php echo e(isset($p->dt_nasc) ? calcula_idade($p->dt_nasc) : ""); ?> </td>
                                    <?php if(($p->id_sexo == 'F')): ?>
                                        <td align="center"><span class='fa fa-female' style='color:deeppink;'></span></td>
                                    <?php else: ?>
                                        <td align="center"><span class='fa fa-male' style='color:blue;'></span></td>
                                    <?php endif; ?>
                                    <td> <?php echo e($p->nm_procedimento); ?></td>
                                    <td align="center" width="110"><a href="<?php echo e(route('atendimentos/procedimentos').'/'.$p->cd_prontuario); ?>" title='Ir para procedimentos' class='btn btn-success  fa fa-plus-square <?php echo e(verficaPermissaoBotao('recurso.atendimentos/procedimentos')); ?>'></a></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </table>
                    </div>
                </div>
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse-procedimentos-radiologicos">
                            <b id="total-procedimentos">Aguardando realização de procedimentos Radiológicos (<?php echo e(count($procedimentos_radiologicos)); ?>)</b>
                        </a>
                    </h4>
                </div>
                <div id="collapse-procedimentos-radiologicos" class="panel-collapse collapse">
                    <div class="panel-body panel-body-no-margin" id="total-procedimentos">
                        <table class="table table-bordered table-hover table-striped table-fila" >
                        <?php $__currentLoopData = $procedimentos_radiologicos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td> <?php echo e($p->nm_pessoa); ?></td>
                                <td> <?php echo e(isset($p->dt_nasc) ? calcula_idade($p->dt_nasc) : ""); ?> </td>
                                <?php if(($p->id_sexo == 'F')): ?>
                                    <td align="center"><span class='fa fa-female' style='color:deeppink;'></span></td>
                                <?php else: ?>
                                    <td align="center"><span class='fa fa-male' style='color:blue;'></span></td>
                                <?php endif; ?>
                                <td> <?php echo e($p->nm_procedimento); ?></td>
                                <td align="center" width="110"><a href="<?php echo e(route('atendimentos/procedimentos').'/'.$p->cd_prontuario); ?>" title='Ir para procedimentos' class='btn btn-success fa fa-plus-square <?php echo e(verficaPermissaoBotao('recurso.atendimentos/procedimentos')); ?>'></a></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </table>
                    </div>
                </div>
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse-concluidos">
                            <b id="total-concluidos">Pacientes atendidos/ Atendimentos finalizados (<?php echo e(count($atendimento_concluido)); ?>)</b>
                        </a>
                    </h4>
                </div>
                <div id="collapse-concluidos" class="panel-collapse collapse">
                    <div class="panel-body panel-body-no-margin" id="total-concluidos">
                        <table class="table table-bordered table-hover table-striped table-fila" >
                        <?php $__currentLoopData = $atendimento_concluido; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ac): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td> <?php echo e($ac->nm_pessoa); ?></td>
                                <td> <?php echo e(isset($ac->dt_nasc) ? calcula_idade($ac->dt_nasc) : ""); ?> </td>
                                <?php if(($ac->id_sexo == 'F')): ?>
                                    <td align="center"><span class='fa fa-female' style='color:deeppink;'></span></td>
                                <?php else: ?>
                                    <td align="center"><span class='fa fa-male' style='color:blue;'></span></td>
                                <?php endif; ?>
                                <td>Entrada:<?php echo e(formata_hora($ac->created_at)); ?> - Conclusão: <?php echo e(formata_hora($ac->finished_at)); ?></td>
                                <td align="center" width="110"><a href="<?php echo e(route('atendimentos/atendimento-medico').'/'.$ac->cd_prontuario); ?>" title='Ir para o prontuário' class='btn btn-primary fa fa-stethoscope <?php echo e(verficaPermissaoBotao('recurso.atendimentos/atendimento-medico')); ?>'></a></td>
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
    <?php echo $__env->make('pessoas/modal-pessoas', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <div class="modal fade" id="novo-atendimento"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Fechar</span></button>
                    <h4 class="modal-title" id="myModalLabel">Selecione o tipo de atendimento</h4>
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
                <div id="mensagem"></div>
                <div class="modal-footer" style="text-align:left !important;">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
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
                                    <option value='1' style=background-color:blue;>NÃO URGENTE</option>
                                    <option value='2' style=background-color:forestgreen;>POUCO URGENTE</option>
                                    <option value='3' style=background-color:yellow;>URGENTE</option>
                                    <option value='4' style=background-color:orange;>MUITO URGENTE</option>
                                    <option value='5' style=background-color:red;>EMERGÊNCIA</option>
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
    <script src="<?php echo e(js_versionado('prontuario.js')); ?>"></script>
    <script>

       /* $(document).ready(function () {

            var gruposAbertosxxy2 = $.cookie('gruposAbertosxxy2');
            if (gruposAbertosxxy2 == null) {
                var gruposAbertosxxy2 = [];
            } else {
                gruposAbertosxxy2 = JSON.parse(gruposAbertosxxy2);
            }


            //adiciona no array quando abre
            $("#accordion").on('shown.bs.collapse', function () {
                var active = $("#accordion .in").attr('id');
                active = String(active);


                if(gruposAbertosxxy2.indexOf(active) == -1) {
                    gruposAbertosxxy2.push(active);
                }

                $.cookie('gruposAbertosxxy2', JSON.stringify(gruposAbertosxxy2));
            });
            //remove no array quando fecha
            $("#accordion").on('hidden.bs.collapse', function () {
                var active = $("#accordion .in").attr('id');
                gruposAbertosxxy2.pull(active);
                $.cookie('gruposAbertosxxy2', JSON.stringify(gruposAbertosxxy2));
                //$.removeCookie('activeAccordionGroup');
            });


            for (index = 0; index < gruposAbertosxxy2.length; ++index) {
                //console.log(gruposAbertosxxy2[index]);
                $("#" + gruposAbertosxxy2[index]).addClass("in");
                //alert(gruposAbertosxxy2[index]);
            }


        });*/


    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.default', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>