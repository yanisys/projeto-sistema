@extends('layouts.default')

@section('conteudo')
    <div class="panel panel-default ">
        <div class="panel-body">
            {!! Form::open(["method" => "GET", "class" => "form-search"]) !!}
            <div class="container-fluid">
                <div class="col-sm-3">
                    <label>Nome</label>
                    {!! Form::text('NOME',(!empty($_REQUEST['nome']) ? $_REQUEST['nome'] : ""),["name" => "nome", "id" => "nome", "placeholder" => "Digite um nome",'class'=>'form-control ']) !!}
                </div>
                <div class="col-sm-3">
                    <label>Situação</label>
                    {{  Form::select('id_situacao', arrayPadrao('situacao',true), (isset($_REQUEST['id_situacao']) ? $_REQUEST['id_situacao'] : "T"),['class'=>  "form-control", 'id' => 'id_situacao']) }}
                </div>
                {!!  Form::submit('Buscar',['class'=>"btn btn-default margin-top-25"]) !!}
            </div>
            {!! Form::close() !!}
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-hover table-striped">
            <thead>
            <tr>
                <th>Código</th>
                <th>Grupo</th>
                <th>Nome</th>
                <th>Email</th>
                <th>Situação</th>
                <th class='text-center' width="190px">Ação</th>
            </tr>
            </thead>
            <tbody>

            @if(!isset($lista) || $lista->IsEmpty())
                <tr><td colspan="6">Sem resultados</td></tr>
            @else
                @foreach($lista as $operador)
                    <tr>
                        <td class='text-center'>{{ $operador->id }}</td>
                        <td class='text-center'>{{ ($operador->nm_grupo_op )}}</td>
                        <td class='text-center'>{{ $operador->nm_pessoa }}</td>
                        <td class='text-center'>{{ $operador->email }}</td>
                        <td class='text-center {{ ($operador->id_situacao == 'A' ? 'text-primary' : 'text-danger')}}'>
                            {{ ($operador->id_situacao == 'A' ? 'Ativo' : 'Inativo')}}
                        </td>
                        <td class='text-center'>
                            <a href="{{ route('operadores/cadastro').'/'.$operador->id }}" class='btn btn-primary btn-sm'>Detalhes</a>
                            <button type='button' data-tabela="users" data-chave="id" data-valor="{{ $operador->id }}" class='{{ verficaPermissaoBotao('recurso.operadores-excluir')  }} btn btn-danger btn-sm btn-excluir'>Excluir</button>
                        </td>
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
    </div>
    <div class="row">
        <div class="col-md-6">
            <a href="{{ route('operadores/cadastro') }}" class="btn btn-success {{ verficaPermissaoBotao('recurso.operadores-adicionar')  }}"><i class="fa fa-plus"></i> Cadastrar Operador</a>
        </div>
        <div class="col-md-6">
            <div class='pull-right'>@isset($lista){{  $lista->links() }}@endisset</div>
        </div>
    </div>
@endsection