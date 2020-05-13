<div class="modal" tabindex="-2" role="dialog" id="modal-medicamento">
    <div class="modal-dialog" role="document" id="dialog-medicamento">
        <div class="modal-content" id="content-medicamento">
            <div class="modal-header">
                <button class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Fechar</span></button>
                <h3 class="modal-title">Escolha um medicamento</h3>
            </div>
            <div class="modal-body modal-body-scroll">
                <div class="panel-body panel-acolhimento">
                    <div class="col-sm-10">
                        <input type="text" maxlength="60" id="pesquisa_nm_medicamento" class="form-control">
                    </div>
                    <button class="btn btn-default" type="button" id="btn-pesquisar-medicamento">Buscar</button>
                </div>
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="table-responsive tabela-pesquisa">
                            <table class="table table-bordered table-hover table-striped font-size-9pt" >
                                <thead>
                                <tr>
                                    <td><b>medicamento</b></td>
                                    <td></td>
                                </tr>
                                </thead>
                                <tbody id="table-medicamento">
                                <tr><td colspan="2">Utilize a busca acima para encontrar um medicamento.</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo e(js_versionado('modal_medicamentos.js')); ?>" defer></script>