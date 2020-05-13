<div id="modal-importa-nfe" class="modal fade"  tabindex="-1" role="dialog" data-backdrop="static" aria-labelledby="myModalLabel" aria-hidden="true">
    <div id="dialog-importa-nfe" class="modal-dialog">
        <div id="content-importa-nfe" class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="myModalLabel">Importação de Nfe</h3>
            </div>
            <div class="modal-body">
                <div class="panel-default">
                    <div class="progress" style="height: 30px">
                        <div class="progress-bar" id="barra-progresso-nfe" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div class="col-md-12">
                        <h4 style="text-align: center" id="progresso-nfe"></h4>
                    </div>
                </div>
                <div id="painel-nfe" class="panel-acolhimento" style="display: none; height: 100%;">
                    <div class="panel-body">
                        <div class="col-sm-5">
                            <label>Produto</label>
                            <input type="text" class="form-control" placeholder="Informe o nome do produto ou o código de barras" id="pesquisa-produto-nfe">
                        </div>
                        <div class="col-sm-3 ">
                            <button type="button" class="btn btn-default margin-top-25" id="btn-pesquisa-produto-nfe">Pesquisar</button>
                        </div>
                        <div class="col-sm-3 pull-right">
                            <div class="col-sm-3 ">
                                <a href={{route('materiais/produto/cadastro')}} target='_blank' class='btn btn-primary margin-top-25 {{verficaPermissaoBotao('recurso.materiais/produto-adicionar')}}'>Novo produto</a>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class='table-responsive' style="height: 350px; overflow-y: auto; overflow-x: hidden">
                            <table class='table table-hover table-striped'>
                                <thead>
                                    <tr>
                                        <th>Código</th>
                                        <th>Produto</th>
                                        <th>Fabricante</th>
                                        <th class='text-center'>Ação</th>
                                    </tr>
                                </thead>
                                <tbody id="tabela-pesquisa-produto">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div id="painel-produto" class="panel-acolhimento" style="display: none; height: 100%;">
                    <div class="col-md-5">
                        <div class="panel-header"><h3>Dados da Nfe</h3></div>
                        <div class="panel-body">
                            <div class='table-responsive' style="height: 350px; overflow-y: auto; overflow-x: hidden">
                                <table class='table table-hover table-striped'>
                                    <tbody id="tabela-dados-nfe">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="panel-header"><h3>Dados do cadastro do produto</h3></div>
                        <div class="panel-body">
                            <div class='table-responsive' style="height: 350px; overflow-y: auto; overflow-x: hidden">
                                <table class='table table-hover table-striped'>
                                    <tbody id="tabela-dados-produto">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row">
                            <div class="pull-right">
                                <button type="button" id="btn-avancar" title="Avançar" class="btn btn-primary">Avançar&nbsp;<span class="fas fa-long-arrow-alt-right"></span></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ js_versionado('modal_evolucao.js') }}" defer></script>
