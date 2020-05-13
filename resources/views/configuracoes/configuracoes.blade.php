
@extends('layouts.default')

@section('conteudo')
    <div class="row">
        @if((session()->get('recurso.configuracoes/alergias')))
            <!-- Tabela de Alergias -->
                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-6 text-center">
                    <a href="{{ route('configuracoes/alergias/lista') }}" class="iconesInicio "><span class="fas fa-allergies fa-5x"> </span>
                        <h4>Alergias</h4></a>
                </div>
        @endif
        @if((session()->get('recurso.configuracoes/origem')))
            <!-- Tabela de Origens -->
                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-6 text-center">
                    <a href="{{ route('configuracoes/origem/lista') }}" class="iconesInicio "><span class="fas fa-city fa-5x"> </span>
                        <h4>Origens</h4></a>
                </div>
        @endif
        @if((session()->get('recurso.configuracoes/procedimentos')))
            <!-- Tabela de Origens -->
                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-6 text-center">
                    <a href="{{ route('configuracoes/procedimentos/lista') }}" class="iconesInicio "><span class="fas fa-procedures fa-5x"> </span>
                        <h4>Procedimentos</h4></a>
                </div>
        @endif
        @if((session()->get('recurso.configuracoes/origem')))
            <!-- Tabela de Origens -->
                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-6 text-center">
                    <a href="{{ route('configuracoes/unidades-comerciais/lista') }}" class="iconesInicio "><span class="fas fa-fill fa-5x"> </span>
                        <h4>Unidades comerciais</h4></a>
                </div>
        @endif
        @if((session()->get('recurso.configuracoes/procedimentos')))
            <!-- Tabela de Origens -->
                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-6 text-center">
                    <a href="{{ route('configuracoes/unidades-medida/lista') }}" class="iconesInicio "><span class="fas fa-fill-drip fa-5x"> </span>
                        <h4>Unidades de medida</h4></a>
                </div>
        @endif
        @if((session()->get('recurso.configuracoes/procedimentos')))
            <!-- Tabela de Origens -->
                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-6 text-center">
                    <a href="{{ route('configuracoes/parametros/lista') }}" class="iconesInicio "><span class="fas fa-cogs fa-5x"> </span>
                        <h4>Parâmetros</h4></a>
                </div>
        @endif
    </div>
@endsection
