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
                    {{  Form::select('status', arrayPadrao('situacao',true), (isset($_REQUEST['status']) ? $_REQUEST['status'] : "T"),['class'=>  "form-control", 'id' => 'status']) }}
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
                <th>Nome</th>
                <th>Profissão</th>
                <th>Conselho/ Inscrição</th>
                <th>CNS</th>
                <th>Situação</th>
                <th class='text-center' width="190px">Ação</th>
            </tr>
            </thead>
            <tbody>

            @if(!isset($lista) || $lista->IsEmpty())
                <tr><td colspan="6">Sem resultados</td></tr>
            @else
                @foreach($lista as $profissional)
                    <tr>
                        <td class='text-center'>{{ $profissional->cd_profissional }}</td>
                        <td class='text-center'>{{ ($profissional->nm_pessoa )}}</td>
                        <td class='text-center'>{{ $profissional->nm_ocupacao }}</td>
                        <td class='text-center'>{{ $profissional->conselho.": ".$profissional->nr_conselho }}</td>
                        <td class='text-center'>{{ ($profissional->cd_beneficiario == '' ? 'Não informado' : $profissional->cd_beneficiario) }}</td>
                        <td class='text-center {{ ($profissional->status == 'A' ? 'text-primary' : 'text-danger')}}'>
                            {{ ($profissional->status == 'A' ? 'Ativo' : 'Inativo')}}
                        </td>
                        <td class='text-center'>
                            <a href="{{ route('profissionais/cadastro').'/'.$profissional->cd_profissional }}" class='btn btn-primary btn-sm'>Detalhes</a>
                            <button type='button' data-tabela="profissional" data-chave="cd_profissional" data-valor="{{ $profissional->cd_profissional }}" class='{{ verficaPermissaoBotao('recurso.profissionais-excluir')  }} btn btn-danger btn-sm btn-excluir'>Excluir</button>
                        </td>
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
    </div>
    <div class="row">
        <div class="col-md-6">
            <a href="{{ route('profissionais/cadastro') }}" class="btn btn-success {{ verficaPermissaoBotao('recurso.profissionais-adicionar')  }}"><i class="fa fa-plus"></i> Cadastrar Profissional</a>
        </div>
        <div class="col-md-6">
            <div class='pull-right'>@isset($lista){{  $lista->links() }}@endisset</div>
        </div>
    </div>
@endsection