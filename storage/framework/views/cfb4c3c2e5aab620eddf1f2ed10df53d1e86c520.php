<div class="modal" tabindex="-1" role="dialog" id="modal-pesquisa">
    <div class="modal-dialog" role="document" id="dialog-pessoa">
        <div class="modal-content" id="content-pessoa">
            <div class="modal-header" style="height: 8%">
                <button class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Fechar</span></button>
                <h3 id="nome-header" class="modal-title">Cadastro de pessoas</h3>
            </div>
            <div class="modal-body" style="height: 80%;">
                <div id="painel_pesquisa">
                    <div class="panel panel-default">
                        <div class="panel-body" id="panel-pesquisa">
                            <div class="container-fluid">
                                <?php if(!isset($pesquisa_beneficiario)): ?>
                                    <div class="col-sm-4">
                                        <label id="nome" for="nome">Nome</label>
                                        <input type="text" maxlength="60" id="nome_pesquisa" placeholder="Digite um nome" class="form-control">
                                    </div>
                                <?php else: ?>
                                    <div class="col-sm-4">
                                        <label id="nome" for="nome">Nome/ Código de beneficiário</label>
                                        <input type="text" maxlength="60" id="nome_pesquisa" placeholder="Digite um nome ou código de beneficiário" class="form-control">
                                    </div>
                                <?php endif; ?>
                                <button class="btn btn-default margin-top-25" type="button" id="realizar-pesquisa">Buscar</button>
                                <button class="btn btn-success pull-right  margin-top-25" type="button" id="novo_cadastro">Novo cadastro</button>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive tabela-pesquisa" >
                        <table class="table table-bordered table-hover table-striped" style="font-size: 14px;">
                            <thead id="head-table-pessoas">
                            </thead>
                            <tbody id="table-pessoas">
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php echo e(Form::open(["id" => "form-modal-pessoas", 'class' => 'form-no-submit'])); ?>

                <div id="painel_cadastro" class="panel with-nav-tabs panel-primary">
                    <div class="panel-heading">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab_informacoes" data-toggle="tab">Informações Gerais</a></li>
                            <li><a href="#tab_endereco" data-toggle="tab">Endereço</a></li>
                            <li><a id="tab_titulo_cpf_cnpj" href="#tab_pfisica" data-toggle="tab">Pessoa Física</a></li>
                            <li><a id="usar_tab_biometria" href="#tab_biometria" data-toggle="tab">Informações Biométricas</a></li>
                            <!-- <h5 id="nome-header" class="text-center"></h5> -->
                            <input type="hidden" name="nao_validar_endereco" id="nao_validar_endereco" value="0">
                            <?php if(!isset($ver)): ?>
                                <?php if(!isset($novo_prontuario)): ?>
                                    <input type="hidden" id="novo_prontuario" value="0">
                                    <input type="button" class="btn btn-primary pull-right seleciona-pessoa" id="seleciona-pesssoa" style="display: none" value="Selecionar">
                                <?php else: ?>
                                    <button type="button" id='painel-planos' data-toggle='modal' data-atendimento='true' data-target="#novo-atendimento" style="display: none" class="btn btn-primary pull-right">Novo atendimento</button>
                                    <input type="hidden" id="novo_prontuario" value="1">
                                <?php endif; ?>
                             <?php endif; ?>
                        </ul>
                    </div>
                    <div class="panel-body">
                        <div class="tab-content">
                            <div class="tab-pane fade in active" id="tab_informacoes">
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="cd_pessoa">Código<span style="color:red">*</span></label>
                                            <?php echo e(Form::text('p_cd_pessoa',(isset($pessoa['cd_pessoa']) ? $pessoa['cd_pessoa'] : ""),["maxlength" => "10", "id" => "p_cd_pessoa",  "disabled" => "disabled", 'class'=> 'form-control cd_pessoa'])); ?>

                                            <?php echo e(Form::hidden('cd_pessoa', (isset($pessoa['cd_pessoa']) ? $pessoa['cd_pessoa'] : "") ,["id" => "cd_pessoa", "name" => "cd_pessoa", "class"=>"cd_pessoa"])); ?>

                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="nm_pessoa">Nome<span style="color:red">*</span></label>
                                            <?php echo e(Form::text('nm_pessoa', (isset($pessoa['nm_pessoa']) ? $pessoa['nm_pessoa'] : "") ,["maxlength" => "60", "id" => "p_nm_pessoa", 'class'=> "maiusculas form-control" ])); ?>

                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="id_situacao">Situação</label>
                                            <?php echo e(Form::select('id_situacao', ['A' => 'ATIVO', 'I' => 'INATIVO'], (isset($pessoa['id_situacao']) ? $pessoa['id_situacao'] : "A"),['class'=> "form-control", "id" => "p_id_situacao"])); ?>

                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <?php if(isset($escolhe_pf_pj)): ?>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="id_pessoa">Tipo</label>
                                                <?php echo e(Form::select('id_pessoa', ['F' => 'PESSOA FÍSICA', 'J' => 'PESSOA JURÍDICA'],'',['class'=> "form-control", 'id' => 'p_id_pessoa'])); ?>

                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <?php echo e(Form::hidden('id_pessoa',(isset($pj) ? "J" : "F"),["name" => "id_pessoa", "id" => "p_id_pessoa", "class" => "id_pessoa"])); ?>

                                    <?php endif; ?>
                                    <div id="escolhe_cpf_cnpj" class="col-md-2">
                                        <div class="form-group">
                                            <label class="label_cnpj_cpf" for="cnpj_cpf">CPF<span style="color:red">*</span></label>
                                            <?php echo e(Form::text('cnpj_cpf',(isset($pessoa['cnpj_cpf']) ? $pessoa['cnpj_cpf'] : ""),["id" => "p_cnpj_cpf", 'class'=> "cnpj_cpf form-control validar"])); ?>

                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="nr_fone1">Telefone</label>
                                            <?php echo e(Form::text('nr_fone1',(isset($pessoa['nr_fone1']) ? $pessoa['nr_fone1'] : ""),["maxlength" => "16", "id" => "p_nr_fone1", 'class'=> "form-control mask-telefone"])); ?>

                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="nr_fone2">Telefone</label>
                                            <?php echo e(Form::text('nr_fone2',(isset($pessoa['nr_fone2']) ? $pessoa['nr_fone2'] : ""),["maxlength" => "16", "id" => "p_nr_fone2", 'class'=> "form-control mask-telefone"])); ?>

                                        </div>
                                    </div>
                                    <div class=<?php echo e(isset($escolhe_pf_pj) ? "col-md-4" : "col-md-5"); ?>>
                                        <div class="form-group">
                                            <label for="ds_email">Email</label>
                                            <?php echo e(Form::text('ds_email',(isset($pessoa['ds_email']) ? $pessoa['ds_email'] : ""),["maxlength" => "50", "id" => "p_ds_email", 'class'=> "minusculas form-control"])); ?>

                                        </div>
                                    </div>
                                </div>
                                <div id="responsaveis">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="nm_responsavel1">Responsável em caso de urgência</label>
                                                <?php echo e(Form::text('nm_responsavel1', (isset($pessoa['nm_responsavel1']) ? $pessoa['nm_responsavel1'] : "") ,["maxlength" => "60", "id" => "p_nm_responsavel1", 'class'=> "maiusculas form-control" ])); ?>

                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="nr_fone_responsavel1">Telefone</label>
                                                <?php echo e(Form::text('nr_fone_responsavel1',(isset($pessoa['nr_fone_responsavel1']) ? $pessoa['nr_fone_responsavel1'] : ""),["maxlength" => "16", "id" => "p_nr_fone_responsavel1", 'class'=> "form-control mask-telefone"])); ?>

                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="nm_medico_responsavel">Médico responsável</label>
                                                <?php echo e(Form::text('nm_medico_responsavel', (isset($pessoa['nm_medico_responsavel']) ? $pessoa['nm_medico_responsavel'] : "") ,["maxlength" => "60", "id" => "p_nm_medico_responsavel", 'class'=> "maiusculas form-control" ])); ?>

                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="nr_fone_medico_responsavel">Telefone</label>
                                                <?php echo e(Form::text('nr_fone_medico_responsavel',(isset($pessoa['nr_fone_medico_responsavel']) ? $pessoa['nr_fone_medico_responsavel'] : ""),["maxlength" => "16", "id" => "p_nr_fone_medico_responsavel", 'class'=> "form-control mask-telefone"])); ?>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <?php echo e(Form::text('nm_responsavel2', (isset($pessoa['nm_responsavel2']) ? $pessoa['nm_responsavel2'] : "") ,["maxlength" => "60", "id" => "p_nm_responsavel2", 'class'=> "maiusculas form-control" ])); ?>

                                                <label for="nm_responsavel2">&nbsp;</label>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <?php echo e(Form::text('nr_fone_responsavel2',(isset($pessoa['nr_fone_responsavel2']) ? $pessoa['nr_fone_responsavel2'] : ""),["maxlength" => "16", "id" => "p_nr_fone_responsavel2", 'class'=> "form-control mask-telefone"])); ?>

                                                <label for="nr_fone_responsavel2">&nbsp;</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="tab_endereco">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <label for="cep">Cep<span style="color:red">*</span></label>
                                                <?php echo e(Form::text('cep',(isset($pessoa['cep']) ? $pessoa['cep'] : ""),["maxlength" => "10", "id" => "p_cep", 'class'=> "form-control mask-cep"])); ?>

                                                <span class="input-group-btn">
                                                    <button class="btn btn-info margin-top-25" type="button" data-toggle="modal" data-target="#modal-pesquisa-cep" ><span class="fa fa-search"></span> </button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-7">
                                        <div class="form-group">
                                            <label for="localidade">Cidade<span style="color:red">*</span></label>
                                            <?php echo e(Form::text('localidade',(isset($pessoa['localidade']) ? $pessoa['localidade'] : ""),["maxlength" => "100", "id" => "p_localidade", 'class'=> "maiusculas form-control"])); ?>

                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <label for="uf">UF<span style="color:red">*</span></label>
                                            <?php echo e(Form::text('uf',(isset($pessoa['uf']) ? $pessoa['uf'] : ""),["maxlength" => "2", "id" => "p_uf", 'class'=> "maiusculas form-control"])); ?>

                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="endereco">Endereço<span style="color:red">*</span></label>
                                            <?php echo e(Form::text('endereco',(isset($pessoa['endereco']) ? $pessoa['endereco'] : ""),["maxlength" => "100", "id" => "p_endereco", 'class'=> "maiusculas form-control"])); ?>

                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="endereco_nro">Número<span style="color:red">*</span></label>
                                            <?php echo e(Form::text('endereco_nro',(isset($pessoa['endereco_nro']) ? $pessoa['endereco_nro'] : ""),["maxlength" => "20", "id" => "p_endereco_nro", 'class'=> "form-control maiusculas"])); ?>

                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="endereco_compl">Complemento</label>
                                            <?php echo e(Form::text('endereco_compl',(isset($pessoa['endereco_compl']) ? $pessoa['endereco_compl'] : ""),["maxlength" => "100", "id" => "p_endereco_compl", 'class'=> "maiusculas form-control"])); ?>

                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="bairro">Bairro<span style="color:red">*</span></label>
                                            <?php echo e(Form::text('bairro',(isset($pessoa['bairro']) ? $pessoa['bairro'] : ""),["maxlength" => "100", "id" => "p_bairro", 'class'=> "maiusculas form-control"])); ?>

                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-11">
                                        <div class="form-group">
                                            <label for="observacoes">Observações</label>
                                            <?php echo e(Form::text('observacoes',(isset($pessoa['observacoes']) ? $pessoa['observacoes'] : ""),["maxlength" => "90", "id" => "p_observacoes", 'class'=> "maiusculas form-control"])); ?>

                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="tab-pane fade" id="tab_pfisica">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="id_sexo">Sexo</label>
                                            <?php echo e(Form::select('id_sexo', ['M' => 'MASCULINO', 'F' => 'FEMININO'], (isset($pessoa['id_sexo']) ? $pessoa['id_sexo'] : "M"),['class'=> "form-control", "id" => "p_id_sexo"])); ?>

                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="id_civil">Estado Civil</label>
                                            <?php echo e(Form::select('id_civil', ['S' => 'SOLTEIRO', 'C' => 'CASADO'], (isset($pessoa['id_civil']) ? $pessoa['id_civil'] : "S"),['class'=> "form-control", "id" => "p_id_civil"])); ?>

                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="id_raca_cor">Raça/ Cor</label>
                                            <?php echo e(Form::select('id_raca_cor', arrayPadrao('raca_cor'), (isset($pessoa['id_raca_cor']) ? $pessoa['id_raca_cor'] : 0),['class'=> "form-control", "id" => "p_id_raca_cor"])); ?>

                                        </div>
                                    </div>
                                    <div class="col-md-4" id="div_etnia" style="display: none">
                                        <div class="form-group">
                                            <label for="cd_etnia">Etnia</label>
                                            <?php echo e(Form::select('cd_etnia', arrayPadrao('etnias'), (isset($pessoa['cd_etnia']) ? $pessoa['cd_etnia'] : null),['class'=> "form-control", "id" => "p_cd_etnia"])); ?>

                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="ident_num">Identidade</label>
                                            <?php echo e(Form::text('ident_num',(isset($pessoa['ident_num']) ? $pessoa['ident_num'] : ""),["maxlength" => "10", "id" => "p_ident_num", 'class'=> "form-control"])); ?>

                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="ident_org">Orgão</label>
                                            <?php echo e(Form::text('ident_org',(isset($pessoa['ident_org']) ? $pessoa['ident_org'] : ""),["maxlength" => "10", "id" => "p_ident_org", 'class'=> "maiusculas form-control"])); ?>

                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="dt_nasc">Dt. Nasc.<span style="color:red">*</span></label>
                                            <?php echo e(Form::text('dt_nasc',(isset($pessoa['dt_nasc']) ? $pessoa['dt_nasc'] : ""),["id" => "p_dt_nasc", 'class'=> "form-control mask-data"])); ?>

                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="cd_nacionalidade">Nacionalidade</label>
                                            <?php echo e(Form::select('cd_nacionalidade', arrayPadrao('nacionalidades'), (isset($pessoa['cd_nacionalidade']) ? $pessoa['cd_nacionalidade'] : 10),['class'=> "form-control", "id" => "p_cd_nacionalidade"])); ?>

                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="ds_natural">Naturalidade</label>
                                            <?php echo e(Form::text('ds_natural',(isset($pessoa['ds_natural']) ? $pessoa['ds_natural'] : ""),["maxlength" => "40", "id" => "p_ds_natural", 'class'=> "maiusculas form-control"])); ?>

                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="nm_pai">Nome do Pai</label>
                                            <?php echo e(Form::text('nm_pai',(isset($pessoa['nm_pai']) ? $pessoa['nm_pai'] : ""),["maxlength" => "40", "id" => "p_nm_pai", 'class'=> "maiusculas form-control"])); ?>

                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="nm_mae">Nome da Mãe</label>
                                            <?php echo e(Form::text('nm_mae',(isset($pessoa['nm_mae']) ? $pessoa['nm_mae'] : ""),["maxlength" => "40", "id" => "p_nm_mae", 'class'=> "maiusculas form-control"])); ?>

                                        </div>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="ds_trabalho">Empresa</label>
                                            <?php echo e(Form::text('ds_trabalho',(isset($pessoa['ds_trabalho']) ? $pessoa['ds_trabalho'] : ""),["maxlength" => "30", "id" => "p_ds_trabalho", 'class'=> "maiusculas form-control"])); ?>

                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="ds_atividade">Atividade</label>
                                            <?php echo e(Form::text('ds_atividade',(isset($pessoa['ds_atividade']) ? $pessoa['ds_atividade'] : ""),["maxlength" => "60", "id" => "p_ds_atividade", 'class'=> "maiusculas form-control"])); ?>

                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="cd_beneficiario">CNS</label>
                                            <?php echo e(Form::text('cd_beneficiario',(isset($pessoa['cd_beneficiario']) ? $pessoa['cd_beneficiario'] : ""),["maxlength" => "20", "id" => "p_cd_beneficiario", 'class'=> "form-control mask-numeros-20"])); ?>

                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="tab-pane fade" id="tab_pjuridica">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="id_regime">Regime</label>
                                            <?php echo e(Form::select('id_regime', ['S' => 'SIMPLES', 'O' => 'OUTRO'], (isset($pessoa['id_regime']) ? $pessoa['id_regime'] : "S"),['class'=> "form-control", 'id' => 'p_id_regime'])); ?>

                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="id_contribuinte">Contribuinte</label>
                                            <?php echo e(Form::select('id_contribuinte', ['S' => 'SIM', 'N' => 'NÃO'], (isset($pessoa['id_contribuinte']) ? $pessoa['id_contribuinte'] : "N"),['class'=> "form-control", 'id' => 'p_id_contribuinte'])); ?>

                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="nm_contato">Nome do Contato</label>
                                            <?php echo e(Form::text('nm_contato',(isset($pessoa['nm_contato']) ? $pessoa['nm_contato'] : ""),["maxlength" => "40", "id" => "p_nm_contato", 'class'=> "maiusculas form-control"])); ?>

                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="dt_nasc_contato">Dt Nasc. Contato</label>
                                            <?php echo e(Form::date('dt_nasc_contato',(isset($pessoa['dt_nasc_contato']) ? $pessoa['dt_nasc_contato'] : ""),["id" => "p_dt_nasc_contato", 'class'=> "form-control"])); ?>

                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="nm_fantasia">Nome Fantasia</label>
                                            <?php echo e(Form::text('nm_fantasia',(isset($pessoa['nm_fantasia']) ? $pessoa['nm_fantasia'] : ""),["maxlength" => "40", "id" => "p_nm_fantasia", 'class'=> "maiusculas form-control"])); ?>

                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="inscricao">Inscrição</label>
                                            <?php echo e(Form::text('inscricao',(isset($pessoa['inscricao']) ? $pessoa['inscricao'] : ""),["maxlength" => "14", "id" => "p_inscricao", 'class'=> "form-control"])); ?>

                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="cd_regiao">Região</label>
                                            <?php echo e(Form::text('cd_regiao',(isset($pessoa['cd_regiao']) ? $pessoa['cd_regiao'] : ""),["maxlength" => "10", "id" => "p_cd_regiao", 'class'=> "form-control"])); ?>

                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="tab_biometria">
                                <div class="col-md-1"></div>
                                <div class="col-md-4">
                                    <div class="panel panel-default">
                                        <div id="corpo-painel-foto" class="panel-body">
                                            <img class="img-responsive foto-pessoa" id="foto-pessoa" src="<?php echo e(isset($pessoa['nm_arquivo']) ? asset("storage/app/images/pessoas/".$pessoa['cd_pessoa']."/".arrayPadrao('tipo_arquivo')[1]."/".$pessoa['nm_arquivo']): asset('public/images/sem_foto.jpg')); ?>">
                                            <input type="hidden" id="arquivo-foto-pessoa" name="foto_pessoa">
                                        </div>
                                        <div class="panel-footer" style="text-align: center">
                                            <button type="button" data-toggle="modal" id="btn-modal-webcam" data-target="#modal-capturar-foto" class="btn btn-primary" title="Capturar imagem com a câmera">Tirar foto &nbsp<span class="fa fa-camera"></span></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-1"></div>
                                <div class="col-md-4">
                                    <div class="panel panel-default">
                                        <div class="panel-body">
                                            <img class="img-responsive foto-pessoa" id="imagem_digital" src="<?php echo e(asset('public/images/sem_digital.jpg')); ?>">
                                            <?php echo e(Form::hidden('impressao_digital', "",['class'=> "form-control", "name"=>"impressao_digital","id" => "p_impressao_digital"])); ?>

                                        </div>
                                        <div class="panel-footer" style="text-align: center">
                                            <button type="button" class="btn btn-primary" title="Capturar impressões digitais" id="bt-capturar-biometria">Capturar &nbsp<span class="fas fa-fingerprint"></span></button>
                                            <button type="button" class="btn btn-primary" title="Comparar impressões digitais" id="bt-comparar-biometria">Comparar &nbsp<span class="fas fa-fingerprint"></span></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php echo e(Form::close()); ?>

            </div>
            <div class="modal-footer" style="height: 12%;">
                <div class="mensagem-retorno"></div>
                <button type="button" class="btn btn-success pull-right salvar-pessoa" id="bt-salvar">Salvar</button>
                <button type="button" class="btn btn-success " style="display: none" id="nova_pesquisa">Nova pesquisa</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="modal-pesquisa-cep">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document" id="dialog-pesquisa-cep">
        <div class="modal-content" id="content-pesquisa-cep">
            <div class="modal-header">
                <button class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Fechar</span></button>
                <h3 id="nome-header" class="modal-title">Pesquisa de Endereço</h3>
            </div>
            <div class="modal-body">

                <div class="panel panel-default">
                    <div class="panel-body" id="panel-pesquisa">
                        <div class="container-fluid">
                            <div class="col-sm-2">
                                <label id="nome" for="nome">UF</label>
                                <input type="text" maxlength="2" id="uf_pesquisa_cep" value="RS" class="form-control">
                            </div>
                            <div class="col-sm-3">
                                <label id="nome" for="nome">Cidade</label>
                                <input type="text" maxlength="25" id="cidade_pesquisa_cep" value="<?php echo e(session()->get('cidade_estabelecimento')); ?>" class="form-control">
                            </div>
                            <div class="col-sm-5">
                                <label id="nome" for="nome">Endereço</label>
                                <input type="text" maxlength="25" id="endereco_pesquisa_cep" placeholder="Digite um nome" class="form-control">
                            </div>
                            <button class="btn btn-default margin-top-25" type="button" id="btn-pesquisar-cep">Buscar</button>
                        </div>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-body" id="panel-pesquisa">
                         <div class="table-responsive tabela-pesquisa">
                            <table class="table table-bordered table-hover table-striped font-size-9pt" >
                                <tr>
                                    <td><b>Cep</b></td>
                                    <td><b>Logradouro</b></td>
                                    <td><b>Complemento</b></td>
                                    <td><b>Bairro</b></td>
                                    <td><b>Localidade</b></td>
                                    <td><b></b></td>
                                </tr>
                                <tbody id="table-cep">
                                <tr><td colspan="6">Sem resultados para exibir....</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

 <?php echo $__env->make('pessoas/modal-foto', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>


<?php if(isset($verPessoaSemPesquisa)): ?>
    <script>
        $('#painel_cadastro').show();
        $('#painel_pesquisa').hide();
    </script>
<?php else: ?>
    <script>
        $('#painel_cadastro').hide();
        $('#painel_pesquisa').show();
    </script>
<?php endif; ?>
<script src="<?php echo e(js_versionado('modal_pessoa.js')); ?>" defer></script>
<script src="<?php echo e(js_versionado('biometria.js')); ?>" defer></script>

<?php header('Access-Control-Allow-Origin: *'); ?>