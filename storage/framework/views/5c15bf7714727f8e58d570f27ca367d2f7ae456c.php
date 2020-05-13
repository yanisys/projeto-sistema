<?php $__env->startSection('conteudo'); ?>

    <div class="panel panel-default ">
        <?php if(isset($competencia)): ?>
            <form enctype="multipart/form-data" id="arquivo-tabela-unificada-sus" method="POST">
                <div class="panel-heading text-center">
                  <!--  <h4>Competência: <?php echo e($competencia->dt_competencia); ?>

                        <input type="file" id="upload_arquivos_sus" name="upload_file" accept=".zip"/>
                        <label class="btn btn-primary btn-xs" id="upload_btn" for="upload_arquivos_sus">Atualizar</label> -->
                    </h4>
                </div>
                <?php echo e(csrf_field()); ?>

            </form>
        <?php endif; ?>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="grupo_configuracao">Grupo</label>
                        <?php echo e(Form::select('grupo_configuracao',$grupo,(isset($_POST['grupo_configuracao']) ? $_POST['grupo_configuracao'] : 0),['id'=>'grupo_configuracao','class'=>'form-control'])); ?>

                    </div>
                </div>
                <div id="exibe_sub_grupo" class="col-md-4" style="display: none">
                    <div class="form-group">
                        <label for="sub_grupo">Sub grupo</label>
                        <select id="sub_grupo_configuracao" class="form-control"></select>
                    </div>
                </div>
                <div id="exibe_forma_organizacao" class="col-md-4" style="display: none">
                    <div class="form-group">
                        <label for="forma_organizacao">Forma de organização</label>
                        <select id="forma_organizacao_configuracao" class="form-control"></select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="pesquisa_procedimento">Nome do procedimento</label>
                        <input type="text" id="pesquisa_configuracao_procedimento" class="form-control" placeholder="Informe o nome ou o código do procedimento">
                    </div>
                </div>
                <div class="col-md-1">
                    <button id="iniciar_pesquisa_configuracao_procedimento" class="btn btn-default margin-top-25">Pesquisar</button>
                </div>
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-hover table-striped">
            <thead>
            <tr>
                <th>Código</th>
                <th>Nome</th>
                <th>Permitido</th>
            </tr>
            </thead>
            <tbody id="tabela_configuracao_procedimento">

            </tbody>
        </table>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('custom_js'); ?>
    <script src="<?php echo e(js_versionado('configuracoes.js')); ?>"></script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.default', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>