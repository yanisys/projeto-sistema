@extends('layouts.default')

@section('conteudo')

    <div class="panel panel-default ">
        <div class="panel-body">
            {!! Form::open(["method" => "GET", "class" => "form-search"]) !!}
            <div class="container-fluid">
                <div class="col-sm-2">
                    <label>Código</label>
                    {!! Form::text('cd_movimentacao',(!empty($_REQUEST['cd_movimentacao']) ? $_REQUEST['cd_movimentacao'] : ""),["name" => "cd_movimentacao", "placeholder" => "Digite o código",'class'=>'form-control']) !!}
                </div>
                <div class="col-sm-2">
                    <label>Data Inicial</label>
                    {!! Form::date('dt_ini',(!empty($_REQUEST['dt_ini']) ? $_REQUEST['dt_ini'] :  date('Y/m/01') ),["name" => "dt_ini", "min" => "1900-01-01", "max" => "2099-12-31", "class"=>"form-control"]) !!}
                </div>
                <div class="col-sm-2">
                    <label>Data Final</label>
                    {!! Form::date('dt_fim',(!empty($_REQUEST['dt_fim']) ? $_REQUEST['dt_fim'] : date('Y/m/d')),["name" => "dt_fim", "min" => "1900-01-01", "max" => "2099-12-31", 'class'=>'form-control']) !!}
                </div>
                <div class="col-sm-3">
                    <label>Descrição</label>
                    {!! Form::text('nm_movimento',(!empty($_REQUEST['nm_movimento']) ? $_REQUEST['nm_movimento'] : ""),["name" => "nm_movimento", "placeholder" => "Digite o nome do movimento",'class'=>'form-control']) !!}
                </div>
                <div class="col-sm-2 ">
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
                <th class='text-center' width="190px">Data da movimentação</th>
                <th>Descrição</th>
                <th class='text-center' width="190px">Ação</th>
            </tr>
            </thead>
            <tbody>

            @if(!isset($lista) || $lista->IsEmpty())
                <tr><td colspan="3">Sem resultados</td></tr>
            @else
                @foreach($lista as $movimentacao)
                    <tr>
                        <td class='text-center'>{{ $movimentacao->cd_movimentacao }}</td>
                        <td class='text-center'>{{ formata_data($movimentacao->created_at) }}</td>
                        <td class='text-center'>{{ $movimentacao->nm_movimento }}</td>
                        <td class='text-center'>
                            <a href="{{ route('materiais/movimentacao/cadastro').'/'.$movimentacao->cd_movimentacao }}" class={{ verficaPermissaoBotao('recurso.materiais/movimentacao-editar') }} 'btn btn-primary btn-sm'>Editar</a>
                            <button type='button' data-tabela="movimentacao" data-chave="cd_movimentacao" data-valor="{{$movimentacao->cd_movimentacao}}" class='{{ verficaPermissaoBotao('recurso.materiais/movimentacao-excluir')  }} btn btn-danger btn-sm btn-excluir'>Excluir</button>
                        </td>
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
     </div>
    <div class="row">
        <div class="col-md-6">
            <a href="{{ route('materiais/movimentacao/cadastro') }}" class="{{ verficaPermissaoBotao('recurso.materiais/movimentacao-adicionar')  }} btn btn-success"><i class="fa fa-plus"></i> Cadastrar Movimentação</a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class='pull-right'>@isset($lista){{  $lista->links() }}@endisset</div>
        </div>
    </div>

@endsection