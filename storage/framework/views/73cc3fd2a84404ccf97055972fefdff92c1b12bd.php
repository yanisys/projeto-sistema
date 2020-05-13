<?php $__env->startSection('conteudo'); ?>
    <div class="panel panel-default ">
        <div class="panel-body">
            <?php echo Form::open(["method" => "GET", "class" => "form-search"]); ?>

            <div class="container-fluid">
                <div class="col-sm-5">
                    <label>Nome</label>
                    <?php echo Form::text('nm_produto',(!empty($_REQUEST['nm_produto']) ? $_REQUEST['nm_produto'] : ""),["name" => "nm_produto", "id" => "busca_produto", "placeholder" => "Digite o nome do produto ou cod. barras",'class'=>'form-control']); ?>

                </div>
                <div class="col-sm-3">
                    <label>Situação</label>
                    <?php echo e(Form::select('situacao', arrayPadrao('situacao',true), (isset($_REQUEST['situacao']) ? $_REQUEST['situacao'] : "A"),['class'=>  "form-control", 'id' => 'situacao'])); ?>

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
                <th>Fabricante</th>
                <th>Situação</th>
                <th class='text-center' width="190px">Ação</th>
            </tr>
            </thead>
            <tbody>
            <?php if(!isset($lista) || $lista->IsEmpty()): ?>
                <tr><td colspan="3">Sem resultados</td></tr>
            <?php else: ?>
                <?php $__currentLoopData = $lista; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $produto): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($produto->cd_produto); ?></td>
                        <td><?php echo e($produto->nm_produto . (isset($produto->ds_produto) && !empty($produto->ds_produto) ? " - ".$produto->ds_produto : "")); ?></td>
                        <td><?php echo e($produto->nm_laboratorio); ?></td>
                        <td class='text-center <?php echo e(($produto->situacao == 'A' ? 'text-primary' : 'text-danger')); ?>'>
                            <?php echo e(($produto->situacao == 'A' ? 'Ativo' : 'Inativo')); ?>

                        </td>
                        <td class='text-center'>
                            <a href="<?php echo e(route('materiais/produto/cadastro').'/'.$produto->cd_produto); ?>" class=<?php echo e(verficaPermissaoBotao('recurso.materiais/produto-editar')); ?> 'btn btn-primary btn-sm'>Editar</a>
                            <button type='button' data-tabela="produto" data-chave="cd_produto" data-valor="<?php echo e($produto->cd_produto); ?>" class='<?php echo e(verficaPermissaoBotao('recurso.materiais/produto-excluir')); ?> btn btn-danger btn-sm btn-excluir'>Excluir</button>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="row">
        <div class="col-md-6">
            <a href="<?php echo e(route('materiais/produto/cadastro')); ?>" class="<?php echo e(verficaPermissaoBotao('recurso.materiais/produto-adicionar')); ?> btn btn-success"><i class="fa fa-plus"></i> Cadastrar Produto</a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class='pull-right'><?php if(isset($lista)): ?><?php echo e($lista->links()); ?><?php endif; ?></div>
        </div>
    </div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.default', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>