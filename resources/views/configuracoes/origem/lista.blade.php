@extends('layouts.default')

@section('conteudo')

    <div class="panel panel-default ">
        <div class="panel-body">
            {!! Form::open(["method" => "GET", "class" => "form-search"]) !!}
            <div class="container-fluid">
                <div class="col-sm-3">
                    <label>Nome</label>
                    {!! Form::text('nm_origem',(!empty($_REQUEST['nm_origem']) ? $_REQUEST['nm_origem'] : ""),["name" => "nm_origem", "id" => "busca_origem", "placeholder" => "Digite o nome do origem",'class'=>'form-control']) !!}
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
                <th>Descrição</th>
                <th class='text-center' width="190px">Ação</th>
            </tr>
            </thead>
            <tbody>

            @if(!isset($lista) || $lista->IsEmpty())
                <tr><td colspan="3">Sem resultados</td></tr>
            @else
                @foreach($lista as $origem)
                    <tr>
                        <td class='text-center'>{{ $origem->nm_origem }}</td>
                        <td class='text-center'>
                            <a href="{{ route('configuracoes/origem/cadastro').'/'.$origem->cd_origem }}" class={{ verficaPermissaoBotao('recurso.configuracoes/origem-editar') }} 'btn btn-primary btn-sm'>Editar</a>
                            <button type='button' data-tabela="origem" data-chave="cd_origem" data-valor="{{ $origem->cd_origem }}" class='{{ verficaPermissaoBotao('recurso.configuracoes/origem-excluir')  }} btn btn-danger btn-sm btn-excluir'>Excluir</button>
                        </td>
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
    </div>
    <div class="row">
        <div class="col-md-6">
            <a href="{{ route('configuracoes/origem/cadastro') }}" class="{{ verficaPermissaoBotao('recurso.configuracoes/origem-adicionar')  }} btn btn-success"><i class="fa fa-plus"></i> Cadastrar Origem</a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class='pull-right'>@isset($lista){{  $lista->links() }}@endisset</div>
        </div>
    </div>

@endsection