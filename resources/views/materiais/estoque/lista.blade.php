@extends('layouts.default')

@section('conteudo')

    <div class="panel panel-default ">
        <div class="panel-body">
            {!! Form::open(["method" => "GET", "class" => "form-search"]) !!}
            <div class="container-fluid">
                <div class="col-md-3">
                    <label for="id_produto">Localização<br></label>
                    {{  Form::select('cd_sala', $sala, (isset($_REQUEST['cd_sala']) ? $_REQUEST['cd_sala'] : ""),['class'=> "form-control", 'id' => 'cd_sala']) }}
                </div>
                <div class="col-sm-3">
                    <label>Nome</label>
                    {!! Form::text('nm_produto',(!empty($_REQUEST['nm_produto']) ? $_REQUEST['nm_produto'] : ""),["name" => "nm_produto", "id" => "busca_produto", "placeholder" => "Digite o nome do produto",'class'=>'form-control']) !!}
                </div>
                <div class="col-sm-2">
                    <label>Lote</label>
                    {!! Form::text('lote',(!empty($_REQUEST['lote']) ? $_REQUEST['lote'] : ""),["name" => "lote", "id" => "lote", "placeholder" => "Digite o Nº do lote",'class'=>'form-control']) !!}
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
                <th>Localização</th>
                <th>Produto</th>
                <th>Lote</th>
                <th>Fabricação</th>
                <th>Validade</th>
                <th>Quantidade</th>
            </tr>
            </thead>
            <tbody>

            @if(!isset($estoque))
                <tr><td colspan="3">Sem resultados</td></tr>
            @else
                @foreach($estoque as $e)
                    @if($e->quantidade != 0)
                        <tr>
                                <td>{{ $e->nm_sala }}</td>
                                <td>
                                    @if(!(session()->get('recurso.materiais/produto-editar')))
                                        {{ $e->nm_produto." - ".$e->ds_produto }}
                                    @else
                                    <a href="{{ route('materiais/produto/cadastro').'/'.$e->cd_produto }}" title="Clique aqui para ir para o cadastro do produto" target="_blank" style="color: #000030" }}>
                                        {{ $e->nm_produto." - ".$e->ds_produto }}
                                    </a>
                                    @endif
                                </td>
                                <td class='text-center'>{{ $e->lote }}</td>
                                <td class='text-center'>{{ formata_data($e->dt_fabricacao) }}</td>
                                <td class='text-center'>{{ formata_data($e->dt_validade) }}</td>
                                <td class='text-center'>{{ $e->quantidade . " " . ($e->fracionamento == 0 ? $e->nm_unidade_comercial : $e->nm_unidade_medida)}}</td>
                        </tr>
                    @endif
                @endforeach
            @endif
            </tbody>
        </table>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class='pull-right'>@isset($estoque){{  $estoque->links() }}@endisset</div>
        </div>
    </div>

@endsection