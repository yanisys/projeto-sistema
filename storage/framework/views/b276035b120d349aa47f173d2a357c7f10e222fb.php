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
                <th class="text-center">Procedimento</th>
                <th class="text-center">Responsável</th>
                <th class='text-center' width="190px">Ação</th>
            </tr>
            </thead>
            <tbody>
            <?php if(!isset($prontuario) || $prontuario->IsEmpty()): ?>
                <tr><td colspan="5">Sem resultados</td></tr>
            <?php else: ?>
                <?php $__currentLoopData = $prontuario; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td class='text-center'>
                            <?php echo e(formata_data_hora($p->dt_hr_solicitacao)); ?>

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

                            <?php if(isset($p->nm_sala)): ?><span style="background-color: #ddd32f"> - CHAMADO NO PAINEL: <?php echo e($p->nm_sala); ?></span><?php endif; ?>
                        </td>
                        <td class='text-center'>
                            <?php echo e($p->nm_procedimento); ?>

                        </td>
                        <td class='text-center'>
                            <?php echo e($p->nm_medico); ?>

                        </td>
                        <td align="center" width="9%">
                            <a href="<?php echo e(route('atendimentos/procedimentos').'/'.$p->cd_prontuario); ?>" title='Ir para procedimentos/ Evolução clínica' class='btn btn-success  fa fa-plus-square <?php echo e(verficaPermissaoBotao('recurso.atendimentos/procedimentos')); ?>'></a>
                        </td>

                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.default', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>