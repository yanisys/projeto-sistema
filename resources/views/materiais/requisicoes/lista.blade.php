@extends('layouts.default')

@section('conteudo')

    <div class="panel panel-default ">
        <div class="panel-body">
            {!! Form::open(["method" => "GET", "class" => "form-search"]) !!}
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-3">
                        <label>Estoque</label>
                        {{  Form::select('cd_sala_destino', $sala, (session()->get('cd_sala') ? session()->get('cd_sala') : 0),['class'=> "form-control", 'id' => 'cd_sala_destino']) }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-2">
                        <label>Data Inicial</label>
                        {!! Form::text('dt_ini',(!empty($_REQUEST['dt_ini']) ? $_REQUEST['dt_ini'] :  date('d/m/Y') ),["name" => "dt_ini", 'placeholder' => 'dd/mm/aaaa', 'class'=>'form-control mask-data']) !!}
                    </div>
                    <div class="col-sm-2">
                        <label>Data Final</label>
                        {!! Form::text('dt_fim',(!empty($_REQUEST['dt_fim']) ? $_REQUEST['dt_fim'] : date('d/m/Y')),["name" => "dt_fim", 'placeholder' => 'dd/mm/aaaa', 'class'=>'form-control mask-data']) !!}
                    </div>
                    <div class="col-sm-2">
                        <label>Resp. solicitação</label>
                        {!! Form::text('responsavel',(!empty($_REQUEST['responsavel']) ? $_REQUEST['responsavel'] : ''),["name" => "responsavel", 'placeholder' => 'Infome o nome do responsável', 'class'=>'form-control']) !!}
                    </div>
                    <div class="col-md-3">
                        <label>Origem solicitação</label>
                        {{  Form::select('cd_sala_origem', $sala, (isset($_REQUEST['cd_sala_origem']) ? $_REQUEST['cd_sala_origem'] : 0),['class'=> "form-control", 'id' => 'cd_sala_origem']) }}
                    </div>
                    <div class="col-md-2">
                        <label>Status</label>
                        {{  Form::select('situacao', ['T' => 'Todos', 'A' => 'Em aberto','C' => 'Concluído'], (isset($_REQUEST['situacao']) ? $_REQUEST['situacao'] : 'A'),['class'=> "form-control", 'id' => 'situacao']) }}
                    </div>
                    <div class="col-sm-1 ">
                        {!!  Form::submit('Buscar',['class'=>"btn btn-default margin-top-25"]) !!}
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover table-striped">
            <thead>
            <tr>
                <th>Data</th>
                <th>Resp. solicitação</th>
                <th>Origem da solicitação</th>
                <th>Estoque</th>
                <th>Situação</th>
                <th class='text-center' width="190px">Ação</th>
            </tr>
            </thead>
            <tbody>

            @if(!isset($lista) || $lista->IsEmpty())
                <tr><td colspan="3">Sem resultados</td></tr>
            @else
                @foreach($lista as $requisicoes)
                    <tr>
                        <td class='text-center'>{{ formata_data_hora($requisicoes->created_at) }}</td>
                        <td class='text-center'>{{ $requisicoes->nm_pessoa }}</td>
                        <td class='text-center'>{{ $requisicoes->nm_sala_origem }}</td>
                        <td class='text-center'>{{ $requisicoes->nm_sala_destino }}</td>
                        <td class='text-center {{ ($requisicoes->situacao == 'A' ? 'text-primary' : 'text-default')}}'>
                            {{ ($requisicoes->situacao == 'A' ? 'Em aberto' : 'Concluído')}}
                        </td>
                        <td class='text-center'>
                            <a href="{{ route('materiais/requisicoes/requisicao-pdf').'/'.$requisicoes->cd_requisicao }}" title="Imprimir a requisição" target="_blank" class={{ verficaPermissaoBotao('recurso.materiais/requisicoes-editar') }} 'btn btn-primary btn-sm'><span class="fas fa-print"></span></a>
                            <a href="{{ route('materiais/requisicoes/cadastro').'/'.$requisicoes->cd_requisicao }}" title="Editar a requisição" class={{ verficaPermissaoBotao('recurso.materiais/requisicoes-editar') }} 'btn btn-primary btn-sm'><span class="fas fa-edit"></span></a>
                            <a href="{{ route('materiais/requisicoes/atendimento').'/'.$requisicoes->cd_requisicao }}" title="Atender a requisição" class={{ verficaPermissaoBotao('recurso.materiais/requisicoes-editar') }} 'btn btn-primary btn-sm'><span class="fas fa-clipboard-check"></span></a>
                            <button type='button' data-tabela="requisicoes" data-chave="cd_requisicao" title="Excluir a requisição" data-valor="{{ $requisicoes->cd_requisicao }}" class='{{ verficaPermissaoBotao('recurso.materiais/requisicoes-excluir')  }} btn btn-danger btn-sm btn-excluir'><span class="fas fa-trash"></span></button>
                        </td>
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
    </div>
    <div class="row">
        <div class="col-md-6">
            <a href="{{ route('materiais/requisicoes/cadastro') }}" class="{{ verficaPermissaoBotao('recurso.materiais/requisicoes-adicionar')  }} btn btn-success"><i class="fa fa-plus"></i> Cadastrar Requisição</a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class='pull-right'>@isset($lista){{  $lista->links() }}@endisset</div>
        </div>
    </div>

@endsection