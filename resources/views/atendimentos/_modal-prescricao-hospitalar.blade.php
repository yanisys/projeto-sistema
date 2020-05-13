<div id="modal-prescricao-hospitalar" class="modal fade modal-prescricao"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog dialog-prescricao" role="document">
        <div class="modal-content content-prescricao">
            <div class="modal-header">
                <button class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Fechar</span></button>
                <h3 class="modal-title">Prescrição hospitalar</h3>
            </div>
            <div class="modal-body modal-body-scroll-500">
                <input type="hidden" name="tipo_conduta" id="tipo_conduta_hidden">
                <input type="hidden" id="id_prescricao_hospitalar" value="0">
                <div class="panel-body">
                    <div class="btn-group" role="group">
                        <input class="btn btn-info btn-exibe-prescricao-hospitalar" data-titulo="Dieta" data-configuracao="dieta" value="Dieta" type="button">
                        <input class="btn btn-info btn-exibe-prescricao-hospitalar" data-titulo="Controle de sinais vitais" data-configuracao="sinais-vitais" value="C.S.V." type="button">
                        <input class="btn btn-info btn-exibe-prescricao-hospitalar" data-titulo="Outros cuidados" data-configuracao="outros-cuidados" value="Outros cuidados" type="button">
                        <input class="btn btn-info btn-exibe-prescricao-hospitalar" data-titulo="Oxigenoterapia" data-configuracao="oxigenoterapia" value="Oxigenoterapia" type="button">
                        <input class="btn btn-info btn-exibe-prescricao-hospitalar" data-titulo="Medicação" data-configuracao="medicacao" value="Medicaçao" type="button">
                    </div>
                    <a style="display: none" id="rota_prescricao_hospitalar" href="{{ route('relatorios/prescricao-hospitalar').'/'.$acolhimento->cd_prontuario }}" target="_blank" class="btn btn-primary pull-right"><span class="fa fa-print"></span></a>
                </div>
                <div class="col-md-12">
                    <div class="panel-body painel-geral" id="painel-geral-hospitalar">
                        <div id="div-hospitalar-outros-cuidados" style="display: none">
                            <div class="panel-heading"><b>Outros cuidados</b></div>
                            <div class="panel-body panel-acolhimento">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="posicao">Selecione uma posição</label>
                                            {{ Form::select('posicao',arrayPadrao('posicoes_enfermagem'),(isset($_POST['posicao']) ? $_POST['posicao'] : ""),["id" => "posicao", 'class'=> ($errors->has("posicao") ? "form-control is-invalid" : "form-control")]) }}
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="descricao_posicao">Observação/ Recomendação</label>
                                            {{ Form::text('descricao_posicao', (isset($_POST['descricao_posicao']) ? $_POST['descricao_posicao'] : "") ,["maxlength" => "50", "id" => "descricao_posicao", 'class'=> ($errors->has("descricao_posicao") ? "form-control is-invalid" : "form-control") ]) }}
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <button type="button" id="add-item-outros-cuidados" title="Adicionar item" class="btn btn-primary margin-top-10 pull-right add-prescricao" value="outros-cuidados"><span class="fas fa-plus"></span></button>
                                    </div>
                                </div>
                            </div>
                            <div class="div-mostra-outros-cuidados col-md-12 limpar"></div>
                        </div>
                        <div id="div-hospitalar-oxigenoterapia" style="display: none">
                            <div class="panel-heading"><b>Oxigenoterapia</b></div>
                            <div class="panel-body panel-acolhimento">
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="qtde_oxigenio">Quantidade(L/min)</label>
                                            {{ Form::text('qtde_oxigenio', (isset($_POST['qtde_oxigenio']) ? $_POST['qtde_oxigenio'] : "") ,["maxlength" => "2", "max"=>15, "id" => "qtde_oxigenio", 'class'=> ($errors->has("qtde_oxigenio") ? "form-control is-invalid" : "form-control intervalo") ]) }}
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="administracao_oxigenio">Administrar oxigênio</label>
                                            {{ Form::select('administracao_oxigenio',arrayPadrao('administracao_oxigenio'),(isset($_POST['administracao_oxigenio']) ? $_POST['administracao_oxigenio'] : ""),["id" => "administracao_oxigenio", 'class'=> ($errors->has("administracao_oxigenio") ? "form-control is-invalid" : "form-control")]) }}
                                        </div>
                                    </div>
                                    <div class="col-md-7">
                                        <div class="form-group">
                                            <label for="descricao_posicao">Observação/ Recomendação</label>
                                            {{ Form::text('descricao_oxigenio', (isset($_POST['descricao_oxigenio']) ? $_POST['descricao_oxigenio'] : "") ,["maxlength" => "50", "id" => "descricao_oxigenio", 'class'=> ($errors->has("descricao_posicao") ? "form-control is-invalid" : "form-control") ]) }}
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <button type="button" id="add-item-oxigenoterapia" title="Adicionar item" class="btn btn-primary margin-top-10 pull-right add-prescricao" value="outros-cuidados"><span class="fas fa-plus"></span></button>
                                    </div>
                                </div>
                            </div>
                            <div class="div-mostra-oxigenoterapia col-md-12 limpar"></div>
                        </div>
                        <div id="div-hospitalar-dieta" style="display: none">
                            <div class="panel-heading"><b>Dieta</b></div>
                            <div class="panel-body panel-acolhimento">
                                <div class="panel-body">
                                    <div class="col-md-8">
                                        <label for="dieta">Selecione uma dieta</label>
                                        {{ Form::select('dieta',arrayPadrao('dieta'),(isset($_POST['dieta']) ? $_POST['dieta'] : ""),["id" => "dieta", 'class'=> ($errors->has("dieta") ? "form-control is-invalid" : "form-control")]) }}
                                    </div>
                                    <div class="col-md-4">
                                        <label for="dieta">Via</label>
                                        {{ Form::select('via_dieta',arrayPadrao('via_dieta'),(isset($_POST['via_dieta']) ? $_POST['via_dieta'] : ""),["id" => "via_dieta", 'class'=> ($errors->has("via_dieta") ? "form-control is-invalid" : "form-control")]) }}
                                    </div>
                                    <div class="col-md-11">
                                        <div class="form-group">
                                            <label for="descricao_dieta">Observação/ Recomendação</label>
                                            {{ Form::text('descricao_dieta', (isset($_POST['descricao_dieta']) ? $_POST['descricao_dieta'] : "") ,["maxlength" => "50", "id" => "descricao_dieta", 'class'=> ($errors->has("descricao_dieta") ? "form-control is-invalid" : "form-control") ]) }}
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <button type="button" id="add-item-dieta" title="Adicionar item" class="btn btn-primary margin-top-21 pull-right add-prescricao" value="dieta"><span class="fas fa-plus"></span></button>
                                    </div>
                                </div>
                            </div>
                            <div class="div-mostra-dieta col-md-12 limpar"></div>
                        </div>
                        <div id="div-hospitalar-sinais-vitais" style="display: none">
                            <div class="panel-heading"><b>Controle dos sinais vitais (CSV)</b></div>
                            <div class="panel-body panel-acolhimento">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <label for="periodo_csv">Período</label>
                                                <input type="text" id="periodo_csv" class="form-control  mask-numeros-3" aria-label="...">
                                                <div class="input-group-btn">
                                                    <button id="btn-periodo_csv" type="button" data-valor="2" data-texto="{{arrayPadrao('periodo')[2]}}" class="btn dropdown-toggle margin-top-10 btn-branco" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{arrayPadrao('periodo')[2]}}<span class="caret"></span></button>
                                                    <ul class="dropdown-menu dropdown-menu-right">
                                                        @foreach(arrayPadrao('periodo') as $key=>$periodo)
                                                            <li class="itens" data-nome="periodo_csv" data-valor="{{$key}}" data-texto="{{$periodo}}"><a href="#">{{$periodo}}</a></li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="descricao_csv">Observação/ Recomendação</label>
                                            {{ Form::text('descricao_csv', (isset($_POST['descricao_csv']) ? $_POST['descricao_csv'] : "") ,["maxlength" => "50", "id" => "descricao_csv", 'class'=> ($errors->has("descricao_csv") ? "form-control is-invalid" : "form-control") ]) }}
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <button type="button" id="add-item-csv" title="Adicionar item" class="btn btn-primary margin-top-10 pull-right add-prescricao" value="sinais-vitais"><span class="fas fa-plus"></span></button>
                                    </div>
                                </div>
                            </div>
                            <div class="div-mostra-sinais-vitais col-md-12 limpar"></div>
                        </div>
                        <div id="div-hospitalar-medicacao" style="display: none">
                            <div class="panel-heading"><b>Medicação</b></div>
                            <div class="panel-body panel-acolhimento">
                                <div class="row">
                                    <div class="col-md-11">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <label for="cd_medicamento">Medicamento</label>
                                                <input type="text" data-id="" id="cd_medicamento" disabled class="form-control">
                                                <input type="hidden" id="cd_medicamento_hidden">
                                                <span class="input-group-btn">
                                               <button type="button" class="btn btn-default margin-top-10 btn-modal-medicamento"><span class="fa fa-search"></span></button>
                                            </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <label for="dose">Dose</label>
                                                <input type="text" id="dose" class="form-control mask-numeros-3" aria-label="...">
                                                <div class="input-group-btn">
                                                    <button id="btn-dose" type="button" data-valor="1" data-texto="{{arrayPadrao('aplicacao')[1]}}" class="btn dropdown-toggle margin-top-10 btn-branco" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{arrayPadrao('aplicacao')[1]}}<span class="caret"></span></button>
                                                    <ul class="dropdown-menu dropdown-menu-right" id="lista-tipo-dose">
                                                        @foreach($unidades_medida as $key=>$tipo)
                                                            <li class="itens" data-nome="dose" data-valor="{{$key}}" data-texto="{{$tipo}}"><a href="#">{{$tipo}}</a></li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="dose">Via</label>
                                        <div class="form-group">
                                            <button id="btn-via" type="button" data-valor="1" data-texto="{{arrayPadrao('via')[1]}}" class="btn dropdown-toggle btn-branco" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{arrayPadrao('via')[1]}}<span class="caret"></span></button>
                                            <ul class="dropdown-menu dropdown-menu-right" id="lista-via">
                                                @foreach(arrayPadrao('via') as $key=>$via)
                                                    <li class="itens" data-nome="via" data-valor="{{$key}}" data-texto="{{$via}}"><a href="#">{{$via}}</a></li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <label for="dose">Intervalo</label>
                                                <input type="text" id="intervalo" class="form-control mask-numeros-3" aria-label="...">
                                                <div class="input-group-btn">
                                                    <button id="btn-intervalo" type="button" data-valor="2" data-texto="{{arrayPadrao('periodo')[2]}}" class="btn dropdown-toggle margin-top-10 btn-branco" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{arrayPadrao('periodo')[2]}}<span class="caret"></span></button>
                                                    <ul class="dropdown-menu dropdown-menu-right">
                                                        @foreach(['1' => 'Minutos', '2' => 'Horas'] as $key=>$periodo)
                                                            <li class="itens" data-nome="intervalo" data-valor="{{$key}}" data-texto="{{$periodo}}"><a href="#">{{$periodo}}</a></li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-11">
                                        <div class="form-group">
                                            <label for="observacao_medicamento">Observação/ Recomendação</label>
                                            {{ Form::text('observacao_medicamento', (isset($_POST['observacao_medicamento']) ? $_POST['observacao_medicamento'] : "") ,["maxlength" => "200", "id" => "observacao_medicamento", 'class'=> ($errors->has("observacao_medicamento") ? "form-control is-invalid" : "form-control") ]) }}
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <button type="button" title="Adicionar item" class="btn btn-primary pull-right add-medicacao margin-top-10"><span class="fas fa-plus"></span></button>
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
<script src="{{ js_versionado('modal_prescricao.js') }}" defer></script>
