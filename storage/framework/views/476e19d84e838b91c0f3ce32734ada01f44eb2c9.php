<div class="modal" tabindex="-1" role="dialog" id="modal-procedimentos">
    <div class="modal-dialog" role="document" id="dialog-procedimentos">
        <div class="modal-content" id="content-procedimentos">
            <div class="modal-header">
                <button class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Fechar</span></button>
                <h3 class="modal-title">Solicitação de procedimentos</h3>
            </div>
            <div class="modal-body modal-body-scroll">
                <?php echo e(Form::hidden('cd_prontuario',(isset($acolhimento->cd_prontuario) ? $acolhimento->cd_prontuario : ""),["name" => "cd_prontuario", "id" => "cd_prontuario"])); ?>
                <div class="panel-body panel-acolhimento">
                    <div class="col-sm-10">
                        <input type="text" maxlength="60" id="pesquisa_nm_procedimento" class="form-control">
                    </div>
                    <button class="btn btn-default" type="button" id="btn-pesquisar-procedimento">Buscar</button>
                </div>
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="table-responsive tabela-pesquisa">
                            <table class="table table-bordered table-hover table-striped font-size-9pt" >
                                <thead>
                                    <tr>
                                        <td><b>Procedimento</b></td>
                                        <td></td>
                                    </tr>
                                </thead>
                                <tbody id="table-procedimentos">
                                    <tr><td colspan="2">Utilize a busca acima para encontrar procedimentos.</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <?php echo e(Form::textarea('modal_descricao_solicitacao',"",["name" => "descricao_solicitacao", "placeholder" => "Digite aqui alguma observação para ser adicionada junto à este procedimento." , "maxlength" => "1000", "rows"=>"1", "id" => "modal_obs_procedimento",  'class'=> "form-control"])); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo e(js_versionado('modal_procedimentos.js')); ?>" defer></script>