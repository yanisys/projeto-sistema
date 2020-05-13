<?php $__env->startSection('conteudo'); ?>

    <div class="panel panel-default ">
        <div class="panel-body">
            <?php echo Form::open(["method" => "GET", "class" => "form-search"]); ?>
            <div class="container-fluid">
                <div class="col-sm-3">
                    <label>Nome</label>
                    <?php echo Form::text('NOME',(!empty($_REQUEST['nome']) ? $_REQUEST['nome'] : ""),["name" => "nome", "id" => "nome", "placeholder" => "Digite um nome",'class'=>'form-control ']); ?>
                </div>
                <div class="col-sm-3">
                    <label>Situação</label>
                    <?php echo e(Form::select('status', arrayPadrao('situacao',true), (isset($_REQUEST['status']) ? $_REQUEST['status'] : "T"),['class'=>  "form-control", 'id' => 'status'])); ?>
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
                <th>Código</th>
                <th>Nome</th>
                <th>Profissão</th>
                <th>Conselho/ Inscrição</th>
                <th>CNS</th>
                <th>Situação</th>
                <th class='text-center' width="190px">Ação</th>
            </tr>
            </thead>
            <tbody>

            <?php if(!isset($lista) || $lista->IsEmpty()): ?>
                <tr><td colspan="6">Sem resultados</td></tr>
            <?php else: ?>
                <?php $__currentLoopData = $lista; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $profissional): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td class='text-center'><?php echo e($profissional->cd_profissional); ?></td>
                        <td class='text-center'><?php echo e(($profissional->nm_pessoa )); ?></td>
                        <td class='text-center'><?php echo e($profissional->nm_ocupacao); ?></td>
                        <td class='text-center'><?php echo e($profissional->conselho.": ".$profissional->nr_conselho); ?></td>
                        <td class='text-center'><?php echo e(($profissional->cd_beneficiario == '' ? 'Não informado' : $profissional->cd_beneficiario)); ?></td>
                        <td class='text-center <?php echo e(($profissional->status == 'A' ? 'text-primary' : 'text-danger')); ?>'>
                            <?php echo e(($profissional->status == 'A' ? 'Ativo' : 'Inativo')); ?>
                        </td>
                        <td class='text-center'>
                            <a href="<?php echo e(route('profissionais/cadastro').'/'.$profissional->cd_profissional); ?>" class='btn btn-primary btn-sm'>Detalhes</a>
                            <button type='button' data-tabela="profissional" data-chave="cd_profissional" data-valor="<?php echo e($profissional->cd_profissional); ?>" class='<?php echo e(verficaPermissaoBotao('recurso.profissionais-excluir')); ?> btn btn-danger btn-sm btn-excluir'>Excluir</button>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="row">
        <div class="col-md-6">
            <a href="<?php echo e(route('profissionais/cadastro')); ?>" class="btn btn-success <?php echo e(verficaPermissaoBotao('recurso.profissionais-adicionar')); ?>"><i class="fa fa-plus"></i> Cadastrar Profissional</a>
        </div>
        <div class="col-md-6">
            <div class='pull-right'><?php if(isset($lista)): ?><?php echo e($lista->links()); ?><?php endif; ?></div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.default', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>