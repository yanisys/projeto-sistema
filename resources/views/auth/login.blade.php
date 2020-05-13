@extends('layouts.app')

@section('content')
    <div class="container">
        <header><img src="{{ asset('public/images/logo.png') }}" alt="Nicola - Planos de Saúde" class="img-responsive" /></header>
        <div class="row">
            <div class="col-sm-4 col-sm-offset-4 border">
                <form class='form-signin' method="POST" action="{{ route('login') }}" aria-label="{{ __('Login') }}">
                    @csrf
                    <div class="form-group">
                        <input type="text" id='email' name='email' class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" placeholder='Login' value="{{ old('email') }}" required autofocus >
                    </div>
                    <div class="form-group">
                        <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" placeholder='Senha' name="password" required>
                        <a class="btn btn-link" href="{{ route('password.request') }}">Esqueceu a sua senha?</a>
                    </div>
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    {{ $error }}
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="form-group row">
                        <div class="col-md-6 offset-md-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remember">
                                    Lembrar de mim
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row mb-0">
                        <div class="col-md-8 offset-md-4">
                            <button type="submit" class="btn btn-primary">
                                Entrar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection