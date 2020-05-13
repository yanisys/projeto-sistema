<?php $__env->startSection('conteudo'); ?>

    <div class="panel panel-default ">
        <div class="panel-body">
            <?php echo Form::open(["method" => "GET", "class" => "form-search"]); ?>

            <div class="container-fluid">
                <div class="col-md-3">
                    <label for="id_produto">Localização<br></label>
                    <?php echo e(Form::select('cd_sala', $sala, (isset($_REQUEST['cd_sala']) ? $_REQUEST['cd_sala'] : ""),['class'=> "form-control", 'id' => 'cd_sala'])); ?>

                </div>
                <div class="col-sm-3">
                    <label>Nome</label>
                    <?php echo Form::text('nm_produto',(!empty($_REQUEST['nm_produto']) ? $_REQUEST['nm_produto'] : ""),["name" => "nm_produto", "id" => "busca_produto", "placeholder" => "Digite o nome do produto",'class'=>'form-control']); ?>

                </div>
                <div class="col-sm-2">
                    <label>Lote</label>
                    <?php echo Form::text('lote',(!empty($_REQUEST['lote']) ? $_REQUEST['lote'] : ""),["name" => "lote", "id" => "lote", "placeholder" => "Digite o Nº do lote",'class'=>'form-control']); ?>

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
                <th>Localização</th>
                <th>Produto</th>
                <th>Lote</th>
                <th>Fabricação</th>
                <th>Validade</th>
                <th>Quantidade</th>
            </tr>
            </thead>
            <tbody>

            <?php if(!isset($estoque)): ?>
                <tr><td colspan="3">Sem resultados</td></tr>
            <?php else: ?>
                <?php $__currentLoopData = $estoque; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($e->quantidade != 0): ?>
                        <tr>
                                <td><?php echo e($e->nm_sala); ?></td>
                                <td>
                                    <?php if(!(session()->get('recurso.materiais/produto-editar'))): ?>
                                        <?php echo e($e->nm_produto." - ".$e->ds_produto); ?>

                                    <?php else: ?>
                                    <a href="<?php echo e(route('materiais/produto/cadastro').'/'.$e->cd_produto); ?>" title="Clique aqui para ir para o cadastro do produto" target="_blank" style="color: #000030" }}>
                                        <?php echo e($e->nm_produto." - ".$e->ds_produto); ?>

                                    </a>
                                    <?php endif; ?>
                                </td>
                                <td class='text-center'><?php echo e($e->lote); ?></td>
                                <td class='text-center'><?php echo e(formata_data($e->dt_fabricacao)); ?></td>
                                <td class='text-center'><?php echo e(formata_data($e->dt_validade)); ?></td>
                                <td class='text-center'><?php echo e($e->quantidade . " " . ($e->fracionamento == 0 ? $e->nm_unidade_comercial : $e->nm_unidade_medida)); ?></td>
                        </tr>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class='pull-right'><?php if(isset($estoque)): ?><?php echo e($estoque->links()); ?><?php endif; ?></div>
        </div>
    </div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.default', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>