<?php $__env->startSection('conteudo'); ?>

    <div class="panel panel-default ">
        <div class="panel-body">
            <?php echo Form::open(["method" => "GET", "class" => "form-search"]); ?>

            <div class="container-fluid">
                <div class="col-sm-3">
                    <label>Nome</label>
                    <?php echo Form::text('ds_sala',(!empty($_REQUEST['ds_sala']) ? $_REQUEST['ds_sala'] : ""),["name" => "ds_sala", "id" => "busca_sala", "placeholder" => "Digite o nome do sala",'class'=>'form-control']); ?>

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
                <th class='text-center' width="190px">Ação</th>
            </tr>
            </thead>
            <tbody>

            <?php if(!isset($lista) || $lista->IsEmpty()): ?>
                <tr><td colspan="3">Sem resultados</td></tr>
            <?php else: ?>
                <?php $__currentLoopData = $lista; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sala): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td class='text-center'><?php echo e($sala->cd_sala); ?></td>
                        <td class='text-center'><?php echo e($sala->ds_sala); ?></td>
                        <td class='text-center'>
                            <a href="<?php echo e(route('salas/cadastro').'/'.$sala->cd_sala); ?>" class=<?php echo e(verficaPermissaoBotao('recurso.salas-editar')); ?> 'btn btn-primary btn-sm'>Editar</a>
                            <button type='button' data-tabela="sala" data-chave="cd_sala" data-valor="<?php echo e($sala->cd_sala); ?>" class='<?php echo e(verficaPermissaoBotao('recurso.salas-excluir')); ?> btn btn-danger btn-sm btn-excluir'>Excluir</button>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="row">
        <div class="col-md-6">
            <a href="<?php echo e(route('salas/cadastro')); ?>" class="<?php echo e(verficaPermissaoBotao('recurso.salas-adicionar')); ?> btn btn-success"><i class="fa fa-plus"></i> Cadastrar Sala</a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class='pull-right'><?php if(isset($lista)): ?><?php echo e($lista->links()); ?><?php endif; ?></div>
        </div>
    </div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.default', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>