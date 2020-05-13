<?php $__env->startSection('conteudo'); ?>

    <div class="panel panel-default ">
        <div class="panel-body">
            <?php echo Form::open(["method" => "GET", "class" => "form-search"]); ?>

            <div class="container-fluid">
                <div class="col-sm-3">
                    <label>CPF/ CNPJ</label>
                    <?php echo Form::text('cnpj_cpf',(!empty($_REQUEST['cnpj_cpf']) ? $_REQUEST['cnpj_cpf'] : ""),["name" => "cnpj_cpf", "id" => "cnpj_cpf", "placeholder" => "Digite o código",'class'=>'form-control mask-numeros-14']); ?>

                </div>
                <div class="col-sm-3">
                    <label>Nome</label>
                    <?php echo Form::text('nome',(!empty($_REQUEST['nome']) ? $_REQUEST['nome'] : ""),["name" => "nome", "id" => "nome", "placeholder" => "Digite um nome",'class'=>'form-control ']); ?>

                </div>
                <div class="col-sm-3">
                    <label>Situação</label>
                    <?php echo e(Form::select('id_situacao', arrayPadrao('situacao',true), (isset($_REQUEST['id_situacao']) ? $_REQUEST['id_situacao'] : "A"),['class'=>  "form-control", 'id' => 'id_situacao'])); ?>

                </div>
                <?php echo Form::submit('Buscar',['class'=>"btn btn-default margin-top-25"]); ?>

            </div>
            <?php echo Form::close(); ?>

        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-hover table-striped" style="font-size: 14px">
            <thead>
            <tr>
                <th>CPF/CNPJ</th>
                <th>Nome</th>
                <th>Situação</th>
                <th>Tipo</th>
                <th class='text-center' width="190px">Ação</th>
            </tr>
            </thead>
            <tbody>

            <?php if(!isset($lista) || $lista->IsEmpty()): ?>
                <tr><td colspan="5">Sem resultados</td></tr>
            <?php else: ?>
                <?php $__currentLoopData = $lista; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $l): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td class='text-center'><?php echo e($l->cnpj_cpf); ?></td>
                        <td class='text-center'><?php echo e($l->nm_pessoa); ?></td>
                        <td class='text-center <?php echo e(($l->id_situacao == 'A' ? 'text-primary' : 'text-danger')); ?>'>
                            <?php echo e(($l->id_situacao == 'A' ? 'Ativo' : 'Inativo')); ?>

                        </td>
                        <td class='text-center'><?php echo e(($l->id_pessoa == 'F' ? 'Físico' : 'Juridico')); ?></td>
                        <td class='text-center'>
                            <button class="btn-modal-pessoa btn btn-primary btn-sm <?php echo e(verficaPermissaoBotao('recurso.pessoas-editar')); ?>"
                                    data-modo="editar" data-cd-pessoa='<?php echo e($l->cd_pessoa); ?>'>
                                Editar
                            </button>
                            <button type='button' data-tabela="pessoa" data-chave="cd_pessoa"  data-valor="<?php echo e($l->cd_pessoa); ?>" class='<?php echo e(verficaPermissaoBotao('recurso.pessoas-excluir')); ?> btn btn-danger btn-sm btn-excluir'>Excluir</button>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="row">
        <div class="col-md-6">
            <button class="btn-modal-pessoa btn btn-success margin-top-25 <?php echo e(verficaPermissaoBotao('recurso.pessoas-adicionar')); ?>"
                    data-modo="novo">
                Cadastrar Pessoa
            </button>
        </div>
        <div class="col-md-6">
            <div class='pull-right'><?php if(isset($lista)): ?><?php echo e($lista->links()); ?><?php endif; ?></div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('painel-modal'); ?>
    <?php echo $__env->make('pessoas/modal-pessoas', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.default', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>