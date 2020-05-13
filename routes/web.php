<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'Home@index');
Route::get('home', 'Home@index')->name('home');

/*-------------------------------------------------------------------------
| Pessoas
|--------------------------------------------------------------------------*/
Route::any('pessoas/cadastro/{cdPessoa}', 'Pessoas@cadastrar')->name('pessoas/cadastro');
Route::any('pessoas/cadastro', 'Pessoas@cadastrar')->name('pessoas/cadastro');
Route::any('pessoas/lista', 'Pessoas@listar')->name('pessoas/lista');
//Route::any('pessoas/remover', 'Pessoas@remover')->name('pessoas/remover');
/*-------------------------------------------------------------------------
| Operadores
|--------------------------------------------------------------------------*/
Route::any('operadores/cadastro/{cdPessoa}', 'Operadores@cadastrar')->name('operadores/cadastro');
Route::any('operadores/cadastro', 'Operadores@cadastrar')->name('operadores/cadastro');
Route::any('operadores/lista', 'Operadores@listar')->name('operadores/lista');
Route::any('operadores/remover', 'Operadores@remover')->name('operadores/remover');
Route::any('operadores/meus-dados', 'Operadores@meus_dados')->name('operadores/meus-dados');
/*-------------------------------------------------------------------------
| Grupos de Operadores
|--------------------------------------------------------------------------*/
Route::any('grupos/cadastro/{cdGrupo}', 'Grupos@cadastrar')->name('grupos/cadastro');
Route::any('grupos/cadastro', 'Grupos@cadastrar')->name('grupos/cadastro');
Route::any('grupos/lista', 'Grupos@listar')->name('grupos/lista');
Route::any('grupos/remover', 'Grupos@remover')->name('grupos/remover');
/*-------------------------------------------------------------------------
| Estabelecimentos
|--------------------------------------------------------------------------*/
Route::any('estabelecimentos/lista', 'Estabelecimentos@listar')->name('estabelecimentos/lista');
Route::any('estabelecimentos/cadastro/{idEstabelecimento}', 'Estabelecimentos@cadastrar')->name('estabelecimentos/cadastro');
Route::any('estabelecimentos/cadastro', 'Estabelecimentos@cadastrar')->name('estabelecimentos/cadastro');
Route::any('estabelecimentos/remover', 'Estabelecimentos@remover')->name('estabelecimentos/remover');
Route::any('selecionar_estabelecimento', 'Estabelecimentos@selecionar_estabelecimento')->name('selecionar_estabelecimento');
/*-------------------------------------------------------------------------
| Prontuários
|--------------------------------------------------------------------------*/
Route::any('prontuarios/lista', 'Prontuarios@listar')->name('prontuarios/lista');
/*-------------------------------------------------------------------------
| Planos
|--------------------------------------------------------------------------*/
Route::any('planos/cadastro/{cdPlano}', 'Planos@cadastrar')->name('planos/cadastro');
Route::any('planos/cadastro', 'Planos@cadastrar')->name('planos/cadastro');
Route::any('planos/lista', 'Planos@listar')->name('planos/lista');
Route::any('planos/remover', 'Planos@remover')->name('planos/remover');
/*-------------------------------------------------------------------------
| Salas
|--------------------------------------------------------------------------*/
Route::any('salas/cadastro/{cdSala}', 'Salas@cadastrar')->name('salas/cadastro');
Route::any('salas/cadastro', 'Salas@cadastrar')->name('salas/cadastro');
Route::any('salas/lista', 'Salas@listar')->name('salas/lista');
Route::any('salas/remover', 'Salas@remover')->name('salas/remover');
/*-------------------------------------------------------------------------
| Profissionais
|--------------------------------------------------------------------------*/
Route::any('profissionais/cadastro/{cdProfissional}', 'Profissionais@cadastrar')->name('profissionais/cadastro');
Route::any('profissionais/cadastro', 'Profissionais@cadastrar')->name('profissionais/cadastro');
Route::any('profissionais/lista', 'Profissionais@listar')->name('profissionais/lista');
Route::any('profissionais/remover', 'Profissionais@remover')->name('profissionais/remover');
/*-------------------------------------------------------------------------
| Beneficiários
|--------------------------------------------------------------------------*/
Route::any('beneficiarios/cadastro/{cdBeneficiario}', 'Beneficiarios@cadastrar')->name('beneficiarios/cadastro');
Route::any('beneficiarios/cadastro', 'Beneficiarios@cadastrar')->name('beneficiarios/cadastro');
Route::any('beneficiarios/lista', 'Beneficiarios@listar')->name('beneficiarios/lista');

