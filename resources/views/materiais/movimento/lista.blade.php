@extends('layouts.default')

@section('conteudo')

    <div class="panel panel-default ">
        <div class="panel-body">
            {!! Form::open(["method" => "GET", "class" => "form-search"]) !!}
            <div class="container-fluid">
                <div class="col-sm-3">
                    <label>Nome</label>
                    {!! Form::text('nm_movimento',(!empty($_REQUEST['nm_movimento']) ? $_REQUEST['nm_movimento'] : ""),["name" => "nm_movimento", "id" => "busca_movimento", "placeholder" => "Digite o nome da movimento",'class'=>'form-control']) !!}
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
                <th>Tipo de movimentação</th>
                <th>Situação</th>
                <th class='text-center' width="190px">Ação</th>
            </tr>
            </thead>
            <tbody>

            @if(!isset($lista) || $lista->IsEmpty())
                <tr><td colspan="3">Sem resultados</td></tr>
            @else
                @foreach($lista as $movimento)
                    <tr>
                        <td class='text-center'>{{ $movimento->cd_movimento }}</td>
                        <td class='text-center'>{{ $movimento->nm_movimento }}</td>
                        <td class='text-center'>{{ arrayPadrao('tipo_movimento')[$movimento->tp_movimento] }}</td>
                        <td class='text-center {{ ($movimento->situacao == 'A' ? 'text-primary' : 'text-danger')}}'>
                            {{ ($movimento->situacao == 'A' ? 'Ativo' : 'Inativo')}}
                        </td>
                        <td class='text-center'>
                            <a href="{{ route('materiais/movimento/cadastro').'/'.$movimento->cd_movimento }}" class={{ verficaPermissaoBotao('recurso.materiais/movimento-editar') }} 'btn btn-primary btn-sm'>Editar</a>
                            <button type='button' data-tabela="movimento" data-chave="cd_movimento" data-valor="{{$movimento->cd_movimento}}" class='{{ verficaPermissaoBotao('recurso.materiais/movimento-excluir')  }} btn btn-danger btn-sm btn-excluir'>Excluir</button>
                        </td>
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
    </div>
    <div class="row">
        <div class="col-md-6">
            <a href="{{ route('materiais/movimento/cadastro') }}" class="{{ verficaPermissaoBotao('recurso.materiais/movimento-adicionar')  }} btn btn-success"><i class="fa fa-plus"></i> Cadastrar Movimentação</a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class='pull-right'>@isset($lista){{  $lista->links() }}@endisset</div>
        </div>
    </div>

@endsection