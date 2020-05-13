<?php $__env->startSection('conteudo'); ?>

    <div class="panel panel-default ">
        <div class="panel-body">
            <?php echo Form::open(["method" => "POST", "class" => "form-search", "target" => "_blank"]); ?>

            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-3">
                        <label>Produto</label>
                        <?php echo Form::text('nm_produto',(!empty($_REQUEST['nm_produto']) ? $_REQUEST['nm_produto'] : ""),["name" => "nm_produto", "placeholder" => "Digite o nome do produto",'class'=>'form-control']); ?>

                    </div>
                    <div class="col-sm-3">
                        <label for="cd_sala">Estoque</label>
                        <?php echo e(Form::select('cd_sala', $sala, (!empty($_REQUEST['cd_sala']) ? $_REQUEST['cd_sala'] : 0),['class'=> "form-control", "id" => "cd_sala"])); ?>

                    </div>
                    <div class="col-sm-3">
                        <label>Lote</label>
                        <?php echo Form::text('lote',(!empty($_REQUEST['lote']) ? $_REQUEST['lote'] : ""),["name" => "lote", "placeholder" => "Digite o lote",'class'=>'form-control']); ?>

                    </div>
                    <div class="col-sm-3">
                        <label>Validade</label>
                        <?php echo Form::text('dt_validade',(!empty($_REQUEST['dt_validade']) ? $_REQUEST['dt_validade'] : ""),["name" => "dt_validade", "placeholder" => "Digite a validade",'class'=>'form-control mask-data']); ?>

                    </div>
                </div>
                <div class="row">
                    <div class="row">&nbsp;</div>
                    <div class="col-sm-2">
                        <label>Agrupar por estoque</label>
                        <?php echo Form::checkbox('agrupar_estoque',1,["name" => "agrupar_estoque", "placeholder" => "Digite o lote",'class'=>'form-control']); ?>

                    </div>
                    <div class="col-sm-2">
                        <label>Agrupar por lote</label>
                        <?php echo Form::checkbox('agrupar_lote',1,["name" => "agrupar_lote", "placeholder" => "Digite o lote",'class'=>'form-control']); ?>

                    </div>
                    <div class="col-sm-2">
                        <label>Agrupar por validade</label>
                        <?php echo Form::checkbox('agrupar_validade',1,["name" => "agrupar_validade", "placeholder" => "Digite o lote",'class'=>'form-control']); ?>

                    </div>
                    <div class="col-sm-1 pull-right">
                        <?php echo Form::submit('Buscar',['class'=>"btn btn-default margin-top-25"]); ?>

                    </div>
                </div>
            </div>
            <?php echo Form::close(); ?>

        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.default', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>