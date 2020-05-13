@extends('layouts.default')

@section('conteudo')

    <div class="panel panel-default ">
        <div class="panel-body">
            {!! Form::open(["method" => "GET", "class" => "form-search"]) !!}
            <div class="container-fluid">
                <div class="col-sm-3">
                    <label>Nome</label>
                    {!! Form::text('nm_sala',(!empty($_REQUEST['nm_sala']) ? $_REQUEST['nm_sala'] : ""),["name" => "nm_sala", "id" => "busca_sala", "placeholder" => "Digite o nome do local",'class'=>'form-control']) !!}
                </div>
                <div class="col-sm-3">
                    <label>Tipo</label>
                    {{  Form::select('tipo', arrayPadrao('tipo_sala',true), (isset($_REQUEST['tipo']) ? $_REQUEST['tipo'] : "T"),["name" => "tipo", 'class'=>  "form-control", 'id' => 'tipo']) }}
                </div>
                <div class="col-sm-3">
                    <label>Situação</label>
                    {{  Form::select('situacao', arrayPadrao('situacao',true), (isset($_REQUEST['situacao']) ? $_REQUEST['situacao'] : "T"),['class'=>  "form-control", 'id' => 'situacao']) }}
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
                <th>Tipo</th>
                <th>Situação</th>
                <th class='text-center' width="190px">Ação</th>
            </tr>
            </thead>
            <tbody>

            @if(!isset($lista) || $lista->IsEmpty())
                <tr><td colspan="5">Sem resultados</td></tr>
            @else
                @foreach($lista as $sala)
                    <tr>
                        <td class='text-center'>{{ $sala->cd_sala }}</td>
                        <td class='text-center'>{{ $sala->nm_sala }}</td>
                        <td class='text-center'>{{ arrayPadrao('tipo_sala')[$sala->tipo] }}</td>
                        <td class='text-center {{ ($sala->situacao == 'A' ? 'text-primary' : 'text-danger')}}'>{{ ($sala->situacao == 'A' ? 'Ativo' : 'Inativo')}}</td>
                        <td class='text-center'>
                            <a href="{{ route('salas/cadastro').'/'.$sala->cd_sala }}" class={{ verficaPermissaoBotao('recurso.salas-editar') }} 'btn btn-primary btn-sm'>Editar</a>
                            <button type='button' data-tabela="sala" data-chave="cd_sala" data-valor="{{ $sala->cd_sala }}" class='{{ verficaPermissaoBotao('recurso.salas-excluir')  }} btn btn-danger btn-sm btn-excluir'>Excluir</button>
                        </td>
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
    </div>
    <div class="row">
        <div class="col-md-6">
            <a href="{{ route('salas/cadastro') }}" class="{{ verficaPermissaoBotao('recurso.salas-adicionar')  }} btn btn-success"><i class="fa fa-plus"></i> Cadastrar Sala</a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class='pull-right'>@isset($lista){{  $lista->links() }}@endisset</div>
        </div>
    </div>

@endsection