<?php $__env->startSection('conteudo'); ?>

    <div class="panel panel-default ">
        <div class="panel-body">
            <?php echo Form::open(["method" => "GET", "class" => "form-search"]); ?>

            <div class="container-fluid">
                <div class="col-sm-3">
                    <label>Código do cartão</label>
                    <?php echo Form::text('cd_beneficiario',(!empty($_REQUEST['cd_beneficiario']) ? $_REQUEST['cd_beneficiario'] : ""),["name" => "cd_beneficiario", "id" => "cd_beneficiario", "placeholder" => "Digite um número de cartão",'class'=>'form-control mask-numeros-20']); ?>

                </div>
                <div class="col-sm-2">
                    <label>Nome</label>
                    <?php echo Form::text('NOME',(!empty($_REQUEST['nome']) ? $_REQUEST['nome'] : ""),["name" => "nome", "id" => "nome", "placeholder" => "Digite um nome",'class'=>'form-control ']); ?>

                </div>
                <div class="col-sm-2">
                    <label>Plano</label>
                    <?php echo e(Form::select('cd_plano', $planos, (isset($_REQUEST['cd_plano']) ? $_REQUEST['cd_plano'] : "T"),['class'=>  "form-control", 'id' => 'status'])); ?>

                </div>
                <div class="col-sm-2">
                    <label>Tipo de beneficiário</label>
                    <?php echo e(Form::select('parentesco', arrayPadrao('parentesco',true), (isset($_REQUEST['parentesco']) ? $_REQUEST['parentesco'] : "T"),['class'=>  "form-control", 'id' => 'parentesco'])); ?>

                </div>
                <div class="col-sm-2">
                    <label>Situação</label>
                    <?php echo e(Form::select('id_situacao', arrayPadrao('situacao',true), (isset($_REQUEST['id_situacao']) ? $_REQUEST['id_situacao'] : "T"),['class'=>  "form-control", 'id' => 'id_situacao'])); ?>

                </div>
                <?php echo Form::submit('Buscar',['class'=>"btn btn-default margin-top-25 pull-right"]); ?>

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
                <th>Plano</th>
                <th>Tipo de beneficiário</th>
                <th>Situação</th>
                <th class='text-center' width="190px">Ação</th>
            </tr>
            </thead>
            <tbody>

            <?php if(!isset($lista) || $lista->IsEmpty()): ?>
                <tr><td colspan="6">Sem resultados</td></tr>
            <?php else: ?>
                <?php $__currentLoopData = $lista; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $beneficiario): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td class='text-center'><?php echo e($beneficiario->cd_beneficiario); ?></td>
                        <td class='text-center'><?php echo e($beneficiario->nm_pessoa); ?></td>
                        <td class='text-center'><?php echo e($beneficiario->ds_plano); ?></td>
                        <td class='text-center'><?php echo e(arrayPadrao('parentesco')[$beneficiario->parentesco]); ?></td>
                        <td class='text-center <?php echo e(($beneficiario->id_situacao == 'A' ? 'text-primary' : 'text-danger')); ?>'>
                            <?php echo e(($beneficiario->id_situacao == 'A' ? 'Ativo' : 'Inativo')); ?>

                        </td>
                        <td class='text-center'>
                            <a href="<?php echo e(route('beneficiarios/cadastro').'/'.$beneficiario->id_beneficiario); ?>" class='btn btn-primary btn-sm'>Detalhes</a>
                            <button type='button' data-tabela="beneficiario" data-chave="id_beneficiario" data-valor="<?php echo e($beneficiario->id_beneficiario); ?>" class='<?php echo e(verficaPermissaoBotao('recurso.beneficiarios-excluir')); ?> btn btn-danger btn-sm btn-excluir'>Excluir</button>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="row">
        <div class="col-md-6">
            <a href="<?php echo e(route('beneficiarios/cadastro')); ?>" class="btn btn-success <?php echo e(verficaPermissaoBotao('recurso.beneficiarios-adicionar')); ?>"><i class="fa fa-plus"></i> Cadastrar Beneficiário</a>
        </div>
        <div class="col-md-6">
            <div class='pull-right'><?php if(isset($lista)): ?><?php echo e($lista->links()); ?><?php endif; ?></div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.default', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>