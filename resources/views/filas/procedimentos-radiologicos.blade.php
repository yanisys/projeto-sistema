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
                <th class="text-center">Procedimento</th>
                <th class="text-center">Responsável</th>
                <th class='text-center' width="190px">Ação</th>
            </tr>
            </thead>
            <tbody>
            @if(!isset($prontuario) || $prontuario->IsEmpty())
                <tr><td colspan="5">Sem resultados</td></tr>
            @else
                @foreach($prontuario as $p)
                    <tr>
                        <td class='text-center'>
                            {{ formata_data_hora($p->dt_hr_solicitacao) }}
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
                            {{$p->nm_procedimento}}
                        </td>
                        <td class='text-center'>
                            {{ $p->nm_medico }}
                        </td>
                        <td align="center" width="9%">
                            <a href="{{ route('atendimentos/procedimentos').'/'.$p->cd_prontuario }}" title='Ir para procedimentos/ Evolução clínica' class='btn btn-success  fa fa-plus-square {{ verficaPermissaoBotao('recurso.atendimentos/procedimentos')}}'></a>
                        </td>

                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
    </div>

@endsection

