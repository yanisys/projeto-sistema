<?php $__env->startSection('conteudo'); ?>
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

    <?php echo e(Form::open(['id' => 'form-cadastra-acolhimento', 'class' => 'form-no-submit'])); ?>

    <div class="panel panel-primary" id="panel-acolhimento">
        <div class="panel-heading">
        <!--    <button id='chamar-painel' data-toggle='modal' data-target="#escolhe-sala" class="btn btn-primary pull-right">Chamar painel</button> -->
            <button class="btn btn-primary pull pull-right ver_pessoa" type="button" data-toggle="modal" data-target="#modal-pesquisa">Ver cadastro</button>
            <button type="button" class="btn btn-primary pull pull-right" id="ver-historico" type="button" value='<?php echo e((isset($lista['cd_pessoa']) ? $lista['cd_pessoa'] : "")); ?>' data-toggle="modal" data-target="#modal-historico">Histórico</button>
            <a href="<?php echo e(route('relatorios/prontuario').'/'.$lista['cd_prontuario']); ?>" target="_blank" class="btn btn-primary pull-right"><span class="fa fa-print"></span></a>
            <h4>Acolhimento - Dados do Paciente</h4>
        </div>
        <div class="panel-body">
            <?php echo e(Form::hidden('cd_pessoa',(isset($lista['cd_pessoa']) ? $lista['cd_pessoa'] : ""),["name" => "cd_pessoa", "id" => "cd_pessoa_disabled"  ])); ?>

            <div class="panel-heading">Objetivo</div>
            <div class="panel-body panel-acolhimento">
                <div class="row">
                    <input type="hidden" id='sexo' name="id_sexo" value="<?php echo e($lista['id_sexo']); ?>">
                    <input type="hidden" id='dt_nasc' name="dt_nasc" value="<?php echo e($lista['dt_nasc']); ?>">
                    <?php echo e(Form::hidden('cd_prontuario',(isset($lista['cd_prontuario']) ? $lista['cd_prontuario'] : ""),["name" => "cd_prontuario", "id" => "cd_prontuario"])); ?>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="cintura">Cintura (cm)</label>
                            <?php echo e(Form::text('cintura', (isset($lista['cintura']) ? $lista['cintura'] : "") ,["maxlength" => "3", "id" => "cintura", 'class'=> ($errors->has("cintura") ? "form-control is-invalid" : "form-control mask-inteiro3") ])); ?>

                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="quadril">Quadril (cm)</label>
                            <?php echo e(Form::text('quadril', (isset($lista['quadril']) ? $lista['quadril'] : "") ,["maxlength" => "3", "id" => "quadril", 'class'=> ($errors->has("quadril") ? "form-control is-invalid mask-inteiro3" : "form-control mask-inteiro3") ])); ?>

                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="indice_cintura_quadril">Índice cintura/ quadril</label>
                            <?php echo e(Form::text('t_indice_cintura_quadril', "" ,["maxlength" => "5", "disabled" => "disabled", "class"=> "indice_cintura_quadril form-control" ])); ?>

                            <?php echo e(Form::hidden('indice_cintura_quadril',"",["name" => "indice_cintura_quadril", "class" => "indice_cintura_quadril"])); ?>

                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="peso">Peso (Kg)</label>
                            <?php echo e(Form::text('peso', (isset($lista['peso']) ? $lista['peso'] : "") ,["maxlength" => "7", "id" => "peso", 'class'=> ($errors->has("peso") ? "form-control is-invalid" : "form-control mask-decimal33") ])); ?>

                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="altura">Altura (m)</label>
                            <?php echo e(Form::text('altura', (isset($lista['altura']) ? $lista['altura'] : "") ,["maxlength" => "4", "id" => "altura", 'class'=> ($errors->has("altura") ? "form-control is-invalid" : "form-control  mask-decimal12") ])); ?>

                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="massa_corporal">Massa corporal</label>
                            <?php echo e(Form::text('t_massa_corporal', (isset($lista['massa_corporal']) ? $lista['massa_corporal'] : '') ,["maxlength" => "5", "disabled" => "disabled", 'class'=> ($errors->has("massa_corporal") ? "massa_corporal form-control is-invalid" : "massa_corporal form-control") ])); ?>

                            <?php echo e(Form::hidden('massa_corporal',(isset($lista['massa_corporal']) ? $lista['massa_corporal'] : ""),["name" => "massa_corporal", "class" => "massa_corporal"])); ?>

                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="pressao_arterial">P.A (mmHg)</label>
                            <?php echo e(Form::text('pressao_arterial',(isset($lista['pressao_arterial']) ? $lista['pressao_arterial'] : ""),["name" => "pressao_arterial", "maxlength" => "5", "id" => "pressao_arterial",  'class'=> ($errors->has("pressao_arterial") ? "form-control is-invalid" : "form-control mask-pressao-arterial")])); ?>

                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="temperatura">Temperatura (ºC)</label>
                            <?php echo e(Form::text('temperatura', (isset($lista['temperatura']) ? $lista['temperatura'] : "") ,["maxlength" => "5", "id" => "temperatura", 'class'=> ($errors->has("temperatura") ? "form-control is-invalid" : "form-control   mask-decimal22") ])); ?>

                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="freq_cardiaca">Freq. cardíaca/ pulso (bpm)</label>
                            <?php echo e(Form::text('freq_cardiaca',(isset($lista['freq_cardiaca']) ? $lista['freq_cardiaca'] : ""),["name" => "freq_cardiaca", "maxlength" => "3", "id" => "freq_cardiaca",  'class'=> ($errors->has("freq_cardiaca") ? "form-control is-invalid mask-inteiro3" : "form-control mask-inteiro3")])); ?>

                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="freq_respiratoria">Freq. respiratória (mrm)</label>
                            <?php echo e(Form::text('freq_respiratoria', (isset($lista['freq_respiratoria']) ? $lista['freq_respiratoria'] : "") ,["maxlength" => "3", "id" => "freq_respiratoria", 'class'=> ($errors->has("freq_respiratoria") ? "form-control is-invalid mask-inteiro3" : "form-control mask-inteiro3") ])); ?>

                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="estado_nutricional">Estado nutricional</label>
                            <?php echo e(Form::text('t_estado_nutricional', (isset($lista['estado_nutricional']) ? $lista['estado_nutricional'] : "") ,["maxlength" => "5", "disabled" => "disabled",'class'=> "estado_nutricional form-control" ])); ?>

                            <?php echo e(Form::hidden('estado_nutricional',(isset($lista['estado_nutricional']) ? $lista['estado_nutricional'] : ""),["name" => "estado_nutricional", "class" => "estado_nutricional"])); ?>

                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="risco_cintura">Risco associado à circunfêrencia da cintura</label>
                            <?php echo e(Form::text('t_risco_cintura', (isset($lista['risco_cintura']) ? $lista['risco_cintura'] : "") ,["maxlength" => "5", "disabled" => "disabled", 'class'=> ($errors->has("risco_cintura") ? "form-control is-invalid" : "risco_cintura form-control") ])); ?>

                            <?php echo e(Form::hidden('risco_cintura',(isset($lista['risco_cintura']) ? $lista['risco_cintura'] : ""),["name" => "risco_cintura", "class" => "risco_cintura"])); ?>

                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="glicemia_capilar">Glicemia Capilar (md/dll)</label>
                            <?php echo e(Form::text('t_glicemia_capilar',(isset($lista['glicemia_capilar']) ? $lista['glicemia_capilar'] : ""),["name" => "t_glicemia_capilar", "disabled" => "disabled","maxlength" => "5", 'class'=> ($errors->has("glicemia_capilar") ? "form-control is-invalid" : "glicemia_capilar form-control")])); ?>

                            <?php echo e(Form::hidden('glicemia_capilar',(isset($lista['glicemia_capilar']) ? $lista['glicemia_capilar'] : ""),["name" => "glicemia_capilar", "class" => "glicemia_capilar"])); ?>

                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="glicemia">&nbsp</label>
                            <?php echo e(Form::text('t_glicemia', (isset($lista['glicemia']) ? $lista['glicemia'] : "") ,["maxlength" => "5", "disabled" => "disabled", "id" => "t_glicemia", 'class'=> ($errors->has("glicemia") ? "form-control is-invalid" : "form-control") ])); ?>

                            <?php echo e(Form::hidden('glicemia',(isset($lista['glicemia']) ? $lista['glicemia'] : ""),["name" => "glicemia", "id" => "glicemia"])); ?>

                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="saturacao">Saturação (%O2)</label>
                            <?php echo e(Form::text('saturacao', (isset($lista['saturacao']) ? $lista['saturacao'] : "") ,["maxlength" => "3", "id" => "saturacao", 'class'=> ($errors->has("saturacao") ? "form-control is-invalid" : "form-control mask-numeros-3") ])); ?>

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
                            <?php echo e(Form::select('abertura_ocular',arrayPadrao('abertura_ocular'),(isset($lista['abertura_ocular']) ? $lista['abertura_ocular'] : ""),["id" => "abertura_ocular",  'class'=> ($errors->has("abertura_ocular") ? "form-control is-invalid" : "form-control")])); ?>

                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="resposta_verbal">Resposta verbal</label>
                            <?php echo e(Form::select('resposta_verbal', arrayPadrao('resposta_verbal'),(isset($lista['resposta_verbal']) ? $lista['resposta_verbal'] : "") ,["id" => "resposta_verbal", 'class'=> ($errors->has("resposta_verbal") ? "form-control is-invalid" : "form-control") ])); ?>

                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="resposta_motora">Resposta motora</label>
                            <?php echo e(Form::select('resposta_motora',arrayPadrao('resposta_motora'), (isset($lista['resposta_motora']) ? $lista['resposta_motora'] : "") ,["id" => "resposta_motora", 'class'=> ($errors->has("resposta_motora") ? "form-control is-invalid" : "form-control") ])); ?>

                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="escore_glasgow">escore</label>
                            <?php echo e(Form::text('t_escore_glasgow', (isset($lista['escore_glasgow']) ? $lista['escore_glasgow'] : "") ,["maxlength" => "5", "disabled" => "disabled", "id" => "t_escore_glasgow", 'class'=> ($errors->has("escore_glasgow") ? "form-control is-invalid" : "form-control") ])); ?>

                            <?php echo e(Form::hidden('escore_glasgow',(isset($lista['escore_glasgow']) ? $lista['escore_glasgow'] : ""),["name" => "escore_glasgow", "id" => "escore_glasgow"])); ?>

                        </div>
                    </div>
                </div>
            </div>
            <div class="panel-heading">Avaliação</div>
            <div class="panel-body panel-acolhimento">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="avaliacao">Avaliação do profissional de saúde (Histórico de saúde/Queixa principal)</label>
                            <?php echo e(Form::textarea('avaliacao',(isset($lista['avaliacao']) ? $lista['avaliacao'] : ""),["name" => "avaliacao", "maxlength" => "950", "rows"=>"3", "id" => "avaliacao",  'class'=> ($errors->has("avaliacao") ? "form-control is-invalid" : "form-control")])); ?>

                        </div>
                    </div>
                </div>
            </div>
            <div class="panel-heading">Classificação de risco</div>
            <div class="panel-body panel-acolhimento">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="avaliacao">Avaliação do profissional de saúde</label>
                            <select id="classifica" class="form-control">
                                <option value='1' style=background-color:blue;>NÃO URGENTE</option>
                                <option value='2' style=background-color:forestgreen;>POUCO URGENTE</option>
                                <option value='3' style=background-color:yellow;>URGENTE</option>
                                <option value='4' style=background-color:orange;>MUITO URGENTE</option>
                                <option value='5' style=background-color:red;>EMERGÊNCIA</option>
                                <option value='6' style=background-color:black;>DESISTENTE/ EVADIU</option>
                            </select>
                        </div>
                    </div>
                    <?php echo e(Form::hidden('classificacao',(isset($lista['classificacao']) ? $lista['classificacao'] : ""),["name" => "classificacao", "id" => "classificacao"])); ?>

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
                <?php if((session()->get('recurso.atendimentos-acolhimento-salvar'))): ?>
                    <?php echo e(Form::submit('Salvar',['class'=>"btn btn-success pull-right"])); ?>

                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php echo e(Form::close()); ?>


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
                            <select id="salas" class="form-control" style="color:#287da1;">
                                <?php $__currentLoopData = $salas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value='<?php echo e($s->cd_sala); ?>' <?php echo e((session()->get('cd_sala')==$s->cd_sala) ? "selected" : ""); ?>><?php echo e($s->ds_sala); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="painel"><h4>Selecione um painel</h4></label>
                            <select id="paineis" class="form-control" style="color:#287da1;">
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
    <?php echo $__env->make('atendimentos/modal-historico', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make('pessoas/modal-pessoas', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('custom_js'); ?>
   <script src="<?php echo e(js_versionado('prontuario.js')); ?>"></script>
<?php $__env->stopSection(); ?>



<?php echo $__env->make('layouts.default', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>