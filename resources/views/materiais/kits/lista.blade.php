@extends('layouts.default')

@section('conteudo')

    <div class="panel panel-default ">
        <div class="panel-body">
            {!! Form::open(["method" => "GET", "class" => "form-search"]) !!}
            <div class="container-fluid">
                <div class="col-sm-3">
                    <label>Nome</label>
                    {!! Form::text('nm_kit',(!empty($_REQUEST['nm_kit']) ? $_REQUEST['nm_kit'] : ""),["name" => "nm_kit", "id" => "busca_kits", "placeholder" => "Digite o nome do local",'class'=>'form-control']) !!}
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
                <th class='text-center' width="190px">Ação</th>
            </tr>
            </thead>
            <tbody>

            @if(!isset($lista) || $lista->IsEmpty())
                <tr><td colspan="3">Sem resultados</td></tr>
            @else
                @foreach($lista as $kits)
                    <tr>
                        <td class='text-center'>{{ $kits->cd_kit }}</td>
                        <td class='text-center'>{{ $kits->nm_kit }}</td>
                        <td class='text-center'>
                            <a href="{{ route('materiais/kits/cadastro').'/'.$kits->cd_kit }}" class={{ verficaPermissaoBotao('recurso.materiais/kits-editar') }} 'btn btn-primary btn-sm'>Editar</a>
                            <button type='button' data-tabela="kits" data-chave="cd_kit" data-valor="{{ $kits->cd_kit }}" class='{{ verficaPermissaoBotao('recurso.materiais/kits-excluir')  }} btn btn-danger btn-sm btn-excluir'>Excluir</button>
                        </td>
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
    </div>
    <div class="row">
        <div class="col-md-6">
            <a href="{{ route('materiais/kits/cadastro') }}" class="{{ verficaPermissaoBotao('recurso.materiais/kits-adicionar')  }} btn btn-success"><i class="fa fa-plus"></i> Cadastrar Kit</a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class='pull-right'>@isset($lista){{  $lista->links() }}@endisset</div>
        </div>
    </div>

@endsection