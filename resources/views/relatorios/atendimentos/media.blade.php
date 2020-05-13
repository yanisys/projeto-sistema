@extends('layouts.default')

@section('conteudo')

    <div class="panel panel-default ">
        <div class="panel-body">
            {!! Form::open(["method" => "GET", "class" => "form-search"]) !!}
            <div class="row">
                <div class="col-sm-3">
                    <label>Data inicial</label>
                    {!! Form::text('dt_inicial',(!empty($_REQUEST['dt_inicial']) ? $_REQUEST['dt_inicial'] : date('01/m/Y')),["name" => "dt_inicial", "placeholder" => "dd/mm/yyyy",'class'=>'form-control mask-data']) !!}
                </div>
                <div class="col-sm-3">
                    <label>Data final</label>
                    {!! Form::text('dt_final',(!empty($_REQUEST['dt_final']) ? $_REQUEST['dt_final'] : date('d/m/Y')),["name" => "dt_final", "placeholder" => "dd/mm/yyyy",'class'=>'form-control mask-data']) !!}
                </div>
                <div class="col-sm-6 ">
                    {!!  Form::submit('Gerar Relatório',['class'=>"btn btn-default margin-top-25"]) !!}
                </div>
            </div>
<!--            <div class="row">
                <div class="col-sm-12">
                    <br>
                    <label>Selecione quais relatórios deseja visualizar:</label>
                    <label class="cbcontainer">Média diária de atendimentos
                        <input type="checkbox" checked="checked">
                        <span class="checkmark"></span>
                    </label>
                    <label class="cbcontainer">Atendimentos conforme o dia da semana
                        <input type="checkbox" checked="checked">
                        <span class="checkmark"></span>
                    </label>
                    <label class="cbcontainer">Quantitativo de curativos realizados por bairro e turno
                        <input type="checkbox" checked="checked">
                        <span class="checkmark"></span>
                    </label>
                    <label class="cbcontainer">Atendimentos conforme a faixa etária
                        <input type="checkbox" checked="checked">
                        <span class="checkmark"></span>
                    </label>
                    <label class="cbcontainer">Atendimentos realizados por bairro de origem
                        <input type="checkbox" checked="checked">
                        <span class="checkmark"></span>
                    </label>
                    <label class="cbcontainer">Atendimentos conforme o gênero
                        <input type="checkbox" checked="checked">
                        <span class="checkmark"></span>
                    </label>
                    <label class="cbcontainer">Classificação de risco
                        <input type="checkbox" checked="checked">
                        <span class="checkmark"></span>
                    </label>
                    <label class="cbcontainer">Diagnósticos conforme grupo de CID
                        <input type="checkbox" checked="checked">
                        <span class="checkmark"></span>
                    </label>
                    <label class="cbcontainer">Diagnósticos conforme CID
                        <input type="checkbox" checked="checked">
                        <span class="checkmark"></span>
                    </label>
                </div>
            </div>
            -->
            {!! Form::close() !!}
        </div>
    </div>




@endsection