<?php $__env->startSection('conteudo'); ?>

    <div class="panel panel-default ">
        <div class="panel-body">
            <?php echo Form::open(["method" => "GET", "class" => "form-search"]); ?>

            <div class="container-fluid">
                <div class="col-sm-3">
                    <label>Nome</label>
                    <?php echo Form::text('ds_plano',(!empty($_REQUEST['ds_plano']) ? $_REQUEST['ds_plano'] : ""),["name" => "ds_plano", "id" => "busca_plano", "placeholder" => "Digite o nome do plano",'class'=>'form-control']); ?>

                </div>
                <div class="col-sm-3 ">
                    <?php echo Form::submit('Buscar',['class'=>"btn btn-default margin-top-25"]); ?>

                </div>
            </div>
            <?php echo Form::close(); ?>

        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover table-striped">
            <thead>
            <tr>
                <th>Código</th>
                <th>Descrição</th>
                <th>Tipo de plano</th>
                <th class='text-center' width="190px">Ação</th>
            </tr>
            </thead>
            <tbody>

            <?php if(!isset($lista) || $lista->IsEmpty()): ?>
                <tr><td colspan="4">Sem resultados</td></tr>
            <?php else: ?>
                <?php $__currentLoopData = $lista; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plano): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td class='text-center'><?php echo e($plano->cd_plano); ?></td>
                        <td class='text-center'><?php echo e($plano->ds_plano); ?></td>
                        <td class='text-center'><?php echo e(arrayPadrao('tipo_plano')[$plano->tp_plano]); ?></td>
                        <td class='text-center'>
                            <a href="<?php echo e(route('planos/cadastro').'/'.$plano->cd_plano); ?>" class="btn btn-primary btn-sm">Detalhes</a>
                            <button type='button' data-tabela="plano" data-chave="cd_plano" data-valor="<?php echo e($plano->cd_plano); ?>" class='<?php echo e(verficaPermissaoBotao('recurso.planos-excluir')); ?> btn btn-danger btn-sm btn-excluir'>Excluir</button>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="row">
        <div class="col-md-6">
            <a href="<?php echo e(route('planos/cadastro')); ?>" class="btn btn-success <?php echo e(verficaPermissaoBotao('recurso.planos-adicionar')); ?>"><i class="fa fa-plus"></i>Cadastrar Plano</a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class='pull-right'><?php if(isset($lista)): ?><?php echo e($lista->links()); ?><?php endif; ?></div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.default', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>