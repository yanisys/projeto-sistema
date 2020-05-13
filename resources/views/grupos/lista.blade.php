@extends('layouts.default')

@section('conteudo')
    <div class="panel panel-default ">
        <div class="panel-body">
            {!! Form::open(["method" => "GET", "class" => "form-search"]) !!}
            <div class="container-fluid">
                <div class="col-sm-3">
                    <label>Nome</label>
                    {!! Form::text('nome',(!empty($_REQUEST['nome']) ? $_REQUEST['nome'] : ""),["name" => "nome", "id" => "nome", "placeholder" => "Digite um nome",'class'=>'form-control ']) !!}
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
                <th>Nome</th>
                <th class='text-center' width="190px">Ação</th>
            </tr>
            </thead>
            <tbody id="tabela-permissoes">
            @if(!isset($lista) || $lista->IsEmpty())
                <tr><td colspan="2">Sem resultados</td></tr>
            @else
                @foreach($lista as $grupo)
                    <tr>
                        <td class='text-left'>{{ $grupo->nm_grupo_op }}</td>
                        <td class='text-center'>
                            <a href="{{ route('grupos/cadastro').'/'.$grupo->cd_grupo_op }}" class='btn btn-primary btn-sm'>Detalhes</a>
                            <button type='button' data-tabela="grupo_op" data-chave="cd_grupo_op" data-valor="{{ $grupo->cd_grupo_op }}" class='{{ verficaPermissaoBotao('recurso.grupos-excluir')  }} btn btn-danger btn-sm btn-excluir'>Excluir</button>
                        </td>
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
    </div>

    <div class="row">
        <div class="col-md-6">
            <a href="{{ route('grupos/cadastro') }}" class="{{ verficaPermissaoBotao('recurso.grupos-adicionar')  }} btn btn-success"><i class="fa fa-plus"></i> Cadastrar Grupo</a>
        </div>
        <div class="col-md-6">
            <div class='pull-right'>@isset($lista){{  $lista->links() }}@endisset</div>
        </div>
    </div>
@endsection
