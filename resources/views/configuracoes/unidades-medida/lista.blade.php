@extends('layouts.default')

@section('conteudo')

    <div class="panel panel-default ">
        <div class="panel-body">
            {!! Form::open(["method" => "GET", "class" => "form-search"]) !!}
            <div class="container-fluid">
                <div class="col-sm-3">
                    <label>Nome</label>
                    {!! Form::text('nm_unidade_medida',(!empty($_REQUEST['nm_unidade_medida']) ? $_REQUEST['nm_unidade_medida'] : ""),["name" => "nm_unidade_medida", "id" => "nm_unidade_medida", "placeholder" => "Digite o nome da unidade de medida",'class'=>'form-control']) !!}
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
                <th>Abreviação</th>
                <th>Situação</th>
                <th class='text-center' width="190px">Ação</th>
            </tr>
            </thead>
            <tbody>

            @if(!isset($lista) || $lista->IsEmpty())
                <tr><td colspan="3">Sem resultados</td></tr>
            @else
                @foreach($lista as $umed)
                    <tr>
                        <td class='text-center'>{{ $umed->cd_unidade_medida }}</td>
                        <td class='text-center'>{{ $umed->nm_unidade_medida }}</td>
                        <td class='text-center'>{{ $umed->abreviacao }}</td>
                        <td class='text-center {{ ($umed->situacao == 'A' ? 'text-primary' : 'text-danger')}}'>
                            {{ ($umed->situacao == 'A' ? 'Ativo' : 'Inativo') }}
                        </td>
                        <td class='text-center'>
                            <a href="{{ route('configuracoes/unidades-medida/cadastro').'/'.$umed->cd_unidade_medida }}" class={{ verficaPermissaoBotao('recurso.configuracoes/alergias-editar') }} 'btn btn-primary btn-sm'>Editar</a>
                            <button type='button' data-tabela="unidade_medida" data-chave="cd_unidade_medida" data-valor="{{ $umed->cd_unidade_medida }}" class='{{ verficaPermissaoBotao('recurso.configuracoes/alergias-excluir')  }} btn btn-danger btn-sm btn-excluir'>Excluir</button>
                        </td>
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
    </div>
    <div class="row">
        <div class="col-md-6">
            <a href="{{ route('configuracoes/unidades-medida/cadastro') }}" class="{{ verficaPermissaoBotao('recurso.configuracoes/alergias-adicionar')  }} btn btn-success"><i class="fa fa-plus"></i> Cadastrar</a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class='pull-right'>@isset($lista){{  $lista->links() }}@endisset</div>
        </div>
    </div>

@endsection