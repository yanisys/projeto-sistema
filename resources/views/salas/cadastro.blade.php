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

    {{ Form::open(['id' => 'cadastra-salas', 'class' => 'form-no-submit']) }}
    <div class="panel panel-primary">
        <div class="panel-heading">
            @if((session()->get('recurso.salas-editar')))
                <a href="{{ route('salas/cadastro') }}" class="btn btn-primary pull-right margin-top-10">Novo</a>
            @endif
            <h4>Dados da Sala</h4>
        </div>
        <div class="panel-body">
            <div class="tab-content">
                <div class="tab-pane fade in active" id="tab_info">

                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="cd_sala">Código<span style="color:red">*</span></label>
                                {{ Form::text('cd_sala',(isset($sala['cd_sala']) ? $sala['cd_sala'] : ""),["name" => "cd_sala", "maxlength" => "10", "id" => "cd_sala",  "disabled" => "disabled", 'class'=> ($errors->has("cd_sala") ? "form-control is-invalid" : "form-control")]) }}
                                {{ Form::hidden('cd_sala',(isset($sala['cd_sala']) ? $sala['cd_sala'] : ""),["name" => "cd_sala", "id" => "cd_sala"]) }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nm_sala">Descrição da sala<span
                                style="color:red">*</span></label>
                                {{ Form::text('nm_sala', (isset($sala['nm_sala']) ? $sala['nm_sala'] : "") ,["maxlength" => "100", "id" => "nm_sala", "name" => "nm_sala", 'class'=> ($errors->has("nm_sala") ? "form-control is-invalid maiusculas" : "form-control maiusculas") ]) }}
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label>Tipo</label>
                                {{  Form::select('tipo', arrayPadrao('tipo_sala'), (isset($sala['tipo']) ? $sala['tipo'] : "E"),['class'=>  "form-control", 'id' => 'tipo']) }}
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label>Situação</label>
                                {{  Form::select('situacao', ['A'=>'Ativo', 'I'=>'Inativo'], (isset($sala['situacao']) ? $sala['situacao'] : "A"),['class'=>  "form-control", 'id' => 'situacao']) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel-footer fixed-panel-footer" >
            @if((session()->get('recurso.salas-editar')))
                {{ Form::submit('Salvar',['class'=>"btn btn-success pull-right", 'id' => 'salvar-sala']) }}
            @endif
        </div>

    </div>

    {{ Form::close() }}

@endsection