/*-------------------------------------------------------------------------
| Configuracoes
|--------------------------------------------------------------------------*/
Route::any('configuracoes/configuracoes', 'Configuracoes\Procedimentos@index')->name('configuracoes/configuracoes');
    /*-------------------------------------------------------------------------
    | Procedimentos
    |--------------------------------------------------------------------------*/
    Route::any('configuracoes/procedimentos/lista', 'Configuracoes\Procedimentos@procedimentos')->name('configuracoes/procedimentos/lista');
    /*-------------------------------------------------------------------------
    | Alergias
    |--------------------------------------------------------------------------*/
    Route::any('configuracoes/alergias/cadastro/{cdAlergia}', 'Configuracoes\Alergias@cadastrar')->name('configuracoes/alergias/cadastro');
    Route::any('configuracoes/alergias/cadastro', 'Configuracoes\Alergias@cadastrar')->name('configuracoes/alergias/cadastro');
    Route::any('configuracoes/alergias/lista', 'Configuracoes\Alergias@listar')->name('configuracoes/alergias/lista');
    /*-------------------------------------------------------------------------
    | Origem
    |--------------------------------------------------------------------------*/
    Route::any('configuracoes/origem/cadastro/{cdOrigem}', 'Configuracoes\Origem@cadastrar')->name('configuracoes/origem/cadastro');
    Route::any('configuracoes/origem/cadastro', 'Configuracoes\Origem@cadastrar')->name('configuracoes/origem/cadastro');
    Route::any('configuracoes/origem/lista', 'Configuracoes\Origem@listar')->name('configuracoes/origem/lista');
    /*-------------------------------------------------------------------------
    | Unidades Comerciais
    |--------------------------------------------------------------------------*/
    Route::any('configuracoes/unidades-comerciais/cadastro/{cdUnidadeComercial}', 'Configuracoes\Unidades_comerciais@cadastrar')->name('configuracoes/unidades-comerciais/cadastro');
    Route::any('configuracoes/unidades-comerciais/cadastro', 'Configuracoes\Unidades_comerciais@cadastrar')->name('configuracoes/unidades-comerciais/cadastro');
    Route::any('configuracoes/unidades-comerciais/lista', 'Configuracoes\Unidades_comerciais@listar')->name('configuracoes/unidades-comerciais/lista');
    /*-------------------------------------------------------------------------
    | Unidades de Medidas
    |--------------------------------------------------------------------------*/
    Route::any('configuracoes/unidades-medida/cadastro/{cdUnidadeMedida}', 'Configuracoes\Unidades_medida@cadastrar')->name('configuracoes/unidades-medida/cadastro');
    Route::any('configuracoes/unidades-medida/cadastro', 'Configuracoes\Unidades_medida@cadastrar')->name('configuracoes/unidades-medida/cadastro');
    Route::any('configuracoes/unidades-medida/lista', 'Configuracoes\Unidades_medida@listar')->name('configuracoes/unidades-medida/lista');
    /*-------------------------------------------------------------------------
    | Parâmetros
    |--------------------------------------------------------------------------*/
    Route::any('configuracoes/parametros/cadastro/{cdParametro}', 'Configuracoes\Parametros@cadastrar')->name('configuracoes/parametros/cadastro');
    Route::any('configuracoes/parametros/cadastro', 'Configuracoes\Parametros@cadastrar')->name('configuracoes/parametros/cadastro');
    Route::any('configuracoes/parametros/lista', 'Configuracoes\Parametros@listar')->name('configuracoes/parametros/lista');

