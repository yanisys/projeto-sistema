@extends('layouts.default')

@section('conteudo-full')

    <div class="panel panel-default ">
        <div class="panel-body">
            {!! Form::open(["method" => "GET", "class" => "form-search"]) !!}
            <div class="row">
                <div class="col-sm-2">
                    <label>Paciente</label>
                    {!! Form::text('nm_pessoa',(!empty($_REQUEST['nm_pessoa']) ? $_REQUEST['nm_pessoa'] : ""),["name" => "nm_pessoa", "placeholder" => "Digite o nome do paciente",'class'=>'form-control']) !!}
                </div>
                <div class="col-sm-2">
                    <label>Data nasc.</label>
                    {!! Form::text('dt_nasc',(!empty($_REQUEST['dt_nasc']) ? $_REQUEST['dt_nasc'] :  '' ),["name" => "dt_nasc", 'placeholder' => 'dd/mm/aaaa', 'class'=>'form-control mask-data']) !!}
                </div>
                <div class="col-sm-2">
                    <label>Nome do Médico</label>
                    {!! Form::text('nm_medico',(!empty($_REQUEST['nm_medico']) ? $_REQUEST['nm_medico'] : ""),["name" => "nm_medico", "placeholder" => "Digite o nome do médico",'class'=>'form-control']) !!}
                </div>
                <div class="col-sm-2">
                    <label for="id_situacao">Situação</label>
                    {{  Form::select('status', arrayPadrao('situacao_prontuario'), (!empty($_REQUEST['status']) ? $_REQUEST['status'] : "T"),['class'=> "form-control", "id" => "status"]) }}
                </div>
                <div class="col-sm-2">
                    <label for="id_situacao">Classificação de risco</label>
                    {{  Form::select('classificacao', arrayPadrao('classificar_risco','T'), (!empty($_REQUEST['classificacao']) ? $_REQUEST['classificacao'] : 'T'),['class'=> "form-control", "id" => "classificacao"]) }}
                </div>
                <div class="col-sm-2">
                    <label for="motivo_alta">Motivo da alta</label>
                    {{  Form::select('motivo_alta', $motivo_alta, (!empty($_REQUEST['motivo_alta']) ? $_REQUEST['motivo_alta'] : 'T'),['class'=> "form-control", "id" => "motivo_alta"]) }}
                </div>
            </div>
            <div class="row">
                <div class="col-sm-2">
                    <label for="filtro">Filtro intervalo de datas</label>
                    {{  Form::select('filtro', $intervalos_datas, (!empty($_REQUEST['filtro']) ? $_REQUEST['filtro'] : 'am'),['class'=> "form-control", "id" => "filtro"]) }}
                </div>
                <div class="col-sm-2">
                    <label>Data Inicial</label>
                    {!! Form::text('dt_ini',(!empty($_REQUEST['dt_ini']) ? $_REQUEST['dt_ini'] :  date('d/m/Y') ),["name" => "dt_ini", 'placeholder' => 'dd/mm/aaaa', 'class'=>'form-control mask-data']) !!}
                </div>
                <div class="col-sm-1">
                    <label>Hora</label>
                    {!! Form::text('hr_ini',(!empty($_REQUEST['hr_ini']) ? $_REQUEST['hr_ini'] :  '' ),["name" => "hr_ini",'placeholder' => 'hh:mm', 'class'=>'form-control mask-hora']) !!}
                </div>
                <div class="col-sm-2">
                    <label>Data Final</label>
                    {!! Form::text('dt_fim',(!empty($_REQUEST['dt_fim']) ? $_REQUEST['dt_fim'] : date('d/m/Y')),["name" => "dt_fim", 'placeholder' => 'dd/mm/aaaa', 'class'=>'form-control mask-data']) !!}
                </div>
                <div class="col-sm-1">
                    <label>Hora</label>
                    {!! Form::text('hr_fim',(!empty($_REQUEST['hr_fim']) ? $_REQUEST['hr_fim'] :  '' ),["name" => "hr_fim", 'placeholder' => 'hh:mm','class'=>'form-control mask-hora']) !!}
                </div>
                <div class="col-sm-2">
                    <label>Cid</label>
                    {!! Form::text('cid',(!empty($_REQUEST['cid']) ? $_REQUEST['cid'] : ""),["name" => "cid", "placeholder" => "Digite o nome da Cid",'class'=>'form-control']) !!}
                </div>
                <div class="col-sm-1 ">
                    {!!  Form::submit('Buscar',['id' => 'relatorio_prontuario_tela', 'class' => 'btn btn-primary margin-top-25']) !!}
                </div>
                <div class="col-sm-1 ">
                    {!!  Form::submit('Relatório',['id' => 'relatorio_prontuario_pdf', 'class' => 'btn btn-primary margin-top-25']) !!}
                </div>
                {!!  Form::hidden('tp_relatorio','',['id' => 'tp_relatorio_prontuario']) !!}
            </div>
            {!! Form::close() !!}
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover table-striped">
            <thead>
            <tr>
                <th>Data/ Hora</th>
                <th>Paciente</th>
                <th>Sexo</th>
                <th>Médico</th>
                <th>Cid</th>
                <th>Motivo Alta</th>
                <th>Status</th>
                <th class='text-center' width="150px">Ação</th>
            </tr>
            </thead>
            <tbody>

            @if(!isset($lista) || $lista->IsEmpty())
                <tr><td colspan="6">Sem resultados</td></tr>
            @else
                @foreach($lista as $prontuario)
                    <tr>
                        <td>{{ formata_data_hora($prontuario->created_at) }}</td>
                        <td>{{ $prontuario->nm_pessoa }}</td>
                        <td class='text-center'>
                            @if(($prontuario->id_sexo == 'F'))
                                <span class='fa fa-female' style='color:deeppink;'></span>
                            @else
                                <span class='fa fa-male' style='color:blue;'></span>
                            @endif
                        </td>
                        <td>{{ $prontuario->nm_medico }}</td>
                        <td>{{ (isset($prontuario->nm_cid)) ? $prontuario->nm_cid : "" }}</td>
                        <td>{{ (isset($prontuario->motivo_alta) && $prontuario->motivo_alta !== 0) ? arrayPadrao('motivo_alta')[$prontuario->motivo_alta] : '' }}</td>
                        <td>{{ arrayPadrao('status_prontuario')[$prontuario->status] }}</td>
                        <td class='text-center'>
                            <a href="{{ route('atendimentos/atendimento-medico').'/'.$prontuario->cd_prontuario }}" class={{ verficaPermissaoBotao('recurso.prontuarios-editar') }} 'btn btn-primary btn-sm'>Editar</a>
                            <button type='button' data-tabela="prontuarios" data-chave="cd_prontuario" data-valor="{{ $prontuario->cd_prontuario }}" class='{{ verficaPermissaoBotao('recurso.prontuarios-excluir')  }} btn btn-danger btn-sm btn-excluir'>Excluir</button>
                        </td>
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class='pull-left'><b>@isset($lista){{"Total de registros: ". $lista->total() }} @endisset</b></div>
            <div class='pull-right'>@isset($lista){{ $lista->links() }}@endisset</div>
        </div>
    </div>

@endsection