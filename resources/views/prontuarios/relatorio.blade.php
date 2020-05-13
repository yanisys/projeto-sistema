
@extends('layouts.relatorio')

@section('css')
    <link rel="stylesheet" href="{{ asset('public/css/relatorios/prontuario_v1.0.css') }}">
@endsection

@section('header')


@endsection

@section('conteudo')
    <div class="pagina">
        <div class="div-titulo text-center" style="font-size: 16px;">{{ session('nm_estabelecimento').' ('.session('cnes_estabelecimento').')' }} - Relatório de prontuários
        </div>
        <div style="font-size: 12px">
            <br>{{ "Período: " . $filtros['inicio'] . " a " . $filtros['final'] }}
        </div>
        <table width="100%" class="pure-table" border="0">
            <tr>
                <td><b>Data/ Hora</b></td>
                <td><b>Paciente</b></td>
                <td><b>Sexo</b></td>
                <td><b>Dt. nasc</b></td>
                <td><b>Médico</b></td>
                <td><b>Cid</b></td>
                <td><b>Motivo alta</b></td>
            </tr>
            @if(!isset($lista) || $lista->IsEmpty())
                <tr><td colspan="6">Sem resultados</td></tr>
            @else
                @foreach($lista as $prontuario)
                    <tr>
                        <td>{{ formata_data_hora($prontuario->created_at) }}</td>
                        <td>{{ $prontuario->nm_pessoa }}</td>
                        <td class='text-center'>{{ $prontuario->id_sexo }}</td>
                        <td>{{ isset($prontuario->dt_nasc) ? formata_data($prontuario->dt_nasc) : '' }}</td>
                        <td>{{ $prontuario->nm_medico }}</td>
                        <td>{{ (isset($prontuario->nm_cid)) ? $prontuario->nm_cid : "" }}</td>
                        <td>{{ (isset($prontuario->motivo_alta) && $prontuario->motivo_alta !== 0) ? arrayPadrao('motivo_alta')[$prontuario->motivo_alta] : '' }}</td>
                    </tr>
                @endforeach
                    <tr>
                        <td colspan="7">Total: {{ count($lista) }}</td>
                    </tr>
            @endif
        </table>
    </div>
@endsection

@section('footer')
    <hr>
    <small>Impresso em {{ date("d/m/Y - H:m:s") }}</small>
@endsection
