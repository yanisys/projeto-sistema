<div id="modal-receituario" class="modal fade"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="font-size: 9px">
    <div class="modal-dialog" role="document" id="dialog-receituario">
        <div class="modal-content" id="content-receituario">
            <div class="modal-header">
                <button id="fechar-modal-receituario" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Fechar</span></button>
                <h3 id="div-titulo-receituario" class="modal-title"></h3>
                <div class="titulo-div-ambulatorial" id="titulo-div-ambulatorial-modal" style="display: none"></div>
            </div>
            <div class="modal-body modal-body-scroll-500">
                <input type="hidden" name="tipo_conduta" id="tipo_conduta_hidden">
                <input type="hidden" id="cd_prescricao_hidden">
                <input type="hidden" id="id_receituario" value="0">
                <input type="hidden" id="id_prescricao_ambulatorial" value="0">
                <input type="hidden" id="id_prescricao_hospitalar" value="0">
                <div id="div-mostra-botoes-prescricao-ambulatorial">
                    <div class="panel-body">
                        <div class="btn-group" role="group">
                            <input class="btn btn-info btn-add-prescricao-receita" data-titulo="Dieta" data-configuracao="dieta" data-botoes="prescricao_ambulatorial" value="Dieta" type="button">
                            <input class="btn btn-info btn-add-prescricao-receita" data-titulo="Controle de sinais vitais" data-configuracao="sinais-vitais" data-botoes="prescricao_ambulatorial" value="C.S.V." type="button">
                            <input class="btn btn-info btn-add-prescricao-receita" data-titulo="Outros cuidados" data-configuracao="outros-cuidados" data-botoes="prescricao_ambulatorial" value="Outros cuidados" type="button">
                            <input class="btn btn-info btn-add-prescricao-receita" data-titulo="Oxigenoterapia" data-configuracao="oxigenoterapia" data-botoes="prescricao_ambulatorial" value="Oxigenoterapia" type="button">
                            <input class="btn btn-info btn-add-prescricao-receita" data-titulo="Medicação" data-configuracao="medicacao" data-botoes="prescricao_ambulatorial" value="Medicaçao" type="button">
                        </div>
                        <div class="btn-group" id="ver_todas_prescricoes" role="group">
                            <button class="btn btn-cinza anterior_proxima_prescricao" value="anterior" title="Ver prescrição anterior"><span class="fas fa-caret-left"></span></button>
                            <button class="btn btn-cinza anterior_proxima_prescricao" value="proxima" title="Ver próxima prescrição"><span class="fas fa-caret-right"></span></button>
                        </div>
                        <div class="btn-group" role="group">
                            <button id="add-atendimento-prescricao" class="btn btn-success">Nova prescrição</button>
                        </div>
                        <div class="btn-group" role="group">
                            <button id="concluir-atendimento-prescricao" class="btn btn-warning">Concluir prescrição</button>
                        </div>
                        <a style="display: none" id="rota_prescricao_ambulatorial" href="<?php echo e(route('relatorios/prescricao-ambulatorial').'/'.$acolhimento->cd_prontuario.'/1'); ?>" target="_blank" class="btn btn-primary pull-right"><span class="fa fa-print"></span></a>
                        <a style="display: none" id="rota_prescricao_hospitalar" href="<?php echo e(route('relatorios/prescricao-hospitalar').'/'.$acolhimento->cd_prontuario); ?>" target="_blank" class="btn btn-primary pull-right"><span class="fa fa-print"></span></a>

                    </div>
                </div>
                <div id="div-mostra-botoes-prescricao-receita" class="col-md-12">
                    <div class="panel-body">
                        <div class="btn-group" role="group">
                            <input class="btn btn-info btn-add-prescricao-receita" data-titulo="Medicação" data-configuracao="medicacao" data-botoes="receituario_prescricao" value="Medicaçao" type="button">
                            <input class="btn btn-info btn-add-prescricao-receita" data-titulo="Laboratoriais eletivos" data-configuracao="laboratoriais" data-botoes="receituario_prescricao" value="Laboratoriais" type="button">
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="panel-body" id="painel-geral">
                    <div id="div-outros-cuidados" style="display: none">
                        <div class="panel-body panel-acolhimento">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="posicao">Selecione uma posição</label>
                                        <?php echo e(Form::select('posicao',arrayPadrao('posicoes_enfermagem'),(isset($_POST['posicao']) ? $_POST['posicao'] : ""),["id" => "posicao", 'class'=> ($errors->has("posicao") ? "form-control is-invalid" : "form-control")])); ?>

                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <div class="form-group">
                                        <label for="descricao_posicao">Observação/ Recomendação</label>
                                        <?php echo e(Form::text('descricao_posicao', (isset($_POST['descricao_posicao']) ? $_POST['descricao_posicao'] : "") ,["maxlength" => "50", "id" => "descricao_posicao", 'class'=> ($errors->has("descricao_posicao") ? "form-control is-invalid" : "form-control") ])); ?>

                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <button type="button" id="add-item-outros-cuidados" title="Adicionar item" class="btn btn-primary margin-top-10 pull-right add-prescricao" value="outros-cuidados"><span class="fas fa-plus"></span>&nbsp;outros cuidados</button>
                                </div>
                            </div>
                        </div>
                        <div class="div-mostra-outros-cuidados col-md-12 limpar"></div>
                    </div>
                    <div id="div-laboratoriais" style="display: none">
                        <div class="panel-heading"><b>Exames laboratoriais</b>
                            <a href="<?php echo e(route('relatorios/exames-laboratoriais').'/'.$acolhimento->cd_prontuario); ?>" target="_blank" title="Clique aqui para imprimir o receituário dos exames laboratoriais" class="btn btn-primary btn-xs"><span class="fa fa-print"></span></a>
                        </div>
                        <div class="panel-body panel-acolhimento">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="posicao">Selecione um exame</label>
                                        <?php echo e(Form::select('cd_exame_laboratorial',arrayPadrao('exames_laboratoriais'),(isset($_POST['cd_exame_laboratorial']) ? $_POST['cd_exame_laboratorial'] : ""),["id" => "cd_exame_laboratorial", 'class'=> "form-control"])); ?>

                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <div class="form-group">
                                        <label for="observacao_exame_laboratorial">Observação/ Recomendação</label>
                                        <?php echo e(Form::text('observacao_exame_laboratorial', (isset($_POST['observacao_exame_laboratorial']) ? $_POST['observacao_exame_laboratorial'] : "") ,["maxlength" => "200", "id" => "observacao_exame_laboratorial", 'class'=> "form-control" ])); ?>

                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <button type="button" id="add-exame-laboratorial" title="Adicionar item" class="btn btn-primary margin-top-10 pull-right" data-botoes="receituario" value="exame-laboratorial"><span class="fas fa-plus"></span>&nbsp;exame</button>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover table-striped table_exames_laboratoriais font-size-9pt" >

                            </table>
                        </div>
                    </div>
                    <div id="div-oxigenoterapia" style="display: none">
                        <div class="panel-body panel-acolhimento">
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="qtde_oxigenio">Quantidade(L/min)</label>
                                        <?php echo e(Form::text('qtde_oxigenio', (isset($_POST['qtde_oxigenio']) ? $_POST['qtde_oxigenio'] : "") ,["maxlength" => "2", "max"=>15, "id" => "qtde_oxigenio", 'class'=> ($errors->has("qtde_oxigenio") ? "form-control is-invalid" : "form-control intervalo") ])); ?>

                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="administracao_oxigenio">Administrar oxigênio</label>
                                        <?php echo e(Form::select('administracao_oxigenio',arrayPadrao('administracao_oxigenio'),(isset($_POST['administracao_oxigenio']) ? $_POST['administracao_oxigenio'] : ""),["id" => "administracao_oxigenio", 'class'=> ($errors->has("administracao_oxigenio") ? "form-control is-invalid" : "form-control")])); ?>

                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="periodo_oxigenoterapia">Frequência</label>
                                        <div class="input-group">
                                            <div class="input-group-addon">A cada</div>
                                            <input type="text" id="intervalo_oxigenoterapia" class="form-control mask-numeros-2" aria-label="...">
                                            <div class="input-group-btn">
                                                <button id="btn-intervalo-oxigenoterapia" type="button" data-valor="2" data-texto="hr(s)" class="btn dropdown-toggle btn-branco" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">hr(s)<span class="caret"></span></button>
                                                <ul class="dropdown-menu dropdown-menu-right">
                                                    <?php $__currentLoopData = ['2' => 'hr(s)']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$periodo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <li class="itens" data-nome="intervalo_oxigenoterapia" data-valor="<?php echo e($key); ?>" data-texto="<?php echo e($periodo); ?>"><a href="#"><?php echo e($periodo); ?></a></li>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="prazo_oxigenoterapia">Período</label>
                                        <div class="input-group">
                                            <div class="input-group-addon">Durante</div>
                                            <input type="text" id="prazo_oxigenoterapia" class="form-control mask-numeros-2" value="12" aria-label="...">
                                            <div class="input-group-btn">
                                                <button id="btn-prazo-oxigenoterapia" type="button" data-valor="2" data-texto="hr(s)" class="btn dropdown-toggle btn-branco" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">hr(s)<span class="caret"></span></button>
                                                <ul class="dropdown-menu dropdown-menu-right">
                                                    <?php $__currentLoopData = ['2' => 'hr(s)']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$periodo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <li class="itens" data-nome="prazo_oxigenoterapia" data-valor="<?php echo e($key); ?>" data-texto="<?php echo e($periodo); ?>"><a href="#"><?php echo e($periodo); ?></a></li>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="aprazamento_oxigenoterapia">Aprazamento<button class="btn btn-xs" title="Recalcular aprazamento" id="recalcula_aprazamento_oxigenoterapia"><span class="fas fa-redo"></span> </button></label>
                                        <div class="input-group">
                                            <div class="input-group-addon">Início:</div>
                                            <?php echo e(Form::text('aprazamento_oxigenoterapia', (isset($_POST['aprazamento_oxigenoterapia']) ? $_POST['aprazamento_oxigenoterapia'] : "") ,["name" => "aprazamento_oxigenoterapia[]", "maxlength" => "5", "id" => "aprazamento_oxigenoterapia", 'class'=> "form-control font-8 mask-hora" ])); ?>

                                        </div>
                                    </div>
                                </div>
                                <div id="div-aprazamento-oxigenoterapia"></div>
                            </div>
                            <div class="row">
                                <div class="col-md-10">
                                    <div class="form-group">
                                        <label for="descricao_posicao">Observação/ Recomendação</label>
                                        <?php echo e(Form::text('descricao_oxigenio', (isset($_POST['descricao_oxigenio']) ? $_POST['descricao_oxigenio'] : "") ,["maxlength" => "50", "id" => "descricao_oxigenio", 'class'=> ($errors->has("descricao_posicao") ? "form-control is-invalid" : "form-control") ])); ?>

                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <button type="button" id="add-item-oxigenoterapia" title="Adicionar item" class="btn btn-primary margin-top-10 pull-right add-prescricao" value="outros-cuidados"><span class="fas fa-plus"></span>&nbsp;oxigenoterapia</button>
                                </div>
                            </div>
                        </div>
                        <div class="div-mostra-oxigenoterapia col-md-12 limpar"></div>
                    </div>
                    <div id="div-dieta" style="display: none">
                        <div class="panel-body panel-acolhimento">
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="dieta">Selecione uma dieta</label>
                                    <?php echo e(Form::select('dieta',arrayPadrao('dieta'),(isset($_POST['dieta']) ? $_POST['dieta'] : ""),["id" => "dieta", 'class'=> ($errors->has("dieta") ? "form-control is-invalid" : "form-control")])); ?>

                                </div>
                                <div class="col-md-3">
                                    <label for="dieta">Via</label>
                                    <?php echo e(Form::select('via_dieta',arrayPadrao('via_dieta'),(isset($_POST['via_dieta']) ? $_POST['via_dieta'] : ""),["id" => "via_dieta", 'class'=> ($errors->has("via_dieta") ? "form-control is-invalid" : "form-control")])); ?>

                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="periodo_dieta">Frequência</label>
                                        <div class="input-group">
                                            <div class="input-group-addon">A cada</div>
                                            <input type="text" id="intervalo_dieta" class="form-control mask-numeros-2" aria-label="...">
                                            <div class="input-group-btn">
                                                <button id="btn-intervalo-dieta" type="button" data-valor="2" data-texto="hr(s)" class="btn dropdown-toggle btn-branco" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">hr(s)<span class="caret"></span></button>
                                                <ul class="dropdown-menu dropdown-menu-right">
                                                    <?php $__currentLoopData = ['2' => 'hr(s)']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$periodo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <li class="itens" data-nome="intervalo_dieta" data-valor="<?php echo e($key); ?>" data-texto="<?php echo e($periodo); ?>"><a href="#"><?php echo e($periodo); ?></a></li>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="prazo_dieta">Período</label>
                                        <div class="input-group">
                                            <div class="input-group-addon">Durante</div>
                                            <input type="text" id="prazo_dieta" class="form-control mask-numeros-2" value="12" aria-label="...">
                                            <div class="input-group-btn">
                                                <button id="btn-prazo-dieta" type="button" data-valor="2" data-texto="hr(s)" class="btn dropdown-toggle btn-branco" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">hr(s)<span class="caret"></span></button>
                                                <ul class="dropdown-menu dropdown-menu-right">
                                                    <?php $__currentLoopData = ['2' => 'hr(s)']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$periodo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <li class="itens" data-nome="prazo_dieta" data-valor="<?php echo e($key); ?>" data-texto="<?php echo e($periodo); ?>"><a href="#"><?php echo e($periodo); ?></a></li>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="aprazamento_dieta">Aprazamento<button class="btn btn-xs" title="Recalcular aprazamento" id="recalcula_aprazamento_dieta"><span class="fas fa-redo"></span> </button></label>
                                        <div class="input-group">
                                            <div class="input-group-addon">Início:</div>
                                            <?php echo e(Form::text('aprazamento_dieta', (isset($_POST['aprazamento_dieta']) ? $_POST['aprazamento_dieta'] : "") ,["name" => "aprazamento_dieta[]", "maxlength" => "5", "id" => "aprazamento_dieta", 'class'=> "form-control font-8 mask-hora" ])); ?>

                                        </div>
                                    </div>
                                </div>
                                <div id="div-aprazamento-dieta"></div>
                            </div>
                            <div class="row">
                                <div class="col-md-10">
                                    <div class="form-group">
                                        <label for="descricao_dieta">Observação/ Recomendação</label>
                                        <?php echo e(Form::text('descricao_dieta', (isset($_POST['descricao_dieta']) ? $_POST['descricao_dieta'] : "") ,["maxlength" => "200", "id" => "descricao_dieta", 'class'=> ($errors->has("descricao_dieta") ? "form-control is-invalid" : "form-control") ])); ?>

                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <button type="button" id="add-item-dieta" title="Adicionar item" class="btn btn-primary margin-top-10 add-prescricao" value="dieta"><span class="fas fa-plus"></span>&nbsp;dieta</button>
                                </div>
                            </div>
                        </div>
                        <div class="div-mostra-dieta col-md-12 limpar"></div>
                    </div>
                    <div id="div-sinais-vitais" style="display: none">
                        <div class="panel-body panel-acolhimento">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="periodo_csv">Frequência</label>
                                        <div class="input-group">
                                            <div class="input-group-addon">A cada</div>
                                            <input type="text" id="intervalo_csv" class="form-control mask-numeros-2" aria-label="...">
                                            <div class="input-group-btn">
                                                <button id="btn-intervalo-csv" type="button" data-valor="2" data-texto="hr(s)" class="btn dropdown-toggle btn-branco" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">hr(s)<span class="caret"></span></button>
                                                <ul class="dropdown-menu dropdown-menu-right">
                                                    <?php $__currentLoopData = ['2' => 'hr(s)']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$periodo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <li class="itens" data-nome="intervalo_csv" data-valor="<?php echo e($key); ?>" data-texto="<?php echo e($periodo); ?>"><a href="#"><?php echo e($periodo); ?></a></li>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="prazo_csv">Período</label>
                                        <div class="input-group">
                                            <div class="input-group-addon">Durante</div>
                                            <input type="text" id="prazo_csv" class="form-control mask-numeros-2" value="12" aria-label="...">
                                            <div class="input-group-btn">
                                                <button id="btn-prazo-csv" type="button" data-valor="2" data-texto="hr(s)" class="btn dropdown-toggle btn-branco" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">hr(s)<span class="caret"></span></button>
                                                <ul class="dropdown-menu dropdown-menu-right">
                                                    <?php $__currentLoopData = ['2' => 'hr(s)']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$periodo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <li class="itens" data-nome="prazo_csv" data-valor="<?php echo e($key); ?>" data-texto="<?php echo e($periodo); ?>"><a href="#"><?php echo e($periodo); ?></a></li>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label for="descricao_csv">Observação/ Recomendação</label>
                                        <?php echo e(Form::text('descricao_csv', (isset($_POST['descricao_csv']) ? $_POST['descricao_csv'] : "") ,["maxlength" => "50", "id" => "descricao_csv", 'class'=> ($errors->has("descricao_csv") ? "form-control is-invalid" : "form-control") ])); ?>

                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="aprazamento_csv">Aprazamento<button class="btn btn-xs" title="Recalcular aprazamento" id="recalcula_aprazamento_csv"><span class="fas fa-redo"></span> </button></label>
                                        <div class="input-group">
                                            <div class="input-group-addon">Início:</div>
                                            <?php echo e(Form::text('aprazamento_csv', (isset($_POST['aprazamento_csv']) ? $_POST['aprazamento_csv'] : "") ,["name" => "aprazamento_csv[]", "maxlength" => "5", "id" => "aprazamento_csv", 'class'=> "form-control font-8 mask-hora" ])); ?>

                                        </div>
                                    </div>
                                </div>
                                <div id="div-aprazamento-csv"></div>
                                <div class="col-md-2 pull-right margin-top-15">
                                    <button type="button" id="add-item-csv" title="Adicionar item" class="btn btn-primary margin-top-10 pull-right add-prescricao" value="sinais-vitais"><span class="fas fa-plus"></span>&nbsp;csv</button>
                                </div>
                            </div>
                        </div>
                        <div class="div-mostra-sinais-vitais col-md-12 limpar"></div>
                    </div>
                    <div id="div-medicacao" style="display: none;">
                        <div id="div-impressao-medicacao" class="panel-heading" style="display: none"><b>Medicação</b>
                            <a href="<?php echo e(route('relatorios/receita').'/'.$acolhimento->cd_prontuario); ?>" target="_blank" title="Clique aqui para imprimir a receita de medicação" class="btn btn-primary btn-xs"><span class="fa fa-print"></span></a>
                            <a href="<?php echo e(route('relatorios/receita-especial').'/'.$acolhimento->cd_prontuario); ?>" target="_blank" title="Clique aqui para imprimir a receita de medicação de controle especial" class="btn btn-primary btn-xs">Especial &nbsp;<span class="fa fa-print"></span></a>
                        </div>
                        <div class="panel-body panel-acolhimento">
                            <div class="row">
                                <div class="col-md-9">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <label for="cd_medicamento">Medicamento</label>
                                            <input type="text" data-id="" id="cd_medicamento" disabled class="form-control">
                                            <input type="hidden" id="cd_medicamento_hidden">
                                            <span class="input-group-btn">
                                               <button type="button" class="btn btn-default margin-top-10" id="btn-modal-medicamento"><span class="fa fa-search"></span></button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2 margin-top-15">
                                    <input type="checkbox" class="custom-control-input" value="1" id="se-necessario">
                                    <label class="custom-control-label" for="se-necessario">Se necessário</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <label for="dose">Dose</label>
                                            <input type="text" id="dose" class="form-control mask-numeros-3" aria-label="...">
                                            <div class="input-group-btn">
                                                <button id="btn-dose" type="button" data-valor="1" data-texto="<?php echo e(isset($unidades_medida) ? current($unidades_medida) : ''); ?>" class="btn dropdown-toggle margin-top-10 btn-branco" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo e(isset($unidades_medida) ? current($unidades_medida) : ''); ?><span class="caret"></span></button>
                                                <ul class="dropdown-menu dropdown-menu-right">
                                                    <?php $__currentLoopData = $unidades_medida; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$tipo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <li class="itens" data-nome="dose" data-valor="<?php echo e($key); ?>" data-texto="<?php echo e($tipo); ?>"><a href="#"><?php echo e($tipo); ?></a></li>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <label for="dose">Via</label>
                                    <div class="form-group">
                                        <button id="btn-via" type="button" data-valor="1" data-texto="<?php echo e(arrayPadrao('via')[1]); ?>" class="btn dropdown-toggle btn-branco" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo e(arrayPadrao('via')[1]); ?><span class="caret"></span></button>
                                        <ul id="dropdown-vias-aplicacao" class="dropdown-menu dropdown-menu-right">
                                            <?php $__currentLoopData = arrayPadrao('via'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$via): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <li class="itens" data-nome="via" data-valor="<?php echo e($key); ?>" data-texto="<?php echo e($via); ?>"><a href="#"><?php echo e($via); ?></a></li>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="intervalo_medicacao">Frequência</label>
                                        <div class="input-group">
                                            <div class="input-group-addon">A cada</div>
                                            <input type="text" id="intervalo_medicacao" class="form-control mask-numeros-2" aria-label="...">
                                            <div class="input-group-btn">
                                                <button id="btn-intervalo-medicacao" type="button" data-valor="2" data-texto="hr(s)" class="btn dropdown-toggle btn-branco" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">hr(s)<span class="caret"></span></button>
                                                <ul class="dropdown-menu dropdown-menu-right">
                                                    <li class="itens" data-nome="intervalo-medicacao" data-valor="2" data-texto="hr(s)"><a href="#">hr(s)</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="prazo_medicacao">Período</label>
                                        <div class="input-group">
                                            <div class="input-group-addon">Durante</div>
                                            <input type="text" id="prazo_medicacao" class="form-control mask-numeros-2" value="12" aria-label="...">
                                            <div class="input-group-btn">
                                                <button id="btn-prazo-medicacao" type="button" data-valor="2" data-texto="hr(s)" class="btn dropdown-toggle btn-branco" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">hr(s)<span class="caret"></span></button>
                                                <ul class="dropdown-menu dropdown-menu-right">
                                                    <li class="itens" data-nome="prazo-medicacao" data-valor="2" data-texto="hr(s)"><a href="#">hr(s)</a></li>
                                                    <li class="itens" id="intervalo-medicacao-dia" style="display: none" data-nome="prazo-medicacao" data-valor="3" data-texto="dia(s)"><a href="#">dia(s)</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="aprazamento_medicacao">Aprazamento<button class="btn btn-xs" title="Recalcular aprazamento" id="recalcula_aprazamento_medicacao"><span class="fas fa-redo"></span> </button></label>
                                        <div class="input-group">
                                            <div class="input-group-addon">Início:</div>
                                            <?php echo e(Form::text('aprazamento_medicacao', (isset($_POST['aprazamento_medicacao']) ? $_POST['aprazamento_medicacao'] : "") ,["name" => "aprazamento_medicacao[]", "maxlength" => "5", "id" => "aprazamento_medicacao", 'class'=> "form-control font-8 mask-hora" ])); ?>

                                        </div>
                                    </div>
                                </div>
                                <div id="div-aprazamento-medicacao"></div>
                            </div>
                            <div class="row">
                                <div class="col-md-10">
                                    <div class="form-group">
                                        <label for="observacao_medicamento">Observação/ Recomendação</label>
                                        <?php echo e(Form::text('observacao_medicamento', (isset($_POST['observacao_medicamento']) ? $_POST['observacao_medicamento'] : "") ,["maxlength" => "200", "id" => "observacao_medicamento", 'class'=> ($errors->has("observacao_medicamento") ? "form-control is-invalid" : "form-control") ])); ?>

                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <button type="button" title="Adicionar item" class="btn btn-primary pull-right add-medicacao margin-top-10"><span class="fas fa-plus"></span>&nbsp;medicação</button>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover table-striped table_medicacao font-size-9pt" >

                            </table>
                        </div>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo $__env->make('atendimentos/modal-medicamentos', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<script src="<?php echo e(js_versionado('modal_receituario.js')); ?>" defer></script>
