@extends('layouts.receituario')

@section('css')
    <link rel="stylesheet" href="{{ asset('public/css/relatorios/receituario_especial_v1.0.css') }}">
@endsection

@section('header')
    @for($x=0;$x<2;$x++)
    <div class="header" style="width: 49%; display: inline-block; font-size: 10px">
        <h3>{{ session('nm_estabelecimento')}}
            <br>RECEITUÁRIO DE CONTROLE ESPECIAL</h3>
        <div style="text-align: left">
            <h4>IDENTIFICAÇÃO DO EMITENTE</h4>
            <b>{{ $medico->nm_medico }}</b><br>
            <b>{{ $medico->nm_ocupacao ." | ".$medico->conselho." ".$medico->nr_conselho}}</b><br>
            {{ $estabelecimento->endereco. " ".$estabelecimento->endereco_nro ." | ".$estabelecimento->bairro}}<br>
            {{ $estabelecimento->localidade." | ".$estabelecimento->uf}}<br>
            {{(isset($estabelecimento->nr_fone1) && $estabelecimento->nr_fone1 !== "") ? "Fone: ".$estabelecimento->nr_fone1." - " : ""}}
            {{(isset($estabelecimento->ds_email) && $estabelecimento->ds_email !== "") ? "E-mail: ".$estabelecimento->ds_email : ""}}
            <h4>IDENTIFICAÇÃO DO PACIENTE</h4>
            <b>{{ $paciente->nm_paciente }}</b><br>
            {{ $paciente->endereco. " ".$paciente->endereco_nro ." | ".$paciente->bairro." | ".$paciente->localidade." | ".$paciente->uf}}<br>
            PRESCRIÇÃO: {{$titulo}}
        </div>
    </div>
    @endfor
@endsection

@section('conteudo')
    <div class="pagina" style="padding-top: 10px">
        <table width="100%" class="pure-table" style="font-size: 10px" border="0">
        @if(isset($receita_medicacao))
            @foreach($receita_medicacao as $key => $r)
                <tr>
                    <td width="50%">{{($key + 1).". ".$r->nm_produto."-----"}}</td>
                    <td width="50%">{{($key + 1).". ".$r->nm_produto."-----"}}</td>
                </tr>
                <tr>
                    <td width="50%" style="padding-left: 20px">{{$r->dose." ".$r->abreviacao.", ".arrayPadrao('via')[$r->tp_via].", de ".$r->intervalo." em ".$r->intervalo." ".($r->intervalo > 1 ? arrayPadrao('periodo_plural')[$r->tp_intervalo] : arrayPadrao('periodo')[$r->tp_intervalo]).", durante ".$r->prazo." ".($r->prazo > 1 ? arrayPadrao('periodo_plural')[$r->tp_prazo] : arrayPadrao('periodo')[$r->tp_prazo])}}</td>
                    <td width="50%" style="padding-left: 20px">{{$r->dose." ".$r->abreviacao.", ".arrayPadrao('via')[$r->tp_via].", de ".$r->intervalo." em ".$r->intervalo." ".($r->intervalo > 1 ? arrayPadrao('periodo_plural')[$r->tp_intervalo] : arrayPadrao('periodo')[$r->tp_intervalo]).", durante ".$r->prazo." ".($r->prazo > 1 ? arrayPadrao('periodo_plural')[$r->tp_prazo] : arrayPadrao('periodo')[$r->tp_prazo])}}</td>
                </tr>
                    <tr>
                        <td width="50%"  style="padding-left: 20px">
                            @if(isset($r->observacao_medicamento))
                                Obs: {{$r->observacao_medicamento}}
                            @endif
                        </td>
                        <td width="50%"  style="padding-left: 20px">
                            @if(isset($r->observacao_medicamento))
                                Obs: {{$r->observacao_medicamento}}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td width="50%"  style="padding-left: 20px">
                            @if(isset($r->aprazamento))
                                Horários: {{$r->aprazamento}}
                            @endif
                        </td>
                        <td width="50%"  style="padding-left: 20px">
                            @if(isset($r->aprazamento))
                                Horários: {{$r->aprazamento}}
                            @endif
                        </td>
                    </tr>
                <tr><td width="50%"></td><td width="50%"></td></tr>
            @endforeach
        @endif
        </table>
    </div>
@endsection

@section('footer')
    @for($x=0;$x<2;$x++)
    <div class="footer" style="width: 49%; display: inline-block; padding-top: 0px; font-size: 9px; text-align: left">
        <div class="footer-box">
            <h4 style="padding: 0; margin: 0">IDENTIFICAÇÃO DO COMPRADOR</h4>
            <p>Nome:_______________________________</p>
            <p>Ident.:_________________ Emissor:_______</p>
            <p>End.:________________________________ </p>
            <p>Cidade:______________________ UF:_____ </p>
            <p>Telefone:_____________________</p>
        </div>
        <div class="footer-box">
            <h4 style="padding: 0; margin: 0">IDENTIFICAÇÃO DO FORNECEDOR</h4>
            <p style="padding-top: 20px">______________________________</p>
            <p style="padding-left: 20px;">Assinatura do farmacêutico</p><br>
            <p style="padding-left: 20px; padding-top: 10px">Data:____/_____/________</p>
        </div>
    </div>
    @endfor
@endsection