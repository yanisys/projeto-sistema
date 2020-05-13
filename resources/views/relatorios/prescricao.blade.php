@extends('layouts.relatorio')
@section('css')
    <link rel="stylesheet" href="{{ asset('public/css/relatorios/prescricao.css') }}">
@endsection
@section('header')
    <div class="font-12px">
        <h3>{{ session('nm_estabelecimento')}}</h3>
        <div style="text-align: left">
            <h4>IDENTIFICAÇÃO DO EMITENTE</h4>
            <b>{{ $medico->nm_medico }}</b><br>
            <b>{{ $medico->nm_ocupacao ." | ".$medico->conselho." ".$medico->nr_conselho}}</b><br>
            {{ $estabelecimento->endereco. " ".$estabelecimento->endereco_nro ." | ".$estabelecimento->bairro}}<br>
            {{ $estabelecimento->localidade." | ".$estabelecimento->uf}}<br>
            {{(isset($estabelecimento->nr_fone1) && $estabelecimento->nr_fone1 !== "") ? "Fone: ".$estabelecimento->nr_fone1." - " : ""}}
            {{(isset($estabelecimento->ds_email) && $estabelecimento->ds_email !== "") ? "E-mail: ".$estabelecimento->ds_email : ""}}
        </div>
        <br><h3>{{ $paciente->nm_paciente }}</h3><br>
        <b>{{$titulo}}</b>
    </div>
    <hr>
@endsection
@section('conteudo')
    <div class="pagina">
        @foreach($status as $st)
            @if($st=='C')
                <table class="pure-table-bordered cinza" border="0">
            @else
                <table class="pure-table-bordered" border="0">
            @endif
            @if(isset($prescricao_dieta))
                @foreach($prescricao_dieta as $key=>$pd)
                    @if($st == $pd->status)
                        @if($key===0)
                            <tr><th>Dieta</th></tr>
                        @endif
                        {{$contador++}}
                        <tr>
                            <td style="padding-left: 20px; width: 500px;">{{$contador.") ".arrayPadrao('dieta')[$pd->dieta]." - ".arrayPadrao('via')[$pd->via_dieta].(isset($pd->descricao_dieta) ? " - Obs: ".$pd->descricao_dieta : "")}}</td>
                            <td style="width: 175px">{{$pd->aprazamento}}<br><br></td>
                        </tr>
                    @endif
                @endforeach
            @endif
            @if(isset($prescricao_csv))
                @foreach($prescricao_csv as $key=>$pc)
                    @if($st == $pc->status)
                        @if($key===0)
                            <tr><th>CSV</th></tr>
                        @endif
                        {{$contador++}}
                        <tr>
                            <td style="padding-left: 20px; width: 500px;">{{$contador.") De ".$pc->intervalo_csv." em ".$pc->intervalo_csv." ".($pc->intervalo_csv == 1 ? arrayPadrao('periodo')[$pc->tp_intervalo_csv] : arrayPadrao('periodo_plural')[$pc->tp_intervalo_csv]).(isset($pc->intervalo_csv) ? " - Obs: ".$pc->descricao_csv : "")}}</td>
                            <td style="width: 175px">
                            @if(isset($pc->aprazamento))
                                 {{$pd->aprazamento}}
                            @endif
                            </td>
                        </tr>
                    @endif
                @endforeach
            @endif
            @if(isset($prescricao_outros_cuidados))
                @foreach($prescricao_outros_cuidados as $key=>$poc)
                    @if($st == $poc->status)
                        @if($key===0)
                            <tr><th>Outros Cuidados</th></tr>
                        @endif
                        {{$contador++}}
                        <tr>
                            <td style="padding-left: 20px; width: 500px;">{{$contador.") ".arrayPadrao('posicoes_enfermagem')[$poc->posicao].(isset($poc->descricao_posicao) ? " - Obs: ".$poc->descricao_posicao : "")}}</td>
                        </tr>
                    @endif
                @endforeach
            @endif
            @if(isset($prescricao_oxigenoterapia))
                @foreach($prescricao_oxigenoterapia as $key=>$po)
                    @if($st == $po->status)
                        @if($key===0)
                            <tr><th>Oxigenoterapia</th></tr>
                        @endif
                        {{$contador++}}
                        <tr>
                            <td style="padding-left: 20px; width: 500px;">{{$contador.") ".$po->qtde_oxigenio." L/min - ".arrayPadrao('administracao_oxigenio')[$po->administracao_oxigenio].(isset($po->descricao_oxigenio) ? " - Obs: ".$po->descricao_oxigenio : "")}}</td>
                            <td style="width: 175px">{{$po->aprazamento}}<br><br></td>
                        </tr>
                    @endif
                @endforeach
            @endif
            @if(isset($prescricao_medicacao))
                @foreach($prescricao_medicacao as $key=>$pm)
                    @if($st == $pm->status)
                        @if($key===0)
                            <tr><th>Medicação</th></tr>
                        @endif
                        {{$contador++}}
                        <tr>
                            <td style="padding-left: 20px; border-bottom: none">{{$contador.") ".$pm->nm_produto." - ".$pm->dose." ".$pm->abreviacao.", ".arrayPadrao('via')[$pm->tp_via].", de ".$pm->intervalo." em ".$pm->intervalo." ".($pm->intervalo > 1 ? arrayPadrao('periodo_plural')[$pm->tp_intervalo] : arrayPadrao('periodo')[$pm->tp_intervalo]).", durante ".$pm->prazo." ".($pm->prazo > 1 ? arrayPadrao('periodo_plural')[$pm->tp_prazo] : arrayPadrao('periodo')[$pm->tp_prazo])}}</td>
                            <td style="width: 175px; border-bottom: none">
                                @if(isset($pm->aprazamento))
                                    {{$pm->aprazamento}}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" style="padding-left: 20px;">
                                @if(isset($pm->observacao_medicamento))
                                    Obs: {{$pm->observacao_medicamento}}
                                @endif
                            </td>
                        </tr>
                    @endif
                @endforeach
            @endif
            </table>
        @endforeach
    </div>
@endsection

@section('footer')

    <hr>
    <div>
        <b>MÉDICO RESPONSÁVEL: {{ $medico->nm_medico }} - {{$medico->conselho." ".$medico->nr_conselho}}</b><br>
        <small>Impresso em {{ date("d/m/Y - H:m:s") }}</small>
    </div>

@endsection