<?php $__env->startSection('conteudo'); ?>
    <div class="row">
        <?php if((session()->get('recurso.pessoas'))): ?>
            <!-- Tabela de pessoas -->
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-6 text-center">
                <a href="<?php echo e(route('pessoas/lista')); ?>" class="iconesInicio "><span class="fa fa-male fa-5x"> </span>
                    <h4>Pessoas</h4></a>
            </div>
        <?php endif; ?>
        <?php if((session()->get('recurso.operadores'))): ?>
            <!-- Tabela de operadores -->
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-6 text-center">
                <a href="<?php echo e(route('operadores/lista')); ?>" class="iconesInicio "><span class="fa fa-user fa-5x"> </span>
                    <h4>Operadores</h4></a>
            </div>
        <?php endif; ?>
        <?php if((session()->get('recurso.grupos'))): ?>
            <!-- Tabela grupos de operadores -->
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-6 text-center">
                <a href="<?php echo e(route('grupos/lista')); ?>" class="iconesInicio "><span class="fa fa-users fa-5x"> </span>
                    <h4>Grupos de Operadores</h4></a>
           </div>
        <?php endif; ?>
        <?php if((session()->get('recurso.planos'))): ?>
            <!-- Tabela de Planos -->
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-6 text-center">
                <a href="<?php echo e(route('planos/lista')); ?>" class="iconesInicio "><span class="fa fa-medkit fa-5x"> </span>
                    <h4>Planos</h4></a>
            </div>
        <?php endif; ?>
        <?php if((session()->get('recurso.estabelecimentos'))): ?>
            <!-- Tabela de Estabelecimentos -->
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-6 text-center">
                <a href="<?php echo e(route('estabelecimentos/lista')); ?>" class="iconesInicio "><span class="fas fa-hospital fa-5x"> </span>
                    <h4>Estabelecimentos</h4></a>
            </div>
        <?php endif; ?>
        <?php if((session()->get('recurso.beneficiarios'))): ?>
            <!-- Tabela de Beneficiários -->
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-6 text-center">
                <a href="<?php echo e(route('beneficiarios/lista')); ?>" class="iconesInicio "><span class="fa fa-user-plus fa-5x"> </span>
                    <h4>Beneficiários</h4></a>
            </div>
        <?php endif; ?>
        <?php if((session()->get('recurso.salas'))): ?>
            <!-- Tabela de Salas -->
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-6 text-center">
                <a href="<?php echo e(route('salas/lista')); ?>" class="iconesInicio "><span class="fas fa-hospital-alt fa-5x"> </span>
                    <h4>Salas</h4></a>
            </div>
        <?php endif; ?>
        <?php if((session()->get('recurso.profissionais'))): ?>
            <!-- Cadastro de profissionais-->
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-6 text-center">
                <a href="<?php echo e(route('profissionais/lista')); ?>" class="iconesInicio "><span class="fa fa-user-md fa-5x"> </span>
                    <h4>Profissionais de saúde</h4></a>
            </div>
        <?php endif; ?>
        <?php if((session()->get('recurso.atendimentos'))): ?>
            <!-- Tabela de Atendimentos -->
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-6 text-center">
                <a href="<?php echo e(route('atendimentos/fila')); ?>" class="iconesInicio "><span class="fa fa-stethoscope fa-5x"> </span>
                    <h4>Atendimento</h4></a>
            </div>
        <?php endif; ?>
        <?php if((session()->get('recurso.painel'))): ?>
            <!-- Painel de chamadas-->
                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-6 text-center">
                    <a href="<?php echo e(route('atendimentos/painel')); ?>" class="iconesInicio "><span class="fa fa-tv fa-5x"> </span>
                        <h4>Painel de chamadas</h4></a>
                </div>
        <?php endif; ?>
        <?php if((session()->get('recurso.prontuarios'))): ?>
            <!-- Tabela de Prontuários -->
                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-6 text-center">
                    <a href="<?php echo e(route('prontuarios/lista')); ?>" class="iconesInicio "><span class="fas fa-file fa-5x"> </span>
                        <h4>Prontuários</h4></a>
                </div>
        <?php endif; ?>
        <?php if((session()->get('recurso.relatorios/atendimentos'))): ?>
            <!-- Relatório de Atendimentos -->
                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-6 text-center">
                    <a href="<?php echo e(route('relatorios/atendimentos/media')); ?>" class="iconesInicio "><span class="fas fa-chart-bar fa-5x"> </span>
                        <h4>Relatório de Atendimentos</h4></a>
                </div>
        <?php endif; ?>
        <?php if((session()->get('recurso.relatorios/bpa'))): ?>
            <!-- Relatório de Atendimentos -->
                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-6 text-center">
                    <a href="<?php echo e(route('relatorios/bpa')); ?>" class="iconesInicio "><span class="fa fa-file fa-5x"> </span>
                        <h4>Arquivo Bpa</h4></a>
                </div>
        <?php endif; ?>
        <?php if((session()->get('recurso.configuracoes/procedimentos'))): ?>
            <!-- Tabela de Prontuários -->
                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-6 text-center">
                    <a href="<?php echo e(route('configuracoes/configuracoes')); ?>" class="iconesInicio "><span class="fa fa-cog fa-5x"> </span>
                        <h4>Configurações</h4></a>
                </div>
        <?php endif; ?>
        <?php if((session()->get('recurso.materiais'))): ?>
            <!-- Tabela de Produtos -->
                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-6 text-center">
                    <a href="<?php echo e(route('materiais/produtos')); ?>" class="iconesInicio "><span class="fas fa-box fa-5x"> </span>
                        <h4>Materiais</h4></a>
                </div>
        <?php endif; ?>
        
            <!-- Implementar funcionamento -->
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-6 text-center">
                <a href="#" class="iconesInicio "><span class="fas fa-ambulance fa-5x"> </span>
                    <h4>Samu</h4></a>
            </div>
            
            <!-- Implementar funcionamento -->
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-6 text-center">
                <a href="#" class="iconesInicio "><span class="fas fa-cash-register fa-5x"> </span>
                    <h4>Financeiro</h4></a>
            </div>
            
            <!-- Implementar funcionamento -->
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-6 text-center">
                <a href="#" class="iconesInicio "><span class="fas fa-hotel fa-5x"> </span>
                    <h4>Hotelaria</h4></a>
            </div>
            
            <!-- Implementar funcionamento -->
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-6 text-center">
                <a href="#" class="iconesInicio "><span class="fas fa-street-view fa-5x"> </span>
                    <h4>ACS</h4></a>
            </div>
            
            <!-- Implementar funcionamento -->
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-6 text-center">
                <a href="#" class="iconesInicio "><span class="fas fa-file-alt fa-5x"> </span>
                    <h4>Protocolos</h4></a>
            </div>
        
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.default', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>