/*-------------------------------------------------------------------------
| PRODUTOS-> INÍCIO
|--------------------------------------------------------------------------*/
Route::any('materiais/produtos', 'Materiais\Produto@index')->name('materiais/produtos');
    /*-------------------------------------------------------------------------
    | Movimentos
    |--------------------------------------------------------------------------*/
    Route::any('materiais/movimento/cadastro/{cdMovimento}', 'Materiais\Movimento@cadastrar')->name('materiais/movimento/cadastro');
    Route::any('materiais/movimento/cadastro', 'Materiais\Movimento@cadastrar')->name('materiais/movimento/cadastro');
    Route::any('materiais/movimento/lista', 'Materiais\Movimento@listar')->name('materiais/movimento/lista');
    Route::any('materiais/movimento/remover', 'Materiais\Movimento@remover')->name('materiais/movimento/remover');
    /*-------------------------------------------------------------------------
    | Produto
    |--------------------------------------------------------------------------*/
    Route::any('materiais/produto/cadastro/{cdProduto}', 'Materiais\Produto@cadastrar')->name('materiais/produto/cadastro');
    Route::any('materiais/produto/cadastro', 'Materiais\Produto@cadastrar')->name('materiais/produto/cadastro');
    Route::any('materiais/produto/lista', 'Materiais\Produto@listar')->name('materiais/produto/lista');
    Route::any('materiais/produto/remover', 'Materiais\Produto@remover')->name('materiais/produto/remover');
    /*-------------------------------------------------------------------------
    | Grupos
    |--------------------------------------------------------------------------*/
    Route::any('materiais/grupo/cadastro/{cdProduto}', 'Materiais\Grupo@cadastrar')->name('materiais/grupo/cadastro');
    Route::any('materiais/grupo/cadastro', 'Materiais\Grupo@cadastrar')->name('materiais/grupo/cadastro');
    Route::any('materiais/grupo/lista', 'Materiais\Grupo@listar')->name('materiais/grupo/lista');
    Route::any('materiais/grupo/remover', 'Materiais\Grupo@remover')->name('materiais/grupo/remover');
    /*-------------------------------------------------------------------------
    | Fornecedores
    |--------------------------------------------------------------------------*/
    Route::any('materiais/fornecedores/cadastro/{cdFornecedor}', 'Materiais\Fornecedores@cadastrar')->name('materiais/fornecedores/cadastro');
    Route::any('materiais/fornecedores/cadastro', 'Materiais\Fornecedores@cadastrar')->name('materiais/fornecedores/cadastro');
    Route::any('materiais/fornecedores/lista', 'Materiais\Fornecedores@listar')->name('materiais/fornecedores/lista');
    Route::any('materiais/fornecedores/remover', 'Materiais\Fornecedores@remover')->name('materiais/fornecedores/remover');
    /*-------------------------------------------------------------------------
    | Movimentação de Produto
    |--------------------------------------------------------------------------*/
    Route::any('materiais/movimentacao/cadastro/{cdMovimentacao}', 'Materiais\Movimentacao@cadastrar')->name('materiais/movimentacao/cadastro');
    Route::any('materiais/movimentacao/cadastro', 'Materiais\Movimentacao@cadastrar')->name('materiais/movimentacao/cadastro');
    Route::any('materiais/movimentacao/lista', 'Materiais\Movimentacao@listar')->name('materiais/movimentacao/lista');
    Route::any('materiais/movimentacao/remover', 'Materiais\Movimentacao@remover')->name('materiais/movimentacao/remover');
    Route::any('materiais/movimentacao/movimento-sala', 'Materiais\Movimentacao@movimento_sala')->name('materiais/movimentacao/movimento-sala');
    /*-------------------------------------------------------------------------
    | Estoque de Produto
    |--------------------------------------------------------------------------*/
    Route::any('materiais/estoque/lista', 'Materiais\Estoque@listar')->name('materiais/estoque/lista');
    Route::any('materiais/estoque/remover', 'Materiais\Estoque@remover')->name('materiais/estoque/remover');
    /*-------------------------------------------------------------------------
    | Kits
    |--------------------------------------------------------------------------*/
    Route::any('materiais/kits/cadastro/{cdKit}', 'Materiais\Kits@cadastrar')->name('materiais/kits/cadastro');
    Route::any('materiais/kits/cadastro', 'Materiais\Kits@cadastrar')->name('materiais/kits/cadastro');
    Route::any('materiais/kits/lista', 'Materiais\Kits@listar')->name('materiais/kits/lista');
    Route::any('materiais/kits/remover', 'Materiais\Kits@remover')->name('materiais/kits/remover');
    /*-------------------------------------------------------------------------
    | Relatórios
    |--------------------------------------------------------------------------*/
    Route::any('materiais/relatorios/estoque', 'Materiais\Relatorios@relatorio_estoque')->name('materiais/relatorios/estoque');
    /*-------------------------------------------------------------------------
    | Requisições
    |--------------------------------------------------------------------------*/
    Route::any('materiais/requisicoes/cadastro/{cdRequisicao}', 'Materiais\Requisicoes@cadastrar')->name('materiais/requisicoes/cadastro');
    Route::any('materiais/requisicoes/cadastro', 'Materiais\Requisicoes@cadastrar')->name('materiais/requisicoes/cadastro');
    Route::any('materiais/requisicoes/lista', 'Materiais\Requisicoes@listar')->name('materiais/requisicoes/lista');
    Route::any('materiais/requisicoes/remover', 'Materiais\Requisicoes@remover')->name('materiais/requisicoes/remover');
    Route::any('materiais/requisicoes/requisicao-pdf/{cdRequisicao}', 'Materiais\Requisicoes@requisicao_pdf')->name('materiais/requisicoes/requisicao-pdf');
    Route::any('materiais/requisicoes/requisicao-pdf', 'Materiais\Requisicoes@requisicao_pdf')->name('materiais/requisicoes/requisicao-pdf');
    Route::any('materiais/requisicoes/atendimento/{cdRequisicao}', 'Materiais\Requisicoes@atendimento')->name('materiais/requisicoes/atendimento');
    Route::any('materiais/requisicoes/atendimento', 'Materiais\Requisicoes@atendimento')->name('materiais/requisicoes/atendimento');
    /*-------------------------------------------------------------------------
    | Filas
    |--------------------------------------------------------------------------*/
    Route::any('filas/filas', 'Filas@index')->name('filas/filas');
    Route::any('filas/acolhimento', 'Filas@aguardando_acolhimento')->name('filas/acolhimento');
    Route::any('filas/atendimento-medico', 'Filas@aguardando_atendimento_medico')->name('filas/atendimento-medico');
    Route::any('filas/medicina-interna', 'Filas@medicina_interna')->name('filas/medicina-interna');
    Route::any('filas/atendimentos-finalizados', 'Filas@atendimentos_finalizados')->name('filas/atendimentos-finalizados');
    Route::any('filas/procedimentos', 'Filas@aguardando_procedimentos')->name('filas/procedimentos');
    Route::any('filas/procedimentos-radiologicos', 'Filas@aguardando_procedimentos_radiologicos')->name('filas/procedimentos-radiologicos');

