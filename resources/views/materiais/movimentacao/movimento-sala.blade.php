@extends('layouts.default')

@section('conteudo-full')

<div class="col-md-7">
    <div class="panel panel-default ">
        <div class="panel-body">
            <div class="container-fluid">
                <div class="col-md-3">
                    <label for="id_produto">Origem<br></label>
                    {{  Form::select('cd_sala_origem', $sala, (session()->get('cd_sala') ? session()->get('cd_sala') : 1),['class'=> "form-control", 'id' => 'cd_sala_origem']) }}
                </div>
                {{  Form::hidden('pesquisar_por',  0,['class'=> "form-control", 'id' => 'pesquisar_por']) }}
                <div class="col-md-7">
                    <label>Descrição</label>
                    {!! Form::text('nm_produto_origem',"",["name" => "nm_produto_origem", "id" => "nm_produto_origem", 'class'=>'form-control']) !!}
                </div>
                <div class="col-sm-2">
                    {!!  Form::button('Buscar',['id'=>'buscar_produto_origem','class'=>"btn btn-default margin-top-25"]) !!}
                </div>
            </div>
        </div>
    </div>
    <div class="panel panel-default" style="height: 400px">
        <div class="panel-body">
            <div id="tabela-pesquisa-estoque" class="table-responsive" style="height: 380px; overflow-y: auto">

            </div>
        </div>
    </div>
</div>
<div class="col-md-5">
    <div class="panel panel-default">
        <div class="panel-body">
            {!! Form::open(["method" => "GET", "class" => "form-search"]) !!}
            <div class="container-fluid">
                {!! Form::hidden('nr_doc_hidden', "0",["id" => "nr_doc_hidden"]) !!}
                <div class="col-md-5">
                    <label for="cd_sala_destino">Destino<br></label>
                    {{  Form::select('cd_sala_destino', $sala, (isset($_REQUEST['cd_sala_destino']) ? $_REQUEST['cd_sala_destino'] : ""),['class'=> "form-control", 'id' => 'cd_sala_destino']) }}
                </div>
                <div class="col-md-7">
                    <div class="input-group">
                        <label for="cd_cfop">Profissional<br></label>
                        {{ Form::text('cd_user', '' ,["name" => "cd_user", "id" => "cd_user", "disabled", 'class'=> 'form-control']) }}
                        {{ Form::hidden('cd_user', 0 ,["name" => "cd_user", 'id' => 'cd_user_hidden', 'class'=> 'form-control']) }}
                        <span class="input-group-btn">
                             <button type="button" data-toggle="modal" class="btn btn-info margin-top-25" id="btn-modal-pesquisa-user"><span class="fa fa-search"></span></button>
                        </span>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
    <div class="panel panel-default" style="height: 400px">
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-hover table-striped table-responsive-sm">
                    <thead>
                    <tr>
                        <th width="80px">Remover</th>
                        <th>Produto</th>
                        <th>Lote</th>
                        <th>Qtde</th>
                    </tr>
                    </thead>
                    <tbody id="tabela-mostra-movimentos">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <button id="finalizar-transferencia-produtos" type="button" class="btn btn-success pull-right">Finalizar</button>
    </div>
</div>
<script src="{{ js_versionado('produtos.js') }}" defer></script>
@endsection

@section('painel-modal')
    @include('materiais.movimentacao.modal-pesquisa-user')
@endsection