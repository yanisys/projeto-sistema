@extends('layouts.receituario')

@section('css')
    <link rel="stylesheet" href="{{ asset('public/css/relatorios/receituario_v1.0.css') }}">
@endsection

@section('header')
    @for($x=0;$x<2;$x++)
        <div class="header" style="width: 49%; display: inline-block; font-size: 10px">
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
            PRESCRIÇÃO: {{$titulo}}
        </div>
    @endfor
@endsection

@section('conteudo')
    <div class="pagina maiusculas" style="padding-top: 10px">
        <table width="100%" class="pure-table" style="font-size: 10px" border="0">
            @if(isset($receita_exame_laboratorial))
                @foreach($receita_exame_laboratorial as $key => $r)
                    <tr>
                        <td width="50%">{{($key+1).". ".arrayPadrao('exames_laboratoriais')[$r->cd_exame_laboratorial]}}</td>
                        <td width="50%">{{($key+1).". ".arrayPadrao('exames_laboratoriais')[$r->cd_exame_laboratorial]}}</td>
                    </tr>
                    <tr>
                        <td width="50%" style="padding-left: 20px">
                            @if(isset($r->observacao_exame_laboratorial))
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{"Obs: ".$r->observacao_exame_laboratorial}}
                            @endif
                        </td>
                        <td width="50%" style="padding-left: 20px">
                            @if(isset($r->observacao_exame_laboratorial))
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{"Obs: ".$r->observacao_exame_laboratorial}}
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
        <div class="footer" style="width: 49%; display: inline-block; font-size: 10px">
            <hr>
            <small>Impresso em {{ date("d/m/Y - H:m:s") }}</small>
            <br>
        </div>
    @endfor
@endsection