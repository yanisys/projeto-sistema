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

    <?php echo e(Form::open(['id' => 'form-cadastra-atendimento-medico', 'class' => 'form-no-submit'])); ?>
    <div class="panel with-nav-tabs panel-primary" >
        <div class="panel-heading">
            <div class="btn-group pull-right" role="group">
             <!--   <button type="button" data-toggle='modal' data-target="#modal-exibe-consulta" class="btn btn-warning pull-right">Exibe consulta</button>  -->
                <?php if(session()->get('recurso.atendimentos-atendimento-medico-salvar-apos-medicacao') && isset($atendimento) && $atendimento->status == 'E' && session()->get('profissional')): ?>
                    <button type="button" id='btn-modal-finalizar' data-toggle='modal' data-target="#modal-finalizar-atendimento" class="btn btn-danger">Finalizar Atendimento após Medicação</button>
                <?php endif; ?>
                <?php if((session()->get('recurso.atendimentos-atendimento-medico-salvar')) && session()->get('profissional') && $prontuario->status == 'A'): ?>
                    <?php if(((!isset($atendimento)) || (isset($atendimento) && $atendimento->status == 'A'))): ?>
                        <button type="button" id='btn-modal-finalizar' data-toggle='modal' data-target="#modal-finalizar-atendimento" class="btn btn-danger">Finalizar Atendimento</button>
                    <?php endif; ?>
                <?php endif; ?>
                <?php if((session()->get('recurso.atendimentos-atendimento-medico-salvar')) && session()->get('profissional') && $prontuario->status == 'A'): ?>
                    <?php if((!isset($atendimento)) || (isset($atendimento) && $atendimento->status == 'A')): ?>
                            <button type="button" id='chamar-painel' data-toggle='modal' data-target="#escolhe-sala" class="btn btn-primary">Chamar no Painel</button>
                    <?php endif; ?>
                <?php endif; ?>
                <button type="button" class="btn btn-primary ver_pessoa" data-toggle="modal" data-target="#modal-pesquisa">Ver Cadastro</button>
                <button type="button" class="btn btn-primary" id="ver-historico" value='<?php echo e((isset($acolhimento->cd_pessoa) ? $acolhimento->cd_pessoa : "")); ?>' data-toggle="modal" data-target="#modal-historico">Histórico</button>

                <a href="<?php echo e(route('relatorios/prontuario').'/'.$acolhimento->cd_prontuario); ?>" target="_blank" class="btn btn-primary pull-right"><span class="fa fa-print"></span></a>
            </div>
             <ul class="nav nav-tabs remember-tabs">
                <li id="enfermagem"><a class="tab" href="#tab_enfermagem" data-toggle="tab">Enfermagem</a></li>
                <li id="subjetivo" class="active"><a class="tab" href="#tab_subjetivo" data-toggle="tab">Subjetivo</a></li>
                <li id="objetivo"><a class="tab" href="#tab_objetivo" data-toggle="tab">Objetivo</a></li>
                <li id="avaliacao"><a class="tab" href="#tab_avaliacao" data-toggle="tab">Avaliação</a></li>
                <li id="plano"><a class="tab" href="#tab_plano" data-toggle="tab">Plano</a></li>
                <li id="conduta"><a href="#tab_conduta" data-toggle="tab">Conduta</a></li>
            </ul>
        </div>
        <div class="panel-body fixed-panel-body">
            <?php echo e(Form::hidden('cd_pessoa',(isset($acolhimento->cd_pessoa) ? $acolhimento->cd_pessoa : ""),["name" => "cd_pessoa", "id" => "cd_pessoa_disabled"  ])); ?>
            <?php echo e(Form::hidden('tab_selecionada','',["name" => "tab_selecionada", "id" => "tab_selecionada"  ])); ?>
            <?php echo e(Form::hidden('status','A',["name" => "status", "id" => "status-prontuario"])); ?>
            <?php echo e(Form::hidden('cd_procedimento',(isset($atendimento->cd_procedimento) ? $atendimento->cd_procedimento : 301060096) ,["id" => "cd_procedimento", "name" => "cd_procedimento",(isset($atendimento->cd_procedimento) ? "disabled"  : ""),'class'=> ($errors->has("cd_procedimento") ? "form-control is-invalid" : "form-control")])); ?>
            <input type="hidden" id='sexo' value="<?php echo e($acolhimento->id_sexo); ?>">
            <input type="hidden" id='dt_nasc' value="<?php echo e($acolhimento->dt_nasc); ?>">
            <div class="tab-content">
                <div class="tab-pane fade" id="tab_enfermagem">
                    <div class="row">
                        <div class="col-md-10">
                            <div class="panel-heading"><b>Avaliação de Enfermagem</b></div>
                            <div class="panel-body panel-acolhimento">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="avaliacao_enfermagem">Motivo da consulta (Queixa principal)</label>
                                            <?php echo e(Form::textarea('avaliacao_enfermagem',(isset($acolhimento->avaliacao) ? $acolhimento->avaliacao : ""),["name" => "avaliacao_enfermagem", "maxlength" => "950", "disabled", "rows"=>"3", "id" => "avaliacao_enfermagem",  'class'=> ($errors->has("avaliacao_enfermagem") ? "form-control is-invalid" : "form-control")])); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <br><br>
                                        <div class='table-responsive'>
                                            <table class='table table-bordered table-hover'>
                                                <tr>
                                                    <th style="text-align: center">Últimas consultas</th>
                                                </tr>
                                                <tbody  id='lista-ultimas-consultas'>
                                                <?php if(isset($ultimos_atendimentos[0]->cd_prontuario)): ?>
                                                    <tr><td style="text-align: center;"><b><a href="<?php echo e(route('relatorios/prontuario').'/'.$ultimos_atendimentos[0]->cd_prontuario); ?>" style="color: <?php echo e($ultimos_atendimentos[0]->cor); ?>" title="Clique para ver o prontuário" target="_blank"><?php echo e(formata_data($ultimos_atendimentos[0]->created_at)); ?></a></b></td></tr>
                                                    <?php if(isset($ultimos_atendimentos[1]->cd_prontuario)): ?>
                                                        <tr><td style="text-align: center;"><b><a href="<?php echo e(route('relatorios/prontuario').'/'.$ultimos_atendimentos[1]->cd_prontuario); ?>" style="color: <?php echo e($ultimos_atendimentos[1]->cor); ?>" title="Clique para ver o prontuário" target="_blank"><?php echo e(formata_data($ultimos_atendimentos[1]->created_at)); ?></a></b></td></tr>
                                                    <?php endif; ?>
                                                    <?php if(isset($ultimos_atendimentos[2]->cd_prontuario)): ?>
                                                        <tr><td style="text-align: center;"><b><a href="<?php echo e(route('relatorios/prontuario').'/'.$ultimos_atendimentos[2]->cd_prontuario); ?>" style="color: <?php echo e($ultimos_atendimentos[2]->cor); ?>" title="Clique para ver o prontuário" target="_blank"><?php echo e(formata_data($ultimos_atendimentos[2]->created_at)); ?></a></b></td></tr>
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    <tr><td>Nenhum resultado para exibir.</td></tr>
                                                <?php endif; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-heading"><b>Objetivo</b></div>
                    <div class="panel-body panel-acolhimento">
                        <div class="row">
                            <input type="hidden" id='sexo' name="id_sexo" value="<?php echo e($acolhimento->id_sexo); ?>">
                            <input type="hidden" id='dt_nasc' name="dt_nasc" value="<?php echo e($acolhimento->dt_nasc); ?>">
                            <?php echo e(Form::hidden('cd_prontuario',(isset($acolhimento->cd_prontuario) ? $acolhimento->cd_prontuario : ""),["name" => "cd_prontuario", "id" => "cd_prontuario"])); ?>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="cintura">Cintura (cm)</label>
                                    <?php echo e(Form::text('cintura', (isset($acolhimento->cintura) ? $acolhimento->cintura : "") ,["maxlength" => "3", "id" => "cintura", "disabled", 'class'=> ($errors->has("cintura") ? "form-control is-invalid" : "form-control mask-inteiro3") ])); ?>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="quadril">Quadril (cm)</label>
                                    <?php echo e(Form::text('quadril', (isset($acolhimento->quadril) ? $acolhimento->quadril : "") ,["maxlength" => "3", "id" => "quadril", "disabled", 'class'=> ($errors->has("quadril") ? "form-control is-invalid mask-inteiro3" : "form-control mask-inteiro3") ])); ?>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="indice_cintura_quadril">Índice cintura/ quadril</label>
                                    <?php echo e(Form::text('t_indice_cintura_quadril', "" ,["maxlength" => "5", "disabled", "class"=> "indice_cintura_quadril form-control" ])); ?>
                                    <?php echo e(Form::hidden('indice_cintura_quadril',"",["name" => "indice_cintura_quadril", "class" => "indice_cintura_quadril"])); ?>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="peso">Peso (Kg)</label>
                                    <?php echo e(Form::text('peso', (isset($acolhimento->peso) ? $acolhimento->peso : "") ,["maxlength" => "7",  "disabled", "id" => "peso", 'class'=> ($errors->has("peso") ? "form-control is-invalid" : "form-control mask-decimal33") ])); ?>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="altura">Altura (m)</label>
                                    <?php echo e(Form::text('altura', (isset($acolhimento->altura) ? $acolhimento->altura : "") ,["maxlength" => "4",  "disabled", "id" => "altura", 'class'=> ($errors->has("altura") ? "form-control is-invalid" : "form-control  mask-decimal12") ])); ?>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="massa_corporal">Massa corporal</label>
                                    <?php echo e(Form::text('t_massa_corporal', (isset($acolhimento->massa_corporal) ? $acolhimento->massa_corporal : '') ,["maxlength" => "5",  "disabled", 'class'=> ($errors->has("massa_corporal") ? "massa_corporal form-control is-invalid" : "massa_corporal form-control") ])); ?>
                                    <?php echo e(Form::hidden('massa_corporal',(isset($acolhimento->massa_corporal) ? $acolhimento->massa_corporal : ""),["name" => "massa_corporal", "class" => "massa_corporal"])); ?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="pressao_arterial">P.A (mmHg)</label>
                                    <?php echo e(Form::text('pressao_arterial',(isset($acolhimento->pressao_arterial) ? $acolhimento->pressao_arterial : ""),["name" => "pressao_arterial",  "disabled", "maxlength" => "5", "id" => "pressao_arterial",  'class'=> ($errors->has("pressao_arterial") ? "form-control is-invalid" : "form-control mask-pressao-arterial")])); ?>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="temperatura">Temperatura (ºC)</label>
                                    <?php echo e(Form::text('temperatura', (isset($acolhimento->temperatura) ? $acolhimento->temperatura : "") ,["maxlength" => "5",  "disabled", "id" => "temperatura", 'class'=> ($errors->has("temperatura") ? "form-control is-invalid" : "form-control   mask-decimal22") ])); ?>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="freq_cardiaca">Freq. cardíaca/ pulso (bpm)</label>
                                    <?php echo e(Form::text('freq_cardiaca',(isset($acolhimento->freq_cardiaca) ? $acolhimento->freq_cardiaca : ""),["name" => "freq_cardiaca",  "disabled", "maxlength" => "3", "id" => "freq_cardiaca",  'class'=> ($errors->has("freq_cardiaca") ? "form-control is-invalid mask-inteiro3" : "form-control mask-inteiro3")])); ?>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="freq_respiratoria">Freq. respiratória (mrm)</label>
                                    <?php echo e(Form::text('freq_respiratoria', (isset($acolhimento->freq_respiratoria) ? $acolhimento->freq_respiratoria : "") ,["maxlength" => "3",  "disabled", "id" => "freq_respiratoria", 'class'=> ($errors->has("freq_respiratoria") ? "form-control is-invalid mask-inteiro3" : "form-control mask-inteiro3") ])); ?>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="estado_nutricional">Estado nutricional</label>
                                    <?php echo e(Form::text('t_estado_nutricional', (isset($acolhimento->estado_nutricional) ? $acolhimento->estado_nutricional : "") ,["maxlength" => "5", "disabled" => "disabled",'class'=> "estado_nutricional form-control" ])); ?>
                                    <?php echo e(Form::hidden('estado_nutricional',(isset($acolhimento->estado_nutricional) ? $acolhimento->estado_nutricional : ""),["name" => "estado_nutricional", "class" => "estado_nutricional"])); ?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="risco_cintura">Risco associado à circunfêrencia da cintura</label>
                                    <?php echo e(Form::text('t_risco_cintura', (isset($acolhimento->risco_cintura) ? $acolhimento->risco_cintura : "") ,["maxlength" => "5", "disabled" => "disabled", 'class'=> ($errors->has("risco_cintura") ? "form-control is-invalid" : "risco_cintura form-control") ])); ?>
                                    <?php echo e(Form::hidden('risco_cintura',(isset($acolhimento->risco_cintura) ? $acolhimento->risco_cintura : ""),["name" => "risco_cintura", "class" => "risco_cintura"])); ?>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="glicemia_capilar">Glicemia Capilar (mg/dL)</label>
                                    <?php echo e(Form::text('glicemia_capilar',(isset($acolhimento->glicemia_capilar) ? $acolhimento->glicemia_capilar : ""),["name" => "glicemia_capilar",  "disabled", "maxlength" => "3", 'class'=> ($errors->has("glicemia_capilar") ? " mask-numeros-3 form-control is-invalid" : " mask-numeros-3 form-control")])); ?>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="glicemia">&nbsp;</label>
                                    <?php echo e(Form::text('t_glicemia', (isset($acolhimento->glicemia) ? $acolhimento->glicemia : "") ,["maxlength" => "5", "disabled" => "disabled", "id" => "t_glicemia", 'class'=> ($errors->has("glicemia") ? "form-control is-invalid" : "form-control") ])); ?>
                                    <?php echo e(Form::hidden('glicemia',(isset($acolhimento->glicemia) ? $acolhimento->glicemia : ""),["name" => "glicemia", "id" => "glicemia"])); ?>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="saturacao">Saturação (%O2)</label>
                                    <?php echo e(Form::text('saturacao', (isset($acolhimento->saturacao) ? $acolhimento->saturacao : "") ,["maxlength" => "3",  "disabled", "id" => "saturacao", 'class'=> ($errors->has("saturacao") ? "form-control is-invalid" : "form-control mask-numeros-3") ])); ?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-8">
                                <div class="panel-heading"><b>Escala de coma de Glasgow</b></div>
                                <div class="panel-body panel-acolhimento">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="abertura_ocular">Abertura ocular</label>
                                                <?php echo e(Form::select('abertura_ocular',arrayPadrao('abertura_ocular'),(isset($acolhimento->abertura_ocular) ? $acolhimento->abertura_ocular : ""),["id" => "abertura_ocular",  "disabled", 'class'=> ($errors->has("abertura_ocular") ? "form-control is-invalid" : "form-control")])); ?>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="resposta_verbal">Resposta verbal</label>
                                                <?php echo e(Form::select('resposta_verbal', arrayPadrao('resposta_verbal'),(isset($acolhimento->resposta_verbal) ? $acolhimento->resposta_verbal : "") ,["id" => "resposta_verbal", "disabled", 'class'=> ($errors->has("resposta_verbal") ? "form-control is-invalid" : "form-control") ])); ?>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="resposta_motora">Resposta motora</label>
                                                <?php echo e(Form::select('resposta_motora',arrayPadrao('resposta_motora'), (isset($acolhimento->resposta_motora) ? $acolhimento->resposta_motora : "") ,["id" => "resposta_motora", "disabled", 'class'=> ($errors->has("resposta_motora") ? "form-control is-invalid" : "form-control") ])); ?>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="escore_glasgow">Escore</label>
                                                <?php echo e(Form::text('t_escore_glasgow', (isset($acolhimento->escore_glasgow) ? $acolhimento->escore_glasgow : "") ,["maxlength" => "5", "disabled" => "disabled", "id" => "t_escore_glasgow", 'class'=> ($errors->has("escore_glasgow") ? "form-control is-invalid" : "form-control") ])); ?>
                                                <?php echo e(Form::hidden('escore_glasgow',(isset($acolhimento->escore_glasgow) ? $acolhimento->escore_glasgow : ""),["name" => "escore_glasgow", "id" => "escore_glasgow"])); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="panel-heading"><b>Escala de dor</b></div>
                                <div class="panel-body panel-acolhimento">
                                    <div class="range-control" id="<?php echo e(($acolhimento->idade > 12) ? 'escala_dor_adulta' : 'escala_dor_infantil'); ?>">
                                        <!-- <input id="inputRange" type="range" min="0" max="10" value="0" data-thumbwidth="1"> -->
                                        <?php echo e(Form::range('nivel_de_dor',(isset($acolhimento->nivel_de_dor) ? $acolhimento->nivel_de_dor : 0), ["id" => 'inputRange',  "disabled", "min" => 0, "max" => 10, "data-thumbwidth" => 1 ])); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="panel-heading"><b>Alergias</b></div>
                            <div class="row">
                                <div class="panel-body panel-acolhimento">
                                     <div class="col-md-12">
                                        <div class='table-responsive'>
                                            <div class="lista-alergias-pessoa"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="panel-heading"><b>História médica pregressa </b><br></div>
                            <div class="row">
                                <div class="panel-body panel-acolhimento">
                                    <div class="col-md-12">
                                        <div class='table-responsive'>
                                            <div class='lista-historia-medica-pregressa'></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="panel-heading"><b>Cirurgias prévias</b></div>
                            <div class="row">
                                <div class="panel-body panel-acolhimento">
                                    <div class="col-md-12">
                                        <div class='table-responsive'>
                                            <div class='lista-cirurgias-previas'></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="panel-heading"><b>Medicamentos em uso</b></div>
                            <div class="row">
                                <div class="panel-body panel-acolhimento">
                                    <div class="col-md-12">
                                        <div class='table-responsive'>
                                            <div class='lista-medicamentos-em-uso'></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="exames_apresentados">Exames apresentados</label>
                                                <?php echo e(Form::textarea('exames_apresentados',(isset($acolhimento->exames_apresentados) ? $acolhimento->exames_apresentados : ""),["name" => "exames_apresentados", "maxlength" => "500", "rows"=>"3", "disabled", "id" => "exames_apresentados",  'class'=> ($errors->has("exames_apresentados") ? "form-control is-invalid" : "form-control")])); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="panel-heading"><b>Conduta</b></div>
                                <div class="row">
                                    <div style="height: 100px;" class="panel-body">
                                        <div class="form-group">
                                            <label for="avaliacao">Avaliação do profissional de saúde</label>
                                            <select id="classifica" disabled="true" class="form-control">
                                                <option value='1' style=background-color:blue;><?php echo e(arrayPadrao('classificar_risco')[$acolhimento->classificacao]); ?></option>
                                            </select>
                                        </div>
                                        <?php echo e(Form::hidden('classificacao',(isset($acolhimento->classificacao) ? $acolhimento->classificacao : ""),["name" => "classificacao", "id" => "classificacao"])); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                            <div id="procedimento-acolhimento" style="display: none" class="col-md-12">
                            <div class="row">
                                <div class="panel-body panel-acolhimento">
                                    <?php if(session()->get('profissional')): ?>
                                        <label for="avaliacao">Lista de Procedimentos</label>
                                    <?php else: ?>
                                        <p class="text-silver">Somente usuários cadastrados na Tabela de Profissionais de Saúde podem solicitar procedimentos.</p>
                                    <?php endif; ?>
                                    <div class="col-md-12">
                                        <div class='table-responsive'>
                                            <table class='table table-bordered table-hover table-striped'>
                                                <thead>
                                                <tr>
                                                    <th>Código</th>
                                                    <th>Nome do Procedimento</th>
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
                <div class="tab-pane fade in active" id="tab_subjetivo">
                    <div class="row">
                        <div class="col-md-10">
                            <div class="panel-heading"><b>Avaliação de Enfermagem</b></div>
                            <div class="panel-body panel-acolhimento">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="avaliacao_enfermagem">Motivo da consulta (Queixa principal)</label>
                                            <?php echo e(Form::textarea('avaliacao_enfermagem',(isset($acolhimento->avaliacao) ? $acolhimento->avaliacao : ""),["name" => "avaliacao_enfermagem", "maxlength" => "950", "disabled", "rows"=>"3", "id" => "avaliacao_disabled",  'class'=> ($errors->has("avaliacao") ? "form-control is-invalid" : "form-control")])); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <br>
                                        <div class='table-responsive'>
                                            <table class='table table-bordered table-hover'>
                                                <tr>
                                                    <th style="text-align: center">Últimas consultas</th>
                                                </tr>
                                                <tbody  id='lista-ultimas-consultas'>
                                                <?php if(isset($ultimos_atendimentos[0]->cd_prontuario)): ?>
                                                    <tr><td style="text-align: center;"><b><a href="<?php echo e(route('relatorios/prontuario').'/'.$ultimos_atendimentos[0]->cd_prontuario); ?>" style="color: <?php echo e($ultimos_atendimentos[0]->cor); ?>" title="Clique para ver o prontuário" target="_blank"><?php echo e(formata_data($ultimos_atendimentos[0]->created_at)); ?></a></b></td></tr>
                                                    <?php if(isset($ultimos_atendimentos[1]->cd_prontuario)): ?>
                                                        <tr><td style="text-align: center;"><b><a href="<?php echo e(route('relatorios/prontuario').'/'.$ultimos_atendimentos[1]->cd_prontuario); ?>" style="color: <?php echo e($ultimos_atendimentos[1]->cor); ?>" title="Clique para ver o prontuário" target="_blank"><?php echo e(formata_data($ultimos_atendimentos[1]->created_at)); ?></a></b></td></tr>
                                                    <?php endif; ?>
                                                    <?php if(isset($ultimos_atendimentos[2]->cd_prontuario)): ?>
                                                        <tr><td style="text-align: center;"><b><a href="<?php echo e(route('relatorios/prontuario').'/'.$ultimos_atendimentos[2]->cd_prontuario); ?>" style="color: <?php echo e($ultimos_atendimentos[2]->cor); ?>" title="Clique para ver o prontuário" target="_blank"><?php echo e(formata_data($ultimos_atendimentos[2]->created_at)); ?></a></b></td></tr>
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    <tr><td>Nenhum resultado para exibir.</td></tr>
                                                <?php endif; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-10">
                            <div class="panel-heading"><b>Avaliação médica</b></div>
                            <div class="panel-body panel-acolhimento">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="avaliacao">Motivo da consulta (Queixa principal)</label>
                                            <?php echo e(Form::textarea('descricao_subjetivo',(isset($avaliacao_subjetivo[0]->descricao_subjetivo) ? $avaliacao_subjetivo[0]->descricao_subjetivo : (isset($_POST['descricao_subjetivo']) ? $_POST['descricao_subjetivo'] : '')),["name" => "descricao_subjetivo", "maxlength" => "1000", "rows"=>"3", "id" => "descricao_subjetivo", isset($avaliacao_subjetivo[0]) ? "disabled"  : "", 'class'=> ($errors->has("descricao_subjetivo") ? "form-control input-sm is-invalid" : "form-control input-sm")])); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="tab_objetivo">
                    <div class="panel-heading"><b>Avaliação de enfermagem</b>&nbsp;<button type="button" class="btn btn-primary btn-xs" id="minimiza_objetivo"><span title="Exibir" class="fas fa-plus"></span></button></div>
                    <div class="panel-body panel-acolhimento" id="avalicao_enfermagem_objetivo" style="display: none">
                        <div class="panel-heading"><b>Sinais vitais</b></div>
                        <div class="panel-body panel-acolhimento">
                            <div class="row">
                                <div class="col-md-1">
                                    <div class="form-group">
                                        <label for="cintura">Cintura (cm)</label>
                                        <?php echo e(Form::text('cintura', (isset($acolhimento->cintura) ? $acolhimento->cintura : "") ,["maxlength" => "3", "disabled", "id" => "cintura", 'class'=> ($errors->has("cintura") ? "form-control is-invalid" : "form-control mask-inteiro3") ])); ?>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="form-group">
                                        <label for="quadril">Quadril (cm)</label>
                                        <?php echo e(Form::text('quadril', (isset($acolhimento->quadril) ? $acolhimento->quadril : "") ,["maxlength" => "3", "disabled", "id" => "quadril", 'class'=> ($errors->has("quadril") ? "form-control is-invalid mask-inteiro3" : "form-control mask-inteiro3") ])); ?>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="form-group">
                                        <label for="indice_cintura_quadril">I. cintura/quadril</label>
                                        <?php echo e(Form::text('t_indice_cintura_quadril', (isset($acolhimento->indice_cintura_quadril) ? $acolhimento->indice_cintura_quadril : "") ,["maxlength" => "5", "disabled" => "disabled", "class"=> "indice_cintura_quadril form-control" ])); ?>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="form-group">
                                        <label for="peso">Peso (Kg)</label>
                                        <?php echo e(Form::text('peso', (isset($acolhimento->peso) ? $acolhimento->peso : "") ,["maxlength" => "7", "disabled", "id" => "peso", 'class'=> ($errors->has("peso") ? "form-control is-invalid" : "form-control mask-decimal33") ])); ?>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="form-group">
                                        <label for="altura">Altura (m)</label>
                                        <?php echo e(Form::text('altura', (isset($acolhimento->altura) ? $acolhimento->altura : "") ,["maxlength" => "4", "disabled", "id" => "altura", 'class'=> ($errors->has("altura") ? "form-control is-invalid" : "form-control  mask-decimal12") ])); ?>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="form-group">
                                        <label for="massa_corporal">Massa corporal</label>
                                        <?php echo e(Form::text('t_massa_corporal', (isset($acolhimento->massa_corporal) ? $acolhimento->massa_corporal : '') ,["maxlength" => "5", "disabled" => "disabled", 'class'=> ($errors->has("massa_corporal") ? "massa_corporal form-control is-invalid" : "massa_corporal form-control") ])); ?>
                                        <?php echo e(Form::hidden('massa_corporal',(isset($acolhimento->massa_corporal) ? $acolhimento->massa_corporal : ""),["name" => "massa_corporal", "class" => "massa_corporal"])); ?>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="form-group">
                                        <label for="pressao_arterial">P.A (mmHg)</label>
                                        <?php echo e(Form::text('pressao_arterial',(isset($acolhimento->pressao_arterial) ? $acolhimento->pressao_arterial : ""),["name" => "pressao_arterial", "disabled", "maxlength" => "5", "id" => "pressao_arterial",  'class'=> ($errors->has("pressao_arterial") ? "form-control is-invalid" : "form-control mask-pressao-arterial")])); ?>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="form-group">
                                        <label for="temperatura">Temp. (ºC)</label>
                                        <?php echo e(Form::text('temperatura', (isset($acolhimento->temperatura) ? $acolhimento->temperatura : "") ,["maxlength" => "5", "disabled", "id" => "temperatura", 'class'=> ($errors->has("temperatura") ? "form-control is-invalid" : "form-control   mask-decimal22") ])); ?>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="freq_cardiaca">Freq. cardíaca/ pulso (bpm)</label>
                                        <?php echo e(Form::text('freq_cardiaca',(isset($acolhimento->freq_cardiaca) ? $acolhimento->freq_cardiaca : ""),["name" => "freq_cardiaca", "disabled", "maxlength" => "3", "id" => "freq_cardiaca",  'class'=> ($errors->has("freq_cardiaca") ? "form-control is-invalid mask-inteiro3" : "form-control mask-inteiro3")])); ?>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="freq_respiratoria">Freq. respiratória (mrm)</label>
                                        <?php echo e(Form::text('freq_respiratoria', (isset($acolhimento->freq_respiratoria) ? $acolhimento->freq_respiratoria : "") ,["maxlength" => "3", "disabled", "id" => "freq_respiratoria", 'class'=> ($errors->has("freq_respiratoria") ? "form-control is-invalid mask-inteiro3" : "form-control mask-inteiro3") ])); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="estado_nutricional">Estado nutricional</label>
                                        <?php echo e(Form::text('t_estado_nutricional', (isset($acolhimento->estado_nutricional) ? $acolhimento->estado_nutricional : "") ,["maxlength" => "5", "disabled" => "disabled",'class'=> "estado_nutricional form-control" ])); ?>
                                        <?php echo e(Form::hidden('estado_nutricional',(isset($acolhimento->estado_nutricional) ? $acolhimento->estado_nutricional : ""),["name" => "estado_nutricional", "class" => "estado_nutricional"])); ?>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="risco_cintura">Risco associado à circunfêrencia da cintura</label>
                                        <?php echo e(Form::text('t_risco_cintura', (isset($acolhimento->risco_cintura) ? $acolhimento->risco_cintura : "") ,["maxlength" => "5", "disabled" => "disabled", 'class'=> ($errors->has("risco_cintura") ? "form-control is-invalid" : "risco_cintura form-control") ])); ?>
                                        <?php echo e(Form::hidden('risco_cintura',(isset($acolhimento->risco_cintura) ? $acolhimento->risco_cintura : ""),["name" => "risco_cintura", "class" => "risco_cintura"])); ?>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="glicemia_capilar">Glicemia Capilar (mg/dL)</label>
                                        <?php echo e(Form::text('glicemia_capilar',(isset($acolhimento->glicemia_capilar) ? $acolhimento->glicemia_capilar : ""),["name" => "glicemia_capilar", "disabled", "maxlength" => "3", 'class'=> ($errors->has("glicemia_capilar") ? " mask-numeros-3 form-control is-invalid" : " mask-numeros-3 form-control")])); ?>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="saturacao">Saturação (%O2)</label>
                                        <?php echo e(Form::text('saturacao', (isset($acolhimento->saturacao) ? $acolhimento->saturacao : "") ,["maxlength" => "3", "disabled", "id" => "saturacao", 'class'=> ($errors->has("saturacao") ? "form-control is-invalid" : "form-control mask-numeros-3") ])); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-8">
                                <div class="panel-heading"><b>Escala de coma de Glasgow</b></div>
                                <div class="panel-body panel-acolhimento">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="abertura_ocular">Abertura ocular</label>
                                                <?php echo e(Form::select('abertura_ocular',arrayPadrao('abertura_ocular'),(isset($acolhimento->abertura_ocular) ? $acolhimento->abertura_ocular : ""),["id" => "abertura_ocular", "disabled", 'class'=> ($errors->has("abertura_ocular") ? "form-control is-invalid" : "form-control")])); ?>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="resposta_verbal">Resposta verbal</label>
                                                <?php echo e(Form::select('resposta_verbal', arrayPadrao('resposta_verbal'),(isset($acolhimento->resposta_verbal) ? $acolhimento->resposta_verbal : "") ,["id" => "resposta_verbal", "disabled", 'class'=> ($errors->has("resposta_verbal") ? "form-control is-invalid" : "form-control") ])); ?>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="resposta_motora">Resposta motora</label>
                                                <?php echo e(Form::select('resposta_motora',arrayPadrao('resposta_motora'), (isset($acolhimento->resposta_motora) ? $acolhimento->resposta_motora : "") ,["id" => "resposta_motora", "disabled", 'class'=> ($errors->has("resposta_motora") ? "form-control is-invalid" : "form-control") ])); ?>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="escore_glasgow">Escore</label>
                                                <?php echo e(Form::text('t_escore_glasgow', (isset($acolhimento->escore_glasgow) ? $acolhimento->escore_glasgow : "") ,["maxlength" => "5", "disabled" => "disabled", "id" => "t_escore_glasgow", 'class'=> ($errors->has("escore_glasgow") ? "form-control is-invalid" : "form-control") ])); ?>
                                                <?php echo e(Form::hidden('escore_glasgow',(isset($acolhimento->escore_glasgow) ? $acolhimento->escore_glasgow : ""),["name" => "escore_glasgow", "id" => "escore_glasgow"])); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="panel-heading"><b>Escala de dor</b></div>
                                <div class="panel-body panel-acolhimento">
                                    <div class="range-control" id="<?php echo e(($acolhimento->idade > 12) ? 'escala_dor_adulta' : 'escala_dor_infantil'); ?>">
                                        <!-- <input id="inputRange" type="range" min="0" max="10" value="0" data-thumbwidth="1"> -->
                                        <?php echo e(Form::range('nivel_de_dor',(isset($acolhimento->nivel_de_dor) ? $acolhimento->nivel_de_dor : 0), ["id" => 'inputRange', "disabled", "min" => 0, "max" => 10, "data-thumbwidth" => 1 ])); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="panel-heading"><b>Alergias</b></div>
                                <div class="panel-body panel-acolhimento">
                                    <div class="col-md-12">
                                        <div class='table-responsive'>
                                            <div class="lista-alergias-pessoa"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="panel-heading"><b>História médica pregressa</b></div>
                                <div class="panel-body panel-acolhimento">
                                    <div class="col-md-12">
                                        <div class='table-responsive'>
                                            <div class='lista-historia-medica-pregressa'></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="panel-heading"><b>Cirurgias prévias</b></div>
                                <div class="panel-body panel-acolhimento">
                                    <div class="col-md-12">
                                        <div class='table-responsive'>
                                            <div class='lista-cirurgias-previas'></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="panel-heading"><b>Medicamentos em uso</b></div>
                                <div class="panel-body panel-acolhimento">
                                    <div class="col-md-12">
                                        <div class='table-responsive'>
                                            <div class='lista-medicamentos-em-uso'></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="exames_apresentados">Exames apresentados</label>
                                            <?php echo e(Form::textarea('exames_apresentados',(isset($acolhimento->exames_apresentados) ? $acolhimento->exames_apresentados : ""),["name" => "exames_apresentados", "maxlength" => "500", "rows"=>"3", "disabled", "id" => "exames_apresentados",  'class'=> ($errors->has("exames_apresentados") ? "form-control is-invalid" : "form-control")])); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel-heading"><b>Avaliação médica</b></div>
                            <div class="panel-body panel-acolhimento">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="avaliacao">Objetivo</label>
                                            <?php echo e(Form::textarea('descricao_objetivo',(isset($atendimento->descricao_objetivo) ? $atendimento->descricao_objetivo : ""),["name" => "descricao_objetivo", "maxlength" => "950", (isset($atendimento->descricao_objetivo) ? "disabled" : ""), "rows"=>"3", "id" => "descricao_objetivo",  'class'=> ($errors->has("descricao_objetivo") ? "form-control is-invalid" : "form-control")])); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="tab_avaliacao">
                 <div class="col-md-12">
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
                        <div class="panel-heading"><b>Diagnóstico principal</b></div>
                    <div class="panel-body panel-acolhimento">
                        <?php if(((!isset($atendimento)) || (isset($atendimento) && $atendimento->status == 'A'))  && $prontuario->status == 'A'): ?>
                             <input type="button" class="btn btn-info btn-sm" id="add-cid-principal" data-toggle="modal" value="Acicionar" data-target="#escolhe-cid">
                        <?php endif; ?>

                        <div id="diagnostico_principal"></div>
                    </div>
                        <div class="panel-heading"><b>Diagnósticos secundários</b></div>
                    <div class="panel-body panel-acolhimento">

                        <?php if(((!isset($atendimento)) || (isset($atendimento) && $atendimento->status == 'A')) && $prontuario->status == 'A'): ?>
                            <input type="button" class="btn btn-info btn-sm" id="add-cid-secundaria" data-toggle="modal" value="Acicionar" data-target="#escolhe-cid">
                        <?php endif; ?>

                        <div id="diagnostico_secundario"></div>
                    </div>
                </div>
                 <div class="col-md-12">
                    <div class="col-md-3" style="height: 25%">
                        <div class="panel-heading"><b>Alergias</b></div>
                        <div class="row">
                            <div class="panel-body panel-acolhimento">
                                <?php if(((!isset($atendimento)) || (isset($atendimento) && $atendimento->status == 'A'))  && $prontuario->status == 'A'): ?>
                                <label for="avaliacao">Lista de alergias &nbsp
                                    <span>
                                        <button type="button" id="btn-modal-alergia" data-toggle="modal"  class="btn btn-info btn-xs">Adicionar</button>
                                    </span>
                                </label>
                                <?php endif; ?>
                                <div class="col-md-12">
                                    <div class='table-responsive'>
                                        <div class="lista-alergias-pessoa"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3" style="height: 25%">
                        <div class="panel-heading"><b>História médica pregressa </b><br></div>
                        <div class="row">
                            <div class="panel-body panel-acolhimento">
                                <?php if(((!isset($atendimento)) || (isset($atendimento) && $atendimento->status == 'A'))  && $prontuario->status == 'A'): ?>
                                <label for="avaliacao">Lista de Cids &nbsp
                                    <span>
                                        <button type="button" data-toggle="modal" class="btn btn-info btn-xs" id="btn-modal-historia-medica-pregressa">Adicionar</button>
                                    </span>
                                </label>
                                <?php endif; ?>
                                <div class="col-md-12">
                                    <div class='table-responsive'>
                                        <div class='lista-historia-medica-pregressa'></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3" style="height: 25%">
                        <div class="panel-heading"><b>Cirurgias prévias</b></div>
                        <div class="row">
                            <div class="panel-body panel-acolhimento">
                                <?php if(((!isset($atendimento)) || (isset($atendimento) && $atendimento->status == 'A'))  && $prontuario->status == 'A'): ?>
                                <label for="avaliacao">Lista de Cirurgias &nbsp
                                    <span>
                                        <button type="button" data-toggle="modal" class="btn btn-info btn-xs" data-target="#modal-cirurgias-previas">Adicionar</button>
                                    </span>
                                </label>
                                <?php endif; ?>
                                <div class="col-md-12">
                                    <div class='table-responsive'>
                                        <div class='lista-cirurgias-previas'></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3" style="height: 25%">
                        <div class="panel-heading"><b>Medicamentos em uso</b></div>
                        <div class="row">
                            <div class="panel-body panel-acolhimento">
                                <?php if(((!isset($atendimento)) || (isset($atendimento) && $atendimento->status == 'A'))  && $prontuario->status == 'A'): ?>
                                <label for="avaliacao">Lista de medicamentos em uso &nbsp
                                    <span>
                                        <button type="button" data-toggle="modal" class="btn btn-info btn-xs" data-target="#modal-medicamentos-em-uso">Adicionar</button>
                                    </span>
                                </label>
                                <?php endif; ?>
                                <div class="col-md-12">
                                    <div class='table-responsive'>
                                        <div class='lista-medicamentos-em-uso'></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                 </div>
                </div>
                <div class="tab-pane fade" id="tab_plano">
                    <div class="panel-body panel-acolhimento">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="descricao_plano">Intervenção/ Procedimentos</label>
                                    <?php echo e(Form::textarea('descricao_plano',(isset($atendimento->descricao_plano) ? $atendimento->descricao_plano : (isset($_POST['descricao_plano']) ? $_POST['descricao_plano'] : '')),["name" => "descricao_plano", "maxlength" => "1000", "rows"=>"3", "id" => "descricao_plano", isset($atendimento->descricao_plano) ? "disabled"  : "", 'class'=> ($errors->has("descricao_plano") ? "form-control input-sm is-invalid" : "form-control input-sm")])); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-heading"><b>Evolução Clínica</b>
                        <?php if(((!isset($atendimento)) || (isset($atendimento) && $atendimento->status == 'A'))  && $prontuario->status == 'A'): ?>
                            <?php if(session()->get('profissional') && (session()->get('recurso.atendimentos-evolucao-salvar'))): ?>
                                <input data-toggle="modal" class="btn btn-info btn-xs" data-target="#modal-detalhes-evolucao" data-permissoes="true" value="Adicionar" type="button">
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                    <div class="panel-body panel-acolhimento">
                        <div class="row">
                            <div style="font-size: 9pt" class='table-responsive'>
                                <table class='table table-hover table-striped'>
                                    <thead>
                                    <tr>
                                        <th width="80">Data/ Hora</th>
                                        <th width="170">Sala/ Leito</th>
                                        <th width="100">Profissional</th>
                                        <th>Descrição</th>
                                    </tr>
                                    </thead>
                                    <tbody class="tabela-detalhes-evolucao">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="tab_conduta">
                    <div class="col-md-12">
                        <div class="panel-heading">
                            <?php if(((!isset($atendimento)) || (isset($atendimento) && $atendimento->status == 'A'))  && $prontuario->status == 'A'): ?>
                                <h4>Receituário<button data-titulo-principal="Receituário" data-toggle="modal" class="btn btn-info btn-xs btn-add-prescricao-receita" data-target="#modal-receituario" data-titulo="Receita médica" data-configuracao="medicacao" title="Clique aqui para adicionar um novo item ao receituário" data-botoes="receituario_prescricao" data-tp-conduta="receituario" value="Adicionar" type="button">Adicionar</button></h4>
                            <?php endif; ?>
                        </div>
                        <div id="div-receituario" class="panel-body panel-acolhimento" style="display: none">
                            <div class="col-md-12">
                                <h5>Medicamentos
                                    <a href="<?php echo e(route('relatorios/receita').'/'.$acolhimento->cd_prontuario); ?>" target="_blank" title="Clique aqui para imprimir a receita de medicação" class="btn btn-primary btn-xs"><span class="fa fa-print"></span></a>
                                    <a href="<?php echo e(route('relatorios/receita-especial').'/'.$acolhimento->cd_prontuario); ?>" target="_blank" title="Clique aqui para imprimir a receita de medicação de controle especial" class="btn btn-primary btn-xs">Especial &nbsp;<span class="fa fa-print"></span></a>
                                </h5>
                                <div class="table-responsive">
                                    <table class="table table-hover table-striped table_medicacao_receituario font-size-9pt" >

                                    </table>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <h5>Exames laboratoriais
                                    <a href="<?php echo e(route('relatorios/exames-laboratoriais').'/'.$acolhimento->cd_prontuario); ?>" target="_blank" title="Clique aqui para imprimir o receituário dos exames laboratoriais" class="btn btn-primary btn-xs"><span class="fa fa-print"></span></a>
                                </h5>
                                <div class="table-responsive">
                                    <table class="table table-hover table-striped table_exames_laboratoriais_receituario font-size-9pt" >

                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="panel-heading"><h4>Prescrição ambulatorial(Pacientes em observação)&nbsp;<button data-toggle="modal" data-titulo-principal="Prescrição Ambulatorial" class="btn btn-info btn-xs btn-add-prescricao-receita" data-target="#modal-receituario" data-titulo="Dieta" data-botoes="prescricao_ambulatorial" data-configuracao="dieta" data-tp-conduta="prescricao_ambulatorial" type="button">Adicionar</button></h4></div>
                        <div class="titulo-div-ambulatorial" id="titulo-div-ambulatorial" style="display: none"></div>
                        <div id="div-prescricao-ambulatorial" class="panel-body panel-acolhimento" style="display: none">
                            <a id="btn-imprimir-prescricao-ambulatorial" style="display: none" href="<?php echo e(route('relatorios/prescricao-ambulatorial').'/'.$acolhimento->cd_prontuario.'/1'); ?>"  target="_blank" class="btn btn-primary pull-right"><span class="fa fa-print"></span></a>
                            <div class="div-mostra-dieta-prescricao-ambulatorial col-md-12"></div>
                            <div class="div-mostra-sinais-vitais-prescricao-ambulatorial col-md-12"></div>
                            <div class="div-mostra-outros-cuidados-prescricao-ambulatorial col-md-12"></div>
                            <div class="div-mostra-oxigenoterapia-prescricao-ambulatorial col-md-12"></div>
                            <div id="div-mostra-prescricao-ambulatorial" class="col-md-12" style="display: none">
                                <div class="table-responsive">
                                    <table class="table table-hover table-striped table_medicacao_prescricao_ambulatorial font-size-9pt" >
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="panel-heading"><h4>Prescrição hospitalar&nbsp;<button data-toggle="modal" data-titulo-principal="Prescrição Hospitalar" class="btn btn-info btn-xs btn-add-prescricao-receita" data-target="#modal-receituario" data-titulo="Dieta" data-botoes="prescricao_hospitalar" data-configuracao="dieta" data-tp-conduta="prescricao_hospitalar" type="button">Adicionar</button></h4></div>
                        <div id="div-prescricao-hospitalar" class="panel-body panel-acolhimento" style="display: none">
                            <a href="<?php echo e(route('relatorios/prescricao-hospitalar').'/'.$acolhimento->cd_prontuario); ?>" target="_blank" class="btn btn-primary pull-right"><span class="fa fa-print"></span></a>
                            <div class="div-mostra-dieta-prescricao-hospitalar col-md-12"></div>
                            <div class="div-mostra-sinais-vitais-prescricao-hospitalar col-md-12"></div>
                            <div class="div-mostra-outros-cuidados-prescricao-hospitalar col-md-12"></div>
                            <div class="div-mostra-oxigenoterapia-prescricao-hospitalar col-md-12"></div>
                            <div id="div-mostra-prescricao-hospitalar" class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-hover table-striped table_medicacao_prescricao_medica font-size-9pt" >
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="panel-heading"><h4>Procedimentos ambulatoriais
                            <?php if($prontuario->status != 'C'): ?>
                                <?php if(session()->get('profissional')): ?>
                                    <button class="btn btn-info btn-xs" id="abre-modal-add-procedimentos" type="button">Adicionar</button>
                                <?php else: ?>
                                    <p class="text-silver">Somente usuários cadastrados na Tabela de Profissionais de Saúde podem solicitar procedimentos.</p>
                                <?php endif; ?>
                            <?php endif; ?>
                            </h4>
                        </div>
                        <div class="panel-body panel-acolhimento lista-procedimentos" id="lista-procedimentos-atendimento-medico" style="display: none">
                            <div class="col-md-12">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
         </div>
        <div class="panel-footer fixed-panel-footer">
            <div class="pull-left"><b>Prontuário Número:</b> <?php echo e($acolhimento->cd_prontuario); ?>
                <br>
                <b>Entrada: </b><?php echo e(formata_hora($prontuario->created_at)); ?>
                 <?php if($prontuario->status == 'C'): ?>
                     <b> Conclusão: </b> <?php echo e(formata_hora($prontuario->finished_at)); ?>
                 <?php endif; ?>
             </div>
            <?php if(isset($atendimento) && $atendimento->status != 'A' && isset($atendimento->motivo_alta)): ?>
                <div class="pull-right" style="color: #dd1609">
                    <b>&nbsp;&nbsp;Motivo da alta: </b> <?php echo e(arrayPadrao('motivo_alta')[$atendimento->motivo_alta]); ?>
                </div>
            <?php endif; ?>
            <div class="pull-right">
                <?php if((session()->get('recurso.atendimentos-atendimento-medico-salvar')) && session()->get('profissional') && ((!isset($atendimento)) || (isset($atendimento) && $atendimento->status == 'A')) && $prontuario->status == 'A'): ?>
                    <?php echo e(Form::submit('Salvar',['class'=>"btn btn-success pull-right"])); ?>
                <?php endif; ?>
            </div>
        </div>
    </div>


    <div id="modal-finalizar-atendimento" class="modal fade"  tabindex="-1" role="dialog" aria-hidden="true">
        <div id="dialog-finalizar-atendimento" class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Finalizar Atendimento</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="motivo_alta">Selecione o motivo da alta</label>
                                <?php echo e(Form::select('motivo_alta',arrayPadrao('motivo_alta'),(isset($atendimento->motivo_alta) ? $atendimento->motivo_alta : 0),["name"=>"motivo_alta", (isset($atendimento) && $atendimento->status !== 'A') ? "disabled" : "", "id"=>"motivo_alta",'class'=> ($errors->has("motivo_alta") ? "form-control input-sm is-invalid" : "form-control")])); ?>
                                <?php if(isset($atendimento) && $atendimento->status != 'A'): ?>
                                    <?php echo e(Form::hidden('motivo_alta',($atendimento->motivo_alta ? $atendimento->motivo_alta : 0),["id"=>"motivo_alta",'class'=> ($errors->has("acompanhante") ? "form-control input-sm is-invalid" : "form-control")])); ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="descricao_alta">Descrição do motivo da alta</label>
                                <?php echo e(Form::textarea('descricao_alta',(isset($atendimento->descricao_alta) ? $atendimento->descricao_alta : '') ,["name" => "descricao_alta", "rows" => 4 , "maxlength" => "950", (isset($atendimento->descricao_alta)) ? "disabled" : "", "id" => "descricao_alta",  'class'=> ($errors->has("descricao_alta") ? "form-control input-sm is-invalid" : "form-control input-sm")])); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-footer" style="text-align:left !important;">
                    <button type="button" class="btn btn-danger margin-top-zero" data-dismiss="modal">Fechar</button>
                    <?php if(session()->get('profissional') && session()->get('recurso.atendimentos-atendimento-medico-salvar-apos-medicacao') && isset($atendimento) && $atendimento->status == 'E'): ?>
                        <?php echo e(Form::submit('Finalizar Atendimento após Medicação',['id'=>'finalizar-atendimento-apos-medicacao', 'class'=>"btn btn-success pull-right"])); ?>
                    <?php endif; ?>
                    <?php if(session()->get('profissional') && (session()->get('recurso.atendimentos-atendimento-medico-salvar'))): ?>
                        <?php if(((!isset($atendimento)) || (isset($atendimento) && $atendimento->status == 'A'))): ?>
                            <?php echo e(Form::submit('Finalizar Atendimento',['id'=>'finalizar-atendimento', 'class'=>"btn btn-success pull-right"])); ?>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <?php echo e(Form::close()); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('painel-modal'); ?>
    <?php echo $__env->make('pessoas.modal-pessoas', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make('atendimentos/modal-procedimentos', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make('atendimentos/modal-historico', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make('atendimentos/modal-evolucao', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>


    <div class="modal fade" id="escolhe-sala"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="myModalLabel">Chamar no Painel</h3>
                </div>
                <div class="modal-body">
                    <div class="panel-body">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="sala"><h4>Selecione uma Sala</h4></label>
                                <select id="salas" class="form-control input-sm" style="color:#287da1;">
                                    <?php $__currentLoopData = $salas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value='<?php echo e($s->cd_sala); ?>' <?php echo e((session()->get('cd_sala')==$s->cd_sala) ? "selected" : ""); ?>><?php echo e($s->nm_sala); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="painel"><h4>Selecione um Painel</h4></label>
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
                    <h3 class="modal-title" id="myModalLabel">Informe o Diagnóstico</h3>
                </div>
                    <div class="panel-body">
                        <div class="panel-body panel-acolhimento">
                            <div class="col-md-8">
                                <div class="input-group">
                                    <label for="id_cid">CID10<br></label>
                                    <input type="text" data-id="" id="id_cid" disabled class="form-control">
                                    <input type="hidden" id="cid_principal">
                                    <span class="input-group-btn">
                                       <!-- <button type="button" data-toggle="modal" class="btn btn-info search margin-top-21" data-fechar="true" data-destino="pesquisa_cid" data-target="#modal-search"><span class="fa fa-search"></span></button> -->
                                        <button type="button" data-toggle="modal" class="btn btn-info margin-top-21" id="btn-modal-cid"><span class="fa fa-search"></span></button>
                                    </span>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="dt_primeiros_sintomas">Dt. primeiros sintomas</label>
                                    <?php echo e(Form::text('dt_primeiros_sintomas',date("d/m/Y"),["id" => "dt_primeiros_sintomas", 'class'=> "form-control input-sm mask-data"])); ?>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="tipo_diagnostico">Tipo de diagnóstico</label>
                                    <?php echo e(Form::select('tipo_diagnostico',arrayPadrao('tipo_diagnostico'),'D',['name'=>'tipo_diagnostico','id'=>'tipo_diagnostico','class'=>'form-control input-sm'])); ?>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="diagnostico_trabalho">Acidente no Trabalho</label>
                                    <?php echo e(Form::select('diagnostico_trabalho',arrayPadrao('opcao'),'I',['name'=>'diagnostico_trabalho','id'=>'diagnostico_trabalho','class'=>'form-control input-sm'])); ?>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="diagnostico_transito">Acidente de Trânsito</label>
                                    <?php echo e(Form::select('diagnostico_transito',arrayPadrao('opcao'),'I',['name'=>'diagnostico_transito','id'=>'diagnostico_transito','class'=>'form-control input-sm'])); ?>
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
                                <input type="hidden" class="form-control input-sm" disabled="disabled" id="d_id_atendimento_procedimento">
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
                                <textarea class="form-control input-sm" id="d_execucao"></textarea>
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
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="status">Data/ Hora execução</label>
                                <input class="form-control input-sm" type="text" disabled="disabled" id="d_hr_execucao">
                            </div>
                        </div>
                    </div>
                 </div>
                <div class="panel-footer" style="text-align:left !important;">
                    <button type="button" class="btn btn-danger margin-top-zero" data-dismiss="modal">Sair</button>
                    <?php echo e(Form::submit('Salvar',['id'=>'salvar-procedimento-atendimento', 'class'=>"btn btn-success pull-right"])); ?>
                    <?php echo e(Form::submit('Encerrar Procedimento',['id'=>'finalizar-procedimento-atendimento', 'class'=>"btn btn-warning pull-right"])); ?>
                </div>
            </div>
        </div>
    </div>


    <?php echo $__env->make('atendimentos/modal-alergia', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make('atendimentos/modal-cirurgias-previas', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make('atendimentos/modal-medicamentos-em-uso', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make('atendimentos/modal-historia-medica-pregressa', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make('atendimentos/modal-pesquisa', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make('atendimentos/modal-cid', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make('atendimentos/modal-receituario', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>


<?php $__env->stopSection(); ?>

<?php $__env->startSection('custom_js'); ?>
    <script src="<?php echo e(js_versionado('prontuario.js')); ?>"></script>
    <!--<script src="<?php echo e(js_versionado('autocompletar.js')); ?>"></script>-->
    <script src="<?php echo e(js_versionado('atendimento_medico.js')); ?>"></script>
<?php $__env->stopSection(); ?>



<?php echo $__env->make('layouts.default', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>