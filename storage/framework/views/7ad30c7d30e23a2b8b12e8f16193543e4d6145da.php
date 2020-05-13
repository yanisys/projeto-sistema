<?php $__env->startSection('conteudo'); ?>

    <div class="panel panel-default ">
        <div class="panel-body">
            <?php echo Form::open(["method" => "GET", "class" => "form-search"]); ?>

            <div class="container-fluid">
                <div class="col-sm-3">
                    <label>Nome</label>
                    <?php echo Form::text('descricao',(!empty($_REQUEST['descricao']) ? $_REQUEST['descricao'] : ""),["name" => "descricao", "id" => "descricao", "placeholder" => "Digite o nome da unidade comercial",'class'=>'form-control']); ?>

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
                <th>Abreviação</th>
                <th>Situação</th>
                <th class='text-center' width="190px">Ação</th>
            </tr>
            </thead>
            <tbody>

            <?php if(!isset($lista) || $lista->IsEmpty()): ?>
                <tr><td colspan="3">Sem resultados</td></tr>
            <?php else: ?>
                <?php $__currentLoopData = $lista; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ucom): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td class='text-center'><?php echo e($ucom->cd_unidade_comercial); ?></td>
                        <td class='text-center'><?php echo e($ucom->descricao); ?></td>
                        <td class='text-center'><?php echo e($ucom->unidade); ?></td>
                        <td class='text-center <?php echo e(($ucom->situacao == 'A' ? 'text-primary' : 'text-danger')); ?>'>
                            <?php echo e(($ucom->situacao == 'A' ? 'Ativo' : 'Inativo')); ?>

                        </td>
                        <td class='text-center'>
                            <a href="<?php echo e(route('configuracoes/unidades-comerciais/cadastro').'/'.$ucom->cd_unidade_comercial); ?>" class=<?php echo e(verficaPermissaoBotao('recurso.configuracoes/alergias-editar')); ?> 'btn btn-primary btn-sm'>Editar</a>
                            <button type='button' data-tabela="unidades_comerciais" data-chave="cd_unidade_comercial" data-valor="<?php echo e($ucom->cd_unidade_comercial); ?>" class='<?php echo e(verficaPermissaoBotao('recurso.configuracoes/alergias-excluir')); ?> btn btn-danger btn-sm btn-excluir'>Excluir</button>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="row">
        <div class="col-md-6">
            <a href="<?php echo e(route('configuracoes/unidades-comerciais/cadastro')); ?>" class="<?php echo e(verficaPermissaoBotao('recurso.configuracoes/alergias-adicionar')); ?> btn btn-success"><i class="fa fa-plus"></i> Cadastrar</a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class='pull-right'><?php if(isset($lista)): ?><?php echo e($lista->links()); ?><?php endif; ?></div>
        </div>
    </div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.default', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>