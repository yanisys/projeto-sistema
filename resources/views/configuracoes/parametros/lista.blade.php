@extends('layouts.default')

@section('conteudo')

    <div class="panel panel-default ">
        <div class="panel-body">
            {!! Form::open(["method" => "GET", "class" => "form-search"]) !!}
            <div class="container-fluid">
                <div class="col-sm-3">
                    <label>Nome</label>
                    {!! Form::text('descricao',(!empty($_REQUEST['descricao']) ? $_REQUEST['descricao'] : ""),["name" => "descricao", "id" => "descricao", "placeholder" => "Digite o nome do parâmetro",'class'=>'form-control']) !!}
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
                <th>Valor</th>
                <th class='text-center' width="190px">Ação</th>
            </tr>
            </thead>
            <tbody>

            @if(!isset($lista) || $lista->IsEmpty())
                <tr><td colspan="3">Sem resultados</td></tr>
            @else
                @foreach($lista as $par)
                    <tr>
                        <td class='text-center'>{{ $par->cd_configuracao }}</td>
                        <td class='text-center maiusculas'>{{ $par->descricao }}</td>
                        <td class='text-center maiusculas'>{{ $valores[$par->cd_configuracao][$par->valor] }}</td>
                        <td class='text-center'>
                            <a href="{{ route('configuracoes/parametros/cadastro').'/'.$par->cd_configuracao }}" class={{ verficaPermissaoBotao('recurso.configuracoes/alergias-editar') }} 'btn btn-primary btn-sm'>Editar</a>
                        </td>
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class='pull-right'>@isset($lista){{  $lista->links() }}@endisset</div>
        </div>
    </div>

@endsection