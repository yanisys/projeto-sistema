@extends('layouts.default')

@section('conteudo')

    <div class="panel panel-default ">
        <div class="panel-body">
            {!! Form::open(["method" => "GET", "class" => "form-search"]) !!}
            <div class="container-fluid">
                <div class="col-sm-3">
                    <label>Código do cartão</label>
                    {!! Form::text('cd_beneficiario',(!empty($_REQUEST['cd_beneficiario']) ? $_REQUEST['cd_beneficiario'] : ""),["name" => "cd_beneficiario", "id" => "cd_beneficiario", "placeholder" => "Digite um número de cartão",'class'=>'form-control mask-numeros-20']) !!}
                </div>
                <div class="col-sm-2">
                    <label>Nome</label>
                    {!! Form::text('NOME',(!empty($_REQUEST['nome']) ? $_REQUEST['nome'] : ""),["name" => "nome", "id" => "nome", "placeholder" => "Digite um nome",'class'=>'form-control ']) !!}
                </div>
                <div class="col-sm-2">
                    <label>Plano</label>
                    {{  Form::select('cd_plano', $planos, (isset($_REQUEST['cd_plano']) ? $_REQUEST['cd_plano'] : "T"),['class'=>  "form-control", 'id' => 'status']) }}
                </div>
                <div class="col-sm-2">
                    <label>Tipo de beneficiário</label>
                    {{  Form::select('parentesco', arrayPadrao('parentesco',true), (isset($_REQUEST['parentesco']) ? $_REQUEST['parentesco'] : "T"),['class'=>  "form-control", 'id' => 'parentesco']) }}
                </div>
                <div class="col-sm-2">
                    <label>Situação</label>
                    {{  Form::select('id_situacao', arrayPadrao('situacao',true), (isset($_REQUEST['id_situacao']) ? $_REQUEST['id_situacao'] : "T"),['class'=>  "form-control", 'id' => 'id_situacao']) }}
                </div>
                {!!  Form::submit('Buscar',['class'=>"btn btn-default margin-top-25 pull-right"]) !!}
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
                <th>Plano</th>
                <th>Tipo de beneficiário</th>
                <th>Situação</th>
                <th class='text-center' width="190px">Ação</th>
            </tr>
            </thead>
            <tbody>

            @if(!isset($lista) || $lista->IsEmpty())
                <tr><td colspan="6">Sem resultados</td></tr>
            @else
                @foreach($lista as $beneficiario)
                    <tr>
                        <td class='text-center'>{{ $beneficiario->cd_beneficiario }}</td>
                        <td class='text-center'>{{ $beneficiario->nm_pessoa }}</td>
                        <td class='text-center'>{{ $beneficiario->ds_plano }}</td>
                        <td class='text-center'>{{ arrayPadrao('parentesco')[$beneficiario->parentesco] }}</td>
                        <td class='text-center {{ ($beneficiario->id_situacao == 'A' ? 'text-primary' : 'text-danger')}}'>
                            {{ ($beneficiario->id_situacao == 'A' ? 'Ativo' : 'Inativo')}}
                        </td>
                        <td class='text-center'>
                            <a href="{{ route('beneficiarios/cadastro').'/'.$beneficiario->id_beneficiario }}" class='btn btn-primary btn-sm'>Detalhes</a>
                            <button type='button' data-tabela="beneficiario" data-chave="id_beneficiario" data-valor="{{ $beneficiario->id_beneficiario }}" class='{{ verficaPermissaoBotao('recurso.beneficiarios-excluir')  }} btn btn-danger btn-sm btn-excluir'>Excluir</button>
                        </td>
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
    </div>
    <div class="row">
        <div class="col-md-6">
            <a href="{{ route('beneficiarios/cadastro') }}" class="btn btn-success {{ verficaPermissaoBotao('recurso.beneficiarios-adicionar')  }}"><i class="fa fa-plus"></i> Cadastrar Beneficiário</a>
        </div>
        <div class="col-md-6">
            <div class='pull-right'>@isset($lista){{  $lista->links() }}@endisset</div>
        </div>
    </div>
@endsection