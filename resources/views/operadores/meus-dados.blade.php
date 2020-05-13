
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
                </a></p>
        </div>
    @endif

    {{ Form::open(['id' => 'meus-dados']) }}
    <div class="panel panel-primary">
        <div class="panel-heading">Meus dados</div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="id">Código de Usuário</label>
                        {{ Form::text('id',(isset($user['id']) ? $user['id'] : ""),[ "id" => "id", "disabled", 'class'=> "form-control"]) }}
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="cd_pessoa">Pessoa</label>
                        {{ Form::text('cd_pessoa',(isset($user['cd_pessoa']) ? $user['cd_pessoa'] : ""),[ 'disabled', "id" => "cd_pessoa",  'class'=> "form-control"]) }}
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="nm_pessoa">Nome</label>
                        {{ Form::text('nm_pessoa', (isset($user['nm_pessoa']) ? $user['nm_pessoa'] : "") ,["disabled", "id" => "nm_pessoa", 'class'=> "form-control" ]) }}
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="Grupo">Grupo</label>
                        {{ Form::text('cd_grupo_op', (isset($user['nm_grupo_op']) ? $user['nm_grupo_op'] : "") ,[ "disabled", "id" => "nm_grupo_op", 'class'=> "form-control" ]) }}
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div class="panel panel-primary">
        <div class="panel-heading">Alterar E-mail</div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-6">
                    {{ Form::checkbox('trocar_email','trocar_email',(isset($_POST['trocar_email']) ? $_POST['trocar_email'] : false),["class" => 'form-check-input']) }}
                    <label class="form-check-label" for="trocar_email">Quero alterar meu E-Mail</label>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label id="mail" for="email">E-Mail de login</label>
                        {{ Form::text('email',(isset($user['email']) ? $user['email'] : "") ,["maxlength" => "60", "id" => "email", 'class'=> ($errors->has("email") ? "form-control is-invalid" : "form-control")]) }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="panel panel-primary">
        <div class="panel-heading">Alterar Senha</div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-6">
                    {{ Form::checkbox('trocar_senha','trocar_senha',(isset($_POST['trocar_senha']) ? $_POST['trocar_senha'] : false),["class" => 'form-check-input']) }}
                    <label class="form-check-label" for="trocar_senha">Quero alterar minha senha</label>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label id="password" for="password">Nova Senha</label>
                        {{ Form::password('nova_senha',["maxlength" => "20", "id" => "nova_senha", 'class'=> ($errors->has("nova_senha") ? "form-control is-invalid" : "form-control")]) }}
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label id="password-confirm" for="password-confirm">Confirmar Nova Senha</label>
                        {{ Form::password('nova_senha_confirmation',["maxlength" => "20", "id" => "nova_senha-confirm", 'class'=> ($errors->has("NOVA_SENHA_confirmation") ? "form-control is-invalid" : "form-control")]) }}
                    </div>
                </div>
            </div>

        </div>

    </div>
    {{ Form::submit('Salvar',['class'=>"btn btn-success"]) }}
    {{ Form::close() }}

    @if (!empty(Session::get('status')))
        <div class="alert alert-info" id="msg">
            {{ Session::get('status') }}
        </div>
    @endif
@endsection
