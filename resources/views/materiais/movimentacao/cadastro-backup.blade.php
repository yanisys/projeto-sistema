@extends('layouts.default')

@section('conteudo')
    @if ($errors->any())
        <div class="alert alert-danger collapse in" id="collapseExample">
            <h4 class="alert-heading">Os seguintes erros foram encontrados:</h4>
            <ul>
                @foreach ($errors->all() as $error)
                    <li> {{ $error }} </li>
                @endforeach
            </ul>
            <hr>
            <p class="mb-0">Por favor, verifique e tente novamente.</p>
            <p><a class="btn btn-primary" data-toggle="collapse" href="#collapseExample" role="button"
                  aria-expanded="true" aria-controls="collapseExample">
                    Fechar
                </a></p>
        </div>
    @endif
    {{ Form::open(['id' => 'cadastra-movimentacao', 'class' => 'form-no-submit']) }}
    <div class="panel panel-primary">
        <div class="panel-heading">
            @if((session()->get('recurso.materiais/movimentacao-editar')))
                <a href="{{ route('materiais/movimentacao/cadastro') }}" title="Limpar" class="btn btn-warning pull-right margin-top-10"><span class="fas fa-broom"></span></a>
            @endif
                <input type="file" id="importar-xml" accept=".xml"/>
                <label class="btn btn-primary pull-right margin-top-10" id="upload_btn" for="importar-xml">Importar Xml</label>
            <h4>Dados do Movimento</h4>
        </div>
        <div class="panel-body">
            <div class="tab-content">
                <div class="tab-pane fade in active" id="tab_info">
                    <div id="principal-movimentacao" class="col-md-12">
                        <div class="row">
                            <div class="col-md-1">
                                <div class="form-group">
                                    <label for="cd_movimentacao">Código<span style="color:red">*</span></label>
                                    {{ Form::text('cd_movimentacao',(isset($movimentacao['cd_movimentacao']) ? $movimentacao['cd_movimentacao'] : ""),["id" => "cd_movimentacao",  "disabled" => "disabled", 'class'=> ($errors->has("cd_movimentacao") ? "form-control is-invalid" : "form-control")]) }}
                                    {{ Form::hidden('cd_movimentacao',(isset($movimentacao['cd_movimentacao']) ? $movimentacao['cd_movimentacao'] : ""),["name" => "cd_movimentacao", "id" => "cd_movimentacao_hidden"]) }}
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="data">Data</label>
                                    {{ Form::text('data',(isset($movimentacao['created_at']) ? formata_data($movimentacao['created_at']) : formata_data(\Carbon\Carbon::now())),["maxlength" => "10", "id" => "data",  "disabled" => "disabled", 'class'=> ($errors->has("data") ? "form-control is-invalid" : "form-control")]) }}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="cd_movimento">Movimento</label>
                                    {{  Form::select('cd_movimento', $movimento, (isset($movimentacao['cd_movimento']) ? $movimentacao['cd_movimento'] : 0),['class'=> "form-control",  'id' => 'cd_movimento']) }}
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="input-group">
                                    <label for="cd_cfop">
                                        @if(!(session()->get('recurso.materiais/fornecedores-adicionar')))
                                            Fornecedor
                                        @else
                                            <a href="{{ route('materiais/fornecedores/cadastro') }}" title="Clique aqui para ir para a tela de cadastro de fornecedores" target="_blank" style="color: #00008b" }}>
                                                Fornecedor
                                            </a>
                                        @endif
                                    </label>
                                    {{ Form::text('cd_emitente_destinatario', (isset($movimentacao['nm_pessoa']) ? $movimentacao['nm_pessoa'] : "") ,["name" => "cd_emitente_destinatario", "id" => "cd_emitente_destinatario", "disabled", 'class'=> ($errors->has("cd_emitente_destinatario") ? "form-control is-invalid" : "form-control")]) }}
                                    {{ Form::hidden('nm_pessoa', (isset($movimentacao['nm_pessoa']) ? $movimentacao['nm_pessoa'] : "") ,["name" => "nm_pessoa", 'id' => 'nm_pessoa']) }}
                                    {{ Form::hidden('cd_emitente_destinatario', (isset($movimentacao['cd_emitente_destinatario']) ? $movimentacao['cd_emitente_destinatario'] : "") ,["name" => "cd_emitente_destinatario", 'id' => 'cd_emitente_destinatario_hidden', 'class'=> 'form-control']) }}
                                    <span class="input-group-btn">
                                         <button type="button" data-toggle="modal" class="btn btn-info margin-top-25" id="btn-modal-pesquisa-fornecedor"><span class="fa fa-search"></span></button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                                    {{ Form::hidden('tp_nf', (isset($movimento['tp_nf']) ? $movimento['tp_nf'] : "") ,["name" => "tp_nf", 'id' => 'tp_nf_hidden', 'class'=> 'form-control']) }}
                                    {{ Form::hidden('tp_conta', (isset($movimento['tp_conta']) ? $movimento['tp_conta'] : "") ,["name" => "tp_conta", 'id' => 'tp_conta_hidden', 'class'=> 'form-control']) }}
                                    {{ Form::hidden('tp_movimento', (isset($movimento['tp_movimento']) ? $movimento['tp_movimento'] : "") ,["name" => "tp_movimento", 'id' => 'tp_movimento_hidden', 'class'=> 'form-control']) }}
                                    {{ Form::hidden('tp_saldo', (isset($movimento['tp_saldo']) ? $movimento['tp_saldo'] : "") ,["name" => "tp_saldo", 'id' => 'tp_saldo_hidden', 'class'=> 'form-control']) }}
                                    {{ Form::hidden('cd_cfop', (isset($movimento['cd_cfop']) ? $movimento['cd_cfop'] : "") ,["name" => "cd_cfop", 'id' => 'cd_cfop_hidden', 'class'=> 'form-control']) }}
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="nr_documento">Nº documento</label>
                                    {{ Form::text('nr_documento',(isset($movimentacao['nr_documento']) ? $movimentacao['nr_documento'] : ""),["maxlength" => "10", "id" => "nr_documento", 'class'=> ($errors->has("nr_documento") ? "form-control is-invalid" : "form-control mask-inteiro")]) }}
                                </div>
                            </div>
                        <!--    <div class="col-md-4">
                                <div class="form-group">
                                    <label for="chave">Chave Nfe</label>
                                    {{ Form::text('chave',(isset($movimentacao['chave']) ? $movimentacao['chave'] : ""),["maxlength" => "47", "id" => "chave", 'class'=> ($errors->has("chave") ? "form-control is-invalid" : "form-control")]) }}
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <label for="mod">Modelo</label>
                                    {{ Form::text('mod',(isset($movimentacao['mod']) ? $movimentacao['mod'] : ""),["maxlength" => "3", "id" => "mod", 'class'=> ($errors->has("mod") ? "form-control is-invalid" : "form-control")]) }}
                                </div>
                            </div> -->
                            <div class="col-md-1">
                                <div class="form-group">
                                    <label for="serie">Série</label>
                                    {{ Form::text('serie',(isset($movimentacao['serie']) ? $movimentacao['serie'] : ""),["maxlength" => "3", "id" => "serie", 'class'=> ($errors->has("serie") ? "form-control is-invalid" : "form-control")]) }}
                                </div>
                            </div>
                        <!--    <div class="col-md-2">
                                <div class="form-group">
                                    <label for="natOp">Natureza da operação</label>
                                    {{ Form::text('natOp',(isset($movimentacao['natOp']) ? $movimentacao['natOp'] : ""),["maxlength" => "60", "id" => "natOp", 'class'=> ($errors->has("natOp") ? "form-control is-invalid" : "form-control")]) }}
                                </div>
                            </div> -->
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="indPag">Ind. pagamento</label>
                                    {{  Form::select('indPag', arrayPadrao('ind_forma_pagamento'), (isset($movimentacao['indPag']) ? $movimentacao['indPag'] : 99),['class'=> ($errors->has("indPag") ? "form-control is-invalid" : "form-control"),  'id' => 'indPag']) }}
                                </div>
                            </div>
                   <!--     </div>
                        <div class="row"> -->
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="dhEmi">Data/Hora de emissão</label>
                                    {{ Form::text('dhEmi',(isset($movimentacao['dhEmi']) ? formata_data_hora($movimentacao['dhEmi']) : ""),["id" => "dhEmi", 'class'=> ($errors->has("dhEmi") ? "form-control mask-data-hora is-invalid" : "form-control mask-data-hora")]) }}
                                </div>
                            </div>
                   <!--         <div class="col-md-2">
                                <div class="form-group">
                                    <label for="dhSaiEnt">Data/Hora de saída</label>
                                    {{ Form::text('dhSaiEnt',(isset($movimentacao['dhSaiEnt']) ? formata_data_hora($movimentacao['dhSaiEnt']) : ""),["maxlength" => "16", "id" => "dhEmi", 'class'=> ($errors->has("dhSaiEnt") ? "form-control mask-data-hora is-invalid" : "form-control mask-data-hora")]) }}
                                </div>
                            </div> -->
                      <!--      <div class="col-md-3">
                                <div class="form-group">
                                    <label for="tpEmis">Tipo de emissão Nfe</label>
                                    {{  Form::select('tpEmis', arrayPadrao('tipo_emissao_nfe'), (isset($movimentacao['tpEmis']) ? $movimentacao['tpEmis'] : 99),['class'=> ($errors->has("tpEmis") ? "form-control is-invalid" : "form-control"),  'id' => 'tpEmis']) }}
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="procEmi">Processo emissão Nfe</label>
                                    {{  Form::select('procEmi', arrayPadrao('processo_emissao_nfe'), (isset($movimentacao['procEmi']) ? $movimentacao['procEmi'] : 99),['class'=> ($errors->has("procEmi") ? "form-control is-invalid" : "form-control"),  'id' => 'procEmi']) }}
                                </div>
                            </div>  -->
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="finNFe">Finalidade Nfe</label>
                                    {{  Form::select('finNFe', arrayPadrao('finalidade_nfe'), (isset($movimentacao['finNFe']) ? $movimentacao['finNFe'] : 99),['class'=> ($errors->has("finNFe") ? "form-control is-invalid" : "form-control"), 'id' => 'finNFe']) }}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="vDesc">Desconto(R$)</label>
                                    {{ Form::text('vDesc',(isset($movimentacao['vDesc']) ? $movimentacao['vDesc'] : ""),["maxlength" => "10", "id" => "vDesc", 'class'=> ($errors->has("vDesc") ? "form-control dinheiro is-invalid" : "form-control dinheiro")]) }}
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="vOutro">Despesas(R$)</label>
                                    {{ Form::text('vOutro',(isset($movimentacao['vOutro']) ? $movimentacao['vOutro'] : ""),["maxlength" => "10", "id" => "vOutro", 'class'=> ($errors->has("vOutro") ? "form-control dinheiro is-invalid" : "form-control dinheiro")]) }}
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="vFrete">Frete(R$)</label>
                                    {{ Form::text('vFrete',(isset($movimentacao['vFrete']) ? $movimentacao['vFrete'] : ""),["maxlength" => "10", "id" => "vFrete", 'class'=> ($errors->has("vFrete") ? "form-control dinheiro is-invalid" : "form-control dinheiro")]) }}
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="vSeg">Seguro(R$)</label>
                                    {{ Form::text('vSeg',(isset($movimentacao['vSeg']) ? $movimentacao['vSeg'] : ""),["maxlength" => "10", "id" => "vSeg", 'class'=> ($errors->has("vSeg") ? "form-control dinheiro is-invalid" : "form-control dinheiro")]) }}
                                </div>
                            </div>
                            <div class="col-md-3 pull-right">
                                <div class="form-group">
                                    <label for="vNF">Total(R$)</label>
                                    {{ Form::text('vNF',(isset($movimentacao['vNF']) ? $movimentacao['vNF'] : ""),["maxlength" => "10", "id" => "vNF", 'class'=> ($errors->has("vNF") ? "form-control dinheiro is-invalid" : "form-control dinheiro")]) }}
                                </div>
                            </div>
                        </div>
                        <div class="row">

                            <div class="col-md-3 pull-right">
                                <div class="form-group">
                                    <label for="cd_sala">Localização<br></label>
                                    {{  Form::select('cd_sala', $sala, (isset($movimentacao['cd_sala']) ? $movimentacao['cd_sala'] : 0),['class'=> ($errors->has("cd_sala") ? "form-control is-invalid" : "form-control"), 'name'=>"cd_sala", 'id' => 'cd_sala']) }}
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="nDup">Título</label>
                                    {{ Form::text('nDup',(isset($movimentacao['nDup']) ? $movimentacao['nDup'] : ""),["maxlength" => "60", "id" => "nDup", 'class'=> ($errors->has("nDup") ? "form-control is-invalid" : "form-control")]) }}
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="dVenc">Vencimento</label>
                                    {{ Form::date('dVenc',(isset($movimentacao['dVenc']) ? $movimentacao['dVenc'] : ""),["maxlength" => "10", "id" => "dVenc", 'class'=> ($errors->has("dVenc") ? "form-control is-invalid" : "form-control")]) }}
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="vDup">Parcela</label>
                                    {{ Form::text('vDup',(isset($movimentacao['vDup']) ? $movimentacao['vDup'] : ""),["maxlength" => "10", "id" => "vDup", 'class'=> ($errors->has("vDup") ? "form-control is-invalid" : "form-control dinheiro")]) }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="itens-movimentacao" class="col-md-12">
                        <hr>
                            <div id="div-movimento-itens"></div>
                            <div class="table-responsive" style="height: 250px">
                                <table class="table table-hover table-striped">
                                    <thead>
                                    <tr>
                                        <th>Produto&nbsp;<input type="button" style="display: {{(isset($movimentacao['cd_movimentacao']) ? 'block' : 'none')}}" class="btn btn-info btn-xs" id="abre-modal-item-movimentacao" data-toggle="modal" value="Acicionar" data-target="#modal-add-item-movimentacao"></th>
                                        <th>Lote</th>
                                        <th>Quantidade</th>
                                        <th>Localização</th>
                                        <th>Fabricação</th>
                                        <th>Validade</th>
                                        <th class='text-center' width="100px">Ação</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if(isset($movimentacao_itens) || !$movimentacao_itens->IsEmpty())
                                        @foreach($movimentacao_itens as $itens)
                                            <tr>
                                                <td class='text-left'>{{ $itens->nm_produto." - ".$itens->ds_produto}}</td>
                                                <td class='text-center'>{{ $itens->lote }}</td>
                                                <td class='text-center'>{{ $itens->quantidade+0 }}</td>
                                                <td class='text-center'>{{ $itens->nm_sala }}</td>
                                                <td class='text-center'>{{ isset($itens->dt_fabricacao) ? formata_data($itens->dt_fabricacao) : '' }}</td>
                                                <td class='text-center'>{{ isset($itens->dt_validade) ? formata_data($itens->dt_validade) : '' }}</td>
                                                <td class='text-center'>
                                                    <button type="button" class="btn btn-info btn-xs detalhes-item-movimentacao" value="{{$itens->cd_movimentacao_itens}}"><span class="fas fa-edit"></span></button>
                                                   <!-- <button title="Excluir" type='button' data-tabela="movimentacao_itens" data-chave="cd_movimentacao_itens" data-valor="{{$itens->cd_movimentacao_itens}}" class='{{ verficaPermissaoBotao('recurso.materiais/movimentacao-excluir')  }} btn btn-danger btn-xs btn-excluir'><span class="fas fa-trash fa-xs"></span></button> -->
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <div class="panel-footer fixed-panel-footer" >
            @if(session()->get('recurso.materiais/movimentacao-editar') || \Carbon\Carbon::parse($movimentacao['created_at'])->gt(\Carbon\Carbon::today()))
                {{ Form::submit('Salvar',['class'=>"btn btn-success pull-right", 'id' => 'salvar-movimentacao']) }}
            @endif
        </div>

    </div>

    {{ Form::close() }}

@endsection

@section('painel-modal')
    <div id="modal-add-item-movimentacao" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div id="dialog-escolhe-movimentacao" class="modal-dialog">
            <div id="content-escolhe-movimentacao" class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="myModalLabel">Movimento de itens</h3>
                </div>
                <div id="painel-movimentacao-itens" class="panel-body">
                    <div class="panel-body panel-acolhimento">
                        <div class="row">
                            <div class="col-md-11">
                                <div class="input-group form-group">
                                    <label for="id_produto">Produto<br></label>
                                    <input type="hidden" data-id="" id="cd_produto_hidden" class="form-control">
                                    <input type="text" data-id="" id="cd_produto" disabled class="form-control">
                                    <span class="input-group-btn">
                                        <button type="button" data-toggle="modal" class="btn btn-info margin-top-10" id="btn-modal-pesquisa-produto"><span class="fa fa-search"></span></button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="qCom">Quantidade</label>
                                    <input type="text" id="qCom" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="uCom">Unidade</label>
                                    {{  Form::select('uCom', $unidades_comerciais, 0,['class'=> "form-control", "disabled"=>"disabled", 'id' => 'uCom']) }}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="total">Quantidade a ser lançada</label>
                                    <input type="text" disabled="disabled" id="total" class="form-control">
                                    <input type="hidden" id="total_hidden" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="dt_fabricacao">Dt. fabricação</label>
                                    <input type="date" id="dt_fabricacao" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="dt_validade">Validade</label>
                                    <input type="date" id="dt_validade" class="form-control ">
                                </div>
                            </div>
                            <div id="div_lote" class="col-md-3">
                                <div class="form-group">
                                    <label for="lote">Nº do lote</label>
                                    <input type="text" id="lote" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="preco_unitario">Valor unitário</label>
                                    <input type="text" id="preco_unitario" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="preco_total">Valor total</label>
                                    <input type="text" id="preco_total" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="cd_produto_fornecedor">Cod. produto</label>
                                    <input type="text" id="cd_produto_fornecedor" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="NCM">Ncm</label>
                                    <input type="text" id="NCM" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="CFOP">Cfop</label>
                                    <input type="text" id="CFOP" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="icms_vICMS">Valor ICMS</label>
                                    <input type="text" id="icms_vICMS" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="ipi_vIPI">Valor IPI</label>
                                    <input type="text" id="ipi_vIPI" class="form-control">
                                </div>
                            </div>
                            <input type="hidden" id="fracao_hidden">
                            <input type="hidden" id="fracao">
                            <input type="hidden" id="qtde_embalagem_hidden">
                            <input type="hidden" id="fracionamento_produto_hidden" class="form-control">
                            <input type="hidden" id="cd_sala_hidden" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="panel-footer" style="text-align:left !important;">
                    <div id="mensagem"></div>
                    <button type="button" class="btn btn-danger margin-top-zero" data-dismiss="modal">Cancelar</button>
                    <button id="add-movimentacao-itens" class="btn btn-primary  pull-right">Salvar</button>
                </div>
            </div>
        </div>
    </div>
    @include('materiais.movimentacao.modal-pesquisa-produto')
    @include('materiais.movimentacao.modal-importa-nfe')
    @include('materiais.movimentacao.modal-pesquisa-fornecedor')
@endsection

@section('custom_js')
    <script src="{{ js_versionado('produtos.js') }}"></script>
@endsection
