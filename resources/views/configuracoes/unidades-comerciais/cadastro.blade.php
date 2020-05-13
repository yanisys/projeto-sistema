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
            <p><a class="btn btn-primary" data-toggle="collapse" href="#collapseExample" role="button"
                  aria-expanded="true" aria-controls="collapseExample">
                    Fechar
                </a></p>
        </div>
    @endif

    {{ Form::open(['id' => 'cadastra-unidades-comerciais', 'class' => 'form-no-submit']) }}
    <div class="panel panel-primary">
        <div class="panel-heading">
            @if((session()->get('recurso.configuracoes/alergias-editar')))
                <a href="{{ route('configuracoes/unidades-comerciais/cadastro') }}" class="btn btn-primary pull-right margin-top-10">Novo</a>
            @endif
            <h4>Dados da Unidade Comercial</h4>
        </div>
        <div class="panel-body">
            <div class="tab-content">
                <div class="tab-pane fade in active" id="tab_info">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="cd_unidade_comercial">Código<span style="color:red">*</span></label>
                                {{ Form::text('cd_unidade_comercial',(isset($unidade_comercial['cd_unidade_comercial']) ? $unidade_comercial['cd_unidade_comercial'] : ""),["name" => "cd_unidade_comercial", "maxlength" => "10", "id" => "cd_unidade_comercial",  "disabled" => "disabled", 'class'=> ($errors->has("cd_unidade_comercial") ? "form-control is-invalid" : "form-control")]) }}
                                {{ Form::hidden('cd_unidade_comercial',(isset($unidade_comercial['cd_unidade_comercial']) ? $unidade_comercial['cd_unidade_comercial'] : ""),["name" => "cd_unidade_comercial", "id" => "cd_unidade_comerciala"]) }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="descricao">Nome<span
                                style="color:red">*</span></label>
                                {{ Form::text('descricao', (isset($unidade_comercial['descricao']) ? $unidade_comercial['descricao'] : "") ,["maxlength" => "30", "name" => "descricao", 'class'=> ($errors->has("nm_alergia") ? "form-control is-invalid maiusculas" : "form-control maiusculas") ]) }}
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="unidade">Abreviação</label>
                                {{ Form::text('unidade', (isset($unidade_comercial['unidade']) ? $unidade_comercial['unidade'] : "") ,["maxlength" => "6",  "name" => "unidade", 'class'=> ($errors->has("unidade") ? "form-control is-invalid maiusculas" : "form-control maiusculas") ]) }}
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label>Situação</label>
                                {{  Form::select('situacao', ['A'=>'Ativo', 'I'=>'Inativo'], (isset($unidade_comercial['situacao']) ? $unidade_comercial['situacao'] : "A"),['class'=>  "form-control", 'id' => 'situacao']) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel-footer fixed-panel-footer" >
            @if((session()->get('recurso.configuracoes/alergias-editar')))
                {{ Form::submit('Salvar',['class'=>"btn btn-success pull-right", 'id' => 'salvar-unidade-comercial']) }}
            @endif
        </div>

    </div>

    {{ Form::close() }}

@endsection

