<?php $__env->startSection('conteudo'); ?>
    <div class="panel panel-default" id="seleciona-estabelecimento">
        <?php echo e(Form::open(['id' => 'cadastra-estabelecimento', 'class' => 'form-no-submit'])); ?>

        <div class="panel-body">
            <div class="col-md-12">
                <div class="form-group">
                    <label for="classificacao"><h4>Selecione um estabelecimento</h4></label>
                    <?php echo e(Form::select('cd_estabelecimento', $estabelecimentos, "",['class'=>  "form-control", 'id' => 'cd_estabelecimento'])); ?>

                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <?php echo e(Form::submit('Selecionar',['class'=>"btn btn-primary margin-top-zero", 'id' => 'salvar-estabelecimento'])); ?>

                </div>
            </div>
        </div>
        <?php echo e(Form::close()); ?>

    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.default', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>