/*-------------------------------------------------------------------------
| PRODUTOS-> FIM
|--------------------------------------------------------------------------*/

/*-------------------------------------------------------------------------
| Permissões
|--------------------------------------------------------------------------*/
Route::any('permissoes/lista', 'Permissoes@listar')->name('permissoes/lista');
/*-------------------------------------------------------------------------
| Contratos
|--------------------------------------------------------------------------*/
Route::any('contratos/lista', 'Contratos@listar')->name('contratos/lista');
/*-------------------------------------------------------------------------
| Atendimentos
|--------------------------------------------------------------------------*/
Route::any('atendimentos/fila', 'Atendimentos@fila')->name('atendimentos/fila');
Route::any('atendimentos/pessoas/lista', 'Atendimentos@lista_pessoas')->name('atendimentos/pessoas/lista');
Route::any('atendimentos/pessoas/cadastro/{cdPessoa}', 'Atendimentos@cadastrar_pessoas')->name('atendimentos/pessoas/cadastro');
Route::any('atendimentos/pessoas/cadastro', 'Atendimentos@cadastrar_pessoas')->name('atendimentos/pessoas/cadastro');
Route::any('atendimentos/acolhimento/{cdProntuario}', 'Atendimentos@acolhimento');
Route::any('atendimentos/acolhimento', 'Atendimentos@acolhimento')->name('atendimentos/acolhimento');
Route::any('atendimentos/atendimento-medico/{cdProntuario}', 'Atendimentos@atendimento_medico');
Route::any('atendimentos/atendimento-medico', 'Atendimentos@atendimento_medico')->name('atendimentos/atendimento-medico');
Route::any('atendimentos/atendimento', 'Atendimentos@cadastra_atendimento')->name('atendimentos/atendimento');
Route::any('atendimentos/remover', 'Atendimentos@remover')->name('atendimentos/remover');
Route::any('atendimentos/painel', 'Painel@painel')->name('atendimentos/painel');
Route::any('atendimentos/procedimentos/{cdProntuario}', 'Atendimentos@procedimentos');
Route::any('atendimentos/procedimentos', 'Atendimentos@procedimentos')->name('atendimentos/procedimentos');
/*-------------------------------------------------------------------------
| Relatorios
|--------------------------------------------------------------------------*/
Route::any('relatorios/prontuario/{cd_prontuario}', 'Relatorios\Prontuario@prontuario')->name('relatorios/prontuario');
Route::any('relatorios/prontuario', 'Relatorios\Prontuario@prontuario')->name('relatorios/prontuario');
Route::any('relatorios/prescricao-ambulatorial/{cd_prontuario}/{cd_prescricao}', 'Relatorios\Prescricao@prescricao_ambulatorial')->name('relatorios/prescricao-ambulatorial');
Route::any('relatorios/prescricao-ambulatorial', 'Relatorios\Prescricao@prescricao_ambulatorial')->name('relatorios/prescricao-ambulatorial');
Route::any('relatorios/prescricao-hospitalar/{cd_prontuario}', 'Relatorios\Prescricao@prescricao_hospitalar')->name('relatorios/prescricao-hospitalar');
Route::any('relatorios/prescricao-hospitalar', 'Relatorios\Prescricao@prescricao_hospitalar')->name('relatorios/prescricao-hospitalar');

