@extends('layouts.default')

@section('conteudo')
    <div class="panel panel-default ">
        <div class="panel-body">
            {!! Form::open(["method" => "GET", "class" => "form-search"]) !!}
            <div class="container-fluid">
                <div class="col-sm-5">
                    <label>Nome</label>
                    {!! Form::text('nm_produto',(!empty($_REQUEST['nm_produto']) ? $_REQUEST['nm_produto'] : ""),["name" => "nm_produto", "id" => "busca_produto", "placeholder" => "Digite o nome do produto ou cod. barras",'class'=>'form-control']) !!}
                </div>
                <div class="col-sm-3">
                    <label>Situação</label>
                    {{  Form::select('situacao', arrayPadrao('situacao',true), (isset($_REQUEST['situacao']) ? $_REQUEST['situacao'] : "A"),['class'=>  "form-control", 'id' => 'situacao']) }}
                </div>
                <div class="col-sm-3 ">
                    {!!  Form::submit('Buscar',['class'=>"btn btn-default margin-top-25"]) !!}
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-hover table-striped">
            <thead>
            <tr>
                <th>Código</th>
                <th>Descrição</th>
                <th>Fabricante</th>
                <th>Situação</th>
                <th class='text-center' width="190px">Ação</th>
            </tr>
            </thead>
            <tbody>
            @if(!isset($lista) || $lista->IsEmpty())
                <tr><td colspan="3">Sem resultados</td></tr>
            @else
                @foreach($lista as $produto)
                    <tr>
                        <td>{{ $produto->cd_produto }}</td>
                        <td>{{ $produto->nm_produto . (isset($produto->ds_produto) && !empty($produto->ds_produto) ? " - ".$produto->ds_produto : "") }}</td>
                        <td>{{ $produto->nm_laboratorio }}</td>
                        <td class='text-center {{ ($produto->situacao == 'A' ? 'text-primary' : 'text-danger')}}'>
                            {{ ($produto->situacao == 'A' ? 'Ativo' : 'Inativo') }}
                        </td>
                        <td class='text-center'>
                            <a href="{{ route('materiais/produto/cadastro').'/'.$produto->cd_produto }}" class={{ verficaPermissaoBotao('recurso.materiais/produto-editar') }} 'btn btn-primary btn-sm'>Editar</a>
                            <button type='button' data-tabela="produto" data-chave="cd_produto" data-valor="{{ $produto->cd_produto }}" class='{{ verficaPermissaoBotao('recurso.materiais/produto-excluir')  }} btn btn-danger btn-sm btn-excluir'>Excluir</button>
                        </td>
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
    </div>
    <div class="row">
        <div class="col-md-6">
            <a href="{{ route('materiais/produto/cadastro') }}" class="{{ verficaPermissaoBotao('recurso.materiais/produto-adicionar')  }} btn btn-success"><i class="fa fa-plus"></i> Cadastrar Produto</a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class='pull-right'>@isset($lista){{  $lista->links() }}@endisset</div>
        </div>
    </div>

@endsection