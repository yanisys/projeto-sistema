
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
            <p><a class="btn btn-primary" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="true" aria-controls="collapseExample">
                    Fechar
                </a>
            </p>
        </div>
    @endif

    {{ Form::open(['id' => 'cadastraoperador', 'class' => 'form-no-submit']) }}
    <div class="panel panel-primary">
        <div class="panel-heading">
            @if((session()->get('recurso.operadores-editar')))
                <a href="{{ route('operadores/cadastro') }}" class="btn btn-primary pull-right margin-top-10">Novo</a>
            @endif
            <h4>Dados do Operador</h4>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="id">Código<span style="color:red">*</span></label>
                        {{ Form::text('id',(isset($user['id']) ? $user['id'] : ""),["name" => "id", "maxlength" => "10", "id" => "id", "disabled"=>"disabled", 'class'=> ($errors->has("id") ? "form-control is-invalid" : "form-control")]) }}
                        {{ Form::hidden('id',(isset($user['id']) ? $user['id'] : ""),["name" => "id", "id" => "id"]) }}
                    </div>
                </div>
                <div class="col-md-2 pull-right">
                    <div class="form-group">
                        <label for="id_situacao">Situação</label>
                        {{  Form::select('id_situacao', ['A' => 'Ativo', 'I' => 'Inativo'], (isset($user['id_situacao']) ? $user['id_situacao'] : "A"),['class'=> ($errors->has("id_situacao") ? "form-control is-invalid" : "form-control"), 'id' => 'id_situacao']) }}
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-2">
                    <div class="input-group">
                        <label for="cd_pessoa">Pessoa<span style="color:red">*</span></label>
                        {{ Form::text('cd_pessoa',(isset($user['cd_pessoa']) ? $user['cd_pessoa'] : ""),["name" => "cd_pessoa", 'disabled', "maxlength" => "10", "id" => "cd_pessoa", 'class'=> ($errors->has("cd_pessoa") ? "form-control is-invalid" : "cd_pessoa form-control")]) }}
                        {{ Form::hidden('cd_pessoa',(isset($user['cd_pessoa']) ? $user['cd_pessoa'] : ""),["name" => "cd_pessoa", "id" => "cd_pessoa_disabled", "class"=>"cd_pessoa" ]) }}
                        <span class="input-group-btn">
                            <!-- <button class="btn btn-info margin-top-25" type="button" data-toggle="modal" data-target="#modal-pesquisa" id="open"><span class="fa fa-search"></span> </button> -->
                            <button class="btn-modal-pessoa btn btn-info margin-top-25 {{ verficaPermissaoBotao('recurso.pessoas-editar') }}" type="button"
                                {{ (isset($user['cd_pessoa']) ? 'data-modo=editar data-cd-pessoa='.$user['cd_pessoa'] : "data-modo=pesquisar") }}>
                                <span class="fa fa-search"></span>
                            </button>
                        </span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="nm_pessoa">Nome<span style="color:red">*</span></label>
                        {{ Form::text('nm_pessoa', (isset($user['nm_pessoa']) ? $user['nm_pessoa'] : "") ,["maxlength" => "60", "disabled", "id" => "nm_pessoa", 'class'=> ($errors->has("nm_pessoa") ? "form-control is-invalid" : "maiusculas form-control") ]) }}
                        {{ Form::hidden('nm_pessoa', (isset($user['nm_pessoa']) ? $user['nm_pessoa'] : "") ,["id" => "nm_pessoa_disabled" ]) }}
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="Grupo">Grupo</label>
                        {{  Form::select('cd_grupo_op', $grupos, (isset($user['cd_grupo_op']) ? $user['cd_grupo_op'] : "USER"),['class'=> ($errors->has("cd_grupo_op") ? "form-control is-invalid" : "form-control"), 'id' => 'cd_grupo_op']) }}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label id="mail" for="email">E-Mail<span style="color:red">*</span></label>
                        {{ Form::text('email',(isset($user['email']) ? $user['email'] : "") ,["maxlength" => "60", "id" => "email", 'class'=> ($errors->has("email") ? "form-control is-invalid" : "form-control")]) }}
                    </div>
                </div>
                @if (empty($user['id']) )
                    <div class="col-md-3">
                        <div class="form-group">
                            <label id="password" for="password">Senha<span style="color:red">*</span></label>
                            {{ Form::password('password',["maxlength" => "20", "id" => "password", 'class'=> ($errors->has("password") ? "form-control is-invalid" : "form-control")]) }}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label id="password-confirm" for="password-confirm">Confirmar Senha<span style="color:red">*</span></label>
                            {{ Form::password('password_confirmation',["maxlength" => "20", "id" => "password-confirm", 'class'=> ($errors->has("password_confirmation") ? "form-control is-invalid" : "form-control")]) }}
                        </div>
                    </div>
                @endif
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <div class="panel panel-default">
                            <div class="panel-heading"><h4>Selecione os estabelecimentos permitidos para o operador</h4></div>
                            <div class="panel-body">
                                @foreach($estabelecimentos as $e)
                                     <?php $existe = false;
                                     if(isset($estabelecimentos_permitidos)) {
                                          if(in_array($e->cd_estabelecimento,$estabelecimentos_permitidos)){
                                          $existe = true;
                                          }
                                     }?>
                                <div class="col-md-4">
                                    <input id='checkbox-{{ $e->cd_estabelecimento }}' type="checkbox" name='checkbox-{{ $e->cd_estabelecimento }}' value='{{ $e->cd_estabelecimento }}'{{($existe) ? ' checked' : ''}}/>
                                    <label for='checkbox-{{ $e->cd_estabelecimento }}'>{{ $e->nm_estabelecimento }}</label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel-footer fixed-panel-footer" >
            @if((session()->get('recurso.operadores-editar')))
                {{ Form::submit('Salvar',['class'=>"btn btn-success pull-right"]) }}
            @endif
        </div>
    </div>
    {{ Form::close() }}

    @include('pessoas.modal-pessoas')
@endsection

