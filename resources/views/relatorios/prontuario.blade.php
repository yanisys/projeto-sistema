
@extends('layouts.relatorio')

@section('css')
    <link rel="stylesheet" href="{{ asset('public/css/relatorios/prontuario_v1.0.css') }}">
@endsection

@section('header')
        @if($estabelecimento->tp_estabelecimento === 'U')
            <div id="aviso">Esta conta será paga com recursos públicos</div>
        @endif
        <div class="font-12px">
            <h3>{{ 'PREFEITURA MUNICIPAL DE '.session('cidade_estabelecimento') }}</h3>
            <h3>{{ session('nm_estabelecimento').' ('.session('cnes_estabelecimento').')' }}</h3>
        </div>
    <h2>Consulta</h2>
    <hr>
@endsection

@section('conteudo')
    <div class="pagina">
        <div class="recuo font-10px" style="display: inline-block;">
            <b>Data do Atendimento:</b> {{ formata_data_hora($prontuario->prontuario_created_at) }}<br>
            <b>Prontuário N°:</b> {{ $prontuario->cd_prontuario }}<br>
            <b>Nome:</b> {{ $prontuario->nm_pessoa }} - {{ ( ($prontuario->id_sexo == 'M') ? 'Masculino' : 'Feminino') }} - {{ formata_data($prontuario->dt_nasc) }} ({{ calcula_idade($prontuario->dt_nasc) }})<br>
            <b>Cartão Nacional:</b> {{ $prontuario->cd_beneficiario }}<br>
            <b>Nome da Mãe:</b> {{ $prontuario->nm_mae }}<br>
        </div>
        <div class="foto-pessoa" style="display: inline-block;">
            <img src="{{(isset($l->nm_arquivo) ?  asset("storage/app/images/pessoas/".$prontuario->cd_pessoa."/principal/".$prontuario->cd_pessoa.'.png'): asset('public/images/branco.png'))}}">
        </div>
        <div class="div-titulo">Consulta de enfermagem</div>
        <table width="100%" class="pure-table" border="0">
            <tr>
                <td class="maiusculas" colspan="12">
                    <b>
                        Profissional:
                        {{ $prontuario->nm_ocupacao." ".$prontuario->nm_profissional."  -  ".$prontuario->conselho.": ".$prontuario->nr_conselho." - " }}
                        {{ (!empty($prontuario->created_at) ? formata_data_hora($prontuario->created_at) : '' ) }}
                    </b>
                </td>
            </tr>
            <tr>
                <td colspan="12"><b>Motivo da consulta(Queixa principal)</b></td>
            </tr>
            <tr>
                <td colspan="12">{{ $prontuario->avaliacao }}</td>
            </tr>
            <tr>
                <td colspan="2"><b>Cintura</b></td>
                <td colspan="2"><b>Quadril</b></td>
                <td colspan="2"><b>Índice Cintura/Quadril</b></td>
                <td colspan="2"><b>Peso</b></td>
                <td colspan="2"><b>Altura</b></td>
                <td colspan="2"><b>Massa corporal</b></td>
            </tr>
            <tr>
                <td colspan="2">{{ $prontuario->cintura }}</td>
                <td colspan="2">{{ $prontuario->quadril }}</td>
                <td colspan="2">{{ $prontuario->indice_cintura_quadril }}</td>
                <td colspan="2">{{ $prontuario->peso }}</td>
                <td colspan="2">{{ $prontuario->altura }}</td>
                <td colspan="2">{{ $prontuario->massa_corporal }}</td>
            </tr>
            <tr>
                <td colspan="2"><b>Pressão arterial</b></td>
                <td colspan="2"><b>Temperatura</b></td>
                <td colspan="2"><b>Freq. cardíaca/ pulso</b></td>
                <td colspan="2"><b>Freq. respiratória</b></td>
                <td colspan="2"><b>Estado nutricional</b></td>
                <td colspan="2"><b>Risco Associado Cintura</b></td>
            </tr>
            <tr>
                <td colspan="2">{{ $prontuario->pressao_arterial }}</td>
                <td colspan="2">{{ $prontuario->temperatura }}</td>
                <td colspan="2">{{ $prontuario->freq_cardiaca }}</td>
                <td colspan="2">{{ $prontuario->freq_respiratoria }}</td>
                <td colspan="2">{{ $prontuario->estado_nutricional }}</td>
                <td colspan="2">{{ $prontuario->risco_cintura }}</td>
            </tr>
            <tr>
                <td colspan="2"><b>Glicemia Capilar (mg/dil)</b></td>
                <td colspan="2"><b>Saturação</b></td>
                <td colspan="2"><b>Abertura ocular</b></td>
                <td colspan="2"><b>Resposta verbal</b></td>
                <td colspan="2"><b>Resposta motora</b></td>
                <td colspan="2"><b>Escala de Glasgow</b></td>

            </tr>
            <tr>
                <td colspan="2">{{ (!empty($prontuario->glicemia_capilar) ? $prontuario->glicemia_capilar : 'Não Avaliado' ) }}</td>
                <td colspan="2">{{ $prontuario->saturacao }}</td>
                <td colspan="2">{{ (!empty($prontuario->abertura_ocular) ? arrayPadrao('abertura_ocular')[$prontuario->abertura_ocular] : '')}}</td>
                <td colspan="2">{{ (!empty($prontuario->resposta_verbal) ? arrayPadrao('resposta_verbal')[$prontuario->resposta_verbal] : '')}}</td>
                <td colspan="2">{{ (!empty($prontuario->resposta_motora) ? arrayPadrao('resposta_motora')[$prontuario->resposta_motora] : '')}}</td>
                <td colspan="2">{{ (!empty($prontuario->escore_glasgow) ? escala_de_coma_glasgow($prontuario->escore_glasgow) : 'Não Avaliado' ) }}</td>
            </tr>
            <tr>
                <td colspan="4"><b>Exames apresentados</b></td>
                <td colspan="4"><b>Escala de dor</b></td>
                <td colspan="4"><b>Medicamentos em uso</b></td>
            </tr>
            <tr>
                <td colspan="4">{{ $prontuario->exames_apresentados }}</td>
                <td colspan="4">{{ ($prontuario->nivel_de_dor ) }}</td>
                <td colspan="4">
                    @foreach($medicamentos_em_uso as $k => $mu)
                        {{($k > 0) ? ', ': ''}}
                        {{$mu->descricao_medicamento}}
                    @endforeach
                </td>
            </tr>
            <tr>
                <td colspan="4"><b>Alergias</b></td>
                <td colspan="4"><b>História médica pregressa</b></td>
                <td colspan="4"><b>Cirurgias prévias</b></td>
            </tr>
            <tr>
                <td colspan="4">
                    @foreach($alergias as $k => $a)
                        {{($k > 0) ? ', ': ''}}
                        {{$a->nm_alergia}}
                    @endforeach
                </td>
                <td class="maiusculas" colspan="4">
                    @foreach($historia_medica as $k => $hm)
                        {{($k > 0) ? ', ': ''}}
                        {{$hm->nm_cid."(".$hm->cd_cid.")"}}
                    @endforeach
                </td>
                <td class="maiusculas" colspan="4">
                    @foreach($cirurgias_previas as $k => $cp)
                        {{($k > 0) ? ', ': ''}}
                        {{formata_data($cp->dt_cirurgia).": ".$cp->descricao_cirurgia}}
                    @endforeach
                </td>
            </tr>
            <tr>
                <td colspan="12"><b>Conduta</b></td>
            </tr>
            <tr>
                @if(isset($prontuario->classificacao))
                    <td colspan="12">{{($prontuario->classificacao <= 6 ? "CLASSIFICAÇÃO DE RISCO: " : ""). arrayPadrao('classificar_risco')[$prontuario->classificacao]}}
                        @endif
                        @if(!empty($prontuario->classificacao_nova))
                            (RECLASSIFICADO ÀS {{formata_hora($prontuario->hora_alteracao)}}
                            DE {{arrayPadrao('classificar_risco')[$prontuario->classificacao_anterior]}}
                            PARA {{arrayPadrao('classificar_risco')[$prontuario->classificacao_nova]}}.
                            {{ strtoupper($prontuario->motivo)}})
                        @endif
                    </td>
            </tr>
        </table>

        <div class="div-titulo">Consulta Médica</div>
        @if(isset($atendimento_medico))
        <table width="100%" class="pure-table" border="0">
            <tr>
                <td class="maiusculas" colspan="12">
                    <b>
                        Profissional:
                        {{ $atendimento_medico->nm_ocupacao." ".$atendimento_medico->nm_pessoa."  -  ".$atendimento_medico->conselho.": ".$atendimento_medico->nr_conselho." - " }}
                        {{ (!empty($atendimento_medico->created_at) ? formata_data_hora($atendimento_medico->created_at) : '' ) }}
                    </b>
                </td>
            </tr>
            <tr>
                <td colspan="12" style="padding-left: 20px;"><b>SUBJETIVO</b></td>
            </tr>
            <tr>
                <td colspan="12"><b>Motivo da consulta(Queixa principal)</b></td>
            </tr>
            <tr>
                <td colspan="12">
                @foreach($atendimento_subjetivo_descricao as $k => $asd)
                        {{($k > 0) ? ' - ': ''}}
                    {{ $asd->descricao_subjetivo}}
                @endforeach
                </td>
            </tr>
            <tr>
                <td colspan="12" style="padding-left: 20px;"><b>OBJETIVO</b></td>
            </tr>
            <tr>
                <td colspan="12"><b>Avaliação médica</b></td>
            </tr>
            <tr>
                <td colspan="12">
                    {{ $atendimento_medico->descricao_objetivo }}
                </td>
            </tr>
            <tr>
                <td colspan="12" style="padding-left: 20px;"><b>AVALIAÇÃO</b></td>
            </tr>
            <tr>
                <td colspan="12"><b>Descrição</b></td>
            </tr>
            <tr>
                <td colspan="12">
                    @foreach($avaliacao_descricao as $k => $ad)
                        {{($k > 0) ? ' - ': ''}}
                        {{ $ad->descricao_avaliacao}}
                    @endforeach
                </td>
            </tr>
            <tr>
                <td colspan="12"><b>Disgnóstico</b></td>
            </tr>
            <tr>
                <td colspan="2"><b>Tipo</b></td>
                <td colspan="2"><b>CID</b></td>
                <td colspan="2"><b>Acidente</b></td>
                <td colspan="2"><b>Data do Primeiro Sintoma</b></td>
                <td colspan="2"><b>Profissional</b></td>
                <td colspan="2"><b>Data/Hora</b></td>
            </tr>
            @foreach($atendimento_avaliacao_cid as $cid)
                <tr>
                    <td colspan="2">{{ ($cid->cid_principal == 'S' ? 'PRIMÁRIO' : 'SECUNDÁRIO')  }}</td>
                    <td colspan="2">{{ $cid->cd_cid }} - {{ $cid->nm_cid }}</td>
                    <td colspan="2">Trabalho:
                        @switch($cid->diagnostico_trabalho)
                            @case('S')
                            Sim
                            @break
                            @case('N')
                            Não
                            @break
                            @default
                            Não Informado
                        @endswitch
                        Trânsito:
                        @switch($cid->diagnostico_transito)
                            @case('S')
                            Sim
                            @break
                            @case('N')
                            Não
                            @break
                            @default
                            Não Informado
                        @endswitch </td>
                    <td colspan="2">{{ formata_data($cid->dt_primeiros_sintomas) }}</td>
                    <td colspan="2">{{ $cid->nm_ocupacao }} {{ $cid->nm_pessoa }} - {{ $cid->conselho.": ". $cid->nr_conselho}} </td>
                    <td colspan="2">{{ formata_data_hora($cid->created_at) }}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="12" style="padding-left: 20px;"><b>PLANO</b></td>
            </tr>
            <tr>
                <td colspan="12"><b>Intervenção/ Procedimentos</b></td>
            </tr>
            <tr>
                <td colspan="12">{{$atendimento_medico->descricao_plano}}</td>
            </tr>

            <tr>
                <td colspan="12" style="padding-left: 20px;"><b>CONDUTA</b></td>
            </tr>

            @if(isset($prescricao))
                @foreach($prescricao as $tipo_prescricao)
                    @if(isset($tipo_prescricao))
                        @foreach($tipo_prescricao as $p)
                            <tr>
                                <td colspan="12"><b>{{$p->tp_prescricao}}</b></td>
                            </tr>
                            @if($p->tp_prescricao == 'PRESCRICAO_AMBULATORIAL')
                            <tr>
                                <td colspan="12"><b>Prescrição Nº{{$p->cd_prescricao}}</b></td>
                            </tr>
                            @endif
                            @if(isset($p->itens))
                                <?php $contador=0; ?>
                                @if(isset($p->itens['exame_laboratorial'][0]))
                                    <tr>
                                        <td colspan="12"><b>Exames laboratoriais</b></td>
                                    </tr>
                                    @foreach($p->itens['exame_laboratorial'] as $exame)
                                        <tr>
                                            <td colspan="12">{{arrayPadrao('exames_laboratoriais')[$exame->cd_exame_laboratorial].(isset($exame->observacao_exame_laboratorial) ? ' - '.$exame->observacao_exame_laboratorial : '')}}</td>
                                        </tr>
                                    @endforeach
                                @endif
                                @if(isset($p->itens['dieta'][0]))
                                    @foreach($p->itens['dieta'] as $key=>$pd)
                                        @if($key===0)
                                            <tr><td colspan="12"><b>Dieta</b></td></tr>
                                        @endif
                                            {{$contador++}}
                                        <tr>
                                            <td colspan="5">{{$contador.") ".arrayPadrao('dieta')[$pd->dieta]." - ".arrayPadrao('via')[$pd->via_dieta]}}</td>
                                            <td colspan="3">{{"De ".$pd->intervalo_dieta."/ ".$pd->intervalo_dieta." ".arrayPadrao('periodo')[$pd->tp_intervalo_dieta].", durante ".$pd->prazo_dieta." ".arrayPadrao('periodo')[$pd->tp_prazo_dieta]}}</td>
                                            <td colspan="4">{{$pd->aprazamento}}</td>
                                            @if(isset($pd->descricao_dieta))
                                                <tr><td colspan="12">{{$pd->descricao_dieta}}</td></tr>
                                            @endif
                                        </tr>
                                    @endforeach
                                @endif
                                @if(isset($p->itens['csv'][0]))
                                    @foreach($p->itens['csv'] as $key=>$pc)
                                        @if($key===0)
                                            <tr><td colspan="12"><b>CSV</b></td></tr>
                                        @endif
                                        {{$contador++}}
                                        <tr>
                                            <td colspan="5">{{$contador.") ".$pc->descricao_csv}}</td>
                                            <td colspan="3">{{"De ".$pc->intervalo_csv."/ ".$pc->intervalo_csv." ".arrayPadrao('periodo')[$pc->tp_intervalo_csv].", durante ".$pc->prazo_csv." ".arrayPadrao('periodo')[$pc->tp_prazo_csv]}}</td>
                                            <td colspan="4">{{$pc->aprazamento}}</td>
                                        </tr>
                                    @endforeach
                                @endif
                                @if(isset($p->itens['outros_cuidados'][0]))
                                    @foreach($p->itens['outros_cuidados'] as $key=>$poc)
                                        @if($key===0)
                                            <tr><td colspan="12"><b>Outros cuidados</b></td></tr>
                                        @endif
                                        {{$contador++}}
                                        <tr>
                                            <td colspan="12">{{$contador.") ".arrayPadrao('posicoes_enfermagem')[$poc->posicao]." - ".$poc->descricao_posicao}}</td>
                                        </tr>
                                    @endforeach
                                @endif
                                @if(isset($p->itens['oxigenoterapia'][0]))
                                    @foreach($p->itens['oxigenoterapia'] as $key=>$pox)
                                        @if($key===0)
                                            <tr><td colspan="12"><b>Oxigenoterapia</b></td></tr>
                                        @endif
                                        {{$contador++}}
                                        <tr>
                                            <td colspan="5">{{$contador.") ".$pox->qtde_oxigenio."L/min, ".arrayPadrao('administracao_oxigenio')[$pox->administracao_oxigenio]." - ".$pox->descricao_oxigenio}}</td>
                                            <td colspan="3">{{"De ".$pox->intervalo_oxigenoterapia."/ ".$pox->intervalo_oxigenoterapia." ".arrayPadrao('periodo')[$pox->tp_intervalo_oxigenoterapia].", durante ".$pox->prazo_oxigenoterapia." ".arrayPadrao('periodo')[$pox->tp_prazo_oxigenoterapia]}}</td>
                                            <td colspan="4">{{$pox->aprazamento}}</td>
                                        </tr>
                                    @endforeach
                                @endif
                                @if(isset($p->itens['medicacao'][0]))
                                    @foreach($p->itens['medicacao'] as $key=>$med)
                                        @if($key===0)
                                            <tr><td colspan="12"><b>Medicação</b></td></tr>
                                        @endif
                                        {{$contador++}}
                                        <tr>
                                            <td colspan="5">{{$contador.") ".$med->nm_produto." - ".$med->dose." ".$med->abreviacao.", ".arrayPadrao('via')[$med->tp_via]}}</td>
                                            <td colspan="3">{{"De ".$med->intervalo."/ ".$med->intervalo." ".arrayPadrao('periodo')[$med->tp_intervalo].", durante ".$med->prazo." ".arrayPadrao('periodo')[$med->tp_prazo]}}</td>
                                            <td colspan="4">{{$med->aprazamento}}</td>
                                        @if(isset($med->observacao_medicamento))
                                            <tr><td colspan="12">{{$med->observacao_medicamento}}</td></tr>
                                        @endif
                                        </tr>
                                    @endforeach
                                @endif
                            @endif
                        @endforeach
                    @endif
                @endforeach
            @endif

        </table>
        @endif

        @if(count($atendimento_procedimento) > 0)
            <div class="div-titulo">Procedimentos</div>
            <table width="100%" class="pure-table" border="0">
                <tr>
                    <td><b>Procedimento</b></td>
                    <td><b>Data/Hora Solicitação</b></td>
                    <td><b>Profissional Solicitante</b></td>
                    <td><b>Data/Hora Realização</b></td>
                    <td><b>Realizador</b></td>
                </tr>
                @foreach($atendimento_procedimento as $ap)
                    <tr>
                        <td>{{ $ap->cd_procedimento." - ".$ap->nm_procedimento }}</td>
                        <td>{{ formata_data_hora($ap->dt_hr_solicitacao) }}</td>
                        <td>{{ $ap->nm_ocupacao_solicitante }} {{ $ap->nm_solicitante }} - {{ $ap->conselho_solicitante }}: {{ $ap->nr_conselho_solicitante }} </td>
                        <td>{{ formata_data_hora($ap->dt_hr_execucao) }}</td>
                        <td>@isset($ap->nm_executante){{ $ap->nm_ocupacao_executante }} {{ $ap->nm_executante }} - {{ $ap->conselho_executante }}: {{ $ap->nr_conselho_executante }}@endisset</td>
                    </tr>
                    @if(isset($ap->descricao_solicitacao))
                    <tr>
                        <td colspan="5">
                            <b>Descrição da solicitação:</b> {{$ap->descricao_solicitacao}}
                        </td>
                    </tr>
                    @endif
                    @if(isset($ap->descricao_execucao))
                    <tr>
                        <td colspan="5">
                            <b>Descrição da execução:</b> {{$ap->descricao_execucao}}
                        </td>
                    </tr>
                    @endif
                @endforeach
            </table>
        @endif

        @if(count($atendimento_evolucao) > 0)
            <div class="div-titulo">Evolução Clínica</div>
            <table width="100%" class="pure-table" border="0">
                <tr>
                    <td><b>Data/ Hora</b></td>
                    <td><b>Sala/ Leito</b></td>
                    <td><b>Profissional</b></td>
                    <td><b>Descrição</b></td>
                </tr>
                @foreach($atendimento_evolucao as $ae)
                    <tr>
                        <td>{{ formata_data_hora($ae->created_at) }}</td>
                        <td>{{ $ae->nm_sala }} {{ ((isset($ae->cd_leito) && $ae->cd_leito > 0) ? "/ ".arrayPadrao('leitos')[$ae->cd_leito] : '')}}</td>
                        <td>{{ $ae->nm_ocupacao}} {{ $ae->nm_pessoa}} - {{$ae->conselho}}: {{ $ae->nr_conselho}}</td>
                        <td>{{ $ae->descricao_evolucao }}</td>
                    </tr>
                @endforeach
            </table>
        @endif

        @if((isset($atendimento_medico)) && $atendimento_medico->motivo_alta > 0)
            <div class="div-titulo">Alta</div>
            <div class="recuo">
                <b>Motivo da Alta:</b> {{ arrayPadrao('motivo_alta')[$atendimento_medico->motivo_alta] }}<br>
                <b>Descrição:</b> {{ $atendimento_medico->descricao_alta }}<br>
                <b>Data/ Hora: </b>{{formata_data_hora($atendimento_medico->finished_at)}}
            </div>
        @endif

    </div>
@endsection

@section('footer')
    <hr>
    <small>Impresso em {{ date("d/m/Y - H:m:s") }}</small>
    <br>
    @if($estabelecimento->tp_estabelecimento === 'U')
        <small><i class="text-right">Esta conta será paga com recursos públicos</i></small>
    @endif
@endsection