@extends('layouts.default')


@section('conteudo')
    @if ($errors->any())
        <div class="alert alert-danger collapse in" id="collapseExample">
            <h4 class="alert-heading">Os seguintes erros foram encontrados:</h4>
            <ul>
                @foreach ($errors->all() as $error)
                    <li> {{ $error }} </li>
                @endforeach
            </ul>
            <hr>
            <p class="mb-0">Por favor, verifique e tente novamente.</p>
            <p><a class="btn btn-primary" data-toggle="collapse" href="#collapseExample" role="button"
                  aria-expanded="true" aria-controls="collapseExample">
                    Fechar
                </a></p>
        </div>
    @endif

    {{ Form::open(['id' => 'atendimento-requisicoes', 'method' => 'POST', 'class' => 'form-no-submit']) }}
    <div class="panel panel-primary">
        <div class="panel-heading">
            <div class="font-12px text-center">
                @if(isset($requisicao))
                    <h4><b>Requisição Nº:</b> {{ $requisicao->cd_requisicao }}</h4>
                    <input type="hidden" id="req_paciente" value={{isset($requisicao->id_atendimento_prescricao) ? 1 : 0}}>
                @endif
            </div>
        </div>
        <div class="panel-body">
            <div class="tab-content">
                <div class="tab-pane fade in active" id="tab_info">
                    <b>Data:</b> {{ formata_data_hora($requisicao->created_at) }}  <br>
                    <b>Solicitante:</b> {{ $requisicao->nm_pessoa }} <br>
                    <b>Destino:</b> {{ $requisicao->nm_sala_origem }}<br>
                    @if(isset($requisicao->id_atendimento_prescricao))
                        <b>Paciente:</b> {{ $requisicao->nm_paciente }}
                    @endif
                    <hr>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead>
                    <tr>
                        @if($requisicao->situacao == 'A')
                            <th colspan="1" class="text-center"><h4>Pesquisa</h4></th>
                        @endif
                        <th colspan="3" class="text-center"><h4>Requisição</h4></th>
                        <th colspan="3" class="text-center"><h4>Atendimento</h4></th>
                    </tr>
                    <tr>
                        @if($requisicao->situacao == 'A')
                            <th style="border-right: solid 1px #b7b7b7">Cod. barras</th>
                        @endif
                        <th>Nome</th>
                        <th>Unidade</th>
                        <th style="border-right: solid 1px #b7b7b7">Qtde</th>
                        <th>Lote</th>
                        <th>Validade</th>
                        <th>Qtde</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(isset($requisicao_produto))
                        @foreach($requisicao_produto as $key => $rp)
                            {{ Form::hidden('cd_requisicao_produto',(isset($rp->cd_requisicao_produto) ? $rp->cd_requisicao_produto : ""),["name" => "cd_requisicao_produto[]"]) }}
                            {{ Form::hidden('dt_fabricacao',(isset($rp->dt_fabricacao) ? $rp->dt_fabricacao : 0),["name" => "dt_fabricacao[]","id" => "dt_fabricacao-$key"]) }}
                            {{ Form::hidden('cd_fornecedor',(isset($rp->cd_fornecedor) ? $rp->cd_fornecedor : 0),["name" => "cd_fornecedor[]","id" => "cd_fornecedor-$key"]) }}
                            <tr class="maiusculas">
                                @if($requisicao->situacao == 'A')
                                    <td width="15%" style="border-right: solid 1px #b7b7b7">{{ Form::text('cod_barras',"",["maxlength" => "44", "data-key" => $key, "id" => "cod_barras-$key",  'class'=> "form-control"]) }}</td>
                                @endif
                                <td><strong>{{(isset($rp->nm_produto) ? $rp->nm_produto : "")}}</strong></td>
                                <td><strong>{{(isset($rp->fracionamento) && $rp->fracionamento == 0) ? $rp->nm_unidade_comercial : $rp->nm_unidade_medida}}</strong></td>
                                <td style="border-right: solid 1px #b7b7b7"><strong>{{(isset($rp->quantidade) ? $rp->quantidade : "")}}</strong></td>
                                @if($rp->controle_lote_validade == 1)
                                    <td>{{  Form::select('lote', $rp->lotes, (isset($rp->lote) ? $rp->lote : 0),["name" => "lote[]", "data-key" => $key, ($requisicao->situacao == 'C' ? 'disabled' : ''), 'class'=> "verifica-estoque form-control ".($errors->has("lote[$key]") ? "is-invalid" : ""), 'id' => "lote-$key"]) }}</td>
                                    <td width="15%">{{ Form::text('dt_validade',(isset($rp->dt_validade) ? $rp->dt_validade : ""),["name" => "dt_validade[]", (isset($requisicao->id_atendimento_prescricao) ? "readonly" : ""), "maxlength" => "10", "id" => "validade-$key",  'class'=> "verifica-estoque ".($errors->has("dt_validade[$key]") ? "form-control mask-data is-invalid" : "form-control mask-data")]) }}</td>
                                @else
                                    <td></td>
                                    <td></td>
                                @endif
                                <td width="15%">
                                    <div class="input-group">
                                        {{ Form::number('quantidade_atendimento',(isset($rp->quantidade_atendimento) ? $rp->quantidade_atendimento : ""),["name" => "quantidade_atendimento[]", "data-estoque" => json_encode($rp->estoque), "data-qtde-solicitada"=>$rp->quantidade, "step" => "any", "pattern" => "^\d*(\.\d{0,4})?$", "min" => 0, "max" => ($rp->controle_lote_validade == 0 ? $rp->quantidade-1 : ""), (isset($requisicao->id_atendimento_prescricao) ? "readonly" : ""), "id" => "quantidade-$key", "required", "maxlength" => "10", 'class'=> "verifica-estoque ".($errors->has("quantidade_atendimento[$key]") ? "form-control is-invalid" : "form-control")]) }}
                                        <span class="input-group-addon">{{(isset($rp->fracionamento) && $rp->fracionamento == 0) ? $rp->nm_unidade_comercial : $rp->nm_unidade_medida}}</span>
                                    </div>
                                </td>
                                <td width="5%">
                                    <span style="color: #ffc318; display: none" id="atencao-{{$key}}" title="Preencha os campos corretamente." class="fas fa-exclamation-triangle fa-2x"></span>
                                    <span style="color: #3f742a; display: {{($requisicao->situacao == 'C' ? 'block' : 'none')}}" id="ok-{{$key}}" title="Os valores informados estão corretos!" class="fas fa-check"></span>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>

        </div>
        <div class="panel-footer fixed-panel-footer" >
            @if((session()->get('recurso.materiais/requisicoes-editar')) && $requisicao->situacao == 'A')
                {{ Form::submit('Concluir',['class'=>"btn btn-success pull-right"]) }}
            @endif
        </div>

    </div>
    {{ Form::close() }}
    <script src="{{ js_versionado('produtos_requisicao.js') }}" defer></script>
@endsection


