<div class="modal" tabindex="-2" role="dialog" id="modal-pesquisa-user">
    <div class="modal-dialog" role="document" id="dialog-pesquisa-user">
        <div class="modal-content" id="content-pesquisa-user">
            <div class="modal-header">
                <button class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Fechar</span></button>
                <h3 class="modal-title">Escolha um profissional</h3>
            </div>
            <div class="modal-body modal-body-scroll">
                <div class="panel-body panel-acolhimento">
                    <div class="col-sm-10">
                        <input type="text" maxlength="60" placeholder="Digite o nome do profissional"  id="pesquisa_nm_user" class="form-control">
                    </div>
                    <button class="btn btn-default" type="button" id="btn-pesquisa-user">Buscar</button>
                </div>
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="table-responsive tabela-pesquisa">
                            <table class="table table-bordered table-hover table-striped font-size-9pt" >
                                <thead>
                                <tr>
                                    <td><b>Profissional</b></td>
                                    <td></td>
                                </tr>
                                </thead>
                                <tbody id="table-user">
                                <tr><td colspan="2">Utilize a busca acima para encontrar um peofissional.</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo e(js_versionado('modal_pesquisa_user.js')); ?>" defer></script>