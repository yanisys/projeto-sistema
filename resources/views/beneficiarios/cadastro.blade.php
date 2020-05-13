
@extends('layouts.default')

@section('conteudo')

    @if ($errors->any())
        <div class="alert alert-danger collapse in" id="collapseExample" xmlns="http://www.w3.org/1999/html">
            <h4 class="alert-heading">Os seguintes erros foram encontrados:</h4>
            <ul>
                @foreach ($errors->all() as $error)
                    <li> {{ $error }} </li>
                @endforeach
            </ul>
            <hr>
            <p class="mb-0">Por favor, verifique e tente novamente.</p>
            <p ><a class="btn btn-primary" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="true" aria-controls="collapseExample">
                    Fechar
                </a>
            </p>
        </div>
    @endif

    {{ Form::open(['id' => 'cadastra_beneficiario', 'class' => 'form-no-submit']) }}
    <div class="panel panel-primary">
        <div class="panel-heading">Dados do Titular
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="Contrato">Contrato<span style="color:red">*</span></label>
                        {{ Form::text('cd_contrato',(isset($beneficiario['cd_contrato']) ? $beneficiario['cd_contrato'] : ""),["name" => "cd_contrato", "maxlength" => "10", "id" => "cd_contrato", "disabled"=>"disabled", 'class'=> ($errors->has("id") ? "form-control is-invalid" : "form-control")]) }}
                        {{ Form::hidden('cd_contrato',(isset($beneficiario['cd_contrato']) ? $beneficiario['cd_contrato'] : ""),["name" => "cd_contrato", "id" => "cd_contrato"]) }}
                        {{ Form::hidden('id_beneficiario',(isset($beneficiario['id_beneficiario']) ? $beneficiario['id_beneficiario'] : ""),["name" => "id_beneficiario", "id" => "id"]) }}
                    </div>
                </div>                
                <div class="col-md-2 pull-right">
                    <div class="form-group">
                        <label for="status">Situação</label>
                        {{  Form::select('id_situacao', ['A' => 'Ativo', 'I' => 'Inativo'], (isset($beneficiario['id_situacao']) ? $beneficiario['id_situacao'] : 'A'),['class'=> ($errors->has("id_situacao") ? "form-control is-invalid" : "form-control"), 'id' => 'id_situacao']) }}
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-2">
                    <div class="input-group">
                        <label for="cd_pessoa">Pessoa<span style="color:red">*</span></label>
                        {{ Form::text('cd_pessoa',(isset($beneficiario['cd_pessoa']) ? $beneficiario['cd_pessoa'] : ""),["name" => "cd_pessoa", 'disabled', "maxlength" => "10", "id" => "cd_pessoa", 'class'=> ($errors->has("cd_pessoa") ? "form-control is-invalid" : "form-control cd_pessoa")]) }}
                        {{ Form::hidden('cd_pessoa',(isset($beneficiario['cd_pessoa']) ? $beneficiario['cd_pessoa'] : ""),["name" => "cd_pessoa", "id" => "cd_pessoa_disabled", "class" => "cd_pessoa"]) }}
                        <span class="input-group-btn">
                            <button class="btn btn-info margin-top-25 " type="button" data-toggle="modal" data-target="#modal-pesquisa" id="open"><span class="fa fa-search"></span> </button>
                        </span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="nm_pessoa">Nome<span style="color:red">*</span></label>
                        {{ Form::text('nm_pessoa', (isset($beneficiario['nm_pessoa']) ? $beneficiario['nm_pessoa'] : "") ,["disabled", "id" => "nm_pessoa", 'class'=> ($errors->has("nm_pessoa") ? "form-control is-invalid" : "form-control") ]) }}
                        {{ Form::hidden('nm_pessoa', (isset($beneficiario['nm_pessoa']) ? $beneficiario['nm_pessoa'] : "") ,["id" => "nm_pessoa_disabled" ]) }}
                    </div>
                </div>
                <div class="col-md-2 pull-right">
                    <div class="form-group">
                        <label for="parentesco">Parentesco</label>
                        {{  Form::select('parentesco', arrayPadrao('parentesco'), (isset($beneficiario['parentesco']) ? $beneficiario['parentesco'] : 1),['class'=> ($errors->has("id_situacao") ? "form-control is-invalid" : "form-control"), 'id' => 'parentesco']) }}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="cd_plano">Plano<span style="color:red">*</span></label>
                        {{  Form::select('cd_plano', $planos, (isset($beneficiario['cd_plano']) ? trim($beneficiario['cd_plano']) : ""),['class'=>  "form-control", (isset($beneficiario['cd_plano']) && !empty($beneficiario['id_beneficiario'])) ? "disabled" : "",'id' => 'cd_plano']) }}
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="cd_beneficiario">Código do cartão<span style="color:red">*</span></label>
                        {{ Form::text('cd_beneficiario',(isset($beneficiario['cd_beneficiario']) ? $beneficiario['cd_beneficiario']  : "") ,["maxlength" => "20", "id" => "cd_beneficiario", 'class'=> ($errors->has("cd_beneficiario") ? "form-control is-invalid" : "form-control")]) }}
                    </div>
                </div>
                @if((session()->get('recurso.beneficiarios-adicionar')))
                    {{ Form::submit('Salvar',['class'=>"btn btn-success margin-botton-5 pull-right",'id'=>'salvar']) }}
                @endif
                {{ Form::close() }}
            </div>
            <div id="mensagem"></div>
            @if (!empty(Session::get('status')))
                <div class="alert alert-info" id="msg">
                    {{ Session::get('status') }}
                </div>
            @endif
            @if((isset($beneficiario['id_beneficiario']) && (!empty($beneficiario['id_beneficiario']))))
            <div class="panel panel-primary">
                <div class="panel-heading text-center">Cadastro de dependentes</div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped"  style="font-size: 14px">
                            <thead>
                            <tr>
                                <th>Código do cartão</th>
                                <th>Nome</th>
                                <th>Parentesco</th>
                                <th>Situação</th>
                                <th>Ação</th>
                            </tr>
                            </thead>
                            <tbody>

                            @if(!isset($dependente) || $dependente->IsEmpty())
                                <tr><td colspan="5">Sem resultados</td></tr>
                            @else
                                @foreach($dependente as $d)
                                    <tr>
                                        <td class='text-center'>{{ $d->cd_beneficiario }}</td>
                                        <td class='text-center'>{{ $d->nm_pessoa }}</td>
                                        <td class='text-center'>{{ arrayPadrao('parentesco')[$d->parentesco] }}</td>
                                        <td class='text-center {{ ($d->id_situacao == 'A' ? 'text-primary' : 'text-danger')}}'>
                                            {{ ($d->id_situacao == 'A' ? 'Ativo' : 'Inativo')}}
                                        </td>
                                        <td class='text-center'>
                                            <a href="{{ route('beneficiarios/cadastro').'/'.$d->id_beneficiario }}" class='btn btn-primary btn-sm'>Editar</a>
                                            <button type='button' data-tabela="beneficiario" data-chave="id_beneficiario" data-valor="{{ $d->id_beneficiario }}" class='{{ verficaPermissaoBotao('recurso.beneficiarios-excluir')  }} btn btn-danger btn-sm btn-excluir'>Excluir</button>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <input type="button" id='salvar-dependente' style="display: none" title='Salvar dependente' value="Salvar dependente" class='btn btn-primary'>
        </div>
        @endif
    </div>

    @if((isset($beneficiario['id_beneficiario']) && (!empty($beneficiario['id_beneficiario']))))
        <button id='cadastra-dependente' title='Cadastrar um novo dependente' class='btn btn-primary'>Adicionar dependente</button>
    @endif
@endsection

@section('painel-modal')
    @include('pessoas.modal-pessoas')
@endsection