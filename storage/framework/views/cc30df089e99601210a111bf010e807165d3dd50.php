<?php $__env->startSection('conteudo'); ?>
    <div class="panel panel-default ">
        <div class="panel-body">
            <?php echo Form::open(["method" => "GET", "class" => "form-search"]); ?>

            <div class="container-fluid">
                <div class="col-sm-3">
                    <label>Nome</label>
                    <?php echo Form::text('nome',(!empty($_REQUEST['nome']) ? $_REQUEST['nome'] : ""),["name" => "nome", "id" => "nome", "placeholder" => "Digite um nome",'class'=>'form-control ']); ?>

                </div>
                <?php echo Form::submit('Buscar',['class'=>"btn btn-default margin-top-25"]); ?>

            </div>
            <?php echo Form::close(); ?>

        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-hover table-striped">
            <thead>
            <tr>
                <th>Nome</th>
                <th class='text-center' width="190px">Ação</th>
            </tr>
            </thead>
            <tbody id="tabela-permissoes">
            <?php if(!isset($lista) || $lista->IsEmpty()): ?>
                <tr><td colspan="2">Sem resultados</td></tr>
            <?php else: ?>
                <?php $__currentLoopData = $lista; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $grupo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td class='text-left'><?php echo e($grupo->nm_grupo_op); ?></td>
                        <td class='text-center'>
                            <a href="<?php echo e(route('grupos/cadastro').'/'.$grupo->cd_grupo_op); ?>" class='btn btn-primary btn-sm'>Detalhes</a>
                            <button type='button' data-tabela="grupo_op" data-chave="cd_grupo_op" data-valor="<?php echo e($grupo->cd_grupo_op); ?>" class='<?php echo e(verficaPermissaoBotao('recurso.grupos-excluir')); ?> btn btn-danger btn-sm btn-excluir'>Excluir</button>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="row">
        <div class="col-md-6">
            <a href="<?php echo e(route('grupos/cadastro')); ?>" class="<?php echo e(verficaPermissaoBotao('recurso.grupos-adicionar')); ?> btn btn-success"><i class="fa fa-plus"></i> Cadastrar Grupo</a>
        </div>
        <div class="col-md-6">
            <div class='pull-right'><?php if(isset($lista)): ?><?php echo e($lista->links()); ?><?php endif; ?></div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.default', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>