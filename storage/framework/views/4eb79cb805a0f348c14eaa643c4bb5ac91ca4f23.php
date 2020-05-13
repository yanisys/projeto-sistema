<?php $__env->startSection('conteudo'); ?>

    <div class="panel panel-default ">
        <div class="panel-body">
            <?php echo Form::open(["method" => "GET", "class" => "form-search"]); ?>

            <div class="container-fluid">
                <div class="col-sm-3">
                    <label>Nome</label>
                    <?php echo Form::text('nm_sala',(!empty($_REQUEST['nm_sala']) ? $_REQUEST['nm_sala'] : ""),["name" => "nm_sala", "id" => "busca_sala", "placeholder" => "Digite o nome do local",'class'=>'form-control']); ?>

                </div>
                <div class="col-sm-3">
                    <label>Tipo</label>
                    <?php echo e(Form::select('tipo', arrayPadrao('tipo_sala',true), (isset($_REQUEST['tipo']) ? $_REQUEST['tipo'] : "T"),["name" => "tipo", 'class'=>  "form-control", 'id' => 'tipo'])); ?>

                </div>
                <div class="col-sm-3">
                    <label>Situação</label>
                    <?php echo e(Form::select('situacao', arrayPadrao('situacao',true), (isset($_REQUEST['situacao']) ? $_REQUEST['situacao'] : "T"),['class'=>  "form-control", 'id' => 'situacao'])); ?>

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
                <th>Tipo</th>
                <th>Situação</th>
                <th class='text-center' width="190px">Ação</th>
            </tr>
            </thead>
            <tbody>

            <?php if(!isset($lista) || $lista->IsEmpty()): ?>
                <tr><td colspan="5">Sem resultados</td></tr>
            <?php else: ?>
                <?php $__currentLoopData = $lista; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sala): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td class='text-center'><?php echo e($sala->cd_sala); ?></td>
                        <td class='text-center'><?php echo e($sala->nm_sala); ?></td>
                        <td class='text-center'><?php echo e(arrayPadrao('tipo_sala')[$sala->tipo]); ?></td>
                        <td class='text-center <?php echo e(($sala->situacao == 'A' ? 'text-primary' : 'text-danger')); ?>'><?php echo e(($sala->situacao == 'A' ? 'Ativo' : 'Inativo')); ?></td>
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