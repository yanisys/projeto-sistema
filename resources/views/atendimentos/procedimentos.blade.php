
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

    <div class="panel panel-primary">
        <div class="panel-heading">
            @if(session()->get('profissional') && (session()->get('recurso.atendimentos-procedimentos-salvar')))
                <button id='chamar-painel' data-toggle='modal' data-target="#escolhe-sala" class="btn btn-primary pull-right">Chamar painel</button>
            @endif
            <button class="btn btn-primary pull pull-right ver_pessoa" type="button" data-toggle="modal" value="{{$lista[0]->cd_pessoa}}"  data-target="#modal-pesquisa">Ver cadastro</button>
            <button type="button" class="btn btn-primary pull pull-right" id="ver-historico" value='{{(isset($lista[0]->cd_pessoa) ? $lista[0]->cd_pessoa : "")}}' data-toggle="modal" data-target="#modal-historico">Histórico</button>
            @if($acolhimento->classificacao == 7)
                <button type="button" class="btn btn-danger pull pull-right" id="finalizar-atendimento-sem-medico">Finalizar atendimento</button>
                <input type="hidden" name="id_status_classificacao" value="7">
            @else
                <a href="{{ route('atendimentos/atendimento-medico').'/'.$lista[0]->cd_prontuario }}" title='Ir para o prontuário' class='btn btn-primary pull-right {{ verficaPermissaoBotao('recurso.atendimentos/atendimento-medico')}}'>Prontuário</a>
            @endif
            <a href="{{ route('relatorios/prontuario').'/'.$lista[0]->cd_prontuario }}" target="_blank" class="btn btn-primary pull-right"><span class="fa fa-print"></span></a>
            <input id='cd_prontuario' type="hidden" value="{{$lista[0]->cd_prontuario}}">
            <h4>Procedimentos/ Evolução Clínica</h4>
        </div>
        <div class="panel-body">
            <div class="panel-heading">Procedimentos</div>
            <div class="panel-body panel-acolhimento">
                @if(session()->get('profissional') && (session()->get('recurso.atendimentos-procedimentos-adicionar')))
                    <input data-toggle="modal" class="btn btn-info btn-sm" data-target="#modal-procedimentos" data-permissoes="true" value="Adicionar" type="button">
                @endif
                <div class="panel-body">
                    <div class="row">
                        <div style="font-size: 9pt" class='table-responsive'>
                            <table class='table table-bordered table-hover table-striped'>
                                <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Nome procedimento</th>
                                    <th>Situação</th>
                                    <th class='text-center' width="100px">Ação</th>
                                </tr>
                                </thead>
                                <tbody id='lista-procedimentos' data-permissoes="true" class="lista-procedimentos">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel-heading">Evolução Clínica</div>
            <div class="panel-body panel-acolhimento">
                @if(session()->get('profissional') && (session()->get('recurso.atendimentos-evolucao-salvar')))
                    <input data-toggle="modal" class="btn btn-info btn-sm" data-target="#modal-detalhes-evolucao" data-permissoes="true" value="Adicionar" type="button">
                @endif
                <div class="panel-body">
                    <div class="row">
                        <div style="font-size: 9pt" class='table-responsive'>
                            <table class='table table-bordered table-hover table-striped'>
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
        <div class="panel-footer fixed-panel-footer">

        </div>
    </div>

    @include('pessoas.modal-pessoas')
    @include('atendimentos/modal-procedimentos')
    @include('atendimentos/modal-pesquisa')
    @include('atendimentos/modal-historico')
    @include('atendimentos/modal-evolucao')
@endsection
@section('painel-modal')
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
                                <textarea class="form-control input-sm" rows="1" disabled="disabled" id="d_solicitacao"></textarea>
                            </div>
                        </div>
                    </div>
                    {{ Form::open(['id' => 'form-detalhes-procedimento', 'class' => 'form-no-submit']) }}
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="descricao_execucao">Detalhes da execução</label>
                                {{ Form::textarea('d_execucao',"",["name" => "descricao_execucao", "maxlength" => "2000", "rows"=>"15", "id" => "d_execucao",  'class'=> ($errors->has("descricao_execucao") ? "form-control input-sm is-invalid" : "form-control input-sm")]) }}
                                {{ Form::hidden('d_id_atendimento_procedimento','',["name" => "id_atendimento_procedimento", "id" => "d_id_atendimento_procedimento"]) }}
                                {{ Form::hidden('id_status','A',["name" => "id_status", "id" => "id_status_procedimento"]) }}
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
                    @if(session()->get('profissional') && (session()->get('recurso.atendimentos-procedimentos-salvar')))
                        {{ Form::submit('Salvar',['id'=>'salvar-procedimento', 'class'=>"btn btn-success pull-right"]) }}
                        {{ Form::submit('Encerrar',['id'=>'finalizar-procedimento', 'class'=>"btn btn-success pull-right"]) }}
                    @endif
                </div>
                {{Form::close()}}
            </div>
        </div>
    </div>

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
                                    @foreach($salas as $s)
                                        <option value='{{$s->cd_sala}}' {{(session()->get('cd_sala')==$s->cd_sala) ? "selected" : ""}}>{{$s->nm_sala}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="painel"><h4>Selecione um painel</h4></label>
                                <select id="paineis" class="form-control input-sm" style="color:#287da1;">
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
@endsection

@section('custom_js')
    <script src="{{ js_versionado('prontuario.js') }}"></script>
    <script src="{{ js_versionado('atendimento_medico.js') }}"></script>
@endsection