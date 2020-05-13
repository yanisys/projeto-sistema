<div class="modal" tabindex="-2" role="dialog" id="modal-alergia">
    <div class="modal-dialog" role="document" id="dialog-alergia">
        <div class="modal-content" id="content-alergia">
            <div class="modal-header">
                <button class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Fechar</span></button>
                <h3 class="modal-title">Escolha uma alergia</h3>
            </div>
            <div class="modal-body modal-body-scroll">
                <div class="panel-body panel-acolhimento">
                    <div class="col-sm-10">
                        <input type="text" maxlength="60" id="pesquisa_nm_alergia" class="form-control">
                    </div>
                    <button class="btn btn-default" type="button" id="btn-pesquisar-alergia">Buscar</button>
                </div>
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="table-responsive tabela-pesquisa">
                            <table class="table table-bordered table-hover table-striped font-size-9pt" >
                                <thead>
                                <tr>
                                    <td><b>Alergia</b></td>
                                    <td></td>
                                </tr>
                                </thead>
                                <tbody id="table-alergia">
                                <tr><td colspan="2">Utilize a busca acima para encontrar uma alergia.</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ js_versionado('modal_alergia.js') }}" defer></script>