Route::any('relatorios/atendimentos/media', 'Relatorios\Atendimentos@media')->name('relatorios/atendimentos/media');
Route::any('relatorios/receita-especial/{cd_prontuario}', 'Relatorios\Receituario@receita_especial')->name('relatorios/receita-especial');
Route::any('relatorios/receita-especial', 'Relatorios\Receituario@receita_especial')->name('relatorios/receita-especial');
Route::any('relatorios/receita/{cd_prontuario}', 'Relatorios\Receituario@receita')->name('relatorios/receita');
Route::any('relatorios/receita', 'Relatorios\Receituario@receita')->name('relatorios/receita');
Route::any('relatorios/exames-laboratoriais/{cd_prontuario}', 'Relatorios\Receituario@exames_laboratoriais')->name('relatorios/exames-laboratoriais');
Route::any('relatorios/exames-laboratoriais', 'Relatorios\Receituario@exames_laboratoriais')->name('relatorios/exames-laboratoriais');
Route::any('relatorios/atendimentos/indicadores_qualitativos', 'Relatorios\Atendimentos@indicadores_qualitativos')->name('relatorios/atendimentos/indicadores_qualitativos');
Route::any('relatorios/bpa', 'Relatorios\Bpa@bpa')->name('relatorios/bpa');

/*-------------------------------------------------------------------------
| QUERY_LOG
|--------------------------------------------------------------------------*/
Route::any('log/lista', 'QueryLogs@listar')->name('log/lista');
Route::any('ajaxDesc', 'QueryLogs@ajaxDesc')->name('ajaxDesc');
/*-------------------------------------------------------------------------
| API
|--------------------------------------------------------------------------*/
Route::any('api/status-pessoa', 'API@setStatusPessoa');
Route::any('api/delete', 'API@delete');
/*-------------------------------------------------------------------------
| Ajax
|--------------------------------------------------------------------------*/
Route::post('ajax/auto-preencher-endereco', 'Pessoas@auto_preencher_endereco')->name('ajax/auto-preencher-endereco');
Route::post('ajax/pesquisar-cep', 'Pessoas@pesquisar_cep')->name('ajax/pesquisar-cep');
Route::post('ajax/buscar-pessoa', 'Pessoas@buscar_pessoa')->name('ajax/buscar-pessoa');
Route::post('ajax/buscar-planos', 'Atendimentos@buscar_planos')->name('ajax/buscar-planos');
Route::post('ajax/atendimentos/fila', 'Atendimentos@atualiza_fila')->name('ajax/atendimentos/fila');
Route::post('ajax/atendimentos/reclassificar', 'Atendimentos@reclassificar')->name('ajax/atendimentos/reclassificar');
Route::post('ajax/atendimentos/add-atendimento-avaliacao-cid', 'Atendimentos@add_atendimento_avaliacao_cid')->name('ajax/atendimentos/add-atendimento-avaliacao-cid');
Route::post('ajax/atendimentos/lista-atendimento-avaliacao-cid', 'Atendimentos@lista_atendimento_avaliacao_cid')->name('ajax/atendimentos/lista-atendimento-avaliacao-cid');
Route::post('ajax/atendimentos/exclui-atendimento-avaliacao-cid', 'Atendimentos@exclui_atendimento_avaliacao_cid')->name('ajax/atendimentos/exclui-atendimento-avaliacao-cid');
Route::post('ajax/atendimentos/add-cid-historia-medica-pregressa', 'Atendimentos@add_cid_historia_medica_pregressa')->name('ajax/atendimentos/add-cid-historia-medica-pregressa');
Route::post('ajax/atendimentos/lista-cid-historia-medica-pregressa', 'Atendimentos@lista_cid_historia_medica_pregressa')->name('ajax/atendimentos/lista-cid-historia-medica-pregressa');
Route::post('ajax/atendimentos/exclui-cid-historia-medica-pregressa', 'Atendimentos@exclui_cid_historia_medica_pregressa')->name('ajax/atendimentos/exclui-cid-historia-medica-pregressa');
Route::post('ajax/atendimentos/add-cirurgia-previa', 'Atendimentos@add_cirurgia_previa')->name('ajax/atendimentos/add-cirurgia-previa');
Route::post('ajax/atendimentos/lista-cirurgia-previa', 'Atendimentos@lista_cirurgia_previa')->name('ajax/atendimentos/lista-cirurgia-previa');
Route::post('ajax/atendimentos/exclui-cirurgia-previa', 'Atendimentos@exclui_cirurgia_previa')->name('ajax/atendimentos/exclui-cirurgia-previa');
Route::post('ajax/atendimentos/add-medicamento-em-uso', 'Atendimentos@add_medicamento_em_uso')->name('ajax/atendimentos/add-medicamento-em-uso');
Route::post('ajax/atendimentos/lista-medicamentos-em-uso', 'Atendimentos@lista_medicamentos_em_uso')->name('ajax/atendimentos/lista-medicamento-em-uso');
Route::post('ajax/atendimentos/exclui-medicamento-em-uso', 'Atendimentos@exclui_medicamento_em_uso')->name('ajax/atendimentos/exclui-medicamento-em-uso');
Route::post('ajax/atendimentos/add-atendimento-procedimento', 'Atendimentos@add_atendimento_procedimento')->name('ajax/atendimentos/add-atendimento-procedimento');
Route::post('ajax/atendimentos/salva-procedimento-atendimento', 'Atendimentos@salva_procedimento_atendimento')->name('ajax/atendimentos/salva-procedimento-atendimento');
Route::post('ajax/atendimentos/lista-atendimento-procedimento', 'Atendimentos@lista_atendimento_procedimento')->name('ajax/atendimentos/lista-atendimento-procedimento');
Route::post('ajax/atendimentos/exclui-atendimento-procedimento', 'Atendimentos@exclui_atendimento_procedimento')->name('ajax/atendimentos/exclui-atendimento-procedimento');
Route::post('ajax/atendimentos/carrega-modal-procedimento', 'Atendimentos@carrega_modal_procedimento')->name('ajax/atendimentos/carrega-modal-procedimento');
Route::post('ajax/atendimentos/pesquisa-cid', 'Atendimentos@pesquisa_cid')->name('ajax/atendimentos/pesquisa-cid');
Route::post('ajax/atendimentos/pesquisa-medicamento', 'Atendimentos@pesquisa_medicamento')->name('ajax/atendimentos/pesquisa-medicamento');
Route::post('ajax/atendimentos/pesquisa-alergia', 'Atendimentos@pesquisa_alergia')->name('ajax/atendimentos/pesquisa-alergia');
Route::post('ajax/atendimentos/add-alergia-pessoa', 'Atendimentos@add_alergia_pessoa')->name('ajax/atendimentos/add-alergia-pessoa');
Route::post('ajax/atendimentos/lista-alergia-pessoa', 'Atendimentos@lista_alergia_pessoa')->name('ajax/atendimentos/lista-alergia-pessoa');
Route::post('ajax/atendimentos/exclui-alergia-pessoa', 'Atendimentos@exclui_alergia_pessoa')->name('ajax/atendimentos/exclui-alergia-pessoa');
Route::post('ajax/atendimentos/pesquisa-procedimento-ocupacao', 'Atendimentos@pesquisa_procedimento_ocupacao')->name('ajax/atendimentos/pesquisa-procedimento-ocupacao');
Route::post('ajax/atendimentos/pesquisa-procedimento', 'Atendimentos@pesquisa_procedimento')->name('ajax/atendimentos/pesquisa-procedimento');
Route::post('ajax/atendimentos/ver-historico', 'Atendimentos@ver_historico')->name('ajax/atendimentos/ver-historico');
Route::post('ajax/atendimentos/add-atendimento-evolucao', 'Atendimentos@add_atendimento_evolucao')->name('ajax/atendimentos/add-atendimento-evolucao');
Route::post('ajax/atendimentos/lista-atendimento-evolucao', 'Atendimentos@lista_atendimento_evolucao')->name('ajax/atendimentos/lista-atendimento-evolucao');
Route::post('ajax/atendimentos/finalizar-atendimento-sem-medico', 'Atendimentos@finalizar_atendimento_sem_medico')->name('ajax/atendimentos/finalizar-atendimento-sem-medico');
Route::post('ajax/atendimentos/sugere-posologia-medicacao', 'Atendimentos@sugere_posologia_medicacao')->name('ajax/atendimentos/sugere-posologia-medicacao');
Route::post('ajax/atendimentos/editar-prescricao', 'Atendimentos@editar_prescricao')->name('ajax/atendimentos/editar-prescricao');
Route::post('ajax/atendimentos/busca-ultimas-prescricoes', 'Atendimentos@busca_ultimas_prescricoes')->name('ajax/atendimentos/busca-ultimas-prescricoes');
Route::post('ajax/atendimentos/busca-prescricao-ambulatorial', 'Atendimentos@busca_prescricao_ambulatorial')->name('ajax/atendimentos/busca-prescricao-ambulatorial');
Route::post('ajax/atendimentos/add-atendimento-prescricao', 'Atendimentos@add_atendimento_prescricao')->name('ajax/atendimentos/add-atendimento-prescricao');
Route::post('ajax/atendimentos/concluir-atendimento-prescricao', 'Atendimentos@concluir_atendimento_prescricao')->name('ajax/atendimentos/concluir-atendimento-prescricao');
Route::post('ajax/atendimentos/add-item-atendimento-prescricao', 'Atendimentos@add_item_atendimento_prescricao')->name('ajax/atendimentos/add-item-atendimento-prescricao');
Route::post('ajax/atendimentos/busca-vias-aplicacao-medicamento', 'Atendimentos@busca_vias_aplicacao_medicamento')->name('ajax/atendimentos/busca-vias-aplicacao-medicamento');

