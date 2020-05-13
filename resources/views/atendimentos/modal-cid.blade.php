<div class="modal" tabindex="-2" role="dialog" id="modal-cid">
    <div class="modal-dialog" role="document" id="dialog-cid">
        <div class="modal-content" id="content-cid">
            <div class="modal-header">
                <button class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Fechar</span></button>
                <h3 class="modal-title">Escolha um CID</h3>
            </div>
            <div class="modal-body modal-body-scroll">
                <div class="panel-body panel-acolhimento">
                    <div class="col-sm-10">
                        <input type="text" maxlength="60" id="pesquisa_nm_cid" class="form-control">
                    </div>
                    <button class="btn btn-default" type="button" id="btn-pesquisar-cid">Buscar</button>
                </div>
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="table-responsive tabela-pesquisa">
                            <table class="table table-bordered table-hover table-striped font-size-9pt" >
                                <thead>
                                <tr>
                                    <td><b>CID</b></td>
                                    <td></td>
                                </tr>
                                </thead>
                                <tbody id="table-cid">
                                <tr><td colspan="2">Utilize a busca acima para encontrar um CID.</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ js_versionado('modal_cid.js') }}" defer></script>