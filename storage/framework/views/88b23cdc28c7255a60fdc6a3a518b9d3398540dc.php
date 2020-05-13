<?php $__env->startSection('conteudo'); ?>

    <div class="panel panel-default ">
        <div class="panel-body">
            <?php echo Form::open(["method" => "GET", "class" => "form-search"]); ?>

            <div class="container-fluid">
                <div class="col-sm-3">
                    <label>Nome</label>
                    <?php echo Form::text('nm_movimento',(!empty($_REQUEST['nm_movimento']) ? $_REQUEST['nm_movimento'] : ""),["name" => "nm_movimento", "id" => "busca_movimento", "placeholder" => "Digite o nome da movimento",'class'=>'form-control']); ?>

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
                <th>Tipo de movimentação</th>
                <th>Situação</th>
                <th class='text-center' width="190px">Ação</th>
            </tr>
            </thead>
            <tbody>

            <?php if(!isset($lista) || $lista->IsEmpty()): ?>
                <tr><td colspan="3">Sem resultados</td></tr>
            <?php else: ?>
                <?php $__currentLoopData = $lista; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $movimento): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td class='text-center'><?php echo e($movimento->cd_movimento); ?></td>
                        <td class='text-center'><?php echo e($movimento->nm_movimento); ?></td>
                        <td class='text-center'><?php echo e(arrayPadrao('tipo_movimento')[$movimento->tp_movimento]); ?></td>
                        <td class='text-center <?php echo e(($movimento->situacao == 'A' ? 'text-primary' : 'text-danger')); ?>'>
                            <?php echo e(($movimento->situacao == 'A' ? 'Ativo' : 'Inativo')); ?>

                        </td>
                        <td class='text-center'>
                            <a href="<?php echo e(route('materiais/movimento/cadastro').'/'.$movimento->cd_movimento); ?>" class=<?php echo e(verficaPermissaoBotao('recurso.materiais/movimento-editar')); ?> 'btn btn-primary btn-sm'>Editar</a>
                            <button type='button' data-tabela="movimento" data-chave="cd_movimento" data-valor="<?php echo e($movimento->cd_movimento); ?>" class='<?php echo e(verficaPermissaoBotao('recurso.materiais/movimento-excluir')); ?> btn btn-danger btn-sm btn-excluir'>Excluir</button>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="row">
        <div class="col-md-6">
            <a href="<?php echo e(route('materiais/movimento/cadastro')); ?>" class="<?php echo e(verficaPermissaoBotao('recurso.materiais/movimento-adicionar')); ?> btn btn-success"><i class="fa fa-plus"></i> Cadastrar Movimentação</a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class='pull-right'><?php if(isset($lista)): ?><?php echo e($lista->links()); ?><?php endif; ?></div>
        </div>
    </div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.default', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>