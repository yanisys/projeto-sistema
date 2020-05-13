
@extends('layouts.relatorio')

@section('css')
    <link rel="stylesheet" href="{{ asset('public/css/relatorios/prontuario_v1.0.css') }}">
@endsection

@section('header')
    <div class="font-12px">
        <h3>{{ session('nm_estabelecimento').' ('.session('cnes_estabelecimento').')' }}</h3>
    </div>
    <hr>
@endsection

@section('conteudo')
    <div class="pagina">
        <div class="div-titulo">Relatório de estoque</div>
        <table width="100%" class="pure-table" border="0">
            <tr>
                <td><b>Produto</b></td>
                @if(isset($estoque[0]->nm_sala))
                    <td><b>Local de estoque</b></td>
                @endif
                @if(isset($estoque[0]->lote))
                    <td><b>Lote</b></td>
                @endif
                @if(isset($estoque[0]->dt_validade))
                <td><b>Validade</b></td>
                @endif
                <td><b>Quantidade</b></td>
            </tr>
            @if(isset($estoque[0]))
                @foreach($estoque as $e)
                    @if($e->quantidade > 0)
                    <tr>
                        <td>{{$e->nm_produto.(isset($e->ds_produto) ? " - " : "").$e->ds_produto}}</td>
                        @if(isset($e->nm_sala))
                            <td>{{$e->nm_sala}}</td>
                        @endif
                        @if(isset($e->lote))
                            <td>{{$e->lote}}</td>
                        @endif
                        @if(isset($estoque[0]->dt_validade))
                            <td>{{isset($e->dt_validade) ? formata_data($e->dt_validade) : ""}}</td>
                        @endif
                        <td>{{$e->quantidade}}</td>
                    </tr>
                    @endif
                @endforeach
            @endif
        </table>
    </div>
@endsection

@section('footer')
    <hr>
    <small>Impresso em {{ date("d/m/Y - H:m:s") }}</small>
@endsection