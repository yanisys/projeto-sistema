@extends('layouts.default')

@section('conteudo')

    <div class="panel panel-default ">
        <div class="panel-body">
            {!! Form::open(["method" => "GET", "class" => "form-search"]) !!}
            <div class="container-fluid">
                <div class="col-sm-3">
                    <label>Nome</label>
                    {!! Form::text('nome',(!empty($_REQUEST['nome']) ? $_REQUEST['nome'] : ""),["name" => "nome", "id" => "nome", "placeholder" => "Digite o nome do estabelecimento",'class'=>'form-control']) !!}
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
                <th>Nome</th>
                <th>Cnes</th>
                <th>Tipo</th>
                <th>Situação</th>
                <th class='text-center' width="190px">Ação</th>
            </tr>
            </thead>
            <tbody>

            @if(!isset($lista) || $lista->IsEmpty())
                <tr><td colspan="5">Sem resultados</td></tr>
            @else
                @foreach($lista as $estabelecimento)
                    <tr>
                        <td class='text-center'>{{ $estabelecimento->cd_estabelecimento }}</td>
                        <td class='text-center'>{{ $estabelecimento->nm_estabelecimento }}</td>
                        <td class='text-center'>{{ $estabelecimento->cnes }}</td>
                        <td class='text-center'>{{ arrayPadrao('tipo_estabelecimento')[$estabelecimento->tp_estabelecimento] }}</td>
                        <td class='text-center {{ ($estabelecimento->status == 'A' ? 'text-primary' : 'text-danger')}}'>
                            {{ ($estabelecimento->status == 'A' ? 'Ativo' : 'Inativo') }}
                        </td>
                        <td class='text-center'>
                            <a href="{{ route('estabelecimentos/cadastro').'/'.$estabelecimento->cd_estabelecimento }}" class='btn btn-primary btn-sm'>Editar</a>
                            <button type='button' data-tabela="estabelecimentos" data-chave="cd_estabelecimento" data-valor="{{ $estabelecimento->cd_estabelecimento }}" class='{{ verficaPermissaoBotao('recurso.estabelecimentos-excluir')  }} btn btn-danger btn-sm btn-excluir'>Excluir</button>
                        </td>
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
    </div>
    <div class="row">
        <div class="col-md-6">
            <a href="{{ route('estabelecimentos/cadastro') }}" class="{{ verficaPermissaoBotao('recurso.estabelecimentos-adicionar')  }} btn btn-success"><i class="fa fa-plus"></i> Cadastrar Estabelecimento</a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class='pull-right'>@isset($lista){{  $lista->links() }}@endisset</div>
        </div>
    </div>

@endsection