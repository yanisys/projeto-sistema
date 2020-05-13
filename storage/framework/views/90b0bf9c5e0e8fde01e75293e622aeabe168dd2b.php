<?php $__env->startSection('conteudo-full'); ?>

    <div class="panel panel-default ">
        <div class="panel-body">
            <?php echo Form::open(["method" => "GET", "class" => "form-search"]); ?>

            <div class="row">
                <div class="col-sm-2">
                    <label>Paciente</label>
                    <?php echo Form::text('nm_pessoa',(!empty($_REQUEST['nm_pessoa']) ? $_REQUEST['nm_pessoa'] : ""),["name" => "nm_pessoa", "placeholder" => "Digite o nome do paciente",'class'=>'form-control']); ?>

                </div>
                <div class="col-sm-2">
                    <label>Data nasc.</label>
                    <?php echo Form::text('dt_nasc',(!empty($_REQUEST['dt_nasc']) ? $_REQUEST['dt_nasc'] :  '' ),["name" => "dt_nasc", 'placeholder' => 'dd/mm/aaaa', 'class'=>'form-control mask-data']); ?>

                </div>
                <div class="col-sm-2">
                    <label>Nome do Médico</label>
                    <?php echo Form::text('nm_medico',(!empty($_REQUEST['nm_medico']) ? $_REQUEST['nm_medico'] : ""),["name" => "nm_medico", "placeholder" => "Digite o nome do médico",'class'=>'form-control']); ?>

                </div>
                <div class="col-sm-2">
                    <label for="id_situacao">Situação</label>
                    <?php echo e(Form::select('status', arrayPadrao('situacao_prontuario'), (!empty($_REQUEST['status']) ? $_REQUEST['status'] : "T"),['class'=> "form-control", "id" => "status"])); ?>

                </div>
                <div class="col-sm-2">
                    <label for="id_situacao">Classificação de risco</label>
                    <?php echo e(Form::select('classificacao', arrayPadrao('classificar_risco','T'), (!empty($_REQUEST['classificacao']) ? $_REQUEST['classificacao'] : 'T'),['class'=> "form-control", "id" => "classificacao"])); ?>

                </div>
                <div class="col-sm-2">
                    <label for="motivo_alta">Motivo da alta</label>
                    <?php echo e(Form::select('motivo_alta', $motivo_alta, (!empty($_REQUEST['motivo_alta']) ? $_REQUEST['motivo_alta'] : 'T'),['class'=> "form-control", "id" => "motivo_alta"])); ?>

                </div>
            </div>
            <div class="row">
                <div class="col-sm-2">
                    <label for="filtro">Filtro intervalo de datas</label>
                    <?php echo e(Form::select('filtro', $intervalos_datas, (!empty($_REQUEST['filtro']) ? $_REQUEST['filtro'] : 'am'),['class'=> "form-control", "id" => "filtro"])); ?>

                </div>
                <div class="col-sm-2">
                    <label>Data Inicial</label>
                    <?php echo Form::text('dt_ini',(!empty($_REQUEST['dt_ini']) ? $_REQUEST['dt_ini'] :  date('d/m/Y') ),["name" => "dt_ini", 'placeholder' => 'dd/mm/aaaa', 'class'=>'form-control mask-data']); ?>

                </div>
                <div class="col-sm-1">
                    <label>Hora</label>
                    <?php echo Form::text('hr_ini',(!empty($_REQUEST['hr_ini']) ? $_REQUEST['hr_ini'] :  '' ),["name" => "hr_ini",'placeholder' => 'hh:mm', 'class'=>'form-control mask-hora']); ?>

                </div>
                <div class="col-sm-2">
                    <label>Data Final</label>
                    <?php echo Form::text('dt_fim',(!empty($_REQUEST['dt_fim']) ? $_REQUEST['dt_fim'] : date('d/m/Y')),["name" => "dt_fim", 'placeholder' => 'dd/mm/aaaa', 'class'=>'form-control mask-data']); ?>

                </div>
                <div class="col-sm-1">
                    <label>Hora</label>
                    <?php echo Form::text('hr_fim',(!empty($_REQUEST['hr_fim']) ? $_REQUEST['hr_fim'] :  '' ),["name" => "hr_fim", 'placeholder' => 'hh:mm','class'=>'form-control mask-hora']); ?>

                </div>
                <div class="col-sm-2">
                    <label>Cid</label>
                    <?php echo Form::text('cid',(!empty($_REQUEST['cid']) ? $_REQUEST['cid'] : ""),["name" => "cid", "placeholder" => "Digite o nome da Cid",'class'=>'form-control']); ?>

                </div>
                <div class="col-sm-1 ">
                    <?php echo Form::submit('Buscar',['id' => 'relatorio_prontuario_tela', 'class' => 'btn btn-primary margin-top-25']); ?>

                </div>
                <div class="col-sm-1 ">
                    <?php echo Form::submit('Relatório',['id' => 'relatorio_prontuario_pdf', 'class' => 'btn btn-primary margin-top-25']); ?>

                </div>
                <?php echo Form::hidden('tp_relatorio','',['id' => 'tp_relatorio_prontuario']); ?>

            </div>
            <?php echo Form::close(); ?>

        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover table-striped">
            <thead>
            <tr>
                <th>Data/ Hora</th>
                <th>Paciente</th>
                <th>Sexo</th>
                <th>Médico</th>
                <th>Cid</th>
                <th>Motivo Alta</th>
                <th>Status</th>
                <th class='text-center' width="150px">Ação</th>
            </tr>
            </thead>
            <tbody>

            <?php if(!isset($lista) || $lista->IsEmpty()): ?>
                <tr><td colspan="6">Sem resultados</td></tr>
            <?php else: ?>
                <?php $__currentLoopData = $lista; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prontuario): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e(formata_data_hora($prontuario->created_at)); ?></td>
                        <td><?php echo e($prontuario->nm_pessoa); ?></td>
                        <td class='text-center'>
                            <?php if(($prontuario->id_sexo == 'F')): ?>
                                <span class='fa fa-female' style='color:deeppink;'></span>
                            <?php else: ?>
                                <span class='fa fa-male' style='color:blue;'></span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo e($prontuario->nm_medico); ?></td>
                        <td><?php echo e((isset($prontuario->nm_cid)) ? $prontuario->nm_cid : ""); ?></td>
                        <td><?php echo e((isset($prontuario->motivo_alta) && $prontuario->motivo_alta !== 0) ? arrayPadrao('motivo_alta')[$prontuario->motivo_alta] : ''); ?></td>
                        <td><?php echo e(arrayPadrao('status_prontuario')[$prontuario->status]); ?></td>
                        <td class='text-center'>
                            <a href="<?php echo e(route('atendimentos/atendimento-medico').'/'.$prontuario->cd_prontuario); ?>" class=<?php echo e(verficaPermissaoBotao('recurso.prontuarios-editar')); ?> 'btn btn-primary btn-sm'>Editar</a>
                            <button type='button' data-tabela="prontuarios" data-chave="cd_prontuario" data-valor="<?php echo e($prontuario->cd_prontuario); ?>" class='<?php echo e(verficaPermissaoBotao('recurso.prontuarios-excluir')); ?> btn btn-danger btn-sm btn-excluir'>Excluir</button>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class='pull-left'><b><?php if(isset($lista)): ?><?php echo e("Total de registros: ". $lista->total()); ?> <?php endif; ?></b></div>
            <div class='pull-right'><?php if(isset($lista)): ?><?php echo e($lista->links()); ?><?php endif; ?></div>
        </div>
    </div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.default', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>