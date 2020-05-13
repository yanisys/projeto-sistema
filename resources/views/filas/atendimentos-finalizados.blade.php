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
                <th class="text-center">Motivo da alta</th>
                <th class="text-center">Idade</th>
                <th class="text-center">Responsável</th>
            </tr>
            </thead>
            <tbody>
            @if(!isset($prontuario) || $prontuario->IsEmpty())
                <tr><td colspan="6">Sem resultados</td></tr>
            @else
                @foreach($prontuario as $p)
                    <tr>
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
                        </td>
                        <td class='text-center'>
                            {{ $p->motivo_alta }}
                        </td>
                        <td class='text-center'>
                            {{ isset($p->dt_nasc) ? calcula_idade($p->dt_nasc) : ""}}
                        </td>
                        <td class='text-center'>
                            {{ $p->medico }}
                        </td>
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
    </div>

@endsection

