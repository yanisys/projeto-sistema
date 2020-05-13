<?php $__env->startSection('conteudo'); ?>

    <div class="panel panel-default ">
        <div class="panel-body">
            <?php echo Form::open(["method" => "GET", "class" => "form-search"]); ?>

            <div class="container-fluid">
                <div class="col-sm-3">
                    <label>Nome</label>
                    <?php echo Form::text('nm_origem',(!empty($_REQUEST['nm_origem']) ? $_REQUEST['nm_origem'] : ""),["name" => "nm_origem", "id" => "busca_origem", "placeholder" => "Digite o nome do origem",'class'=>'form-control']); ?>

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
                <th>Descrição</th>
                <th class='text-center' width="190px">Ação</th>
            </tr>
            </thead>
            <tbody>

            <?php if(!isset($lista) || $lista->IsEmpty()): ?>
                <tr><td colspan="3">Sem resultados</td></tr>
            <?php else: ?>
                <?php $__currentLoopData = $lista; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $origem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td class='text-center'><?php echo e($origem->nm_origem); ?></td>
                        <td class='text-center'>
                            <a href="<?php echo e(route('configuracoes/origem/cadastro').'/'.$origem->cd_origem); ?>" class=<?php echo e(verficaPermissaoBotao('recurso.configuracoes/origem-editar')); ?> 'btn btn-primary btn-sm'>Editar</a>
                            <button type='button' data-tabela="origem" data-chave="cd_origem" data-valor="<?php echo e($origem->cd_origem); ?>" class='<?php echo e(verficaPermissaoBotao('recurso.configuracoes/origem-excluir')); ?> btn btn-danger btn-sm btn-excluir'>Excluir</button>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="row">
        <div class="col-md-6">
            <a href="<?php echo e(route('configuracoes/origem/cadastro')); ?>" class="<?php echo e(verficaPermissaoBotao('recurso.configuracoes/origem-adicionar')); ?> btn btn-success"><i class="fa fa-plus"></i> Cadastrar Origem</a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class='pull-right'><?php if(isset($lista)): ?><?php echo e($lista->links()); ?><?php endif; ?></div>
        </div>
    </div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.default', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>