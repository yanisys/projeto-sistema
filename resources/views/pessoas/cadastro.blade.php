
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
            <p ><a class="btn btn-primary" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="true" aria-controls="collapseExample">
               Fechar
            </a></p>
        </div>
    @endif

    {{ Form::open(['id' => 'form-atendimento-medico', 'class' => 'form-no-submit']) }}
    <div class="panel with-nav-tabs panel-primary">
        <div class="panel-heading">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#tab_info" data-toggle="tab">Informações Gerais</a></li>
                <li><a href="#tab_endereco" data-toggle="tab">Endereço</a></li>
                <li><a id="tab_titulo_cpf_cnpj" href="#tab_pfisica" data-toggle="tab">Pessoa Fisica</a></li>
                <li><a id="tab_titulo_plano" href="#tab_plano" data-toggle="tab">Dados do Plano</a></li>
              <!--  <li><a href="#tab_complementar" data-toggle="tab">Informações Complementares</a></li> -->
            </ul>
        </div>
        <div class="panel-body">
            <div class="tab-content">
                <div class="tab-pane fade in active" id="tab_info">

                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="cd_pessoa">Código<span style="color:red">*</span></label>
                                {{ Form::text('cd_pessoa',(isset($pessoa['cd_pessoa']) ? $pessoa['cd_pessoa'] : ""),["name" => "cd_pessoa", "maxlength" => "10", "id" => "cd_pessoa",  "disabled" => "disabled", 'class'=> ($errors->has("cd_pessoa") ? "form-control is-invalid" : "form-control")]) }}
                                {{ Form::hidden('cd_pessoa',(isset($pessoa['cd_pessoa']) ? $pessoa['cd_pessoa'] : ""),["name" => "cd_pessoa", "id" => "cd_pessoa"]) }}
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="nm_pessoa">Nome<span style="color:red">*</span></label>
                                {{ Form::text('nm_pessoa', (isset($pessoa['nm_pessoa']) ? $pessoa['nm_pessoa'] : "") ,["maxlength" => "60", "id" => "nm_pessoa", 'class'=> ($errors->has("nm_pessoa") ? "maiusculas form-control is-invalid" : "maiusculas form-control") ]) }}
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="id_situacao">Situação</label>
                                {{  Form::select('id_situacao', ['A' => 'ATIVO', 'I' => 'INATIVO'], (isset($pessoa['id_situacao']) ? $pessoa['id_situacao'] : "A"),['class'=> ($errors->has("id_situacao") ? "form-control is-invalid" : "form-control"), 'id' => 'id_situacao']) }}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="id_pessoa">Tipo</label>
                                {{  Form::select('id_pessoa', ['F' => 'PESSOA FÍSICA', 'J' => 'PESSOA JURÍDICA'], (isset($pessoa['id_pessoa']) ? $pessoa['id_pessoa'] : "F"),['class'=> ($errors->has("id_pessoa") ? "form-control is-invalid" : "form-control"), 'id' => 'id_pessoa']) }}
                            </div>
                        </div>
                        <div id="escolhe_cpf_cnpj" class="col-md-2">
                            <div class="form-group">
                                <label class="label_cnpj_cpf" for="cnpj_cpf">CPF<span style="color:red">*</span></label>
                                {{ Form::text('cnpj_cpf',(isset($pessoa['cnpj_cpf']) ? $pessoa['cnpj_cpf'] : ""),["maxlength" => "11", "id" => "cnpj_cpf", 'class'=> ($errors->has("cnpj_cpf") ? "cnpj_cpf form-control is-invalid validar" : "cnpj_cpf form-control validar")]) }}
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="nr_fone1">Telefone</label>
                                {{ Form::text('nr_fone1',(isset($pessoa['nr_fone1']) ? $pessoa['nr_fone1'] : ""),["maxlength" => "16", "id" => "nr_fone1", 'class'=> ($errors->has("nr_fone1") ? "form-control is-invalid" : "form-control mask-telefone")]) }}
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="nr_fone2">Telefone</label>
                                {{ Form::text('nr_fone2',(isset($pessoa['nr_fone2']) ? $pessoa['nr_fone2'] : ""),["maxlength" => "16", "id" => "nr_fone2", 'class'=> ($errors->has("nr_fone2") ? "form-control is-invalid" : "form-control mask-telefone")]) }}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="ds_email">Email</label>
                                {{ Form::text('ds_email',(isset($pessoa['ds_email']) ? $pessoa['ds_email'] : ""),["maxlength" => "50", "id" => "ds_email", 'class'=> ($errors->has("ds_email") ? "minusculas form-control is-invalid" : "minusculas form-control")]) }}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="observacoes">Observações</label>
                                {{ Form::text('observacoes',(isset($pessoa['observacoes']) ? $pessoa['observacoes'] : ""),["maxlength" => "90", "id" => "observacoes", 'class'=> ($errors->has("observacoes") ? "maiusculas form-control is-invalid" : "maiusculas form-control")]) }}
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <button type="button" class="btn btn-default btProximaTab pull-right" >Próximo</button>
                    </div>
                </div>
                <div class="tab-pane fade" id="tab_endereco">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="cep">Cep<span style="color:red">*</span></label>
                                {{ Form::text('cep',(isset($pessoa['cep']) ? $pessoa['cep'] : ""),["maxlength" => "10", "id" => "cep", 'class'=> ($errors->has("cep") ? "form-control is-invalid mask-cep" : "form-control mask-cep")]) }}
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="endereco">Endereço<span style="color:red">*</span></label>
                                {{ Form::text('endereco',(isset($pessoa['endereco']) ? $pessoa['endereco'] : ""),["maxlength" => "40", "id" => "endereco", 'class'=> ($errors->has("endereco") ? "maiusculas form-control is-invalid" : "maiusculas form-control")]) }}
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="endereco_nro">Número<span style="color:red">*</span></label>
                                {{ Form::text('endereco_nro',(isset($pessoa['endereco_nro']) ? $pessoa['endereco_nro'] : ""),["maxlength" => "20", "id" => "endereco_nro", 'class'=> ($errors->has("endereco_nro") ? "form-control is-invalid" : "form-control")]) }}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="endereco_compl">Complemento</label>
                                {{ Form::text('endereco_compl',(isset($pessoa['endereco_compl']) ? $pessoa['endereco_compl'] : ""),["maxlength" => "40", "id" => "endereco_compl", 'class'=> ($errors->has("endereco_compl") ? "maiusculas form-control is-invalid" : "maiusculas form-control")]) }}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="bairro">Bairro<span style="color:red">*</span></label>
                                {{ Form::text('bairro',(isset($pessoa['bairro']) ? $pessoa['bairro'] : ""),["maxlength" => "30", "id" => "bairro", 'class'=> ($errors->has("bairro") ? "maiusculas form-control is-invalid" : "maiusculas form-control")]) }}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="localidade">Cidade<span style="color:red">*</span></label>
                                {{ Form::text('localidade',(isset($pessoa['localidade']) ? $pessoa['localidade'] : ""),["maxlength" => "40", "id" => "localidade", 'class'=> ($errors->has("localidade") ? "maiusculas form-control is-invalid" : "maiusculas form-control")]) }}
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <label for="uf">UF<span style="color:red">*</span></label>
                                {{ Form::text('uf',(isset($pessoa['uf']) ? $pessoa['uf'] : ""),["maxlength" => "2", "id" => "uf", 'class'=> ($errors->has("uf") ? "maiusculas form-control is-invalid" : "maiusculas form-control")]) }}
                            </div>
                        </div>
                    </div>

                    <div class="panel-footer">
                        <button type="button" class="btn btn-default btProximaTab pull-right" >Próximo</button>
                        <button type="button" class="btn btn-default btTabAnterior pull-right" >Anterior</button>
                    </div>
                </div>

                <div class="tab-pane fade" id="tab_pfisica">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="id_sexo">Sexo</label>
                                {{  Form::select('id_sexo', ['M' => 'MASCULINO', 'F' => 'FEMININO'], (isset($pessoa['id_sexo']) ? $pessoa['id_sexo'] : "M"),['class'=> ($errors->has("id_sexo") ? "form-control is-invalid" : "form-control"), 'id' => 'id_sexo']) }}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="id_civil">Estado Civil</label>
                                {{  Form::select('id_civil', ['S' => 'SOLTEIRO', 'C' => 'CASADO'], (isset($pessoa['id_civil']) ? $pessoa['id_civil'] : "S"),['class'=> ($errors->has("id_civil") ? "form-control is-invalid" : "form-control"), 'id' => 'id_civil']) }}
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="ident_num">Identidade</label>
                                {{ Form::text('ident_num',(isset($pessoa['ident_num']) ? $pessoa['ident_num'] : ""),["maxlength" => "10", "id" => "ident_num", 'class'=> ($errors->has("ident_num") ? "form-control is-invalid" : "form-control")]) }}
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="ident_org">Orgão</label>
                                {{ Form::text('ident_org',(isset($pessoa['ident_org']) ? $pessoa['ident_org'] : ""),["maxlength" => "10", "id" => "ident_org", 'class'=> ($errors->has("ident_org") ? "maiusculas form-control is-invalid" : "maiusculas form-control")]) }}
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="dt_nasc">Data de Nascimento</label>
                                {{ Form::date('dt_nasc',(isset($pessoa['dt_nasc']) ? $pessoa['dt_nasc'] : ""),["id" => "dt_nasc", 'class'=> ($errors->has("dt_nasc") ? "form-control is-invalid" : "form-control")]) }}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="ds_natural">Naturalidade</label>
                                {{ Form::text('ds_natural',(isset($pessoa['ds_natural']) ? $pessoa['ds_natural'] : ""),["maxlength" => "40", "id" => "ds_natural", 'class'=> ($errors->has("ds_natural") ? "maiusculas form-control is-invalid" : "maiusculas form-control")]) }}
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="nm_pai">Nome do Pai</label>
                                {{ Form::text('nm_pai',(isset($pessoa['nm_pai']) ? $pessoa['nm_pai'] : ""),["maxlength" => "40", "id" => "nm_pai", 'class'=> ($errors->has("nm_pai") ? "maiusculas form-control is-invalid" : "maiusculas form-control")]) }}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="nm_mae">Nome da Mãe</label>
                                {{ Form::text('nm_mae',(isset($pessoa['nm_mae']) ? $pessoa['nm_mae'] : ""),["maxlength" => "40", "id" => "nm_mae", 'class'=> ($errors->has("nm_mae") ? "maiusculas form-control is-invalid" : "maiusculas form-control")]) }}
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="ds_trabalho">Empresa</label>
                                {{ Form::text('ds_trabalho',(isset($pessoa['ds_trabalho']) ? $pessoa['ds_trabalho'] : ""),["maxlength" => "30", "id" => "ds_trabalho", 'class'=> ($errors->has("ds_trabalho") ? "maiusculas form-control is-invalid" : "maiusculas form-control")]) }}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="ds_atividade">Atividade</label>
                                {{ Form::text('ds_atividade',(isset($pessoa['ds_atividade']) ? $pessoa['ds_atividade'] : ""),["maxlength" => "60", "id" => "ds_atividade", 'class'=> ($errors->has("ds_atividade") ? "maiusculas form-control is-invalid" : "maiusculas form-control")]) }}
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <label for="senha">Senha</label>
                                {{ Form::text('senha',(isset($pessoa['senha']) ? $pessoa['senha'] : ""),["name" => "senha", "maxlength" => "20", "id" => "senha", 'class'=> ($errors->has("senha") ? "form-control is-invalid" : "form-control")]) }}
                            </div>
                        </div>

                    </div>
                    <div class="panel-footer">
                        <button type="button" class="btn btn-default btProximaTab pull-right" >Próximo</button>
                        <button type="button" class="btn btn-default btTabAnterior pull-right" >Anterior</button>
                    </div>
                </div>
                <div class="tab-pane fade" id="tab_pjuridica">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="id_regime">Regime</label>
                                {{  Form::select('id_regime', ['S' => 'SIMPLES', 'O' => 'OUTRO'], (isset($pessoa['id_regime']) ? $pessoa['id_regime'] : "S"),['class'=> ($errors->has("id_regime") ? "form-control is-invalid" : "form-control"), 'id' => 'id_regime']) }}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="id_contribuinte">Contribuinte</label>
                                {{  Form::select('id_contribuinte', ['S' => 'SIM', 'N' => 'NÃO'], (isset($pessoa['id_contribuinte']) ? $pessoa['id_contribuinte'] : "N"),['class'=> ($errors->has("id_contribuinte") ? "form-control is-invalid" : "form-control"), 'id' => 'id_contribuinte']) }}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="nm_contato">Nome do Contato</label>
                                {{ Form::text('nm_contato',(isset($pessoa['nm_contato']) ? $pessoa['nm_contato'] : ""),["maxlength" => "40", "id" => "nm_contato", 'class'=> ($errors->has("nm_contato") ? "maiusculas form-control is-invalid" : "maiusculas form-control")]) }}
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="dt_nasc_contato">Dt Nascimento do Contato</label>
                                {{ Form::date('dt_nasc_contato',(isset($pessoa['dt_nasc_contato']) ? $pessoa['dt_nasc_contato'] : ""),["id" => "dt_nasc_contato", 'class'=> ($errors->has("dt_nasc_contato") ? "form-control is-invalid" : "form-control")]) }}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nm_fantasia">Nome Fantasia</label>
                                {{ Form::text('nm_fantasia',(isset($pessoa['nm_fantasia']) ? $pessoa['nm_fantasia'] : ""),["maxlength" => "40", "id" => "nm_fantasia", 'class'=> ($errors->has("nm_fantasia") ? "maiusculas form-control is-invalid" : "maiusculas form-control")]) }}
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="inscricao">Inscrição</label>
                                {{ Form::text('inscricao',(isset($pessoa['inscricao']) ? $pessoa['inscricao'] : ""),["maxlength" => "14", "id" => "inscricao", 'class'=> ($errors->has("inscricao") ? "form-control is-invalid" : "form-control")]) }}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="cd_regiao">Região</label>
                                {{ Form::text('cd_regiao',(isset($pessoa['cd_regiao']) ? $pessoa['cd_regiao'] : ""),["maxlength" => "10", "id" => "cd_regiao", 'class'=> ($errors->has("cd_regiao") ? "form-control is-invalid" : "form-control")]) }}
                            </div>
                        </div>
                    </div>

                    <div class="panel-footer">
                        <button type="button" class="btn btn-default btProximaTab pull-right" >Próximo</button>
                        <button type="button" class="btn btn-default btTabAnterior pull-right" >Anterior</button>
                    </div>

                </div>
                <div class="tab-pane fade" id="tab_complementar">

                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="cep_aux">Cep auxiliar</label>
                                {{ Form::text('cep_aux',(isset($pessoa['cep_aux']) ? $pessoa['cep_aux'] : 0),["maxlength" => "10", "id" => "cep_aux", 'class'=> ($errors->has("cep_aux") ? "form-control" : "form-control mask-cep")]) }}
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="end_aux">Endereço auxiliar</label>
                                {{ Form::text('end_aux',(isset($pessoa['end_aux']) ? $pessoa['end_aux'] : ""),["maxlength" => "40", "id" => "end_aux", 'class'=> ($errors->has("end_aux") ? "maiusculas form-control is-invalid" : "maiusculas form-control")]) }}
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="end_aux_nro">Número auxiliar</label>
                                {{ Form::text('end_aux_nro',(isset($pessoa['end_aux_nro']) ? $pessoa['end_aux_nro'] : ""),["maxlength" => "20", "id" => "end_aux_nro", 'class'=> ($errors->has("end_aux_nro") ? "form-control is-invalid" : "form-control")]) }}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="end_aux_compl">Complemento</label>
                                {{ Form::text('end_aux_compl',(isset($pessoa['end_aux_compl']) ? $pessoa['end_aux_compl'] : ""),["maxlength" => "40", "id" => "end_aux_compl", 'class'=> ($errors->has("end_aux_compl") ? "maiusculas form-control is-invalid" : "maiusculas form-control")]) }}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="bairro_aux">Bairro auxiliar</label>
                                {{ Form::text('bairro_aux',(isset($pessoa['bairro_aux']) ? $pessoa['bairro_aux'] : ""),["maxlength" => "30", "id" => "bairro_aux", 'class'=> ($errors->has("bairro_aux") ? "maiusculas form-control is-invalid" : "maiusculas form-control")]) }}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="localidade_aux">Cidade auxiliar</label>
                                {{ Form::text('localidade_aux',(isset($pessoa['localidade_aux']) ? $pessoa['localidade_aux'] : ""),["maxlength" => "40", "id" => "localidade_aux", 'class'=> ($errors->has("localidade_aux") ? "maiusculas form-control is-invalid" : "maiusculas form-control")]) }}
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <label for="uf_aux">uf auxiliar</label>
                                {{ Form::text('uf_aux',(isset($pessoa['uf_aux']) ? $pessoa['uf_aux'] : ""),["maxlength" => "2", "id" => "uf_aux", 'class'=> ($errors->has("uf_aux") ? "maiusculas form-control is-invalid" : "maiusculas form-control")]) }}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="nr_fone_aux">Telefone auxiliar</label>
                                {{ Form::text('nr_fone_aux',(isset($pessoa['nr_fone_aux']) ? $pessoa['nr_fone_aux'] : ""),["maxlength" => "16", "id" => "nr_fone_aux", 'class'=> ($errors->has("nr_fone_aux") ? "form-control is-invalid" : "form-control mask-telefone")]) }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="tab_plano">

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="cd_plano">Plano</label>
                                {{  Form::select('cd_plano', $planos, (isset($pessoa['cd_plano']) ? $pessoa['cd_plano'] : "1"),['class'=> ($errors->has("cd_plano") ? "form-control is-invalid" : "form-control"), 'id' => 'cd_plano']) }}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="cd_beneficiario">Código do Cartão</label>
                                {{ Form::text('cd_beneficiario',(isset($pessoa['cd_beneficiario']) ? $pessoa['cd_beneficiario'] : ""),["maxlength" => "8", "id" => "cd_beneficiario", 'class'=> ($errors->has("cd_beneficiario") ? "form-control is-invalid" : "form-control")]) }}
                            </div>
                        </div>
                    </div>

                    <div class="panel-footer">
                            @if((session()->get('recurso.pessoas-editar')))
                                {{ Form::submit('Salvar',['class'=>"btn btn-success pull-right salvar-pessoa"]) }}
                            @endif
                            <button type="button" class="btn btn-default btTabAnterior pull-right" >Anterior</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if((session()->get('recurso.pessoas-editar')))
        {{ Form::submit('Salvar',['class'=>"btn btn-success pull-right salvar-pessoa"]) }}
    @endif
    {{ Form::close() }}
    @if (!empty(Session::get('status')))
        <div class="alert alert-info" id="msg">
            {{ Session::get('status') }}
        </div>
    @endif
@endsection

