<?php $__env->startSection('conteudo'); ?>

    <div class="panel panel-default ">
        <div class="panel-body">
            <?php echo Form::open(["method" => "GET", "class" => "form-search"]); ?>

            <div class="container-fluid">
                <div class="col-sm-4">
                    <label>Paciente</label>
                    <?php echo Form::text('paciente',(!empty($_REQUEST['paciente']) ? $_REQUEST['paciente'] : ""),["name" => "paciente", "placeholder" => "Nome do paciente",'class'=>'form-control']); ?>

                </div>
                <div class="col-sm-4">
                    <label>Responsável</label>
                    <?php echo Form::text('responsavel',(!empty($_REQUEST['responsavel']) ? $_REQUEST['responsavel'] : ""),["name" => "responsavel", "id" => "responsavel", "placeholder" => "Nome do responsável",'class'=>'form-control']); ?>

                </div>
                <?php echo Form::submit('Buscar',['class'=>"btn btn-default margin-top-25"]); ?>

            </div>
            <?php echo Form::close(); ?>

        </div>
    </div>

    <div class="table-responsive font-12px">
        <table class="table table-bordered table-hover table-striped">
            <thead>
            <tr>
                <th class="text-center">Data/ hora chegada</th>
                <th class="text-center">Sexo</th>
                <th class="text-center">Paciente</th>
                <th class="text-center">Motivo da alta</th>
                <th class="text-center">Idade</th>
                <th class="text-center">Responsável</th>
            </tr>
            </thead>
            <tbody>
            <?php if(!isset($prontuario) || $prontuario->IsEmpty()): ?>
                <tr><td colspan="6">Sem resultados</td></tr>
            <?php else: ?>
                <?php $__currentLoopData = $prontuario; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td class='text-center'>
                            <?php echo e(formata_data_hora($p->created_at)); ?>

                        </td>
                        <td class='text-center'>
                            <?php if(($p->id_sexo == 'F')): ?>
                                <span class='fa fa-female' style='color:deeppink;'></span>
                            <?php else: ?>
                                <span class='fa fa-male' style='color:blue;'></span>
                            <?php endif; ?>
                        </td>
                        <td class='text-center'>
                            <?php echo e($p->nm_pessoa); ?>

                        </td>
                        <td class='text-center'>
                            <?php echo e($p->motivo_alta); ?>

                        </td>
                        <td class='text-center'>
                            <?php echo e(isset($p->dt_nasc) ? calcula_idade($p->dt_nasc) : ""); ?>

                        </td>
                        <td class='text-center'>
                            <?php echo e($p->medico); ?>

                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.default', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>