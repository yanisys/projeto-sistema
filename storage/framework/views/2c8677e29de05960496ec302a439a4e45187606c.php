<?php $__env->startSection('conteudo'); ?>
    <div class="row">
        <?php if((session()->get('recurso.materiais/produto'))): ?>
            <!-- Tabela de Produto -->
                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-6 text-center">
                    <a href="<?php echo e(route('materiais/produto/lista')); ?>" class="iconesInicio "><span class="fas fa-box fa-5x"> </span>
                        <h4>Produtos</h4></a>
                </div>
        <?php endif; ?>
        <?php if((session()->get('recurso.materiais/grupo'))): ?>
            <!-- Tabela de Grupos de Produto -->
                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-6 text-center">
                    <a href="<?php echo e(route('materiais/grupo/lista')); ?>" class="iconesInicio "><span class="fas fa-boxes fa-5x"> </span>
                        <h4>Grupos de Produtos</h4></a>
                </div>
        <?php endif; ?>
        <?php if((session()->get('recurso.materiais/movimento'))): ?>
            <!-- Tabela de Movimentação de Produto -->
                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-6 text-center">
                    <a href="<?php echo e(route('materiais/movimento/lista')); ?>" class="iconesInicio "><span class="fas fa-expand-arrows-alt fa-5x"> </span>
                        <h4>Movimento</h4></a>
                </div>
        <?php endif; ?>
        <?php if((session()->get('recurso.materiais/movimentacao'))): ?>
            <!-- Tabela de Movimentação de Estoque -->
                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-6 text-center">
                    <a href="<?php echo e(route('materiais/estoque/lista')); ?>" class="iconesInicio "><span class="fas fa-pallet fa-5x"> </span>
                        <h4>Estoque de produtos</h4></a>
                </div>
        <?php endif; ?>
        <?php if((session()->get('recurso.materiais/movimentacao'))): ?>
            <!-- Tabela de Fornecedores -->
                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-6 text-center">
                    <a href="<?php echo e(route('materiais/fornecedores/lista')); ?>" class="iconesInicio "><span class="fas fa-truck fa-5x"> </span>
                        <h4>Cadastro de fornecedores</h4></a>
                </div>
        <?php endif; ?>
        <?php if((session()->get('recurso.materiais/movimentacao'))): ?>
            <!-- Tabela de Movimentação de Estoque -->
                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-6 text-center">
                    <a href="<?php echo e(route('materiais/movimentacao/lista')); ?>" class="iconesInicio "><span class="far fa-hand-point-right fa-5x"> </span>
                        <h4>Movimentação de produtos</h4></a>
                </div>
        <?php endif; ?>
        <?php if((session()->get('recurso.materiais/kits'))): ?>
            <!-- Tabela de Requisições -->
                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-6 text-center">
                    <a href="<?php echo e(route('materiais/requisicoes/lista')); ?>" class="iconesInicio "><span class="fas fa-dolly fa-5x"> </span>
                        <h4>Requisição de materiais</h4></a>
                </div>
        <?php endif; ?>
        <?php if((session()->get('recurso.materiais/kits'))): ?>
            <!-- Tabela de Kits -->
                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-6 text-center">
                    <a href="<?php echo e(route('materiais/kits/lista')); ?>" class="iconesInicio "><span class="fas fa-medkit fa-5x"> </span>
                        <h4>Kits</h4></a>
                </div>
        <?php endif; ?>
        <?php if((session()->get('recurso.materiais/movimentacao'))): ?>
            <!-- Tabela de Movimentação de Localização -->
                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-6 text-center">
                    <a href="<?php echo e(route('materiais/movimentacao/movimento-sala')); ?>" class="iconesInicio "><span class="fas fa-exchange-alt fa-5x"> </span>
                        <h4>Movimentação interna de produtos</h4></a>
                </div>
        <?php endif; ?>
        <?php if((session()->get('recurso.materiais/movimentacao'))): ?>
            <!-- Tabela de Movimentação de Localização -->
                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-6 text-center">
                    <a href="<?php echo e(route('materiais/relatorios/estoque')); ?>" class="iconesInicio "><span class="fas fa-clipboard-list fa-5x"> </span>
                        <h4>Relatório de estoque</h4></a>
                </div>
        <?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.default', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>