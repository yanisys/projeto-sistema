@extends('layouts.default')

@section('conteudo')

    <div class="panel panel-default ">
        <div class="panel-body">
            {!! Form::open(["method" => "GET", "class" => "form-search"]) !!}
            <div class="container-fluid">
                <div class="col-sm-3">
                    <label>Nome</label>
                    {!! Form::text('ds_plano',(!empty($_REQUEST['ds_plano']) ? $_REQUEST['ds_plano'] : ""),["name" => "ds_plano", "id" => "busca_plano", "placeholder" => "Digite o nome do plano",'class'=>'form-control']) !!}
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
                <th>Tipo de plano</th>
                <th class='text-center' width="190px">Ação</th>
            </tr>
            </thead>
            <tbody>

            @if(!isset($lista) || $lista->IsEmpty())
                <tr><td colspan="4">Sem resultados</td></tr>
            @else
                @foreach($lista as $plano)
                    <tr>
                        <td class='text-center'>{{ $plano->cd_plano }}</td>
                        <td class='text-center'>{{ $plano->ds_plano }}</td>
                        <td class='text-center'>{{ arrayPadrao('tipo_plano')[$plano->tp_plano] }}</td>
                        <td class='text-center'>
                            <a href="{{ route('planos/cadastro').'/'.$plano->cd_plano }}" class="btn btn-primary btn-sm">Detalhes</a>
                            <button type='button' data-tabela="plano" data-chave="cd_plano" data-valor="{{ $plano->cd_plano }}" class='{{ verficaPermissaoBotao('recurso.planos-excluir')  }} btn btn-danger btn-sm btn-excluir'>Excluir</button>
                        </td>
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
    </div>
    <div class="row">
        <div class="col-md-6">
            <a href="{{ route('planos/cadastro') }}" class="btn btn-success {{ verficaPermissaoBotao('recurso.planos-adicionar') }}"><i class="fa fa-plus"></i>Cadastrar Plano</a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class='pull-right'>@isset($lista){{  $lista->links() }}@endisset</div>
        </div>
    </div>
@endsection