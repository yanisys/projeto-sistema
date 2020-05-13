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

    {{ Form::open(['id' => 'cadastra-origem', 'class' => 'form-no-submit']) }}
    <div class="panel panel-primary">
        <div class="panel-heading">
            @if((session()->get('recurso.configuracoes/origem-editar')))
                <a href="{{ route('configuracoes/origem/cadastro') }}" class="btn btn-primary pull-right margin-top-10">Novo</a>
            @endif
            <h4>Dados da Origem</h4>
        </div>
        <div class="panel-body">
            <div class="tab-content">
                <div class="tab-pane fade in active" id="tab_info">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="cd_origem">Código<span style="color:red">*</span></label>
                                {{ Form::text('cd_origem',(isset($origem['cd_origem']) ? $origem['cd_origem'] : ""),["name" => "cd_origem", "maxlength" => "10", "id" => "cd_origem",  "disabled" => "disabled", 'class'=> ($errors->has("cd_origem") ? "form-control is-invalid" : "form-control")]) }}
                                {{ Form::hidden('cd_origem',(isset($origem['cd_origem']) ? $origem['cd_origem'] : ""),["name" => "cd_origem", "id" => "cd_origem"]) }}
                            </div>
                        </div>
                        <div class="col-md-10">
                            <div class="form-group">
                                <label for="nm_origem">Nome<span
                                style="color:red">*</span></label>
                                {{ Form::text('nm_origem', (isset($origem['nm_origem']) ? $origem['nm_origem'] : "") ,["maxlength" => "100", "name" => "nm_origem", 'class'=> ($errors->has("nm_origem") ? "form-control is-invalid maiusculas" : "form-control maiusculas") ]) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel-footer fixed-panel-footer" >
            @if((session()->get('recurso.configuracoes/origem-editar')))
                {{ Form::submit('Salvar',['class'=>"btn btn-success pull-right", 'id' => 'salvar-origem']) }}
            @endif
        </div>

    </div>

    {{ Form::close() }}

@endsection

