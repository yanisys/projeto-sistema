@extends('layouts.default')

@section('conteudo')

    <div class="panel panel-default ">
        <div class="panel-body">
            {!! Form::open(["method" => "GET", "class" => "form-search"]) !!}
            <div class="container-fluid">
                <div class="col-sm-3">
                    <label>Nome</label>
                    {!! Form::text('nm_alergia',(!empty($_REQUEST['nm_alergia']) ? $_REQUEST['nm_alergia'] : ""),["name" => "nm_alergia", "id" => "busca_alergia", "placeholder" => "Digite o nome do alergia",'class'=>'form-control']) !!}
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
                @foreach($lista as $alergia)
                    <tr>
                        <td class='text-center'>{{ $alergia->cd_alergia }}</td>
                        <td class='text-center'>{{ $alergia->nm_alergia }}</td>
                        <td class='text-center'>
                            <a href="{{ route('configuracoes/alergias/cadastro').'/'.$alergia->cd_alergia }}" class={{ verficaPermissaoBotao('recurso.configuracoes/alergias-editar') }} 'btn btn-primary btn-sm'>Editar</a>
                            <button type='button' data-tabela="alergia" data-chave="cd_alergia" data-valor="{{ $alergia->cd_alergia }}" class='{{ verficaPermissaoBotao('recurso.configuracoes/alergias-excluir')  }} btn btn-danger btn-sm btn-excluir'>Excluir</button>
                        </td>
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
    </div>
    <div class="row">
        <div class="col-md-6">
            <a href="{{ route('configuracoes/alergias/cadastro') }}" class="{{ verficaPermissaoBotao('recurso.configuracoes/alergias-adicionar')  }} btn btn-success"><i class="fa fa-plus"></i> Cadastrar Alergia</a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class='pull-right'>@isset($lista){{  $lista->links() }}@endisset</div>
        </div>
    </div>

@endsection