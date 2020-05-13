<?php $__env->startSection('conteudo'); ?>

    <div class="panel panel-default ">
        <div class="panel-body">
            <?php echo Form::open(); ?>

            <div class="container-fluid">
                <div class="col-sm-4">
                    <label>Selecione um painel para exibir</label>
                    <?php echo e(Form::select('cd_painel', arrayPadrao('paineis'), (isset($_POST['cd_painel']) ? $_POST['cd_painel'] : 1),['class'=> ($errors->has("cd_painel") ? "form-control is-invalid" : "form-control"), 'id' => 'cd_painel'])); ?>

                </div>
              <!--  <div class="col-sm-1 pull-right padding-top-25">
                    <label>Ativar Som</label>
                    <input id='checkbox-som' type="checkbox"/>
                </div>-->
                <?php echo Form::submit('Buscar',['id' => 'escolhe-painel', 'class'=>"btn btn-default margin-top-25"]); ?>

            </div>
            <?php echo Form::close(); ?>

        </div>
    </div>
    <button class="btn btn-primary" onclick="openFullscreen();">Tela cheia</button>
    <input id=numero_painel type="hidden">
        <h1 class="text-center"></h1>
        <div class="table-responsive" id="chamar">
            <table class="table table-bordered table-striped" id="painel_chamados">
                <thead>
                    <tr class="chamados">
                        <th class="text-center col-md-6">PACIENTE</th>
                        <th class="text-center col-md-4">SALA</th>
                        <th class="text-center col-md-2">HORÁRIO</th>
                    </tr>
                </thead>
                <tbody id="painel">
                <?php if(!isset($lista) || $lista->IsEmpty()): ?>
                    <tr><td colspan="3">Sem resultados</td></tr>
                <?php else: ?>
                    <?php $__currentLoopData = $lista; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cont => $l): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="chamados <?php echo e($cont ==0 ? 'chamado_atual' : ''); ?>">
                            <td class='text-center'><?php echo e($l->nm_pessoa); ?></td>
                            <td class='text-center'><?php echo e($l->nm_sala); ?></td>
                            <td class='text-center'><?php echo e(formata_hora($l->horario)); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('custom_js'); ?>
    <script src="<?php echo e(js_versionado('prontuario.js')); ?>"></script>
    <script src="<?php echo e(js_versionado('painel.js')); ?>"></script>
    <script src='https://code.responsivevoice.org/responsivevoice.js'></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.default', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>