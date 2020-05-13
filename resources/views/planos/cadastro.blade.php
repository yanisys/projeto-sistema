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

    <div class="panel panel-primary">
        <div class="panel-heading">
            @if((session()->get('recurso.planos-editar')))
                <a href="{{ route('planos/cadastro') }}" class="btn btn-primary pull-right margin-top-10">Novo</a>
            @endif
            <h4>Dados do Plano</h4>
        </div>
        {{ Form::open(['id' => 'cadastra-planos', 'class' => 'form-no-submit']) }}
        <div class="panel-body">
            <div class="tab-content">
                <div class="tab-pane fade in active" id="tab_info">

                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="cd_plano">Código<span style="color:red">*</span></label>
                                {{ Form::text('cd_plano',(isset($plano['cd_plano']) ? $plano['cd_plano'] : ""),["name" => "cd_plano", "maxlength" => "10", "id" => "cd_plano",  "disabled" => "disabled", 'class'=> ($errors->has("cd_plano") ? "form-control is-invalid" : "form-control")]) }}
                                {{ Form::hidden('cd_plano',(isset($plano['cd_plano']) ? $plano['cd_plano'] : ""),["name" => "cd_plano", "id" => "cd_plano"]) }}
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="ds_plano">Descrição do Plano<span
                                     style="color:red">*</span></label>
                                {{ Form::text('ds_plano', (isset($plano['ds_plano']) ? $plano['ds_plano'] : "") ,["maxlength" => "100", "id" => "ds_plano", "name" => "ds_plano", 'class'=> ($errors->has("ds_plano") ? "form-control is-invalid maiusculas" : "form-control maiusculas") ]) }}
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="tp_plano">Tipo de plano<span
                                     style="color:red">*</span>
                                </label>
                                {{  Form::select('tp_plano', arrayPadrao('tipo_plano'), (isset($plano['tp_plano']) ? trim($plano['tp_plano']) : ""),['class'=>  "form-control", 'id' => 'tp_plano']) }}
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="panel-footer fixed-panel-footer" >
            @if((session()->get('recurso.planos-editar')))
                {{ Form::submit('Salvar',['class'=>"btn btn-success pull-right", 'id' => 'salvar-plano']) }}
            @endif
        </div>
        {{ Form::close() }}
    </div>

@endsection

