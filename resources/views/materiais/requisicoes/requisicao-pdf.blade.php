
@extends('layouts.relatorio')

@section('css')
    <link rel="stylesheet" href="{{ asset('public/css/relatorios/requisicao_v1.0.css') }}">
@endsection

@section('header')
    <div class="font-12px" style="margin-top: 0">
        <h3>{{ session('nm_estabelecimento').' ('.session('cnes_estabelecimento').')' }}</h3>
    </div>
    <div class="font-12px">
        <h3>Comprovante de entrega de materiais/ medicamentos</h3>
        @if(isset($requisicao))
            <b>Data da requisição:</b> {{ formata_data_hora($requisicao->created_at) }} <br>
            <b>Nº requisição:</b> {{ $requisicao->cd_requisicao }} <br>
            <b>Responsável solicitação:</b> {{ $requisicao->nm_pessoa }} <br>
            <b>Destino:</b> {{ $requisicao->nm_sala_origem }} <br>
            <b>Estoque:</b> {{ $requisicao->nm_sala_destino}} <br>
            <b>Nome da Mãe:</b> {{ $requisicao->nm_mae }} <br>
            <b>Paciente:</b> {{ $requisicao->nm_paciente }} <br>
            <hr>
        @endif
    </div>
@endsection
@section('conteudo')
    <div class="pagina">
        <table width="100%" class="pure-table maiusculas" border="0">
            <tr>
                <th colspan="3" class="text-center"><h3>Solicitação</h3></th>
                <th colspan="3" class="text-center"><h3>atendimento</h3></th>
            </tr>
            <tr class="height15" style="font-size: 8px">
                <td width="35%"><b>Produto</b></td>
                <td width="15%"><b>Un. medida</b></td>
                <td width="15%" style="border-right: solid 1px #cbcbcb;"><b>Qtde</b></td>
                <td width="15%"><b>Qtde</b></td>
                <td width="15%"><b>Lote</b></td>
                <td width="15%"><b>Validade</b></td>
            </tr>
            @if(isset($requisicao_produto))
                {{$conta_atendimentos = 0}}
                @foreach($requisicao_produto as $rp)
                    <tr>
                        <td height="3%"><b>{{$rp->cd_produto." - ".$rp->nm_produto." - ".$rp->ds_produto}}</b></td>
                        <td><b>{{($rp->fracionamento === 1) ? $rp->nm_unidade_medida : $rp->nm_unidade_comercial}}</b></td>
                        <td style="border-right: solid 1px #cbcbcb;">{{$rp->quantidade}}</td>
                        <td>{{$rp->quantidade_atendimento}}</td>
                        <td>{{$rp->lote}}</td>
                        <td>{{formata_data($rp->dt_validade)}}</td>
                    </tr>
                    {{isset($rp->quantidade_atendimento) ? $conta_atendimentos++ : $conta_atendimentos+=0}}
                @endforeach
                    <tr class="height15" style="font-size: 8px">
                        <td colspan="3" style="border-right: solid 1px #cbcbcb;"><b>Total de itens solicitados: {{count($requisicao_produto)}}</b></td>
                        <td colspan="3"><b>Total de itens entregues: {{$conta_atendimentos}}</b></td>
                    </tr>
            @endif
        </table>
    </div>
@endsection

@section('footer')
    <hr>
    <table width="100%" >
        <tr class="height15">
            <td width="50%" class="text-center">
                <h3>
                _________________________________<br>
                    Responsável pelo atendimento
                </h3>
            </td>
            <td width="50%" class="text-center">
                <h3>
                    _________________________________<br>
                    Recebedor/ Conferente
                </h3>
            </td>
        </tr>
        <tr><td class="text-right" colspan="2"><small>Impresso em {{ date("d/m/Y - H:m:s") }}</small></td></tr>
        <tr><td></td></tr>

    </table>

@endsection