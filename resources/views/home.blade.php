
@extends('layouts.default')

@section('conteudo')
    <?php
        $pes = FALSE; $ope = FALSE; $gop = FALSE; $pla = FALSE; $est = FALSE; $ben = FALSE;
        $sal = FALSE; $psa = FALSE; $ate = FALSE; $pch = FALSE; $pro = FALSE; $rat = FALSE;
        $abp = FALSE; $con = FALSE; $mat = FALSE; $sme = FALSE; $sen = FALSE; $fin = FALSE;
        $hot = FALSE; $hig = FALSE; $acs = FALSE; $pme = FALSE; $pen = FALSE;
        if ((session()->get('recurso.pessoas'))){$pes = TRUE;}
        if ((session()->get('recurso.operadores'))){$ope = TRUE;}
        if ((session()->get('recurso.grupos'))){$gop = TRUE;}
        if ((session()->get('recurso.planos'))){$pla = TRUE;}
        if ((session()->get('recurso.estabelecimentos'))){$est = TRUE;}
        if ((session()->get('recurso.beneficiarios'))){$ben = TRUE;}
        if ((session()->get('recurso.salas'))){$sal = TRUE;}
        if ((session()->get('recurso.profissionais'))){$psa = TRUE;}
        if ((session()->get('recurso.atendimentos'))){$ate = TRUE;}
        if ((session()->get('recurso.painel'))){$pch = TRUE;}
        if ((session()->get('recurso.prontuarios'))){$pro = TRUE;}
        if ((session()->get('recurso.relatorios/atendimentos'))){$rat = TRUE;}
        if ((session()->get('recurso.relatorios/bpa'))){$abp = TRUE;}
        if ((session()->get('recurso.configuracoes/procedimentos'))){$con = TRUE;}
        if ((session()->get('recurso.materiais'))){$mat = TRUE;}
        if ((session()->get('recurso.samu/medico'))){$sme = TRUE;}
        if ((session()->get('recurso.samu/enfermagem'))){$sen = TRUE;}
        if ((session()->get('recurso.financeiro'))){$fin = TRUE;}
        if ((session()->get('recurso.hotelaria'))){$hot = TRUE;}
        if ((session()->get('recurso.higienizacao'))){$hig = TRUE;}
        if ((session()->get('recurso.acs'))){$acs = TRUE;}
        if ((session()->get('recurso.protocolos/medico'))){$pme = TRUE;}
        if ((session()->get('recurso.protocolos/enfermagem'))){$pen = TRUE;}
    ?>

    @if($pes || $ope || $gop || $pla || $est || $ben || $sal || $psa )
        <br>
        <div class="row">
            <h5 class='titulos-home'><b>Cadastros</b></h5>
            <hr class="linha-hr">
            <div class="row">
                @if($pes)
                <!-- Tabela de pessoas -->
                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-6 text-center">
                    <a href="{{ route('pessoas/lista') }}" class="iconesInicio "><span class="fa fa-male fa-3x"> </span>
                        <h5>Pacientes</h5></a>
                </div>
                @endif
                @if($ope)
                    <!-- Tabela de operadores -->
                    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-6 text-center">
                        <a href="{{ route('operadores/lista') }}" class="iconesInicio "><span class="fa fa-user fa-3x"> </span>
                            <h5>Colaboradores</h5></a>
                    </div>
                @endif
                @if($gop)
                    <!-- Tabela grupos de operadores -->
                    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-6 text-center">
                        <a href="{{ route('grupos/lista') }}" class="iconesInicio "><span class="fa fa-users fa-3x"> </span>
                            <h5>Grupos de Colaboradores</h5></a>
                   </div>
                @endif
                @if($pla)
                    <!-- Tabela de Planos -->
                    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-6 text-center">
                        <a href="{{ route('planos/lista') }}" class="iconesInicio "><span class="fa fa-medkit fa-3x"> </span>
                            <h5>Planos</h5></a>
                    </div>
                @endif
                @if($est)
                    <!-- Tabela de Estabelecimentos -->
                    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-6 text-center">
                        <a href="{{ route('estabelecimentos/lista') }}" class="iconesInicio "><span class="fas fa-hospital fa-3x"> </span>
                            <h5>Estabelecimentos</h5></a>
                    </div>
                @endif
                @if($ben)
                    <!-- Tabela de Beneficiários -->
                    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-6 text-center">
                        <a href="{{ route('beneficiarios/lista') }}" class="iconesInicio "><span class="fa fa-user-plus fa-3x"> </span>
                            <h5>Beneficiários</h5></a>
                    </div>
                @endif
                @if($sal)
                    <!-- Tabela de Salas -->
                    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-6 text-center">
                        <a href="{{ route('salas/lista') }}" class="iconesInicio "><span class="fas fa-hospital-alt fa-3x"> </span>
                            <h5>Salas</h5></a>
                    </div>
                @endif
                @if($psa)
                    <!-- Cadastro de profissionais-->
                    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-6 text-center">
                        <a href="{{ route('profissionais/lista') }}" class="iconesInicio "><span class="fa fa-user-md fa-3x"> </span>
                            <h5>Profissionais de saúde</h5></a>
                    </div>
                @endif
            </div>
        </div>
    @endif

    @if($ate || $pch || $mat || $sme || $sen || $fin || $hot || $hig || $acs)
        <br>
        <div class="row">
            <h5 class='titulos-home'><b>Operacionais</b></h5>
            <hr class="linha-hr">
            <div class="row">
                @if($ate)
                    <!-- Tabela de Atendimentos -->
                    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-6 text-center">
                        <a href="{{ route('filas/filas') }}" class="iconesInicio "><span class="fa fa-stethoscope fa-3x"> </span>
                            @if((session()->get('grupo') == 8))
                                <h5>Atendimento Médico</h5></a>
                            @elseif((session()->get('grupo') == 12))
                                <h5>Atendimento de Enfermagem</h5></a>
                            @else
                                <h5>Atendimento</h5></a>
                            @endif
                    </div>
                @endif
                @if($pch)
                    <!-- Painel de chamadas-->
                        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-6 text-center">
                            <a href="{{ route('atendimentos/painel') }}" class="iconesInicio "><span class="fa fa-tv fa-3x"> </span>
                                <h5>Painel de chamadas</h5></a>
                        </div>
                @endif
                @if($mat)
                    <!-- Tabela de Produtos -->
                        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-6 text-center">
                            <a href="{{ route('materiais/produtos') }}" class="iconesInicio "><span class="fas fa-box fa-3x"> </span>
                                <h5>Materiais</h5></a>
                        </div>
                @endif
                @if($sme)
                    <!-- Tela de ferramentas para SAMU Médico -->
                    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-6 text-center">
                        <a href="#" class="iconesInicio "><span class="fas fa-ambulance fa-3x"> </span>
                            <h5>SAMU - Médico</h5></a>
                    </div>
                @endif
                @if($sen)
                    <!-- Tela de ferramentas para SAMU Enfermagem -->
                    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-6 text-center">
                        <a href="#" class="iconesInicio "><span class="fas fa-ambulance fa-3x"> </span>
                            <h5>SAMU - Enfermagem</h5></a>
                    </div>
                @endif
                @if($fin)
                    <!-- Tela de ferramentas financeiras -->
                    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-6 text-center">
                        <a href="#" class="iconesInicio "><span class="fas fa-cash-register fa-3x"> </span>
                            <h5>Financeiro</h5></a>
                    </div>
                @endif
                @if($hot)
                    <!-- Tela de ferramentas de hotelaria -->
                    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-6 text-center">
                        <a href="#" class="iconesInicio "><span class="fas fa-hotel fa-3x"> </span>
                            <h5>Hotelaria</h5></a>
                    </div>
                @endif
                @if($hig)
                    <!-- Tela de ferramentas de hotelaria -->
                    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-6 text-center">
                        <a href="#" class="iconesInicio "><span class="fas fa-pump-soap fa-3x"> </span>
                            <h5>Higienização</h5></a>
                    </div>
                @endif
                @if($acs)
                    <!-- Tela de ferramentas de acs (Agente Comunitário de Saúde) -->
                    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-6 text-center">
                        <a href="#" class="iconesInicio "><span class="fas fa-street-view fa-3x"> </span>
                            <h5>ACS</h5></a>
                    </div>
                @endif
             </div>
        </div>
    @endif

    @if($pro || $rat || $abp)
        <br>
        <div class="row">
            <h5 class='titulos-home'><b>Relatórios e Prontuários</b></h5>
            <hr class="linha-hr">
            <div class="row">
                @if($pro)
                    <!-- Tabela de Prontuários -->
                        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-6 text-center">
                            <a href="{{ route('prontuarios/lista') }}" class="iconesInicio "><span class="fas fa-file fa-3x"> </span>
                                <h5>Prontuários</h5></a>
                        </div>
                @endif
                @if($rat)
                    <!-- Relatório de Atendimentos -->
                        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-6 text-center">
                            <a href="{{ route('relatorios/atendimentos/media') }}" class="iconesInicio "><span class="fas fa-chart-bar fa-3x"> </span>
                                <h5>Relatório de Atendimentos</h5></a>
                        </div>
                @endif
                @if($abp)
                    <!-- Relatório de Atendimentos -->
                        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-6 text-center">
                            <a href="{{ route('relatorios/bpa') }}" class="iconesInicio "><span class="fa fa-file fa-3x"> </span>
                                <h5>Arquivo Bpa</h5></a>
                        </div>
                @endif
            </div>
        </div>
    @endif

    @if($con || $gop)
        <br>
        <div class="row">
            <h5 class='titulos-home'><b>Configurações</b></h5>
            <hr class="linha-hr">
            <div class="row">       
                @if($con)
                    <!-- Tabela de Prontuários -->
                        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-6 text-center">
                            <a href="{{ route('configuracoes/configuracoes') }}" class="iconesInicio "><span class="fa fa-cog fa-3x"> </span>
                                <h5>Configurações</h5></a>
                        </div>
                @endif
                @if($gop)
                    <!-- Tabela grupos de operadores -->
                    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-6 text-center">
                        <a href="{{ route('grupos/lista') }}" class="iconesInicio "><span class="fas fa-users-cog fa-3x"> </span>
                            <h5>Permissões de Grupos</h5></a>
                   </div>
                @endif
            </div>
        </div>
    @endif    

    @if($pme || $pen)
        <br>
        <div class="row">
            <h5 class='titulos-home'><b>Protocolos e PEC</b></h5>
            <hr class="linha-hr">
            <div class="row">
                @if($pme)
                    <!-- Tela de protocolos médicos -->
                    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-6 text-center">
                        <a href="#" class="iconesInicio "><span class="fas fa-file-alt fa-3x"> </span>
                            <h5>Protocolos Médicos</h5></a>
                    </div>
                @endif
                @if($pen)
                    <!-- Tela de protocolos médicos -->
                    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-6 text-center">
                        <a href="#" class="iconesInicio "><span class="fas fa-file-alt fa-3x"> </span>
                            <h5>Protocolos de Enfermagem</h5></a>
                    </div>
                @endif
            </div>
        </div>
    @endif
    <br><br>
@endsection
