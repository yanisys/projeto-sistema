@extends('layouts.default')

@section('conteudo')

    <div class="panel panel-default ">
        <div class="panel-body">
            {!! Form::open() !!}
            <div class="container-fluid">
                <div class="col-sm-4">
                    <label>Selecione um painel para exibir</label>
                    {{  Form::select('cd_painel', arrayPadrao('paineis'), (isset($_POST['cd_painel']) ? $_POST['cd_painel'] : 1),['class'=> ($errors->has("cd_painel") ? "form-control is-invalid" : "form-control"), 'id' => 'cd_painel']) }}
                </div>
              <!--  <div class="col-sm-1 pull-right padding-top-25">
                    <label>Ativar Som</label>
                    <input id='checkbox-som' type="checkbox"/>
                </div>-->
                {!!  Form::submit('Buscar',['id' => 'escolhe-painel', 'class'=>"btn btn-default margin-top-25"]) !!}
            </div>
            {!! Form::close() !!}
        </div>
    </div>
    <button class="btn btn-primary" onclick="openFullscreen();">Tela cheia</button>
    <input id=numero_painel type="hidden">
        <h1 class="text-center"></h1>
        <div class="table-responsive" id="chamar">
            <table class="table table-bordered table-striped" id="painel_chamados">
                <thead>
                    <tr class="chamados">
                        <th class="text-center col-md-6">PACIENTE</th>
                        <th class="text-center col-md-4">SALA</th>
                        <th class="text-center col-md-2">HORÁRIO</th>
                    </tr>
                </thead>
                <tbody id="painel">
                @if(!isset($lista) || $lista->IsEmpty())
                    <tr><td colspan="3">Sem resultados</td></tr>
                @else
                    @foreach($lista as $cont => $l)
                        <tr class="chamados {{$cont ==0 ? 'chamado_atual' : ''}}">
                            <td class='text-center'>{{ $l->nm_pessoa }}</td>
                            <td class='text-center'>{{ $l->nm_sala }}</td>
                            <td class='text-center'>{{ formata_hora($l->horario) }}</td>
                        </tr>
                    @endforeach
                @endif
                </tbody>
            </table>
        </div>
@endsection

@section('custom_js')
    <script src="{{ js_versionado('prontuario.js') }}"></script>
    <script src="{{ js_versionado('painel.js') }}"></script>
    <script src='https://code.responsivevoice.org/responsivevoice.js'></script>
@endsection
