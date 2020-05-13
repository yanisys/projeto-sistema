
@extends('layouts.default')


@section('conteudo')

        <div class="row">

            @if((session()->get('recurso.atendimentos/novo-atendimento')))
            <!--------------------Novo atendimento------------------------------------------------------------------------->
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-6 text-center">
                    <a href="#" class="iconesInicio btn-modal-pessoa" data-modo="pesquisar" data-nao-validar-endereco title="Novo atendimento">
                        <span class="fas fa-address-card fa-3x"></span>
                        <h5>Novo atendimento</h5></a>
                </div>
            @endif
            @if((session()->get('recurso.atendimentos/consulta-enfermagem')))
                <!--------------------Aguardando consulta de enfermagem/ acolhimento--------------------------------------------------->
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-6 text-center">
                    <a href="{{ route('filas/acolhimento') }}" class="iconesInicio "><span class="fas fa-user-nurse fa-3x"></span>
                        <span class="label label-success redondo">{{$aguardando_acolhimento}}</span>
                        <h5>Consulta de enfermagem</h5></a>
                </div>
            @endif
            @if((session()->get('recurso.atendimentos/atendimento-medico')))
                <!---------------------------Aguardando atendimento médico------------------------------------------------------------->
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-6 text-center">
                    <a href="{{ route('filas/atendimento-medico') }}" class="iconesInicio "><span class="fas fa-user-md fa-3x"> </span>
                        <span class="label label-success redondo">{{$aguardando_atendimento_medico}}</span>
                        <h5>Atendimento médico</h5></a>
                </div>
            @endif
            @if((session()->get('recurso.atendimentos/observacao')))
                <!-----------------------Pacientes atendidos/ Medicina Interna--------------------------------------------------------->
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-6 text-center">
                    <a href="{{ route('filas/medicina-interna') }}" class="iconesInicio "><span class="fas fa-clinic-medical fa-3x"> </span>
                        <span class="label label-success redondo">{{$atendimento_interno}}</span>
                        <h5>Observação/ Medicina Interna</h5></a>
                </div>
            @endif
            @if((session()->get('recurso.atendimentos/procedimentos')))
                <!----------------------Aguardando realização de procedimentos--------------------------------------------------------->
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-6 text-center">
                    <a href="{{ route('filas/procedimentos') }}" class="iconesInicio "><span class="fas fa-procedures fa-3x"> </span>
                        <span class="label label-success redondo">{{$procedimentos}}</span>
                        <h5>Procedimentos</h5></a>
                </div>
            @endif
            @if((session()->get('recurso.atendimentos/procedimentos-radiologicos')))
                <!--------------Aguardando realização de procedimentos radiológicos---------------------------------------------------->
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-6 text-center">
                    <a href="{{ route('filas/procedimentos-radiologicos') }}" class="iconesInicio "><span class="fas fa-x-ray fa-3x"> </span>
                        <span class="label label-success redondo">{{$procedimentos_radiologicos}}</span>
                        <h5>Procedimentos radiológicos</h5></a>
                </div>
            @endif
            @if((session()->get('recurso.atendimentos/finalizados')))
                <!---------------Pacientes atendidos/ Atendimentos finalizados--------------------------------------------------------->
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-6 text-center">
                    <a href="{{ route('filas/atendimentos-finalizados') }}" class="iconesInicio "><span class="fas fa-hospital-user fa-3x"> </span>
                        <span class="label label-success redondo">{{$atendimentos_concluidos}}</span>
                        <h5>Prontuários Finalizados</h5></a>
                </div>
            @endif
</div>
@endsection

@section('painel-modal')

    @include('pessoas.modal-pessoas')

    <div class="modal fade" id="novo-atendimento"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Fechar</span></button>
                    <h4 class="modal-title" id="label_modal_atendimento">Selecione o tipo de atendimento</h4>
                </div>
                <div class="modal-body" id="planos_usuario">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-striped">
                            <thead>
                            <tr>
                                <th>Plano</th>
                                <th>Nº cartão</th>
                                <th class='text-center' width="190px">Ação</th>
                            </tr>
                            </thead>
                            <tbody id="tabela_plano">
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-body" id="origem_usuario" style="display: none">
                    <div class="panel-body">
                        <div class="col-md-12">
                            <div class="col-md-9">
                                <label for="Grupo">Origem</label>
                                {{  Form::select('cd_origem', isset($origem) ? $origem : [0 => "Nenhuma origem cadastrada"], "1",['class'=> "form-control", 'id' => 'cd_origem_usuario']) }}
                            </div>
                            <div class="col-md-3">
                                <label for="btn_ir_para_origem">&nbsp</label>
                                <button id="btn_ir_para_origem" type="button" class="btn btn-default" data-dismiss="modal">Novo atendimento</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="text-align:left !important;">
                    <div id="mensagem-novo-atendimento"></div>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>

@endsection



@section('custom_js')
    <script src=" {{ asset('public/js/jquery.cookie.js') }}"></script>

    <script src="{{js_versionado('fila.js')}}"></script>

    <script src="{{js_versionado('prontuario.js')}}"></script>

@endsection