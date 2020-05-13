@extends('layouts.app')

@section('content')
    @if (!empty(Session::get('status')))
        <script>
            swal({
                type: 'success',
                title: '{{ Session::get('status') }}',
                showConfirmButton: false,
                timer: 1500
            })
        </script>
    @endif
    @if (!empty(Session::get('confirmation')))
        <script>
            swal({
                type: 'warning',
                title: 'Atenção',
                html: '{!!  Session::get('confirmation') !!}',
                showConfirmButton: true
            })
        </script>
    @endif

    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a href="{{ route('home') }}">
                    <img src="{{ asset('public/images/logo3.png') }}" alt="" class="img-responsive" />
                </a>
            </div>
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-center" style="padding-left: 50px">
                    <h3>{{ session()->get('nm_estabelecimento')." - ".(!empty($headerText) ? $headerText : '') }}</h3>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{{ session('nome') }} <span class="caret"></span></a>
                        <ul class="dropdown-menu">

                            <li><a href="{{ route('operadores/meus-dados')}}">Meus Dados</a></li>
                            <li><a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Sair</a></li>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container-fluid recuo-top">
        <div class="row">
            @if(isset($breadcrumbs))
                <h4 class="breadcrumb">
                    @foreach ($breadcrumbs as $crumbs)
                        @if ($loop->last)
                            <li class="breadcrumb-item ">{{ $crumbs['titulo'] }}</li>
                            @isset($extracrumbinfo)
                                <li class="breadcrumb-item ">{{ $extracrumbinfo }}</li>
                            @endisset
                        @else
                            <li class="breadcrumb-item "><a href="{{ $crumbs['href'] }}">{{ $crumbs['titulo'] }}</a></li>
                        @endif
                    @endforeach
                </h4>
            @endif

            <div class="col-sm-offset-2 col-sm-6">
                @yield('conteudo-small')
            </div>

            <div class="col-sm-offset-1 col-sm-10">
                @yield('conteudo')
            </div>

            <div class="col-sm-12">
                @yield('conteudo-full')
            </div>
        </div>
    </div>
@endsection

@section('js_variaveis')
    <script>
        var token = '{{ csrf_token() }}';
        var dir = '{{  env('APP_DIR', '/') }}';
    </script>
    <script src="{{ js_versionado('app.js') }}" defer></script>
@endsection