
@extends('layouts.default')

@section('conteudo')
    @if ($errors->any())
        <div class="alert alert-danger collapse in" id="collapseExample">
            <h4 class="alert-heading">Os seguintes erros foram encontrados:</h4>
            <ul>
                @foreach ($errors->all() as $error)
                    <li> {{ $error }} </li>
                @endforeach
            </ul>
            <hr>
            <p class="mb-0">Por favor, verifique e tente novamente.</p>
            <p><a class="btn btn-primary" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="true" aria-controls="collapseExample">Fechar</a></p>
        </div>
    @endif

    {{ Form::open(['id' => 'form-cadastra-acolhimento', 'class' => 'form-no-submit']) }}
    <div class="panel panel-primary" id="panel-acolhimento">
        <div class="panel-heading">
            @if(get_config(2,session()->get('estabelecimento')) == 'S')
                <button id='chamar-painel' type="button" data-toggle='modal' data-target="#escolhe-sala" class="btn btn-primary pull-right">Chamar painel</button>
            @endif
            <button class="btn btn-primary pull pull-right ver_pessoa" type="button" data-toggle="modal" data-target="#modal-pesquisa">Ver cadastro</button>
            <button type="button" class="btn btn-primary pull pull-right" id="ver-historico" value='{{(isset($lista['cd_pessoa']) ? $lista['cd_pessoa'] : "")}}' data-toggle="modal" data-target="#modal-historico">Histórico</button>
            <a href="{{ route('relatorios/prontuario').'/'.$lista['cd_prontuario'] }}" target="_blank" class="btn btn-primary pull-right"><span class="fa fa-print"></span></a>
            <h4>Consulta de Enfermagem/ Acolhimento - Dados do Paciente
            </h4>
        </div>
        <div class="panel-body">
            {{ Form::hidden('cd_pessoa',(isset($lista['cd_pessoa']) ? $lista['cd_pessoa'] : ""),["name" => "cd_pessoa", "id" => "cd_pessoa_disabled"  ]) }}
            <div class="row">
                <div class="col-md-10">
                <div class="panel-heading"><b>Avaliação de Enfermagem</b></div>
                    <div class="panel-body panel-acolhimento">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="avaliacao">Motivo da consulta (Queixa principal)</label>
                                    {{ Form::textarea('avaliacao',(isset($lista['avaliacao']) ? $lista['avaliacao'] : ""),["name" => "avaliacao", "maxlength" => "950", "rows"=>"3", "id" => "avaliacao",  'class'=> ($errors->has("avaliacao") ? "form-control is-invalid" : "form-control")]) }}
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
                                            @if(isset($ultimos_atendimentos[0]->cd_prontuario))
                                                <tr><td style="text-align: center;"><b><a href="{{ route('relatorios/prontuario').'/'.$ultimos_atendimentos[0]->cd_prontuario }}" style="color: {{$ultimos_atendimentos[0]->cor}}" title="Clique para ver o prontuário" target="_blank">{{formata_data($ultimos_atendimentos[0]->created_at)}}</a></b></td></tr>
                                                @if(isset($ultimos_atendimentos[1]->cd_prontuario))
                                                    <tr><td style="text-align: center;"><b><a href="{{ route('relatorios/prontuario').'/'.$ultimos_atendimentos[1]->cd_prontuario }}" style="color: {{$ultimos_atendimentos[1]->cor}}" title="Clique para ver o prontuário" target="_blank">{{formata_data($ultimos_atendimentos[1]->created_at)}}</a></b></td></tr>
                                                @endif
                                                @if(isset($ultimos_atendimentos[2]->cd_prontuario))
                                                    <tr><td style="text-align: center;"><b><a href="{{ route('relatorios/prontuario').'/'.$ultimos_atendimentos[2]->cd_prontuario }}" style="color: {{$ultimos_atendimentos[2]->cor}}" title="Clique para ver o prontuário" target="_blank">{{formata_data($ultimos_atendimentos[2]->created_at)}}</a></b></td></tr>
                                                @endif
                                            @else
                                                <tr><td>Nenhum resultado para exibir.</td></tr>
                                            @endif
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
                    <input type="hidden" id='sexo' name="id_sexo" value="{{$lista['id_sexo']}}">
                    <input type="hidden" id='dt_nasc' name="dt_nasc" value="{{$lista['dt_nasc']}}">
                    {{ Form::hidden('cd_prontuario',(isset($lista['cd_prontuario']) ? $lista['cd_prontuario'] : ""),["name" => "cd_prontuario", "id" => "cd_prontuario"]) }}
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="cintura">Cintura (cm)</label>
                            {{ Form::text('cintura', (isset($lista['cintura']) ? $lista['cintura'] : "") ,["maxlength" => "3", "id" => "cintura", 'class'=> ($errors->has("cintura") ? "form-control is-invalid" : "form-control mask-inteiro3") ]) }}
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="quadril">Quadril (cm)</label>
                            {{ Form::text('quadril', (isset($lista['quadril']) ? $lista['quadril'] : "") ,["maxlength" => "3", "id" => "quadril", 'class'=> ($errors->has("quadril") ? "form-control is-invalid mask-inteiro3" : "form-control mask-inteiro3") ]) }}
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="indice_cintura_quadril">Índice cintura/ quadril</label>
                            {{ Form::text('t_indice_cintura_quadril', "" ,["maxlength" => "5", "disabled" => "disabled", "class"=> "indice_cintura_quadril form-control" ]) }}
                            {{ Form::hidden('indice_cintura_quadril',"",["name" => "indice_cintura_quadril", "class" => "indice_cintura_quadril"]) }}
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="peso">Peso (Kg)</label>
                            {{ Form::text('peso', (isset($lista['peso']) ? $lista['peso'] : "") ,["maxlength" => "7", "id" => "peso", 'class'=> ($errors->has("peso") ? "form-control is-invalid" : "form-control mask-decimal33") ]) }}
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="altura">Altura (m)</label>
                            {{ Form::text('altura', (isset($lista['altura']) ? $lista['altura'] : "") ,["maxlength" => "4", "id" => "altura", 'class'=> ($errors->has("altura") ? "form-control is-invalid" : "form-control  mask-decimal12") ]) }}
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="massa_corporal">Massa corporal</label>
                            {{ Form::text('t_massa_corporal', (isset($lista['massa_corporal']) ? $lista['massa_corporal'] : '') ,["maxlength" => "5", "disabled" => "disabled", 'class'=> ($errors->has("massa_corporal") ? "massa_corporal form-control is-invalid" : "massa_corporal form-control") ]) }}
                            {{ Form::hidden('massa_corporal',(isset($lista['massa_corporal']) ? $lista['massa_corporal'] : ""),["name" => "massa_corporal", "class" => "massa_corporal"]) }}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="pressao_arterial">P.A (mmHg)</label>
                            {{ Form::text('pressao_arterial',(isset($lista['pressao_arterial']) ? $lista['pressao_arterial'] : ""),["name" => "pressao_arterial", "maxlength" => "5", "id" => "pressao_arterial",  'class'=> ($errors->has("pressao_arterial") ? "form-control is-invalid" : "form-control mask-pressao-arterial")]) }}
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="temperatura">Temperatura (ºC)</label>
                            {{ Form::text('temperatura', (isset($lista['temperatura']) ? $lista['temperatura'] : "") ,["maxlength" => "5", "id" => "temperatura", 'class'=> ($errors->has("temperatura") ? "form-control is-invalid" : "form-control   mask-decimal22") ]) }}
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="freq_cardiaca">Freq. cardíaca/ pulso (bpm)</label>
                            {{ Form::text('freq_cardiaca',(isset($lista['freq_cardiaca']) ? $lista['freq_cardiaca'] : ""),["name" => "freq_cardiaca", "maxlength" => "3", "id" => "freq_cardiaca",  'class'=> ($errors->has("freq_cardiaca") ? "form-control is-invalid mask-inteiro3" : "form-control mask-inteiro3")]) }}
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="freq_respiratoria">Freq. respiratória (mrm)</label>
                            {{ Form::text('freq_respiratoria', (isset($lista['freq_respiratoria']) ? $lista['freq_respiratoria'] : "") ,["maxlength" => "3", "id" => "freq_respiratoria", 'class'=> ($errors->has("freq_respiratoria") ? "form-control is-invalid mask-inteiro3" : "form-control mask-inteiro3") ]) }}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="estado_nutricional">Estado nutricional</label>
                            {{ Form::text('t_estado_nutricional', (isset($lista['estado_nutricional']) ? $lista['estado_nutricional'] : "") ,["maxlength" => "5", "disabled" => "disabled",'class'=> "estado_nutricional form-control" ]) }}
                            {{ Form::hidden('estado_nutricional',(isset($lista['estado_nutricional']) ? $lista['estado_nutricional'] : ""),["name" => "estado_nutricional", "class" => "estado_nutricional"]) }}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="risco_cintura">Risco associado à circunfêrencia da cintura</label>
                            {{ Form::text('t_risco_cintura', (isset($lista['risco_cintura']) ? $lista['risco_cintura'] : "") ,["maxlength" => "5", "disabled" => "disabled", 'class'=> ($errors->has("risco_cintura") ? "form-control is-invalid" : "risco_cintura form-control") ]) }}
                            {{ Form::hidden('risco_cintura',(isset($lista['risco_cintura']) ? $lista['risco_cintura'] : ""),["name" => "risco_cintura", "class" => "risco_cintura"]) }}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="glicemia_capilar">Glicemia Capilar (mg/dL)</label>
                            {{ Form::text('glicemia_capilar',(isset($lista['glicemia_capilar']) ? $lista['glicemia_capilar'] : ""),["name" => "glicemia_capilar", "maxlength" => "3", 'class'=> ($errors->has("glicemia_capilar") ? " mask-numeros-3 form-control is-invalid" : " mask-numeros-3 form-control")]) }}
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="glicemia">&nbsp;</label>
                            {{ Form::text('t_glicemia', (isset($lista['glicemia']) ? $lista['glicemia'] : "") ,["maxlength" => "5", "disabled" => "disabled", "id" => "t_glicemia", 'class'=> ($errors->has("glicemia") ? "form-control is-invalid" : "form-control") ]) }}
                            {{ Form::hidden('glicemia',(isset($lista['glicemia']) ? $lista['glicemia'] : ""),["name" => "glicemia", "id" => "glicemia"]) }}
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="saturacao">Saturação (%O2)</label>
                            {{ Form::text('saturacao', (isset($lista['saturacao']) ? $lista['saturacao'] : "") ,["maxlength" => "3", "id" => "saturacao", 'class'=> ($errors->has("saturacao") ? "form-control is-invalid" : "form-control mask-numeros-3") ]) }}
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
                                        {{ Form::select('abertura_ocular',arrayPadrao('abertura_ocular'),(isset($lista['abertura_ocular']) ? $lista['abertura_ocular'] : ""),["id" => "abertura_ocular",  'class'=> ($errors->has("abertura_ocular") ? "form-control is-invalid" : "form-control")]) }}
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="resposta_verbal">Resposta verbal</label>
                                        {{ Form::select('resposta_verbal', arrayPadrao('resposta_verbal'),(isset($lista['resposta_verbal']) ? $lista['resposta_verbal'] : "") ,["id" => "resposta_verbal", 'class'=> ($errors->has("resposta_verbal") ? "form-control is-invalid" : "form-control") ]) }}
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="resposta_motora">Resposta motora</label>
                                        {{ Form::select('resposta_motora',arrayPadrao('resposta_motora'), (isset($lista['resposta_motora']) ? $lista['resposta_motora'] : "") ,["id" => "resposta_motora", 'class'=> ($errors->has("resposta_motora") ? "form-control is-invalid" : "form-control") ]) }}
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="escore_glasgow">Escore</label>
                                        {{ Form::text('t_escore_glasgow', (isset($lista['escore_glasgow']) ? $lista['escore_glasgow'] : "") ,["maxlength" => "5", "disabled" => "disabled", "id" => "t_escore_glasgow", 'class'=> ($errors->has("escore_glasgow") ? "form-control is-invalid" : "form-control") ]) }}
                                        {{ Form::hidden('escore_glasgow',(isset($lista['escore_glasgow']) ? $lista['escore_glasgow'] : ""),["name" => "escore_glasgow", "id" => "escore_glasgow"]) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="panel-heading"><b>Escala de dor</b></div>
                        <div class="panel-body panel-acolhimento">
                            <div class="range-control" id="{{($lista['idade'] > 12) ? 'escala_dor_adulta' : 'escala_dor_infantil'}}">
                                <!-- <input id="inputRange" type="range" min="0" max="10" value="0" data-thumbwidth="1"> -->
                                {{Form::range('nivel_de_dor',(isset($lista['nivel_de_dor']) ? $lista['nivel_de_dor'] : 0), ["id" => 'inputRange', "min" => 0, "max" => 10, "data-thumbwidth" => 1 ])}}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="panel-heading"><b>Alergias</b><button type="button" id="btn-modal-alergia" data-toggle="modal"  class="btn btn-info btn-xs">Adicionar</button></div>
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
                    <div class="panel-heading"><b>História médica pregressa</b><button type="button" data-toggle="modal" class="btn btn-info btn-xs" id="btn-modal-historia-medica-pregressa">Adicionar</button></div>
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
                    <div class="panel-heading"><b>Cirurgias prévias</b><button type="button" data-toggle="modal" class="btn btn-info btn-xs" data-target="#modal-cirurgias-previas">Adicionar</button></div>
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
                    <div class="panel-heading"><b>Medicamentos em uso</b><button type="button" data-toggle="modal" class="btn btn-info btn-xs" data-target="#modal-medicamentos-em-uso">Adicionar</button></div>
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
                                        {{ Form::textarea('exames_apresentados',(isset($lista['exames_apresentados']) ? $lista['exames_apresentados'] : ""),["name" => "exames_apresentados", "maxlength" => "500", "rows"=>"3", "id" => "exames_apresentados",  'class'=> ($errors->has("exames_apresentados") ? "form-control is-invalid" : "form-control")]) }}
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
                                    <select id="classifica" class="form-control">
                                        <option value='1' style=background-color:blue;>{{arrayPadrao('classificar_risco')[1]}}</option>
                                        <option value='2' style=background-color:forestgreen;>{{arrayPadrao('classificar_risco')[2]}}</option>
                                        <option value='3' style=background-color:yellow;>{{arrayPadrao('classificar_risco')[3]}}</option>
                                        <option value='4' style=background-color:orange;>{{arrayPadrao('classificar_risco')[4]}}</option>
                                        <option value='5' style=background-color:red;>{{arrayPadrao('classificar_risco')[5]}}</option>
                                        <option value='6' style=background-color:black;>{{arrayPadrao('classificar_risco')[6]}}</option>
                                        <option value='7' style=background-color:white;>{{arrayPadrao('classificar_risco')[7]}}</option>
                                        <option value='8' style=background-color:white;>{{arrayPadrao('classificar_risco')[8]}}</option>
                                    </select>
                                </div>
                                {{ Form::hidden('classificacao',(isset($lista['classificacao']) ? $lista['classificacao'] : ""),["name" => "classificacao", "id" => "classificacao"]) }}
                            </div>
                        </div>
                    </div>
                </div>
                <div id="procedimento-acolhimento" style="display: none" class="col-md-12">
                    <div class="row">
                        <div class="panel-body panel-acolhimento">
                            @if(session()->get('profissional'))
                                <label for="avaliacao">Lista de Procedimentos &nbsp<span><button class="btn btn-info btn-xs" id="abre-modal-add-procedimentos" type="button"> Adicionar</button></span></label>
                            @else
                                <p class="text-silver">Somente usuários cadastrados na Tabela de Profissionais de Saúde podem solicitar procedimentos.</p>
                            @endif
                                <div class="col-md-12">
                                    <div class='table-responsive'>
                                        <table class='table table-bordered table-hover table-striped'>
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
            <div class="pull-left">{{ "Responsável: ".title_case(Session()->get('profissional')).' ' .Session()->get('nome')}}
                <br>
                @foreach($salas as $s)
                    <div id="sala-rodape">{{ ($s->cd_sala == Session()->get('cd_sala')) ? title_case($s->nm_sala) : '' }}</div>
                @endforeach
            </div>
            <div class="pull-right">
                @if((session()->get('recurso.atendimentos-acolhimento-salvar')) && session()->get('profissional'))
                    {{ Form::submit('Salvar',['class'=>"btn btn-success pull-right"]) }}
                @endif
            </div>
        </div>
    </div>
    {{ Form::close() }}

@endsection

@section('painel-modal')
    @include('atendimentos/modal-procedimentos')
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
                                @foreach($salas as $s)
                                    <option value='{{$s->cd_sala}}' {{(session()->get('cd_sala')==$s->cd_sala) ? "selected" : ""}}>{{$s->nm_sala}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="painel"><h4>Selecione um painel</h4></label>
                            <select id="paineis" class="form-control" style="color:#287da1;">
                                @foreach(arrayPadrao('paineis') as $p => $key)
                                    <option value='{{$p}}'  {{(session()->get('cd_painel')==$p) ? "selected" : ""}}>{{$key}}</option>
                                @endforeach
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
                    {{ Form::submit('Salvar',['id'=>'salvar-procedimento-atendimento', 'class'=>"btn btn-success pull-right"]) }}
                    {{ Form::submit('Encerrar Procedimento',['id'=>'finalizar-procedimento-atendimento', 'class'=>"btn btn-warning pull-right"]) }}
                </div>
            </div>
        </div>
    </div>
    @include('atendimentos/modal-pesquisa')
    @include('atendimentos/modal-cirurgias-previas')
    @include('atendimentos/modal-medicamentos-em-uso')
    @include('atendimentos/modal-historia-medica-pregressa')
    @include('atendimentos/modal-alergia')
    @include('atendimentos/modal-historico')
    @include('pessoas.modal-pessoas')
@endsection

@section('custom_js')
   <script src="{{ js_versionado('prontuario.js') }}"></script>
   <script src="{{ js_versionado('atendimento_medico.js') }}"></script>
   <script>
       $('input[type="range"]').on('input', function() {
           var control = $(this),
               controlMin = control.attr('min'),
               controlMax = control.attr('max'),
               controlVal = control.val(),
               controlThumbWidth = control.data('thumbwidth');
           var range = controlMax - controlMin;
           var position = ((controlVal - controlMin) / range) * 100;
           var positionOffset = Math.round(controlThumbWidth * position / 100) - (controlThumbWidth / 2);
           var output = control.next('output');
           output
               .css('left', 'calc(' + position + '% - ' + positionOffset + 'px)')
               .text(controlVal);

       });
   </script>
@endsection

