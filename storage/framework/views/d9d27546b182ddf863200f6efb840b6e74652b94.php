<?php $__env->startSection('conteudo'); ?>
    <div class="row">
        <?php if((session()->get('recurso.pessoas'))): ?>
            <!-- Tabela de pessoas -->
            <div class="col-md-3 text-center">
                <a href="<?php echo e(route('pessoas/lista')); ?>" class="iconesInicio "><span class="fa fa-male fa-5x"> </span>
                    <h3>Pessoas</h3></a>
            </div>
        <?php endif; ?>
        <?php if((session()->get('recurso.operadores'))): ?>
            <!-- Tabela de operadores -->
            <div class="col-md-3 text-center">
                <a href="<?php echo e(route('operadores/lista')); ?>" class="iconesInicio "><span class="fa fa-user fa-5x"> </span>
                    <h3>Operadores</h3></a>
            </div>
        <?php endif; ?>
        <?php if((session()->get('recurso.grupos'))): ?>
            <!-- Tabela grupos de operadores -->
            <div class="col-md-3 text-center">
                <a href="<?php echo e(route('grupos/lista')); ?>" class="iconesInicio "><span class="fa fa-users fa-5x"> </span>
                    <h3>Grupos de Operadores</h3></a>
           </div>
        <?php endif; ?>
        <?php if((session()->get('recurso.planos'))): ?>
            <!-- Tabela de Planos -->
            <div class="col-md-3 text-center">
                <a href="<?php echo e(route('planos/lista')); ?>" class="iconesInicio "><span class="fa fa-medkit fa-5x"> </span>
                    <h3>Planos</h3></a>
            </div>
        <?php endif; ?>
        <?php if((session()->get('recurso.estabelecimentos'))): ?>
            <!-- Tabela de Estabelecimentos -->
            <div class="col-md-3 text-center">
                <a href="<?php echo e(route('estabelecimentos/lista')); ?>" class="iconesInicio "><span class="fa fa-hospital-o fa-5x"> </span>
                    <h3>Estabelecimentos</h3></a>
            </div>
        <?php endif; ?>
        <?php if((session()->get('recurso.beneficiarios'))): ?>
            <!-- Tabela de Beneficiários -->
            <div class="col-md-3 text-center">
                <a href="<?php echo e(route('beneficiarios/lista')); ?>" class="iconesInicio "><span class="fa fa-user-plus fa-5x"> </span>
                    <h3>Beneficiários</h3></a>
            </div>
        <?php endif; ?>
        <?php if((session()->get('recurso.salas'))): ?>
            <!-- Tabela de Salas -->
            <div class="col-md-3 text-center">
                <a href="<?php echo e(route('salas/lista')); ?>" class="iconesInicio "><span class="fa fa-hotel fa-5x"> </span>
                    <h3>Salas</h3></a>
            </div>
        <?php endif; ?>
        <?php if((session()->get('recurso.profissionais'))): ?>
            <!-- Cadastro de profissionais-->
            <div class="col-md-3 text-center">
                <a href="<?php echo e(route('profissionais/lista')); ?>" class="iconesInicio "><span class="fa fa-user-md fa-5x"> </span>
                    <h3>Profissionais de saúde</h3></a>
            </div>
        <?php endif; ?>
        <?php if((session()->get('recurso.atendimentos'))): ?>
            <!-- Tabela de Atendimentos -->
            <div class="col-md-3 text-center">
                <a href="<?php echo e(route('atendimentos/fila')); ?>" class="iconesInicio "><span class="fa fa-stethoscope fa-5x"> </span>
                    <h3>Atendimento</h3></a>
            </div>
        <?php endif; ?>
        <?php if((session()->get('recurso.painel'))): ?>
            <!-- Painel de chamadas-->
                <div class="col-md-3 text-center">
                    <a href="<?php echo e(route('atendimentos/painel')); ?>" class="iconesInicio "><span class="fa fa-tv fa-5x"> </span>
                        <h3>Painel de chamadas</h3></a>
                </div>
        <?php endif; ?>
        <?php if((session()->get('recurso.prontuarios'))): ?>
            <!-- Tabela de Prontuários -->
                <div class="col-md-3 text-center">
                    <a href="<?php echo e(route('prontuarios')); ?>" class="iconesInicio "><span class="fa fa-tv fa-5x"> </span>
                        <h3>Painel de chamadas</h3></a>
                </div>
        <?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.default', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>