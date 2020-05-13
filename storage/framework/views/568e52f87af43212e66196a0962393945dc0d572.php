<?php $__env->startSection('conteudo'); ?>

    <div class="panel panel-default ">
        <div class="panel-body">
            <?php echo Form::open(["method" => "GET", "class" => "form-search"]); ?>

            <div class="container-fluid">
                <div class="col-sm-3">
                    <label>Nome</label>
                    <?php echo Form::text('nome',(!empty($_REQUEST['nome']) ? $_REQUEST['nome'] : ""),["name" => "nome", "id" => "nome", "placeholder" => "Digite o nome do estabelecimento",'class'=>'form-control']); ?>

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
                <th>Nome</th>
                <th>Cnes</th>
                <th>Tipo</th>
                <th>Situação</th>
                <th class='text-center' width="190px">Ação</th>
            </tr>
            </thead>
            <tbody>

            <?php if(!isset($lista) || $lista->IsEmpty()): ?>
                <tr><td colspan="5">Sem resultados</td></tr>
            <?php else: ?>
                <?php $__currentLoopData = $lista; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $estabelecimento): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td class='text-center'><?php echo e($estabelecimento->cd_estabelecimento); ?></td>
                        <td class='text-center'><?php echo e($estabelecimento->nm_estabelecimento); ?></td>
                        <td class='text-center'><?php echo e($estabelecimento->cnes); ?></td>
                        <td class='text-center'><?php echo e(arrayPadrao('tipo_estabelecimento')[$estabelecimento->tp_estabelecimento]); ?></td>
                        <td class='text-center <?php echo e(($estabelecimento->status == 'A' ? 'text-primary' : 'text-danger')); ?>'>
                            <?php echo e(($estabelecimento->status == 'A' ? 'Ativo' : 'Inativo')); ?>

                        </td>
                        <td class='text-center'>
                            <a href="<?php echo e(route('estabelecimentos/cadastro').'/'.$estabelecimento->cd_estabelecimento); ?>" class='btn btn-primary btn-sm'>Editar</a>
                            <button type='button' data-tabela="estabelecimentos" data-chave="cd_estabelecimento" data-valor="<?php echo e($estabelecimento->cd_estabelecimento); ?>" class='<?php echo e(verficaPermissaoBotao('recurso.estabelecimentos-excluir')); ?> btn btn-danger btn-sm btn-excluir'>Excluir</button>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="row">
        <div class="col-md-6">
            <a href="<?php echo e(route('estabelecimentos/cadastro')); ?>" class="<?php echo e(verficaPermissaoBotao('recurso.estabelecimentos-adicionar')); ?> btn btn-success"><i class="fa fa-plus"></i> Cadastrar Estabelecimento</a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class='pull-right'><?php if(isset($lista)): ?><?php echo e($lista->links()); ?><?php endif; ?></div>
        </div>
    </div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.default', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>