Route::post('ajax/profissionais/pesquisa-ocupacao', 'Profissionais@pesquisa_ocupacao')->name('ajax/profissionais/pesquisa-ocupacao');
Route::post('ajax/buscar-user', 'Pessoas@buscar_user')->name('ajax/buscar-user');
Route::post('ajax/buscar-pessoa-estabelecimento', 'Atendimentos@pesquisa_pessoa_estabelecimento')->name('ajax/buscar-pessoa-estabelecimento');
Route::post('ajax/novo-atendimento', 'Atendimentos@novo_atendimento')->name('ajax/novo-atendimento');
Route::post('ajax/remover-atendimento', 'Atendimentos@remover_atendimento')->name('ajax/remover-atendimento');
Route::post('ajax/chamar-painel', 'Painel@chamar_painel')->name('ajax/chamar-painel');
Route::post('ajax/atualizar-painel', 'Painel@atualizar_painel')->name('ajax/atualizar-painel');
Route::post('ajax/atualizar-chamado-voz', 'Painel@atualizar_chamado_voz')->name('ajax/atualizar-chamado-voz');
Route::post('ajax/salvar-dependente', 'Beneficiarios@salvar_dependente')->name('ajax/salvar-dependente');
Route::post('ajax/pessoas/cadastrar', 'Pessoas@cadastrar_via_modal')->name('ajax/pessoas/cadastrar');
Route::post('ajax/pessoas/preenche-pessoa', 'Pessoas@preenche_modal_pessoa')->name('ajax/pessoas/preenche-pessoa');
Route::post('ajax/configuracoes/preenche-select-sub-grupo', 'Configuracoes\Procedimentos@preenche_select_sub_grupo')->name('ajax/configuracoes/preenche-select-sub-grupo');
Route::post('ajax/configuracoes/preenche-select-forma-organizacao', 'Configuracoes\Procedimentos@preenche_select_forma_organizacao')->name('ajax/configuracoes/preenche-select-forma-organizacao');
Route::post('ajax/configuracoes/pesquisa-procedimentos', 'Configuracoes\Procedimentos@pesquisa_procedimentos')->name('ajax/configuracoes/pesquisa-procedimentos');
Route::post('ajax/configuracoes/add-procedimentos', 'Configuracoes\Procedimentos@add_procedimentos')->name('ajax/configuracoes/add-procedimentos');
Route::post('ajax/configuracoes/remove-procedimentos', 'Configuracoes\Procedimentos@remove_procedimentos')->name('ajax/configuracoes/remove-procedimentos');
Route::post('ajax/configuracoes/atualizar-tabelas-sus', 'Configuracoes\Procedimentos@rotina_atualizacao_tabelas_sus')->name('ajax/configuracoes/atualizar-tabelas-sus');
Route::post('ajax/pessoas/comparar-digital', 'Pessoas@comparar_digital')->name('ajax/pessoas/comparar-digital');
Route::post('ajax/pessoas/salvar-imagem-temp', 'Pessoas@salvar_imagem_temp')->name('ajax/pessoas/salvar-imagem-temp');
Route::post('ajax/edita-preco-produto', 'Materiais\Produto@edita_preco_produto')->name('ajax/edita-preco-produto');
Route::post('ajax/salvar-grupo-produto', 'Materiais\Grupo@salvar_grupo_produto')->name('ajax/salvar-grupo-produto');
Route::post('ajax/preenche-select-produto-grupo', 'Materiais\Produto@preenche_select_produto_grupo')->name('ajax/preenche-select-produto-grupo');
Route::post('ajax/preenche-select-produto-sub-grupo', 'Materiais\Produto@preenche_select_produto_sub_grupo')->name('ajax/preenche-select-produto-sub-grupo');
Route::post('ajax/preenche-parametros-movimento', 'Materiais\Movimentacao@preenche_parametros_movimento')->name('ajax/preenche-parametros-movimento');
Route::post('ajax/materiais/movimento/pesquisa-cfop', 'Materiais\Movimento@pesquisa_cfop')->name('ajax/materiais/movimento/pesquisa-cfop');
Route::post('ajax/sala/pesquisa-user', 'Salas@pesquisa_user')->name('ajax/sala/pesquisa-user');
Route::post('ajax/materiais/movimentacao/pesquisa-produto', 'Materiais\Movimentacao@pesquisa_produto')->name('ajax/materiais/movimentacao/pesquisa-produto');
Route::post('ajax/materiais/movimentacao/adicionar-item', 'Materiais\Movimentacao@adicionar_item')->name('ajax/materiais/movimentacao/adicionar-item');
Route::any('ajax/materiais/movimentacao/mostra-estoque', 'Materiais\Movimentacao@mostra_estoque')->name('ajax/materiais/movimentacao/mostra-estoque');
Route::any('ajax/materiais/movimentacao/mostra-prescricao', 'Materiais\Movimentacao@mostra_prescricao')->name('ajax/materiais/movimentacao/mostra-prescricao');
Route::any('ajax/materiais/movimentacao/pesquisa-prontuario', 'Materiais\Movimentacao@pesquisa_prontuario')->name('ajax/materiais/movimentacao/pesquisa-prontuario');
Route::any('ajax/materiais/movimentacao/pesquisa-fornecedor', 'Materiais\Movimentacao@pesquisa_fornecedor')->name('ajax/materiais/movimentacao/pesquisa-fornecedor');
Route::any('ajax/materiais/movimentacao/finalizar-transferencia-produtos', 'Materiais\Movimentacao@finalizar_transferencia_produtos')->name('ajax/materiais/movimentacao/finalizar-transferencia-produtos');
Route::any('ajax/materiais/movimentacao/detalhes-movimentacao-item', 'Materiais\Movimentacao@detalhes_movimentacao_item')->name('ajax/materiais/movimentacao/detalhes-movimentacao-item');
Route::any('ajax/materiais/movimentacao/add-produto-vinculo', 'Materiais\Movimentacao@add_produto_vinculo')->name('ajax/materiais/movimentacao/add-produto-vinculo');
Route::any('ajax/materiais/kits/add-item', 'Materiais\Kits@add_item')->name('ajax/materiais/kits/add-item');
Route::any('ajax/materiais/requisicoes/add-item', 'Materiais\Requisicoes@add_item')->name('ajax/materiais/requisicoes/add-item');
Route::any('ajax/materiais/kits/pesquisa-kit', 'Materiais\Kits@pesquisa_kit')->name('ajax/materiais/kits/pesquisa-kit');

