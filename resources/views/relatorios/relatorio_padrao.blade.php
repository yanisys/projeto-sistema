
@extends('layouts.relatorio')

@section('css')
    <link rel="stylesheet" href="{{ asset('public/css/relatorios/prontuario_v1.0.css') }}">
@endsection

@section('header')
    <div class="font-12px">
        <h3>{{ 'PREFEITURA MUNICIPAL DE '.session('cidade_estabelecimento') }}</h3>
        <h3>{{ session('nm_estabelecimento').' ('.session('cnes_estabelecimento').')' }}</h3>
    </div>
    <h2>{{ (isset($titulo) ? $titulo : 'Sem Título') }}</h2>
    <hr>
@endsection

@section('conteudo')
    <div class="pagina">
    @foreach ($relatorio as $rel)
        @if($rel->quebrar_pagina && $loop->iteration != 1)
            </div>
            <div class="pagina">
        @endif

        <!-- INICIALIZANDO VARIAVEIS -->
        @php
            $totalizar = null;
            $limite_campos = null;
            foreach ($rel->totalizar as $tot) {
                $totalizar[$tot] = true;
                $totais[$tot] = 0;
            }
            foreach ($rel->limite_campos as $tot){
                $limite_campos[$tot] = true;
                $limite_totais[$tot] = 0;
            }
        @endphp

        @if(!empty($rel->titulo))
            <h2>{{ $rel->titulo }}</h2>
        @endif

        <!-- TABELA DOS DADOS -->
        <table width="100%" class="pure-table" border="0">
            @foreach ($rel->dados as $dados)
                <!-- CABECALHO -->
                @if($loop->iteration == 1)
                    <tr>
                    @foreach ($dados as $key => $value)
                        <td><b>{{ (($key !== $loop->iteration-1) ? title_case($key) : '') }} </b></td>
                    @endforeach
                    </tr>
                @endif
                <!-- LIMITE DE LINHAS -->
                @if ($rel->limite > 0 && $loop->iteration > $rel->limite)
                    @foreach ($dados as $key => $value)
                        @isset($limite_campos[$key])
                            @php($limite_totais[$key] += $value)
                        @endisset
                    @endforeach
                @else
                    <tr>
                    <!-- CELULAS DOS DADOS -->
                    @foreach ($dados as $key => $value)
                        <td>{{ $value }} </td>
                        @isset($totalizar[$key])
                            @php($totais[$key] += $value)
                        @endisset
                    @endforeach
                    </tr>
                @endif

                <!-- RODAPE - TOTALIZANDO LINHAS EXCEDENTE DO LIMITE-->
                @if($loop->last && isset($limite_campos) && $loop->iteration > $rel->limite )
                    <tr>
                    @foreach ($dados as $key => $value)
                        @if($loop->iteration == 1 && $rel->limite_nome != '')
                            <td>Outros</td>
                        @elseif(isset($limite_campos[$key]))
                            <td>{{ round($limite_totais[$key],2) }}</td>
                        @else
                            <td></td>
                        @endif
                    @endforeach
                    </tr>
                @endif
                <!-- RODAPE - TOTALIZANDO COLUNAS-->
                @if($loop->last && isset($totalizar) )
                    <tr>
                    @foreach ($dados as $key => $value)
                        @if($loop->iteration == 1 && $rel->totalizar_nome != '')
                            <td><b>Total</b></td>
                        @elseif(isset($totalizar[$key]))
                            @if(isset($limite_totais[$key]))
                                    @php($totais[$key] += $limite_totais[$key])
                                    @php($limite_totais[$key] = 0)
                            @endif

                            <td><b>{{ round($totais[$key],2) }}</b></td>
                        @else
                            <td></td>
                        @endif
                    @endforeach
                    </tr>
                @endif

            @endforeach
        </table>
    @endforeach
    </div>

@endsection

@section('footer')
    <hr>
    <small>Impresso em {{ date("d/m/Y - H:m:s") }}</small>
@endsection