@extends('layouts.default')

@section('conteudo')

    <div class="panel panel-default ">
        <div class="panel-body">
            {!! Form::open(["method" => "GET", "class" => "form-search"]) !!}
            <div class="container-fluid">
                <div class="col-sm-4">
                    <label>Paciente</label>
                    {!! Form::text('paciente',(!empty($_REQUEST['paciente']) ? $_REQUEST['paciente'] : ""),["name" => "paciente", "placeholder" => "Nome do paciente",'class'=>'form-control']) !!}
                </div>
                <div class="col-sm-4">
                    <label>Responsável</label>
                    {!! Form::text('responsavel',(!empty($_REQUEST['responsavel']) ? $_REQUEST['responsavel'] : ""),["name" => "responsavel", "id" => "responsavel", "placeholder" => "Nome do responsável",'class'=>'form-control']) !!}
                </div>
                {!!  Form::submit('Buscar',['class'=>"btn btn-default margin-top-25"]) !!}
            </div>
            {!! Form::close() !!}
        </div>
    </div>

    <div class="table-responsive font-12px">
        <table class="table table-bordered table-hover table-striped">
            <thead>
            <tr>
                <th class="text-center">Data/ hora chegada</th>
                <th class="text-center">Sexo</th>
                <th class="text-center">Paciente</th>
                <th class="text-center">Idade</th>
                <th class="text-center">Responsável</th>
                <th class='text-center' width="190px">Ação</th>
            </tr>
            </thead>
            <tbody>
            @if(!isset($prontuario) || $prontuario->IsEmpty())
                <tr><td colspan="5">Sem resultados</td></tr>
            @else
                @foreach($prontuario as $p)
                    <tr id="classificacao-risco-{{$p->classificacao}}">
                        <td class='text-center'>
                            {{ formata_data_hora($p->created_at) }}
                        </td>
                        <td class='text-center'>
                            @if(($p->id_sexo == 'F'))
                                <span class='fa fa-female' style='color:deeppink;'></span>
                            @else
                                <span class='fa fa-male' style='color:blue;'></span>
                            @endif
                        </td>
                        <td class='text-center'>
                            {{ $p->nm_pessoa }}
                            @if(isset($p->nm_sala))<span style="background-color: #ddd32f"> - CHAMADO NO PAINEL: {{$p->nm_sala}}</span>@endif
                        </td>
                        <td class='text-center'>
                            {{ isset($p->dt_nasc) ? calcula_idade($p->dt_nasc) : ""}}
                        </td>
                        <td class='text-center'>
                            {{ $p->enfermeiro }}
                        </td>
                        <td align="center" width="9%">
                            <div class="btn-group" role="group">
                                <a href="{{ route('atendimentos/atendimento-medico').'/'.$p->cd_prontuario }}" title='Ir para o prontuário' class='btn btn-primary fa fa-stethoscope {{ verficaPermissaoBotao('recurso.atendimentos/atendimento-medico')}}'></a>
                                <a data-classificacao-atual='{{ $p->classificacao }}' data-cd-prontuario='{{ $p->cd_prontuario }}' title='Reclassificar risco' class="btn btn-warning fas fa-sync-alt abre-modal-reclassificar {{ verficaPermissaoBotao('recurso.atendimentos-acolhimento-salvar')}}"></a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
    </div>

@endsection

@section('painel-modal')

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
                                    <option value='1' style=background-color:blue;>{{arrayPadrao('classificar_risco')[1]}}</option>
                                    <option value='2' style=background-color:forestgreen;>{{arrayPadrao('classificar_risco')[2]}}</option>
                                    <option value='3' style=background-color:yellow;>{{arrayPadrao('classificar_risco')[3]}}</option>
                                    <option value='4' style=background-color:orange;>{{arrayPadrao('classificar_risco')[4]}}</option>
                                    <option value='5' style=background-color:red;>{{arrayPadrao('classificar_risco')[5]}}</option>
                                    <option value='6' style=background-color:black;>{{arrayPadrao('classificar_risco')[6]}}</option>
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

@endsection



@section('custom_js')

    <script src=" {{ asset('public/js/jquery.cookie.js') }}"></script>

    <script src="{{js_versionado('fila.js')}}"></script>

    <script src="{{js_versionado('prontuario.js')}}"></script>

@endsection