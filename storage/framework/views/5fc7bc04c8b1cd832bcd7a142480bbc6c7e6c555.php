<div class="modal" tabindex="-2" role="dialog" id="modal-historia-medica-pregressa">
    <div class="modal-dialog" role="document" id="dialog-historia-medica-pregressa">
        <div class="modal-content" id="content-historia-medica-pregressa">
            <div class="modal-header">
                <button class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Fechar</span></button>
                <h3 class="modal-title">Escolha uma CID</h3>
            </div>
            <div class="modal-body modal-body-scroll">
                <div class="panel-body panel-acolhimento">
                    <div class="col-sm-10">
                        <input type="text" maxlength="60" id="pesquisa_nm_cid_historia_medica" class="form-control">
                    </div>
                    <button class="btn btn-default" type="button" id="btn_pesquisar_cid_historia_medica">Buscar</button>
                </div>
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="table-responsive tabela-pesquisa">
                            <table class="table table-bordered table-hover table-striped font-size-9pt" >
                                <thead>
                                <tr>
                                    <td><b>Cid</b></td>
                                    <td></td>
                                </tr>
                                </thead>
                                <tbody id="table-historia-medica-pregressa">
                                <tr><td colspan="2">Utilize a busca acima para encontrar uma Cid.</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo e(js_versionado('modal_historia_medica_pregressa.js')); ?>" defer></script>