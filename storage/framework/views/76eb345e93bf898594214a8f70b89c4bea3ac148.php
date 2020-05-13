<?php $__env->startSection('conteudo'); ?>

    <div class="panel panel-default ">
        <div class="panel-body">
            <?php echo Form::open(["method" => "GET", "class" => "form-search"]); ?>

            <div class="container-fluid">
                <div class="col-sm-2">
                    <label>Código</label>
                    <?php echo Form::text('cd_movimentacao',(!empty($_REQUEST['cd_movimentacao']) ? $_REQUEST['cd_movimentacao'] : ""),["name" => "cd_movimentacao", "placeholder" => "Digite o código",'class'=>'form-control']); ?>

                </div>
                <div class="col-sm-2">
                    <label>Data Inicial</label>
                    <?php echo Form::date('dt_ini',(!empty($_REQUEST['dt_ini']) ? $_REQUEST['dt_ini'] :  date('Y/m/01') ),["name" => "dt_ini", "min" => "1900-01-01", "max" => "2099-12-31", "class"=>"form-control"]); ?>

                </div>
                <div class="col-sm-2">
                    <label>Data Final</label>
                    <?php echo Form::date('dt_fim',(!empty($_REQUEST['dt_fim']) ? $_REQUEST['dt_fim'] : date('Y/m/d')),["name" => "dt_fim", "min" => "1900-01-01", "max" => "2099-12-31", 'class'=>'form-control']); ?>

                </div>
                <div class="col-sm-3">
                    <label>Descrição</label>
                    <?php echo Form::text('nm_movimento',(!empty($_REQUEST['nm_movimento']) ? $_REQUEST['nm_movimento'] : ""),["name" => "nm_movimento", "placeholder" => "Digite o nome do movimento",'class'=>'form-control']); ?>

                </div>
                <div class="col-sm-2 ">
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
                <th class='text-center' width="190px">Data da movimentação</th>
                <th>Descrição</th>
                <th class='text-center' width="190px">Ação</th>
            </tr>
            </thead>
            <tbody>

            <?php if(!isset($lista) || $lista->IsEmpty()): ?>
                <tr><td colspan="3">Sem resultados</td></tr>
            <?php else: ?>
                <?php $__currentLoopData = $lista; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $movimentacao): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td class='text-center'><?php echo e($movimentacao->cd_movimentacao); ?></td>
                        <td class='text-center'><?php echo e(formata_data($movimentacao->created_at)); ?></td>
                        <td class='text-center'><?php echo e($movimentacao->nm_movimento); ?></td>
                        <td class='text-center'>
                            <a href="<?php echo e(route('materiais/movimentacao/cadastro').'/'.$movimentacao->cd_movimentacao); ?>" class=<?php echo e(verficaPermissaoBotao('recurso.materiais/movimentacao-editar')); ?> 'btn btn-primary btn-sm'>Editar</a>
                            <button type='button' data-tabela="movimentacao" data-chave="cd_movimentacao" data-valor="<?php echo e($movimentacao->cd_movimentacao); ?>" class='<?php echo e(verficaPermissaoBotao('recurso.materiais/movimentacao-excluir')); ?> btn btn-danger btn-sm btn-excluir'>Excluir</button>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
            </tbody>
        </table>
     </div>
    <div class="row">
        <div class="col-md-6">
            <a href="<?php echo e(route('materiais/movimentacao/cadastro')); ?>" class="<?php echo e(verficaPermissaoBotao('recurso.materiais/movimentacao-adicionar')); ?> btn btn-success"><i class="fa fa-plus"></i> Cadastrar Movimentação</a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class='pull-right'><?php if(isset($lista)): ?><?php echo e($lista->links()); ?><?php endif; ?></div>
        </div>
    </div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.default', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>