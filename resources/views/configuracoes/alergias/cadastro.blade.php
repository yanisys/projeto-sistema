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

    {{ Form::open(['id' => 'cadastra-alergias', 'class' => 'form-no-submit']) }}
    <div class="panel panel-primary">
        <div class="panel-heading">
            @if((session()->get('recurso.configuracoes/alergias-editar')))
                <a href="{{ route('configuracoes/alergias/cadastro') }}" class="btn btn-primary pull-right margin-top-10">Novo</a>
            @endif
            <h4>Dados da Alergia</h4>
        </div>
        <div class="panel-body">
            <div class="tab-content">
                <div class="tab-pane fade in active" id="tab_info">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="cd_alergia">Código<span style="color:red">*</span></label>
                                {{ Form::text('cd_alergia',(isset($alergia['cd_alergia']) ? $alergia['cd_alergia'] : ""),["name" => "cd_alergia", "maxlength" => "10", "id" => "cd_alergia",  "disabled" => "disabled", 'class'=> ($errors->has("cd_alergia") ? "form-control is-invalid" : "form-control")]) }}
                                {{ Form::hidden('cd_alergia',(isset($alergia['cd_alergia']) ? $alergia['cd_alergia'] : ""),["name" => "cd_alergia", "id" => "cd_alergia"]) }}
                            </div>
                        </div>
                        <div class="col-md-10">
                            <div class="form-group">
                                <label for="nm_alergia">Nome<span
                                style="color:red">*</span></label>
                                {{ Form::text('nm_alergia', (isset($alergia['nm_alergia']) ? $alergia['nm_alergia'] : "") ,["maxlength" => "100", "name" => "nm_alergia", 'class'=> ($errors->has("nm_alergia") ? "form-control is-invalid maiusculas" : "form-control maiusculas") ]) }}
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="descricao">Descrição</label>
                                {{ Form::textarea('descricao', (isset($alergia['descricao']) ? $alergia['descricao'] : "") ,["maxlength" => "500", "rows"=>"2", "name" => "descricao", 'class'=> ($errors->has("nm_alergia") ? "form-control is-invalid maiusculas" : "form-control maiusculas") ]) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel-footer fixed-panel-footer" >
            @if((session()->get('recurso.configuracoes/alergias-editar')))
                {{ Form::submit('Salvar',['class'=>"btn btn-success pull-right", 'id' => 'salvar-alergia']) }}
            @endif
        </div>

    </div>

    {{ Form::close() }}

@endsection

