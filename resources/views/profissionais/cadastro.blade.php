
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
            <p ><a class="btn btn-primary" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="true" aria-controls="collapseExample">
                    Fechar
                </a>
            </p>
        </div>
    @endif
    <link href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/css/select2.min.css" rel="stylesheet" />
    <script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/js/select2.min.js"></script>
    {{ Form::open(['id' => 'cadastra_profissional', 'class' => 'form-no-submit']) }}
    <div class="panel panel-primary">
        <div class="panel-heading">
            @if((session()->get('recurso.profissionais-editar')))
                <a href="{{ route('profissionais/cadastro') }}" class="btn btn-primary pull-right margin-top-10">Novo</a>
            @endif
            <h4>Dados do Profissional</h4>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="id">Código<span style="color:red">*</span></label>
                        {{ Form::text('cd_profissional',(isset($profissional['cd_profissional']) ? $profissional['cd_profissional'] : ""),["name" => "cd_profissional", "maxlength" => "10", "id" => "id", "disabled"=>"disabled", 'class'=> ($errors->has("id") ? "form-control is-invalid" : "form-control")]) }}
                        {{ Form::hidden('cd_profissional',(isset($profissional['cd_profissional']) ? $profissional['cd_profissional'] : ""),["name" => "cd_profissional", "id" => "id"]) }}
                    </div>
                </div>
                <div class="col-md-2 pull-right">
                    <div class="form-group">
                        <label for="status">Situação</label>
                        {{  Form::select('status', ['A' => 'Ativo', 'I' => 'Inativo'], (isset($profissional['status']) ? $profissional['status'] : "A"),['class'=> ($errors->has("status") ? "form-control is-invalid" : "form-control"), 'id' => 'status']) }}
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-2">
                    <div class="input-group">
                        <label for="cd_pessoa">Pessoa<span style="color:red">*</span></label>
                        {{ Form::text('cd_pessoa',(isset($profissional['cd_pessoa']) ? $profissional['cd_pessoa'] : ""),["name" => "cd_pessoa", 'disabled', "maxlength" => "10", "id" => "cd_pessoa", 'class'=> ($errors->has("cd_pessoa") ? "form-control is-invalid" : "cd_pessoa form-control")]) }}
                        {{ Form::hidden('cd_pessoa',(isset($profissional['cd_pessoa']) ? $profissional['cd_pessoa'] : ""),["name" => "cd_pessoa", "id" => "cd_pessoa_disabled", "class"=>"cd_pessoa" ]) }}
                        <span class="input-group-btn">
                            <button class="btn btn-info margin-top-25 " type="button" data-toggle="modal" data-target="#modal-pesquisa" id="open"><span class="fa fa-search"></span> </button>
                        </span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="nm_pessoa">Nome<span style="color:red">*</span></label>
                        {{ Form::text('nm_pessoa', (isset($profissional['nm_pessoa']) ? $profissional['nm_pessoa'] : "") ,["maxlength" => "60", "disabled", "id" => "nm_pessoa", 'class'=> ($errors->has("nm_pessoa") ? "form-control is-invalid" : "form-control") ]) }}
                        {{ Form::hidden('nm_pessoa', (isset($profissional['nm_pessoa']) ? $profissional['nm_pessoa'] : "") ,["id" => "nm_pessoa_disabled" ]) }}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="input-group">
                        <label for="cd_ocupacao">Profissão<br></label>
                        {{ Form::text('nome_ocupacao',(isset($profissional['nm_ocupacao']) ? $profissional['nm_ocupacao'] : "") ,["id" => "nome_ocupacao", "disabled","name" => "nome_ocupacao",'class'=> ($errors->has("nome_ocupacao") ? "form-control is-invalid" : "form-control")]) }}
                        <span class="input-group-btn">
                            <button type="button" data-toggle="modal" class="btn btn-info search margin-top-25" data-fechar="true" data-destino="pesquisa_ocupacao" data-target="#modal-search"><span class="fa fa-search"></span></button>
                        </span>
                        {{ Form::hidden('cd_ocupacao',(isset($profissional['cd_ocupacao']) ? $profissional['cd_ocupacao'] : "") ,["id" => "cd_ocupacao", "name" => "cd_ocupacao",'class'=> ($errors->has("cd_ocupacao") ? "form-control is-invalid" : "form-control")]) }}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label id="conselho" for="conselho">Nome do conselho</label>
                        {{ Form::text('conselho',(isset($profissional['conselho']) ? $profissional['conselho'] : "") ,["maxlength" => "60", "id" => "conselho", 'class'=> ($errors->has("conselho") ? "form-control is-invalid" : "form-control maiusculas")]) }}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label id="nr_conselho" for="nr_conselho">Nº de inscrição</label>
                        {{ Form::text('nr_conselho',(isset($profissional['nr_conselho']) ? $profissional['nr_conselho'] : "") ,["maxlength" => "60", "id" => "nr_conselho", 'class'=> ($errors->has("nr_conselho") ? "form-control is-invalid" : "form-control maiusculas")]) }}
                    </div>
                </div>
            </div>
        </div>
        <div class="panel-footer fixed-panel-footer" >
            @if((session()->get('recurso.profissionais-editar')))
                {{ Form::submit('Salvar',['class'=>"btn btn-success pull-right"]) }}
            @endif
        </div>

    </div>
    {{ Form::close() }}
    @include('atendimentos/modal-pesquisa')
    @include('pessoas.modal-pessoas')
@endsection

