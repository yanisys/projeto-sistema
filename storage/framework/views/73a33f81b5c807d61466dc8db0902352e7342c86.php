<?php $__env->startSection('conteudo'); ?>

    <div class="panel panel-default ">
        <div class="panel-body">
            <?php echo Form::open(["method" => "GET", "class" => "form-search"]); ?>

            <div class="container-fluid">
                <div class="col-sm-3">
                    <label>Nome</label>
                    <?php echo Form::text('nm_unidade_medida',(!empty($_REQUEST['nm_unidade_medida']) ? $_REQUEST['nm_unidade_medida'] : ""),["name" => "nm_unidade_medida", "id" => "nm_unidade_medida", "placeholder" => "Digite o nome da unidade de medida",'class'=>'form-control']); ?>

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
                <?php $__currentLoopData = $lista; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $umed): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td class='text-center'><?php echo e($umed->cd_unidade_medida); ?></td>
                        <td class='text-center'><?php echo e($umed->nm_unidade_medida); ?></td>
                        <td class='text-center'><?php echo e($umed->abreviacao); ?></td>
                        <td class='text-center <?php echo e(($umed->situacao == 'A' ? 'text-primary' : 'text-danger')); ?>'>
                            <?php echo e(($umed->situacao == 'A' ? 'Ativo' : 'Inativo')); ?>

                        </td>
                        <td class='text-center'>
                            <a href="<?php echo e(route('configuracoes/unidades-medida/cadastro').'/'.$umed->cd_unidade_medida); ?>" class=<?php echo e(verficaPermissaoBotao('recurso.configuracoes/alergias-editar')); ?> 'btn btn-primary btn-sm'>Editar</a>
                            <button type='button' data-tabela="unidade_medida" data-chave="cd_unidade_medida" data-valor="<?php echo e($umed->cd_unidade_medida); ?>" class='<?php echo e(verficaPermissaoBotao('recurso.configuracoes/alergias-excluir')); ?> btn btn-danger btn-sm btn-excluir'>Excluir</button>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="row">
        <div class="col-md-6">
            <a href="<?php echo e(route('configuracoes/unidades-medida/cadastro')); ?>" class="<?php echo e(verficaPermissaoBotao('recurso.configuracoes/alergias-adicionar')); ?> btn btn-success"><i class="fa fa-plus"></i> Cadastrar</a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class='pull-right'><?php if(isset($lista)): ?><?php echo e($lista->links()); ?><?php endif; ?></div>
        </div>
    </div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.default', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>