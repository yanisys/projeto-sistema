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

    {{ Form::open(['id' => 'cadastra-unidades-medida', 'class' => 'form-no-submit']) }}
    <div class="panel panel-primary">
        <div class="panel-heading">
            @if((session()->get('recurso.configuracoes/alergias-editar')))
                <a href="{{ route('configuracoes/unidades-medida/cadastro') }}" class="btn btn-primary pull-right margin-top-10">Novo</a>
            @endif
            <h4>Dados da Unidade Comercial</h4>
        </div>
        <div class="panel-body">
            <div class="tab-content">
                <div class="tab-pane fade in active" id="tab_info">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="cd_unidade_medida">Código<span style="color:red">*</span></label>
                                {{ Form::text('cd_unidade_medida',(isset($unidade_medida['cd_unidade_medida']) ? $unidade_medida['cd_unidade_medida'] : ""),["name" => "cd_unidade_medida", "maxlength" => "10", "id" => "cd_unidade_medida",  "disabled" => "disabled", 'class'=> ($errors->has("cd_unidade_medida") ? "form-control is-invalid" : "form-control")]) }}
                                {{ Form::hidden('cd_unidade_medida',(isset($unidade_medida['cd_unidade_medida']) ? $unidade_medida['cd_unidade_medida'] : ""),["name" => "cd_unidade_medida", "id" => "cd_unidade_medidaa"]) }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="descricao">Nome<span
                                style="color:red">*</span></label>
                                {{ Form::text('descricao', (isset($unidade_medida['nm_unidade_medida']) ? $unidade_medida['nm_unidade_medida'] : "") ,["maxlength" => "40", "name" => "nm_unidade_medida", 'class'=> ($errors->has("nm_unidade_medida") ? "form-control is-invalid maiusculas" : "form-control maiusculas") ]) }}
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="abreviacao">Abreviação</label>
                                {{ Form::text('abreviacao', (isset($unidade_medida['abreviacao']) ? $unidade_medida['abreviacao'] : "") ,["maxlength" => "4",  "name" => "abreviacao", 'class'=> ($errors->has("abreviacao") ? "form-control is-invalid maiusculas" : "form-control maiusculas") ]) }}
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label>Situação</label>
                                {{  Form::select('situacao', ['A'=>'Ativo', 'I'=>'Inativo'], (isset($unidade_medida['situacao']) ? $unidade_medida['situacao'] : "A"),['class'=>  "form-control", 'id' => 'situacao']) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel-footer fixed-panel-footer" >
            @if((session()->get('recurso.configuracoes/alergias-editar')))
                {{ Form::submit('Salvar',['class'=>"btn btn-success pull-right", 'id' => 'salvar-unidade-medida']) }}
            @endif
        </div>

    </div>

    {{ Form::close() }}

@endsection

