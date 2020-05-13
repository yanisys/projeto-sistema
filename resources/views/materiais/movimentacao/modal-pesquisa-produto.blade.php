<div class="modal" tabindex="-1" role="dialog" id="modal-pesquisa-produto">
    <div class="modal-dialog" role="document" id="dialog-pesquisa-produto">
        <div class="modal-content" id="content-pesquisa-produto">
            <div class="modal-header">
                <button class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Fechar</span></button>
                <h3 class="modal-title">Escolha um
                    <a href="{{ route('materiais/produto/cadastro') }}" title="Clique aqui para ir para o cadastro do produto" target="_blank" style="color: #ffffff" }}>
                        <b>produto</b>
                    </a></h3>
            </div>
            <div class="modal-body modal-body-scroll">
                <div class="panel-body panel-acolhimento">
                    <div class="col-sm-10">
                        <input type="text" maxlength="60" id="pesquisa_nm_produto" class="form-control">
                    </div>
                    <button class="btn btn-default" type="button" id="btn_pesquisar_produto">Buscar</button>
                </div>
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="table-responsive tabela-pesquisa">
                            <table class="table table-bordered table-hover table-striped font-size-9pt" >
                                <thead>
                                <tr>
                                    <td><b>Item</b></td>
                                    <td></td>
                                </tr>
                                </thead>
                                <tbody id="table-produto">
                                    <tr><td colspan="2">Utilize a busca acima para encontrar um produto.</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ js_versionado('modal_pesquisa_produto.js') }}" defer></script>