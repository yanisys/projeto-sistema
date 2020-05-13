
<div class="modal" tabindex="-1" role="dialog" id="modal-historico">
    <div class="modal-dialog" role="document" id="dialog-historico">
        <div class="modal-content" id="content-historico">
            <div class="modal-header">
                <button class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Fechar</span></button>
                <h3 id="nome-header" class="modal-title">Histórico</h3>
            </div>
            <div class="modal-body">
                <?php echo e(Form::open(["id" => "form-modal-historico", 'class' => 'form-no-submit'])); ?>

                <div id="painel_cadastro" class="panel with-nav-tabs panel-primary">
                    <div class="panel-heading">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab_indice" data-toggle="tab">Indíce</a></li>
                          <!--  <li><a href="#tab_procedimentos" data-toggle="tab">Procedimentos</a></li>
                            <li><a href="#tab_folha_rosto" data-toggle="tab">Folha de rosto</a></li> -->
                        </ul>
                    </div>
                    <div class="panel-body">
                        <div class="tab-content">
                            <div class="tab-pane fade in active" id="tab_indice">
                                <div class="panel-heading">Procedimentos realizados</div>
                                <div class="panel-body panel-acolhimento">
                                    <div class="row">
                                        <div class='table-responsive'>
                                            <table class='table table-bordered table-hover table-striped'>
                                                <thead>
                                                <tr>
                                                    <th>Data do atendimento</th>
                                                    <th>Procedimento</th>
                                                    <th>Prontuário</th>
                                                    <th>Profissional</th>
                                                    <th>Ocupação</th>
                                                </tr>
                                                </thead>
                                                <tbody id="corpo-historico">
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade">

                            </div>
                            <div class="tab-pane fade">

                            </div>
                        </div>
                    </div>
                    <div class="panel-footer" style="height: 55px">

                    </div>
                </div>
                <?php echo e(Form::close()); ?>

            </div>
        </div>
    </div>
</div>

