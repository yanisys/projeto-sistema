@extends('layouts.default')@section('conteudo')    @if ($errors->any())        <div class="alert alert-danger collapse in" id="collapseExample">            <h4 class="alert-heading">Os seguintes erros foram encontrados:</h4>            <ul>                @foreach ($errors->all() as $error)                    <li> {{ $error }} </li>                @endforeach            </ul>            <hr>            <p class="mb-0">Por favor, verifique e tente novamente.</p>            <p><a class="btn btn-primary" data-toggle="collapse" href="#collapseExample" role="button"                  aria-expanded="true" aria-controls="collapseExample">                    Fechar                </a></p>        </div>    @endif    {{ Form::open(['id' => 'cadastra-produtos', 'class' => 'form-no-submit']) }}    <div class="panel panel-primary with-nav-tabs">        <div class="panel-heading">            @if((session()->get('recurso.materiais/produto-editar')))                <a href="{{ route('materiais/produto/cadastro') }}" class="btn btn-primary pull-right margin-top-10">Novo</a>            @endif                <ul class="nav nav-tabs">                    <li class="active"><a href="#tab_principal" data-toggle="tab">Informações Gerais</a></li>                    <li><a href="#tab_configuracoes" data-toggle="tab">Configurações</a></li>                    <li><a href="#tab_estoque" data-toggle="tab">Estoque</a></li>                    <li><a href="#tab_fiscal" data-toggle="tab">Fiscal</a></li>                    <li><a href="#tab_precos" data-toggle="tab">Preços</a></li>                </ul>        </div>        <div class="panel-body" style="height: 400px;">            <div class="tab-content">                <div class="tab-pane fade in active" id="tab_principal">                    <div class="row">                        <div class="col-md-2">                            <div class="form-group">                                <label for="cd_produto">Código</label>                                {{ Form::text('cd_produto',(isset($produto['cd_produto']) ? $produto['cd_produto'] : ""),["name" => "cd_produto", "maxlength" => "10", "disabled", "id" => "cd_produto", 'class'=> ($errors->has("cd_produto") ? "form-control is-invalid" : "form-control")]) }}                                {{ Form::hidden('cd_produto',(isset($produto['cd_produto']) ? $produto['cd_produto'] : ""),["name" => "cd_produto", "id" => "cd_produto_hidden", 'class'=> ($errors->has("cd_produto") ? "form-control is-invalid" : "form-control")]) }}                            </div>                        </div>                        <div class="col-md-2 pull-right">                            <div class="form-group">                                <label for="situacao">Situação</label>                                {{  Form::select('situacao', ['A' => 'Ativo', 'I' => 'Inativo'], (isset($produto['situacao']) ? $produto['situacao'] : 'A'),['class'=> ($errors->has("situacao") ? "form-control is-invalid" : "form-control"), 'id' => 'situacao']) }}                            </div>                        </div>                    </div>                    <div class="row">                        <div class="col-md-8">                            <div class="form-group">                                <label for="nm_produto">Nome<span                                style="color:red">*</span></label>                                {{ Form::text('nm_produto', (isset($produto['nm_produto']) ? $produto['nm_produto'] : "") ,["maxlength" => "100", "name" => "nm_produto", 'class'=> ($errors->has("nm_produto") ? "form-control is-invalid maiusculas" : "form-control maiusculas") ]) }}                            </div>                        </div>                        <div class="col-md-3 pull-right">                            <div class="form-group">                                <label for="medicamento">Medicamento</label>                                {{  Form::select('medicamento', ['0' => 'Não', '1' => 'Sim'], (isset($produto['medicamento']) ? $produto['medicamento'] : '0'),['class'=> "form-control", 'id' => 'medicamento']) }}                            </div>                        </div>                    </div>                    <div class="row">                        <div class="col-md-7">                            <div class="form-group">                                <label for="ds_produto">Descrição</label>                                {{ Form::text('ds_produto', (isset($produto['ds_produto']) ? $produto['ds_produto'] : "") ,["maxlength" => "150", "name" => "ds_produto", 'class'=> ($errors->has("ds_produto") ? "form-control is-invalid maiusculas" : "form-control maiusculas") ]) }}                            </div>                        </div>                        <div class="col-md-5">                            <div class="form-group">                                <label for="nm_laboratorio">Fabricante</label>                                {{ Form::text('nm_laboratorio', (isset($produto['nm_laboratorio']) ? $produto['nm_laboratorio'] : "") ,["maxlength" => "100", "name" => "nm_laboratorio", 'class'=> ($errors->has("nm_laboratorio") ? "form-control is-invalid maiusculas" : "form-control maiusculas") ]) }}                            </div>                        </div>                    </div>                    <div class="row">                        <div class="col-md-2">                            <div class="form-group">                                <label for="cd_ean">Código EAN<span style="color:red">*</span></label>                                {{ Form::text('cd_ean', (isset($produto['cd_ean']) ? str_pad($produto['cd_ean'], 14, '0', STR_PAD_LEFT) : '0') ,["maxlength" => "14", "name" => "cd_ean", 'class'=> ($errors->has("cd_ean") ? "form-control is-invalid" : "form-control mask-numeros-14") ]) }}                            </div>                        </div>                        <div class="div-medicamento"  style="display: {{(isset($produto['medicamento']) == 0) ? 'none' : 'block'}}">                            <div class="col-md-8">                                <div class="form-group">                                    <label for="principio_ativo">Princípio ativo</label>                                    {{ Form::text('principio_ativo', (isset($produto['principio_ativo']) ? $produto['principio_ativo'] : "") ,["maxlength" => "150", "name" => "principio_ativo", 'class'=> ($errors->has("principio_ativo") ? "form-control is-invalid maiusculas" : "form-control maiusculas") ]) }}                                </div>                            </div>                        </div>                    </div>                    <div class="row">                        <div class="col-md-4">                            <div class="form-group">                                <label for="cd_produto_divisao">Divisão</label>                                {{ Form::select('cd_produto_divisao',$divisao,(isset($produto['cd_produto_divisao']) ? $produto['cd_produto_divisao'] : 0),['name'=>'cd_produto_divisao','id'=>'cd_produto_divisao','class'=>'form-control']) }}                            </div>                        </div>                        <div class="col-md-4">                            <div class="form-group">                                <label for="cd_produto_grupo">Grupo</label>                                {{ Form::select('cd_produto_grupo',$grupo,(isset($produto['cd_produto_grupo']) ? $produto['cd_produto_grupo'] : 0),['name'=>'cd_produto_grupo','id'=>'cd_produto_grupo','class'=>'form-control']) }}                            </div>                        </div>                        <div class="col-md-4">                            <div class="form-group">                                <label for="cd_produto_sub_grupo">Sub-Grupo</label>                                {{ Form::select('cd_produto_sub_grupo',$sub_grupo,(isset($produto['cd_produto_sub_grupo']) ? $produto['cd_produto_sub_grupo'] : 0),['name'=>'cd_produto_sub_grupo','id'=>'cd_produto_sub_grupo','class'=>'form-control']) }}                            </div>                        </div>                    </div>                </div>                <div class="tab-pane fade" id="tab_configuracoes">                    <div class="col-md-12">                        <div class="row">                            <div class="col-md-3">                                <div class="input-group">                                    <label for="cd_unidade_comercial">Unidade comercial</label>                                    {{ Form::select('cd_unidade_comercial',$unidades_comerciais, (isset($produto['cd_unidade_comercial']) ? $produto['cd_unidade_comercial'] : "1"),["name" => "cd_unidade_comercial", ($produto_movimentado ? "disabled" : ""),"id" => "cd_unidade_comercial", 'class'=> "form-control"]) }}                                </div>                            </div>                            <div id="div-fracionamento">                                <div class="col-md-3">                                    <div class="input-group">                                        <label for="qtde_embalagem">Quantidade por embalagem<span style="color:red">*</span></label>                                        {{ Form::text('qtde_embalagem',(isset($produto['qtde_embalagem']) ? $produto['qtde_embalagem'] : ""),["name" => "qtde_embalagem", "maxlength" => "14", "id" => "qtde_embalagem", ($produto_movimentado ? "disabled" : ""),'class'=> "form-control mask-decimal-x-4"]) }}                                    </div>                                </div>                                <div class="col-md-3">                                    <div class="form-group">                                        <label for="cd_fracao_minima">Fração mínima</label>                                        {{  Form::select('cd_fracao_minima', $unidades_medida, (isset($produto['cd_fracao_minima']) ? $produto['cd_fracao_minima'] : '1'),['class'=> "form-control", ($produto_movimentado ? "disabled" : ""), 'id' => 'cd_fracao_minima']) }}                                    </div>                                </div>                                <div class="col-md-2">                                    <div class="form-group">                                        <label for="estoque_minimo">Estoque mínimo</label>                                        {{ Form::text('estoque_minimo',(isset($produto['estoque_minimo']) ? $produto['estoque_minimo'] : '0'),["name" => "estoque_minimo", "maxlength" => "11", "id" => "estoque_minimo",  'class'=> ($errors->has("estoque_minimo") ? "form-control is-invalid" : "form-control mask-inteiro")]) }}                                    </div>                                </div>                            </div>                        </div>                        <div class="row">                            <div class="col-md-2">                                <label for="controle_lote_validade">Controlar lote/validade</label>                                <div class="form-group text-center">                                    <p class="onoff">                                        {{  Form::checkbox('controle_lote_validade', 1, (isset($produto['controle_lote_validade']) && $produto['controle_lote_validade'] == 1) ? true : false,['id' => 'controle_lote_validade', ($produto_movimentado ? "disabled" : ""), 'class' => 'form-group']) }}                                        <label for="controle_lote_validade"></label>                                    </p>                                </div>                            </div>                            <div id="div_aviso_vencimento" class="col-md-2">                                <label for="aviso_vencimento">Avisar sobre vencimento</label>                                <div class="form-group text-center">                                    <p class="onoff">                                        {{  Form::checkbox('aviso_vencimento', 1, (isset($produto['aviso_vencimento']) && $produto['aviso_vencimento'] == 1) ? true : false,['id' => 'aviso_vencimento', 'class' => 'form-group']) }}                                        <label for="aviso_vencimento"></label>                                    </p>                                </div>                            </div>                            <div class="col-md-2">                                <label for="fracionamento">Fracionamento</label>                                <div class="form-group text-center">                                    <p class="onoff">                                        {{  Form::checkbox('fracionamento', 1, (isset($produto['fracionamento']) && $produto['fracionamento'] == 1) ? true : false,['id' => 'fracionamento', ($produto_movimentado ? "disabled" : ""), 'class' => 'form-group']) }}                                        <label for="fracionamento"></label>                                    </p>                                </div>                            </div>                        </div>                        <div class="div-medicamento"  style="display: {{(isset($produto['medicamento']) && $produto['medicamento'] === 0) ? 'none' : 'block'}}">                            <div class="row"><h4>Configurações do medicamento</h4><hr></div>                            <div class="col-md-8">                                <div class="row">                                    <div class="col-md-3">                                        <label for="antimicrobiano">Antimicrobiano</label>                                        <div class="form-group text-center">                                            <p class="onoff">                                                {{  Form::checkbox('antimicrobiano', 1, (isset($produto['antimicrobiano']) && $produto['antimicrobiano'] == 1) ? true : false,['id' => 'antimicrobiano', 'class' => 'form-group']) }}                                                <label for="antimicrobiano"></label>                                            </p>                                        </div>                                    </div>                                    <div class="col-md-3">                                        <label for="controlado">Controlado</label>                                        <div class="form-group text-center">                                            <p class="onoff">                                                {{  Form::checkbox('controlado', 1, (isset($produto['controlado']) && $produto['controlado'] == 1) ? true : false,['id' => 'controlado', 'class' => 'form-group']) }}                                                <label for="controlado"></label>                                            </p>                                        </div>                                    </div>                                    <div class="col-md-3">                                        <label for="injetavel">Injetável</label>                                        <div class="form-group text-center">                                            <p class="onoff">                                                {{  Form::checkbox('injetavel', 1, (isset($produto['injetavel']) && $produto['injetavel'] == 1) ? true : false,['id' => 'injetavel', 'class' => 'form-group']) }}                                                <label for="injetavel"></label>                                            </p>                                        </div>                                    </div>                                    <div class="col-md-3">                                        <label for="prescricao_interna">Prescrição interna</label>                                        <div class="form-group text-center">                                            <p class="onoff">                                                {{  Form::checkbox('prescricao_interna', 1, (isset($produto['prescricao_interna']) && $produto['prescricao_interna'] == 1) ? true : false,['id' => 'prescricao_interna', 'class' => 'form-group']) }}                                                <label for="prescricao_interna"></label>                                            </p>                                        </div>                                    </div>                                </div>                                <div class="row">                                    <div class="col-md-3">                                        <div class="form-group">                                            <label for="estabilidade">Estabilidade</label>                                            {{ Form::text('estabilidade',(isset($produto['estabilidade']) ? $produto['estabilidade'] : "0"),["name" => "estabilidade", "maxlength" => "11", "id" => "estabilidade",  'class'=> ($errors->has("estabilidade") ? "form-control is-invalid" : "form-control mask-inteiro")]) }}                                        </div>                                    </div>                                    <div class="col-md-8">                                        <div class="form-group">                                            <div class="input-group">                                                <label for="cd_kit_vinculado">Kit vinculado a este medicamento</label>                                                {{ Form::text('nm_kit_vinculado',(isset($produto['nm_kit']) ? $produto['nm_kit'] : ""),["name" => "nm_kit_vinculado", "disabled", "id" => "nm_kit_vinculado",  'class'=> ($errors->has("nm_kit_vinculado") ? "form-control is-invalid" : "form-control")]) }}                                                {{ Form::hidden('cd_kit_vinculado',(isset($produto['cd_kit_vinculado']) ? $produto['cd_kit_vinculado'] : null),["name" => "cd_kit_vinculado", "id" => "cd_kit_vinculado_hidden"]) }}                                                <span class="input-group-btn">                                           <button type="button" class="btn btn-default margin-top-25" id="btn-modal-pesquisa-kit"><span class="fa fa-search"></span></button>                                        </span>                                            </div>                                        </div>                                    </div>                                </div>                            </div>                            <div class="col-md-4 pull-left" style="font-size: 10px">                                <h4>Vias de administração</h4>                                <div class="col-md-2">                               @foreach($via_aplicacao_medicamentos as $key=>$via)                                   @if(($key > 0 && ($key%4) == 0))                                </div>                                <div class="col-md-2">                                   @endif                                   <div class="input-group">                                        <input id='checkbox-{{ $via->cd_via_aplicacao_medicamentos }}' title="{{$via->nome}}" type="checkbox" name='via_aplicacao[]' value='{{ $via->cd_via_aplicacao_medicamentos }}' {{(isset($via->existe)) ? 'checked' : ''}}/>                                        <label for='checkbox-{{ $via->cd_via_aplicacao_medicamentos }}'>{{$via->sigla}}</label>                                   </div>                               @endforeach                               </div>                            </div>                        </div>                    </div>                </div>                <div class="tab-pane fade" id="tab_estoque">                    <div class="col-md-12">                        <div class="table-responsive">                            <table class="table table-hover table-striped">                                <thead>                                <tr>                                    <th>Produto</th>                                    <th>Lote</th>                                    <th>Localização</th>                                    <th>Quantidade</th>                                </tr>                                </thead>                                <tbody>                                @if(!isset($estoque))                                    <tr><td colspan="3">Sem resultados</td></tr>                                @else                                    @foreach($estoque as $e)                                        <tr>                                            <td>{{ $e->nm_produto }}</td>                                            <td>{{ $e->lote }}</td>                                            <td>{{ $e->nm_sala }}</td>                                            <td>{{ $e->quantidade }}</td>                                        </tr>                                    @endforeach                                @endif                                </tbody>                            </table>                        </div>                    </div>                </div>                <div class="tab-pane fade" id="tab_fiscal">                    <div class="col-md-12">                        <div class="row">                            <div class="col-md-2">                                <div class="form-group">                                    <label for="icms">Icms(%)</label>                                    {{ Form::text('icms',(isset($produto['icms']) ? $produto['icms'] : "0"),["name" => "icms", "maxlength" => "5", "id" => "icms", 'class'=> "form-control mask-decimal-x-2"]) }}                                </div>                            </div>                            <div class="col-md-2">                                <div class="form-group">                                    <label for="substituicao_tributaria">Substituição tributária(%)</label>                                    {{ Form::text('substituicao_tributaria',(isset($produto['substituicao_tributaria']) ? $produto['substituicao_tributaria'] : "0"),["name" => "substituicao_tributaria", "maxlength" => "5", "id" => "substituicao_tributaria", 'class'=> "form-control mask-decimal-x-2"]) }}                                </div>                            </div>                        </div>                        <div class="row">                            <div class="col-md-2">                                <div class="form-group">                                    <label for="pis">Pis(%)</label>                                    {{ Form::text('pis',(isset($produto['pis']) ? $produto['pis'] : "0"),["name" => "pis", "maxlength" => "5", "id" => "pis", 'class'=> "form-control mask-decimal-x-2"]) }}                                </div>                            </div>                        </div>                        <div class="row">                            <div class="col-md-2">                                <div class="form-group">                                    <label for="cofins">Cofins(%)</label>                                    {{ Form::text('cofins',(isset($produto['cofins']) ? $produto['cofins'] : "0"),["name" => "cofins", "maxlength" => "5", "id" => "cofins", 'class'=> "form-control mask-decimal-x-2"]) }}                                </div>                            </div>                        </div>                        <div class="row">                            <div class="col-md-2">                                <div class="form-group">                                    <label for="cssll">Cssll(%)</label>                                    {{ Form::text('cssll',(isset($produto['cssll']) ? $produto['cssll'] : "0"),["name" => "cssll", "maxlength" => "5", "id" => "cssll", 'class'=> "form-control mask-decimal-x-2"]) }}                                </div>                            </div>                        </div>                        <div class="row">                            <div class="col-md-2">                                <div class="form-group">                                    <label for="ncm">Ncm</label>                                    {{ Form::text('ncm',(isset($produto['ncm']) ? $produto['ncm'] : "0"),["name" => "ncm", "maxlength" => "8", "id" => "ncm", 'class'=> "form-control mask-inteiro"]) }}                                </div>                            </div>                            <div class="col-md-2">                                <div class="form-group">                                    <label for="cd_anvisa">Código Anvisa</label>                                    {{ Form::text('cd_anvisa', (isset($produto['cd_anvisa']) ? str_pad($produto['cd_anvisa'], 13, '0', STR_PAD_LEFT) : '0') ,["maxlength" => "13", "name" => "cd_anvisa", 'class'=> ($errors->has("cd_anvisa") ? "form-control is-invalid" : "form-control mask-inteiro") ]) }}                                </div>                            </div>                        </div>                    </div>                </div>                <div class="tab-pane fade" id="tab_precos">                    <div class="col-md-9">                        <div class="col-md-11">                            <div id="tabela-preco-produto-plano" class="table-responsive">                                <table class="table table-hover table-striped">                                    <thead>                                    <tr>                                        <th colspan="12">Valor e disponibilidade do medicamento por plano</th>                                    </tr>                                    <tr>                                        <th colspan="4">Plano</th>                                        <th colspan="3">Código do produto no plano</th>                                        <th colspan="2">Preço de venda</th>                                        <th colspan="2">Situação</th>                                        <th colspan="1">Ação</th>                                    </tr>                                    </thead>                                    <tbody>                                    @foreach($planos as $key => $p)                                        <tr id="linha-{{$key}}" style="color: {{(isset($p->status) && $p->status === 'A' ? 'black' : 'red')}}">                                            <td colspan="4">{{$p->ds_plano}}</td>                                            <td colspan="3" id="cd-produto-plano-{{$key}}">{{(isset($p->cd_produto_plano) ? $p->cd_produto_plano : '-')}}</td>                                            <td id="preco-{{$key}}" colspan="2">{{(isset($p->preco) ? str_replace(".",",",$p->preco) : '-')}}</td>                                            <td id="status-{{$key}}" colspan="2">{{(isset($p->status) && $p->status === 'A' ? 'Ativo' : 'Inativo')}}</td>                                            <td width="100px">                                                <button type="button" data-toggle="modal" id="btn-{{$key}}" data-cd-produto-plano="{{isset($p->cd_produto_plano) ? $p->cd_produto_plano : ''}}" data-linha="{{$key}}" {{(isset($produto['cd_produto']) ? "" : "disabled")}} title="{{(isset($produto['cd_produto']) ? "" : "Salve o produto para alterar os preços!")}}" data-status="{{(isset($p->status) && $p->status == 'A' ? 'A' : 'I')}}" data-preco="{{isset($p->preco) ? $p->preco : ''}}" data-plano="{{$p->cd_plano}}" data-target="#modal-precos-produtos" class="btn btn-primary btn-xs btn-preco-produto-plano">Editar</button>                                            </td>                                        </tr>                                    @endforeach                                    </tbody>                                </table>                            </div>                        </div>                    </div>                    <div class="col-md-3">                    <!--    <div class="row">                            <div class="col-md-12">                                <div class="form-group">                                    <label for="PMCZF18">Preço consumidor(BrasIndice)</label>                                    {{ Form::text('PMCZF18', (isset($produto['PMCZF18']) ? $produto['PMCZF18'] : "") ,["maxlength" => "100", "disabled", "name" => "PMCZF18", 'class'=> ($errors->has("PMCZF18") ? "form-control is-invalid maiusculas" : "form-control maiusculas") ]) }}                                </div>                            </div>                        </div>                        <div class="row">                            <div class="col-md-12">                                <div class="form-group">                                    <label for="PFABZF18">Preço fabricante(BrasIndice)</label>                                    {{ Form::text('PFABZF18', (isset($produto['PFABZF18']) ? $produto['PFABZF18'] : "") ,["maxlength" => "100", "disabled", "name" => "PFABZF18", 'class'=> ($errors->has("PFABZF18") ? "form-control is-invalid maiusculas" : "form-control maiusculas") ]) }}                                </div>                            </div>                        </div> -->                        <div class="row">                            <div class="col-md-12">                                <div class="form-group">                                    <label for="vlr_ultima_compra">Preço última compra</label>                                    {{ Form::text('vlr_ultima_compra', (isset($produto['vlr_ultima_compra']) ? $produto['vlr_ultima_compra'] : "") ,["maxlength" => "100", "disabled", "name" => "vlr_ultima_compra", 'class'=> ($errors->has("vlr_ultima_compra") ? "form-control is-invalid maiusculas" : "form-control maiusculas") ]) }}                                </div>                            </div>                        </div>                    </div>                </div>            </div>        </div>        <div class="panel-footer fixed-panel-footer" >            @if((session()->get('recurso.materiais/produto-editar')))                {{ Form::submit('Salvar',['class'=>"btn btn-success pull-right", 'id' => 'salvar-produto']) }}            @endif        </div>    </div>    {{ Form::close() }}@endsection@section('painel-modal')    <div class="modal fade" id="modal-precos-produtos"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">        <div class="modal-dialog">            <div class="modal-content">                <div class="modal-header">                    <h3 class="modal-title" id="myModalLabel">{{(isset($produto['nm_produto']) ? $produto['nm_produto'] : "")}}</h3>                </div>                <div class="modal-body">                    <div class="panel-body">                        <div class="col-md-12">                            <div class="row">                            <div class="col-md-6">                                <div class="form-group">                                    <label for="codigo_produto_plano">Código do produto no plano</label>                                    {{ Form::text('codigo_produto_plano', '' ,["maxlength" => "10", 'id' => 'codigo_produto_plano', 'class'=> "form-control"]) }}                                </div>                            </div>                            </div>                            <div class="row">                                <div class="col-md-7">                                    <div class="form-group">                                        <label for="principio_ativo">Preço de venda</label>                                        {{ Form::text('preco_venda_produto_plano', "" ,["maxlength" => "10", 'id' => 'preco_venda_produto_plano', 'class'=> "form-control dinheiro" ]) }}                                    </div>                                </div>                                <div class="col-md-5">                                    <div class="form-group">                                        <label for="situacao">Situação</label>                                        {{  Form::select('status_produto_plano', ['A' => 'Ativo', 'I' => 'Inativo'], 'I',['class'=> "form-control", 'id' => 'status_produto_plano']) }}                                    </div>                                </div>                            </div>                        </div>                    </div>                </div>                <div class="panel-footer" style="text-align:left !important;">                    <button type="button" class="btn btn-danger margin-top-zero" data-dismiss="modal">Cancelar</button>                    <button id="btn-salvar-preco-produto-plano" class="btn btn-success  pull-right">Salvar</button>                </div>            </div>        </div>    </div>    @include('materiais/kits/modal-pesquisa-kit')    <script src="{{ js_versionado('produtos.js') }}" defer></script>@endsection