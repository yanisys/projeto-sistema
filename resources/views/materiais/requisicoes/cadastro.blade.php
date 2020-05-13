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
    {{ Form::open(['id' => 'cadastra-requisicoes', 'class' => 'form-no-submit']) }}
    <div class="panel panel-primary">
        <div class="panel-heading">
            @if((session()->get('recurso.materiais/requisicoes-editar')))
                <a href="{{ route('materiais/requisicoes/cadastro') }}" class="btn btn-primary pull-right margin-top-10">Novo</a>
            @endif
            <h4>Dados da Requisição</h4>
        </div>
        <div class="panel-body">
            <div class="tab-content">
                <div class="tab-pane fade in active" id="tab_info">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="cd_requisicao">Código<span style="color:red">*</span></label>
                                {{ Form::text('cd_requisicao',(isset($requisicoes['cd_requisicao']) ? $requisicoes['cd_requisicao'] : ""),["name" => "cd_requisicao", "maxlength" => "10", "id" => "cd_requisicao",  "disabled" => "disabled", 'class'=> ($errors->has("cd_requisicao") ? "form-control is-invalid" : "form-control")]) }}
                                {{ Form::hidden('cd_requisicao',(isset($requisicoes['cd_requisicao']) ? $requisicoes['cd_requisicao'] : ""),["name" => "cd_requisicao", "id" => "cd_requisicao"]) }}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="cd_sala_origem">Minha localização<br></label>
                                {{  Form::select('cd_sala_origem', $sala, (isset($requisicoes['cd_sala_origem']) ? $requisicoes['cd_sala_origem'] : ""),['class'=> "form-control", (isset($requisicoes['cd_requisicao']) ? "readonly" : ""),'id' => 'cd_sala_origem']) }}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="cd_sala_destino">Solicitar para<br></label>
                                {{  Form::select('cd_sala_destino', $estoque, (isset($requisicoes['cd_sala_destino']) ? $requisicoes['cd_sala_destino'] : ""),['class'=> "form-control", (isset($requisicoes['cd_requisicao']) ? "readonly" : ""),'id' => 'cd_sala_destino']) }}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="solicitante">Solicitante<br></label>
                                {{ Form::text('solicitante',(isset($solicitante) ? $solicitante->nm_pessoa : ""),["disabled" => "disabled", 'class'=> "form-control"]) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel-heading">
                <h3>Produtos
                    @if(isset($requisicoes['cd_requisicao']))
                    <span>
                         <button type="button" data-toggle="modal" data-target="#modal-produto-requisicao" class="btn btn-info btn-xs" id="btn-add-produto-requisicao">Adicionar</button>
                    </span>
                    @endif
                </h3>
                <hr>
            </div>
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Quantidade</th>
                        <th>Fração/ Dosagem</th>
                        <th class='text-center' width="190px">Ação</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(isset($requisicao_produto))
                        @foreach($requisicao_produto as $rp)
                            <tr class="maiusculas">
                                <td>{{$rp->nm_produto." - ".$rp->ds_produto}}</td>
                                <td>{{$rp->quantidade}}</td>
                                <td>{{($rp->fracionamento == 0) ? $rp->nm_unidade_comercial : $rp->nm_unidade_medida}}</td>
                                <td class="text-center"><button data-tabela="requisicao_produto" data-chave="cd_requisicao_produto" data-valor="{{ $rp->cd_requisicao_produto }}" type="button" class="btn btn-danger btn-xs btn-excluir"><span class="fas fa-trash"></span></button></td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>

        </div>
        <div class="panel-footer fixed-panel-footer" >
            @if((session()->get('recurso.materiais/requisicoes-editar')))
                {{ Form::submit('Salvar',['class'=>"btn btn-success pull-right", 'id' => 'salvar-requisicoes']) }}
            @endif
        </div>

    </div>
    {{ Form::close() }}

@endsection

@section('painel-modal')
    <div class="modal fade" id="modal-produto-requisicao"  tabindex="-2" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="myModalLabel">Adicionar produto</h3>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="pesquisa-simples">
                    <div class="panel-body">
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="input-group">
                                    <label for="cd_medicamento">Medicamento</label>
                                    <input type="text" data-id="" id="cd_produto" disabled class="form-control">
                                    <input type="hidden" id="cd_produto_hidden">
                                    <span class="input-group-btn">
                                       <button type="button" class="btn btn-default margin-top-25" id="btn-modal-pesquisa-produto"><span class="fa fa-search"></span></button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-4">
                            <div class="form-group">
                                <label for="quantidade">Quantidade</label>
                                <input type="text" id="quantidade" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-8 col-sm-8">
                            <div class="form-group">
                                <label for="fracao">Fração/ Dosagem</label>
                                <input type="text" disabled id="fracao" class="form-control">
                                <input type="hidden" disabled id="fracao_hidden" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-footer" style="text-align:left !important;">
                    <button type="button" class="btn btn-danger margin-top-zero" data-dismiss="modal">Cancelar</button>
                    <button  class="btn btn-primary  pull-right" id="add-produto-requisicao">Adicionar</button>
                </div>
            </div>
        </div>
    </div>
    @include('materiais/movimentacao/modal-pesquisa-produto')
@endsection

