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

    {{ Form::open(['id' => 'cadastra-parametros', 'class' => 'form-no-submit']) }}
    <div class="panel panel-primary">
        <div class="panel-heading">
            @if((session()->get('recurso.configuracoes/alergias-editar')))
                <a href="{{ route('configuracoes/parametros/cadastro') }}" class="btn btn-primary pull-right margin-top-10">Novo</a>
            @endif
            <h4>Dados do parâmetro</h4>
        </div>
        <div class="panel-body">
            <div class="tab-content">
                <div class="tab-pane fade in active" id="tab_info">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="cd_configuracao">Código<span style="color:red">*</span></label>
                                {{ Form::text('cd_configuracao',(isset($parametro->cd_configuracao) ? $parametro->cd_configuracao : ""),["name" => "cd_configuracao", "maxlength" => "10", "id" => "cd_configuracao",  "disabled" => "disabled", 'class'=> ($errors->has("cd_configuracao") ? "form-control is-invalid" : "form-control")]) }}
                                {{ Form::hidden('cd_configuracao',(isset($parametro->cd_configuracao) ? $parametro->cd_configuracao : ""),["name" => "cd_configuracao", "id" => "cd_configuracao"]) }}
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div class="form-group">
                                <label for="descricao">Descrição<span
                                style="color:red">*</span></label>
                                {{ Form::text('descricao', (isset($parametro->descricao) ? $parametro->descricao : "") ,["maxlength" => "1500", "name" => "descricao", "disabled" => "disabled", 'class'=> ($errors->has("descricao") ? "form-control is-invalid maiusculas" : "form-control maiusculas") ]) }}
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>Valor</label>
                                {{  Form::select('valor', $valores, (isset($parametro->valor) ? $parametro->valor : 0),['class'=>  "form-control", 'id' => 'valor']) }}
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