/*-------------------------------------------------------------------------
| AJAX NFE
|--------------------------------------------------------------------------*/
Route::any('ajax/materiais/movimentacao/importa-xml-nfe', 'Materiais\Movimentacao@importa_xml_nfe')->name('ajax/materiais/movimentacao/importa-xml-nfe');
Route::any('ajax/materiais/movimentacao/existe-fornecedor', 'Materiais\Movimentacao@existe_fornecedor')->name('ajax/materiais/movimentacao/existe-fornecedor');
Route::any('ajax/materiais/movimentacao/cadastra-atualiza-produto', 'Materiais\Movimentacao@cadastra_atualiza_produto')->name('ajax/materiais/movimentacao/cadastra-atualiza-produto');
Route::any('ajax/materiais/movimentacao/cadastra-movimentacao', 'Materiais\Movimentacao@cadastra_movimentacao')->name('ajax/materiais/movimentacao/cadastra-movimentacao');
Route::any('ajax/materiais/movimentacao/cadastra-movimentacao-nfe', 'Materiais\Movimentacao@cadastra_movimentacao_nfe')->name('ajax/materiais/movimentacao/cadastra-movimentacao-nfe');
Route::any('ajax/materiais/movimentacao/verifica-cadastro-produto', 'Materiais\Movimentacao@verifica_cadastro_produto')->name('ajax/materiais/movimentacao/verifica-cadastro-produto');
Route::any('ajax/materiais/movimentacao/cadastra-atualiza-produto', 'Materiais\Movimentacao@cadastra_atualiza_produto')->name('ajax/materiais/movimentacao/cadastra-atualiza-produto');
//Route::any('ajax/materiais/movimentacao/cadastra-item-movimentacao', 'Materiais\Movimentacao@cadastra_item_movimentacao')->name('ajax/materiais/movimentacao/cadastra-item-movimentacao');
Route::any('ajax/materiais/movimentacao/cadastra-item-movimentacao-nfe', 'Materiais\Movimentacao@cadastra_item_movimentacao_nfe')->name('ajax/materiais/movimentacao/cadastra-item-movimentacao-nfe');
/*-------------------------------------------------------------------------
| Testes
|--------------------------------------------------------------------------*/
Route::any('teste', 'Teste@teste')->name('teste');
Route::any('errorlog', 'Teste@errorlog')->name('errorlog');
Route::any('fullerrorlog', 'Teste@fullerrorlog')->name('fullerrorlog');

/*-------------------------------------------------------------------------
| Rota para paginas em construcao
|--------------------------------------------------------------------------*/
Route::view('nope', 'nope')->name('nope');

/*-------------------------------------------------------------------------
| Rotas do sistema de Autorização do Laravel
|--------------------------------------------------------------------------*/
Auth::routes();

