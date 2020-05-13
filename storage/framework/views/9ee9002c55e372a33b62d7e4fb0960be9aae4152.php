<?php $__env->startSection('conteudo-full'); ?>
    <?php if($errors->any()): ?>
        <div class="alert alert-danger collapse in" id="collapseExample">
            <h4 class="alert-heading">Os seguintes erros foram encontrados:</h4>
            <ul>
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li> <?php echo e($error); ?> </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
            <hr>
            <p class="mb-0">Por favor, verifique e tente novamente.</p>
            <p><a class="btn btn-primary" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="true" aria-controls="collapseExample">Fechar</a></p>
        </div>
    <?php endif; ?>
    <link href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/css/select2.min.css" rel="stylesheet" />
    <script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/js/select2.min.js"></script>

    <?php echo e(Form::open(['id' => 'form-cadastra-atendimento-medico', 'class' => 'form-no-submit'])); ?>

    <div class="panel with-nav-tabs panel-primary" >
        <div class="panel-heading">
            <?php if(session()->get('recurso.atendimentos-atendimento-medico-salvar-apos-medicacao') && isset($atendimento[0]) && $atendimento[0]->status == 'E'): ?>
                <?php echo e(Form::submit('Finalizar atendimento após medicação',['id'=>'finalizar-atendimento-apos-medicacao', 'class'=>"btn btn-danger pull-right"])); ?>

            <?php endif; ?>
            <?php if((session()->get('recurso.atendimentos-atendimento-medico-salvar'))): ?>
                <?php if(((!isset($atendimento[0])) || (isset($atendimento[0]) && $atendimento[0]->status == 'A'))): ?>
                    <?php echo e(Form::submit('Finalizar atendimento',['id'=>'finalizar-atendimento', 'class'=>"btn btn-danger pull-right"])); ?>

                <?php endif; ?>
                <?php if((!isset($atendimento[0])) || (isset($atendimento[0]) && $atendimento[0]->status == 'A')): ?>
                        <button type="button" id='chamar-painel' data-toggle='modal' data-target="#escolhe-sala" class="btn btn-primary pull-right">Chamar painel</button>
                <?php endif; ?>
            <?php endif; ?>
            <button type="button" class="btn btn-primary pull pull-right ver_pessoa" data-toggle="modal" data-target="#modal-pesquisa">Ver cadastro</button>
            <button type="button" class="btn btn-primary pull pull-right" id="ver-historico" value='<?php echo e((isset($acolhimento[0]->cd_pessoa) ? $acolhimento[0]->cd_pessoa : "")); ?>' data-toggle="modal" data-target="#modal-historico">Histórico</button>

            <a href="<?php echo e(route('relatorios/prontuario').'/'.$acolhimento[0]->cd_prontuario); ?>" target="_blank" class="btn btn-primary pull-right"><span class="fa fa-print"></span></a>
            <ul class="nav nav-tabs">
                <li id="subjetivo"><a class="tab" href="#tab_subjetivo" data-toggle="tab">Subjetivo</a></li>
                <li id="objetivo" class="active"><a class="tab" href="#tab_objetivo" data-toggle="tab">Objetivo</a></li>
                <li id="avaliacao" ><a class="tab" href="#tab_avaliacao" data-toggle="tab">Avaliação</a></li>
                <li id="plano"><a class="tab" href="#tab_plano" data-toggle="tab">Plano</a></li>
            <!--    <li><a href="#tab_historico" data-toggle="tab">Histórico</a></li> -->
            </ul>
        </div>
        <div class="panel-body fixed-panel-body"  >
            <?php echo e(Form::hidden('cd_pessoa',(isset($acolhimento[0]->cd_pessoa) ? $acolhimento[0]->cd_pessoa : ""),["name" => "cd_pessoa", "id" => "cd_pessoa_disabled"  ])); ?>

            <?php echo e(Form::hidden('tab_selecionada','',["name" => "tab_selecionada", "id" => "tab_selecionada"  ])); ?>

            <?php echo e(Form::hidden('status','A',["name" => "status", "id" => "status"])); ?>

            <input type="hidden" id='sexo' value="<?php echo e($acolhimento[0]->id_sexo); ?>">
            <input type="hidden" id='dt_nasc' value="<?php echo e($acolhimento[0]->dt_nasc); ?>">
            <div class="tab-content">
                <div class="tab-pane fade in active" id="tab_objetivo">
                    <div class="panel-heading">Sinais Vitais</div>
                    <div class="panel-body panel-acolhimento">
                        <div class="row">
                            <?php echo e(Form::hidden('cd_prontuario',(isset($acolhimento[0]->cd_prontuario) ? $acolhimento[0]->cd_prontuario : ""),["name" => "cd_prontuario", "id" => "cd_prontuario"])); ?>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="cintura">Cintura (cm)</label>
                                    <?php echo e(Form::text('cintura', (isset($acolhimento[0]->cintura) ? $acolhimento[0]->cintura : "") ,["maxlength" => "3", "disabled" => "disabled","id" => "cintura", 'class'=> ($errors->has("cintura") ? "form-control input-sm is-invalid" : "form-control input-sm input-sm") ])); ?>

                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="quadril">Quadril (cm)</label>
                                    <?php echo e(Form::text('quadril', (isset($acolhimento[0]->quadril) ? $acolhimento[0]->quadril : "") ,["maxlength" => "3", "disabled" => "disabled","id" => "quadril", 'class'=> ($errors->has("quadril") ? "form-control input-sm input-sm is-invalid" : "form-control input-sm input-sm") ])); ?>

                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="indice_cintura_quadril">Índice cintura/ quadril</label>
                                    <?php echo e(Form::text('indice_cintura_quadril', isset($acolhimento[0]->indice_cintura_quadril) ? $acolhimento[0]->indice_cintura_quadril : ""  ,["maxlength" => "5", "disabled" => "disabled", "class"=> "indice_cintura_quadril form-control input-sm input-sm" ])); ?>

                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="peso">Peso (Kg)</label>
                                    <?php echo e(Form::text('peso', (isset($acolhimento[0]->peso) ? $acolhimento[0]->peso : "") ,["maxlength" => "5", "id" => "peso", "disabled" => "disabled",'class'=> ($errors->has("peso") ? "form-control input-sm input-sm is-invalid" : "form-control input-sm") ])); ?>

                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="altura">Altura (m)</label>
                                    <?php echo e(Form::text('altura', (isset($acolhimento[0]->altura) ? $acolhimento[0]->altura : "") ,["maxlength" => "4", "disabled" => "disabled","id" => "altura", 'class'=> ($errors->has("altura") ? "form-control input-sm is-invalid" : "form-control input-sm") ])); ?>

                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="massa_corporal">Massa corporal</label>
                                    <?php echo e(Form::text('t_massa_corporal', (isset($acolhimento[0]->massa_corporal) ? $acolhimento[0]->massa_corporal : "") ,["maxlength" => "5", "disabled" => "disabled", 'class'=> ($errors->has("massa_corporal") ? "form-control input-sm is-invalid" : "massa_corporal form-control input-sm") ])); ?>

                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="pressao_arterial">P.A (mmHg)</label>
                                    <?php echo e(Form::text('pressao_arterial',(isset($acolhimento[0]->pressao_arterial) ? $acolhimento[0]->pressao_arterial : ""),["name" => "pressao_arterial", "disabled" => "disabled","maxlength" => "3", "id" => "pressao_arterial",  'class'=> ($errors->has("pressao_arterial") ? "form-control input-sm is-invalid" : "form-control input-sm")])); ?>

                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="temperatura">Temperatura (ºC)</label>
                                    <?php echo e(Form::text('temperatura', (isset($acolhimento[0]->temperatura) ? $acolhimento[0]->temperatura : "") ,["maxlength" => "5", "disabled" => "disabled","id" => "temperatura", 'class'=> ($errors->has("temperatura") ? "form-control input-sm is-invalid" : "form-control input-sm") ])); ?>

                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="freq_cardiaca">Freq. Cardíaca (bpm)</label>
                                    <?php echo e(Form::text('freq_cardiaca',(isset($acolhimento[0]->freq_cardiaca) ? $acolhimento[0]->freq_cardiaca : ""),["name" => "freq_cardiaca", "disabled" => "disabled","maxlength" => "3", "id" => "freq_cardiaca",  'class'=> ($errors->has("freq_cardiaca") ? "form-control input-sm is-invalid" : "form-control input-sm")])); ?>

                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="freq_respiratoria">Freq. Respirat. (mrm)</label>
                                    <?php echo e(Form::text('freq_respiratoria', (isset($acolhimento[0]->freq_respiratoria) ? $acolhimento[0]->freq_respiratoria : "") ,["maxlength" => "3", "disabled" => "disabled","id" => "freq_respiratoria", 'class'=> ($errors->has("freq_respiratoria") ? "form-control input-sm is-invalid" : "form-control input-sm") ])); ?>

                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="estado_nutricional">Estado nutricional</label>
                                    <?php echo e(Form::text('estado_nutricional', (isset($acolhimento[0]->estado_nutricional) ? $acolhimento[0]->estado_nutricional : "") ,["maxlength" => "5", "disabled" => "disabled",'class'=> "estado_nutricional form-control input-sm" ])); ?>

                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="risco_cintura">Risco associado à circunfêrencia da cintura</label>
                                    <?php echo e(Form::text('risco_cintura', (isset($acolhimento[0]->risco_cintura) ? $acolhimento[0]->risco_cintura : "") ,["maxlength" => "5", "disabled" => "disabled", 'class'=> ($errors->has("risco_cintura") ? "form-control input-sm is-invalid" : "risco_cintura form-control input-sm") ])); ?>

                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="glicemia_capilar">Glicemia Capilar (md/dll)</label>
                                    <?php echo e(Form::text('glicemia_capilar',(isset($acolhimento[0]->glicemia_capilar) ? $acolhimento[0]->glicemia_capilar : ""),["name" => "glicemia_capilar", "disabled" => "disabled","maxlength" => "5", 'class'=> ($errors->has("glicemia_capilar") ? "form-control input-sm is-invalid" : "glicemia_capilar form-control input-sm")])); ?>

                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="glicemia">&nbsp</label>
                                    <?php echo e(Form::text('glicemia', (isset($acolhimento[0]->glicemia) ? $acolhimento[0]->glicemia : "") ,["maxlength" => "5", "disabled" => "disabled", "id" => "t_glicemia", 'class'=> ($errors->has("glicemia") ? "form-control input-sm is-invalid" : "form-control input-sm") ])); ?>

                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="saturacao">Saturação (%O2)</label>
                                    <?php echo e(Form::text('saturacao', (isset($acolhimento[0]->saturacao) ? $acolhimento[0]->saturacao : "") ,["maxlength" => "5", "disabled" => "disabled","id" => "t_saturacao", 'class'=> ($errors->has("saturacao") ? "form-control input-sm is-invalid" : "form-control input-sm") ])); ?>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-heading">Escala de coma de Glasgow</div>
                    <div class="panel-body panel-acolhimento">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="abertura_ocular">Abertura ocular</label>
                                    <?php echo e(Form::text('abertura_ocular',(isset($acolhimento[0]->abertura_ocular) ? arrayPadrao('abertura_ocular')[$acolhimento[0]->abertura_ocular] : ""),["id" => "abertura_ocular", "disabled" => "disabled", 'class'=> ($errors->has("abertura_ocular") ? "form-control input-sm is-invalid" : "form-control input-sm")])); ?>

                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="resposta_verbal">Resposta verbal</label>
                                    <?php echo e(Form::text('resposta_verbal', (isset($acolhimento[0]->resposta_verbal) ? arrayPadrao('resposta_verbal')[$acolhimento[0]->resposta_verbal] : "") ,["id" => "resposta_verbal", "disabled" => "disabled",'class'=> ($errors->has("resposta_verbal") ? "form-control input-sm is-invalid" : "form-control input-sm") ])); ?>

                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="resposta_motora">Resposta motora</label>
                                    <?php echo e(Form::text('resposta_motora', (isset($acolhimento[0]->resposta_motora) ? arrayPadrao('resposta_motora')[$acolhimento[0]->resposta_motora] : "") ,["id" => "resposta_motora", "disabled" => "disabled", 'class'=> ($errors->has("resposta_motora") ? "form-control input-sm is-invalid" : "form-control input-sm") ])); ?>

                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="escore_glasgow">escore</label>
                                    <?php echo e(Form::text('escore_glasgow', (isset($acolhimento[0]->escore_glasgow) ? escala_de_coma_glasgow($acolhimento[0]->escore_glasgow) : "") ,["maxlength" => "5", "disabled" => "disabled", "id" => "escore_glasgow", 'class'=> ($errors->has("escore_glasgow") ? "form-control input-sm is-invalid" : "form-control input-sm") ])); ?>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-heading">Avaliação de Enfermagem</div>
                    <div class="panel-body panel-acolhimento">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="avaliacao">Avaliação do profissional de saúde (Histórico de saúde/Queixa principal)</label>
                                    <?php echo e(Form::textarea('avaliacao',(isset($acolhimento[0]->avaliacao) ? $acolhimento[0]->avaliacao : ""),["name" => "avaliacao", "disabled" => "disabled","maxlength" => "1000", "rows"=>"3", "id" => "avaliacao",  'class'=> ($errors->has("avaliacao") ? "form-control input-sm is-invalid" : "form-control input-sm")])); ?>

                                </div>
                            </div>
                        </div>
                    </div>
                    <?php if(isset($procedimentos_realizados[0])): ?>
                        <div class="panel-heading">Procedimentos realizados</div>
                        <div class="panel-body panel-acolhimento">
                            <div class="row">
                                <div class='table-responsive'>
                                    <table class='table table-bordered table-hover table-striped'>
                                        <thead>
                                        <tr>
                                            <th>Código</th>
                                            <th>Nome procedimento</th>
                                            <th>Situação</th>
                                            <th>Ação</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php $__currentLoopData = $procedimentos_realizados; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr title=<?php echo e($p->descricao_solicitacao); ?>>
                                                <td><?php echo e($p->cd_procedimento); ?></td>
                                                <td><?php echo e($p->nm_procedimento); ?></td>
                                                <td><?php echo e($p->id_status == 'A' ? 'Pendente' : 'Concluído'); ?></td>
                                                <td>
                                                    <button type='button' class='btn btn-primary btn-sm detalhes-procedimento-c' data-toggle='modal' data-target='#modal-detalhes-procedimento' id='<?php echo e($p->id_atendimento_procedimento); ?>'><span class="fa fa-eye"></span></button>
                                                </td>
                                            <tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="tab-pane fade" id="tab_subjetivo">
                    <div class="panel-heading">Motivo do atendimento</div>
                    <div class="panel-body panel-acolhimento">
                        <?php if(isset($avaliacao_subjetivo[0])): ?>
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#avaliacao_subjetivo">
                                        <b>Histórico do atendimento + </b></a>
                                </h4>
                            </div>
                            <div id="avaliacao_subjetivo" class="panel-collapse collapse">

                                    <?php $__currentLoopData = $avaliacao_subjetivo; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php echo e(formata_hora($d->created_at) . ": "); ?>

                                        <?php echo e($d->nm_ocupacao . $d->nm_pessoa); ?>

                                        <br>
                                        <?php echo e($d->descricao_subjetivo); ?><br><br>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            </div>
                        <?php endif; ?>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="descricao_subjetivo">Descrição</label>
                                    <?php echo e(Form::textarea('descricao_subjetivo',(isset($_POST['descricao_subjetivo'])) ? $_POST['descricao_subjetivo'] :'' ,["name" => "descricao_subjetivo", "maxlength" => "1000", "rows"=>"3", "id" => "descricao_subjetivo",  'class'=> ($errors->has("descricao_subjetivo") ? "form-control input-sm is-invalid" : "form-control input-sm")])); ?>

                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="historia_clinica ">História clínica</label>
                                    <?php echo e(Form::textarea('historia_clinica',(isset($atendimento[0]->historia_clinica) ? $atendimento[0]->historia_clinica  : ""),["name" => "historia_clinica", "maxlength" => "500", "rows"=>"2", (isset($atendimento[0]->historia_clinica) ? "disabled"  : ""), "id" => "historia_clinica", 'class'=> ($errors->has("historia_clinica") ? "form-control input-sm is-invalid" : "form-control input-sm")])); ?>

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="adesao_tratamentos">Adesão a tratamentos prescritos</label>
                                    <?php echo e(Form::textarea('adesao_tratamentos',(isset($atendimento[0]->adesao_tratamentos) ? $atendimento[0]->adesao_tratamentos : ""),["name" => "adesao_tratamentos", "maxlength" => "500", "rows"=>"2", "id" => "adesao_tratamentos", (isset($atendimento[0]->adesao_tratamentos) ? "disabled"  : ""), "id" => "historia_clinica",'class'=> ($errors->has("adesao_tratamentos") ? "form-control input-sm is-invalid" : "form-control input-sm")])); ?>

                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="acompanhante">Acompanhante/cuidador</label>
                                    <?php echo e(Form::text('acompanhante',(isset($atendimento[0]->acompanhante) ? $atendimento[0]->acompanhante : ""),["name" => "acompanhante", "maxlength" => "50", "rows"=>"2", "id" => "acompanhante", (isset($atendimento[0]->acompanhante) ? "disabled"  : ""), "id" => "historia_clinica", 'class'=> ($errors->has("acompanhante") ? "form-control input-sm is-invalid" : "form-control input-sm")])); ?>

                                </div>
                            </div>
                        </div>
                     </div>
                    <div class="panel-heading">BPA</div>
                    <div class="panel-body panel-acolhimento">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="cd_ocupacao">Procedimento<br></label>
                                    <?php echo e(Form::text('nome_procedimento',(isset($atendimento[0]->nm_procedimento) ? $atendimento[0]->nm_procedimento : "ATENDIMENTO MEDICO EM UNIDADE DE PRONTO ATENDIMENTO") ,["id" => "nome_procedimento", "disabled","name" => "nome_procedimento",'class'=> ($errors->has("nome_procedimento") ? "form-control is-invalid" : "form-control")])); ?>

                                   <!-- <span class="input-group-btn">
                                    <button type="button" data-toggle="modal" class="btn btn-info search margin-top-10" data-fechar="true" data-destino="pesquisa_procedimento_ocupacao" <?php echo e((isset($atendimento[0]->nm_procedimento) ? "disabled" : "")); ?>data-target="#modal-search"><span class="fa fa-search"></span></button>
                                    </span>-->
                                    <?php echo e(Form::hidden('cd_procedimento',(isset($atendimento[0]->cd_procedimento) ? $atendimento[0]->cd_procedimento : 301060096) ,["id" => "cd_procedimento", "name" => "cd_procedimento",(isset($atendimento[0]->cd_procedimento) ? "disabled"  : ""),'class'=> ($errors->has("cd_procedimento") ? "form-control is-invalid" : "form-control")])); ?>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="tab_avaliacao">
                    <?php if(isset($avaliacao_descricao[0])): ?>
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#collapse">
                                    <b>Histórico da avaliação + </b></a>
                            </h4>
                        </div>
                        <div id="collapse" class="panel-collapse collapse">
                            <div class="panel-body panel-acolhimento">
                                <?php $__currentLoopData = $avaliacao_descricao; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php echo e(formata_hora($d->created_at) . ": "); ?>

                                    <?php echo e($d->nm_ocupacao . $d->nm_pessoa); ?>

                                    <br>
                                    <?php echo e($d->descricao_avaliacao); ?><br><br>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="panel-body panel-acolhimento">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="descricao_avaliacao">Descrição</label>
                                    <?php echo e(Form::textarea('descricao_avaliacao',(isset($_POST['descricao_avaliacao'])) ? $_POST['descricao_avaliacao'] :'' ,["name" => "descricao_avaliacao", "maxlength" => "1000", "rows"=>"3", "id" => "descricao_avaliacao",  'class'=> ($errors->has("descricao_avaliacao") ? "form-control input-sm is-invalid" : "form-control input-sm")])); ?>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-heading">Alta</div>
                    <div class="panel-body panel-acolhimento">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="motivo_alta">Selecione o motivo da alta</label>
                                    <?php echo e(Form::select('motivo_alta',arrayPadrao('motivo_alta'),(isset($atendimento[0]->motivo_alta) ? $atendimento[0]->motivo_alta : 0),["name"=>"motivo_alta", (isset($atendimento[0]) && $atendimento[0]->status !== 'A') ? "disabled" : "", "id"=>"motivo_alta",'class'=> ($errors->has("acompanhante") ? "form-control input-sm is-invalid" : "form-control")])); ?>

                                    <?php if(isset($atendimento[0]) && $atendimento[0]->status !== 'A'): ?>
                                        <?php echo e(Form::hidden('motivo_alta',($atendimento[0]->motivo_alta ? $atendimento[0]->motivo_alta : 0),["id"=>"motivo_alta",'class'=> ($errors->has("acompanhante") ? "form-control input-sm is-invalid" : "form-control")])); ?>

                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="descricao_alta">Descrição do motivo da alta</label>
                                    <?php echo e(Form::text('descricao_alta',(isset($atendimento[0]->descricao_alta) ? $atendimento[0]->descricao_alta : '') ,["name" => "descricao_alta", "maxlength" => "1000", (isset($atendimento[0]->descricao_alta)) ? "disabled" : "", "id" => "descricao_alta",  'class'=> ($errors->has("descricao_alta") ? "form-control input-sm is-invalid" : "form-control input-sm")])); ?>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-heading">Diagnóstico principal</div>
                    <div class="panel-body panel-acolhimento">
                        <?php if((!isset($atendimento[0])) || (isset($atendimento[0]) && $atendimento[0]->status == 'A')): ?>
                                <input type="button" title="Adicionar CID10" class="btn btn-info btn-sm" id="add-cid-principal" data-toggle="modal" value="Acicionar" data-target="#escolhe-cid">
                            <?php endif; ?>

                        <div id="diagnostico_principal"></div>
                    </div>
                    <div class="panel-heading">Diagnósticos secundários</div>
                    <div class="panel-body panel-acolhimento">

                        <?php if((!isset($atendimento[0])) || (isset($atendimento[0]) && $atendimento[0]->status == 'A')): ?>
                            <input type="button" title="Adicionar CID10" class="btn btn-info btn-sm" id="add-cid-secundaria" data-toggle="modal" value="Acicionar" data-target="#escolhe-cid">
                        <?php endif; ?>

                        <div id="diagnostico_secundario"></div>
                    </div>
                </div>

                <div class="tab-pane fade" id="tab_plano">
                    <div class="panel-body panel-acolhimento">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="descricao_plano">Intervenção/ Procedimentos</label>
                                    <?php echo e(Form::textarea('descricao_plano',(isset($atendimento[0]->descricao_plano) ? $atendimento[0]->descricao_plano : ""),["name" => "descricao_plano", "maxlength" => "1000", "rows"=>"3", "id" => "descricao_plano", isset($atendimento[0]->descricao_plano) ? "disabled"  : "", 'class'=> ($errors->has("descricao_plano") ? "form-control input-sm is-invalid" : "form-control input-sm")])); ?>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="panel-heading">Procedimentos</div>
                    <div class="panel-body panel-acolhimento">
                        <?php if((!isset($atendimento[0])) || (isset($atendimento[0]) && $atendimento[0]->status != 'C')): ?>
                                <button class="btn btn-info btn-sm" id="abre-modal-add-procedimentos" type="button"> Adicionar Novo Procedimento</button>
                        <?php endif; ?>
                        <div class="panel-body">
                            <div class="row">
                                <div class='table-responsive'>
                                    <table class='table table-bordered table-hover table-striped'>
                                        <thead>
                                        <tr>
                                            <th>Código</th>
                                            <th>Nome procedimento</th>
                                            <th>Situação</th>
                                            <th width="100px">Ação</th>
                                        </tr>
                                        </thead>
                                        <tbody  id='lista-procedimentos' data-permissoes="false" class="lista-procedimentos">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
         </div>
        <div class="panel-footer fixed-panel-footer">
            <div class="pull-left"><?php echo e("Responsável: ".title_case(Session()->get('profissional')).' ' .Session()->get('nome')); ?>

                <br>
                <?php $__currentLoopData = $salas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div id="sala-rodape"><?php echo e(($s->cd_sala == Session()->get('cd_sala')) ? title_case($s->ds_sala) : ''); ?></div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <div class="pull-right">
                <?php if((session()->get('recurso.atendimentos-atendimento-medico-salvar')) && ((!isset($atendimento[0])) || (isset($atendimento[0]) && $atendimento[0]->status == 'A'))): ?>
                    <?php echo e(Form::submit('Salvar',['class'=>"btn btn-success pull-right"])); ?>

                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php echo e(Form::close()); ?>


    <?php echo $__env->make('pessoas/modal-pessoas', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make('atendimentos/modal-procedimentos', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('painel-modal'); ?>
    <div class="modal fade" id="escolhe-sala"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="myModalLabel">Chamar painel</h3>
                </div>
                <div class="modal-body">
                    <div class="panel-body">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="sala"><h4>Selecione uma sala</h4></label>
                                <select id="salas" class="form-control input-sm" style="color:#287da1;">
                                    <?php $__currentLoopData = $salas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value='<?php echo e($s->cd_sala); ?>' <?php echo e((session()->get('cd_sala')==$s->cd_sala) ? "selected" : ""); ?>><?php echo e($s->ds_sala); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="painel"><h4>Selecione um painel</h4></label>
                                <select id="paineis" class="form-control input-sm" style="color:#287da1;">
                                    <?php $__currentLoopData = arrayPadrao('paineis'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p => $key): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value='<?php echo e($p); ?>'  <?php echo e((session()->get('cd_painel')==$p) ? "selected" : ""); ?>><?php echo e($key); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-footer" style="text-align:left !important;">
                    <button type="button" class="btn btn-danger margin-top-zero" data-dismiss="modal">Cancelar</button>
                    <button id="chama-painel" class="btn btn-primary  pull-right">Chamar</button>
                </div>
            </div>
        </div>
    </div>

    <div id="escolhe-cid" class="modal fade"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div id="dialog-escolhe-cid" class="modal-dialog">
            <div  class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="myModalLabel">Adicionar Cid10</h3>
                </div>
                    <div class="panel-body">
                        <div class="panel-heading">Diagnóstico</div>
                        <div class="panel-body panel-acolhimento">
                            <div class="col-md-8">
                                <div class="input-group">
                                    <label for="id_cid">CID10<br></label>
                                        <input type="text" data-id="" id="id_cid" disabled class="form-control">
                                    <span class="input-group-btn">
                                        <button type="button" data-toggle="modal" class="btn btn-info search margin-top-20" data-fechar="true" data-destino="pesquisa_cid" data-target="#modal-search"><span class="fa fa-search"></span></button>
                                    </span>
                                        <input type="hidden" id="cid_principal">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="dt_primeiros_sintomas">Dt. primeiros sintomas</label>
                                    <?php echo e(Form::date('dt_primeiros_sintomas',\Carbon\Carbon::now(),["id" => "dt_primeiros_sintomas", 'class'=> ($errors->has("dt_primeiros_sintomas") ? "form-control input-sm is-invalid" : "form-control input-sm")])); ?>

                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="tipo_diagnostico">Tipo de diagnóstico</label>
                                    <?php echo e(Form::select('tipo_diagnostico',arrayPadrao('tipo_diagnostico'),isset($avaliacao['tipo_diagnostico']) ? $avaliacao['tipo_diagnostico']:'D',['name'=>'tipo_diagnostico','id'=>'tipo_diagnostico','class'=>'form-control input-sm'])); ?>

                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="diagnostico_trabalho">Diagnóstico relativo ao trabalho</label>
                                    <?php echo e(Form::select('diagnostico_trabalho',arrayPadrao('opcao'),isset($avaliacao['diagnostico_trabalho']) ? $avaliacao['diagnostico_trabalho']:'I',['name'=>'diagnostico_trabalho','id'=>'diagnostico_trabalho','class'=>'form-control input-sm'])); ?>

                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="diagnostico_transito">Diagnóstico relativo a acidente de trânsito</label>
                                    <?php echo e(Form::select('diagnostico_transito',arrayPadrao('opcao'),isset($avaliacao['diagnostico_transito']) ? $avaliacao['diagnostico_transito']:'I',['name'=>'diagnostico_transito','id'=>'diagnostico_transito','class'=>'form-control input-sm'])); ?>

                                </div>
                            </div>

                        </div>
                    </div>
                <div class="panel-footer" style="text-align:left !important;">
                    <div id="mensagem"></div>
                    <button type="button" class="btn btn-danger margin-top-zero" data-dismiss="modal">Cancelar</button>
                    <button id="add_cid" class="btn btn-primary  pull-right">Adicionar</button>
                </div>
            </div>
        </div>
    </div>

    <div id="modal-detalhes-procedimento" class="modal fade"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div id="dialog-detalhes-procedimento" class="modal-dialog">
            <div  class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="myModalLabel">Detalhes do procedimento</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="status">Procedimento</label>
                                <input type="text" class="form-control input-sm" disabled="disabled" id="d_procedimento">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="status">Solicitante</label>
                                <input type="text" class="form-control input-sm" disabled="disabled" id="d_user_solicitacao">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="status">Data/ Hora Solicitação</label>
                                <input type="text" class="form-control input-sm" disabled="disabled" id="d_hr_solicitacao">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="descricao_solicitacao">Detalhes da solicitação</label>
                                <textarea class="form-control input-sm" disabled="disabled" id="d_solicitacao"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="descricao_execucao">Detalhes da execução</label>
                                <textarea class="form-control input-sm" disabled="disabled" id="d_execucao"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="status">Realizado por</label>
                                <input class="form-control input-sm" type="text" disabled="disabled" id="d_user_execucao">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="status">Data/ Hora execução</label>
                                <input class="form-control input-sm" type="text" disabled="disabled" id="d_hr_execucao">
                            </div>
                        </div>
                    </div>
                 </div>
                <div class="panel-footer" style="text-align:left !important;">
                    <button type="button" class="btn btn-danger margin-top-zero" data-dismiss="modal">Sair</button>
                </div>
            </div>
        </div>
    </div>
    <?php echo $__env->make('atendimentos/modal-historico', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make('atendimentos/modal-pesquisa', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('custom_js'); ?>
    <script src="<?php echo e(js_versionado('prontuario.js')); ?>"></script>
    <script src="<?php echo e(js_versionado('atendimento_medico.js')); ?>"></script>
<?php $__env->stopSection(); ?>



<?php echo $__env->make('layouts.default', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>