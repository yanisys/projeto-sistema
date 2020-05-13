
@extends('layouts.relatorio')

@section('css')
    <link rel="stylesheet" href="{{ asset('public/css/relatorios/prontuario_v1.0.css') }}">
@endsection

@section('header')
    <div class="font-12px">
        <h3>{{ session('nm_estabelecimento').' ('.session('cnes_estabelecimento').')' }}</h3>
        @if(isset($dados[0]))
            <h3>COMPETÊNCIA - {{arrayPadrao('mes')[substr($dados[0]['competencia'],4,6)]."/".substr($dados[0]['competencia'],0,4)}}</h3>
        @endif
    </div>
    <hr>
@endsection

@section('conteudo')
    <div class="pagina">
        <div class="div-titulo">Relatório BPA</div>
        <table width="100%" class="pure-table" border="0">
            <tr>
                <td><b>Ocupação</b></td>
                <td><b>Procedimento</b></td>
                <td><b>Quantidade</b></td>
            </tr>
            @if(isset($dados[0]))
                @foreach($dados as $d)
                <tr>
                    <td>{{$d['cbo'] ." - ". $d['nm_ocupacao']}}</td>
                    <td>{{$d['cd_procedimento'] ." - ". $d['nm_procedimento']}}</td>
                    <td>{{$d['quantidade']}}</td>
                </tr>
                @endforeach
            @endif
        </table>
    </div>
@endsection

@section('footer')
    <hr>
    <small>Impresso em {{ date("d/m/Y - H:m:s") }}</small>
@endsection