<?php $__env->startSection('conteudo'); ?>
    <div class="panel panel-default ">
        <div class="panel-body">
            <?php echo Form::open(["method" => "GET", "class" => "form-search"]); ?>

            <div class="container-fluid">
                <div class="col-sm-3">
                    <label>Nome</label>
                    <?php echo Form::text('nm_produto_divisao',(!empty($_REQUEST['nm_produto_divisao']) ? $_REQUEST['nm_produto_divisao'] : ""),["name" => "nm_produto_divisao", "id" => "busca_produto_divisao", "placeholder" => "Digite o nome da divisão",'class'=>'form-control']); ?>

                </div>
                <div class="col-sm-3 ">
                    <?php echo Form::submit('Buscar',['class'=>"btn btn-default margin-top-25"]); ?>

                </div>
            </div>
            <?php echo Form::hidden('tab_divisao',(!empty($_REQUEST['tab_divisao']) ? $_REQUEST['tab_divisao'] : 0),['id'=>'tab_divisao']); ?>

            <?php echo Form::hidden('tab_grupo',(!empty($_REQUEST['tab_grupo']) ? $_REQUEST['tab_grupo'] : 0),['id'=>'tab_grupo']); ?>

            <?php echo Form::close(); ?>

        </div>
    </div>
    <div class="panel panel-default ">
        <div class="panel-body">
            <div class="col-md-4"><h3>Divisão</h3></div>
            <div class="col-md-4"><h3>Grupo</h3></div>
            <div class="col-md-4"><h3>Sub-Grupo</h3></div>
            <div class="col-md-12 lista-produtos">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <tbody>
                        <tr>
                            <td  colspan="12" style="text-align: center; vertical-align: middle;">
                                <button type="button" title="Clique aqui para adicionar uma nova Divisão" data-toggle="modal" data-target="#modal-grupo-produto" data-titulo="Divisão" data-nome="divisao" data-mestre="" data-nome-mestre="" class="<?php echo e(verficaPermissaoBotao('recurso.materiais/produto-adicionar')); ?> btn btn-success pull-left btn-modal-grupo-produto btn-xs"><span class="fas fa-plus fa-xs"></span> Divisão</button>
                            </td>
                        </tr>
                        <?php if(isset($divisao) && !$divisao->IsEmpty()): ?>
                            <?php $__currentLoopData = $divisao; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $keyd=>$d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td colspan="4"  width="33%" style="text-align: left; vertical-align: middle;">
                                        <button id="btn_tr_dv_<?php echo e($keyd+1); ?>" type="button" title="<?php echo e($_REQUEST['tab_divisao'] === $keyd+1 ? 'Minimizar' : 'Expandir'); ?>" data-valor="<?php echo e($keyd+1); ?>" class="btn btn-primary btn-xs btn-zoom-divisao"><span class="fas fa-<?php echo e($_REQUEST['tab_divisao'] === $keyd+1 ? 'minus' : 'plus'); ?>-square fa-xs"></span></button>
                                        <?php echo e($d->nm_produto_divisao); ?>

                                        <button data-toggle="modal" data-superior="divisao" data-target="#modal-grupo-produto" title="Clique para adicionar um novo Grupo à Divisão <?php echo e(title_case($d->nm_produto_divisao)); ?>" data-titulo="Grupo" data-nome="grupo" data-nome-mestre="<?php echo e(title_case($d->nm_produto_divisao)); ?>" data-mestre="<?php echo e($d->cd_produto_divisao); ?>"  class="<?php echo e(verficaPermissaoBotao('recurso.materiais/grupo-adicionar')); ?> btn btn-success btn-xs btn-modal-grupo-produto pull-right"><span class="fas fa-plus fa-xs"></span> Grupo</button>
                                        <button data-toggle="modal" data-target="#modal-grupo-produto" title="Editar Divisão" data-valor="<?php echo e($d->nm_produto_divisao); ?>" data-titulo="Divisão" data-nome="divisao" data-id="<?php echo e($d->cd_produto_divisao); ?>" class="<?php echo e(verficaPermissaoBotao('recurso.materiais/grupo-editar')); ?> btn btn-primary btn-xs  btn-modal-grupo-produto pull-right"><span class="fas fa-edit fa-xs"></span></button>
                                        <button title="Remover Divisão" data-tabela="produto_divisao" data-chave="cd_produto_divisao" data-valor="<?php echo e($d->cd_produto_divisao); ?>" class="<?php echo e(verficaPermissaoBotao('recurso.materiais/grupo-excluir')); ?> btn btn-danger btn-xs btn-excluir pull-right"><span class="fas fa-trash fa-xs"></span></button>
                                    </td>
                                    <td id="tr_dv_<?php echo e($keyd+1); ?>"class="tr_dv" colspan="4" style="display: <?php echo e($_REQUEST['tab_divisao'] === $keyd+1 ? 'block' : 'none'); ?>">
                                            <div class="table-responsive">
                                                <table class="table table-hover">
                                                    <tbody>
                                                        <?php $__currentLoopData = $grupo; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $keyg=>$g): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <?php if($g->cd_produto_divisao === $d->cd_produto_divisao): ?>
                                                                <tr>
                                                                    <td width="50%" style="text-align: left; vertical-align: middle;">
                                                                        <?php echo e($g->nm_produto_grupo); ?>

                                                                        <button data-toggle="modal" data-target="#modal-grupo-produto" title="Clique para adicionar um novo sub-grupo ao grupo <?php echo e(title_case($g->nm_produto_grupo)); ?>" data-superior="grupo" data-titulo="Sub-Grupo" data-nome="sub-grupo" data-nome-mestre="<?php echo e(title_case($g->nm_produto_grupo)); ?>" data-mestre="<?php echo e($g->cd_produto_grupo); ?>"  class="<?php echo e(verficaPermissaoBotao('recurso.materiais/produto-adicionar')); ?> btn btn-success btn-xs btn-modal-grupo-produto pull-right"><span class="fas fa-plus fa-xs"></span> Sub-Grupo</button>
                                                                        <button id="btn_tr_gr_<?php echo e($keyg+1); ?>" type="button" title="<?php echo e($_REQUEST['tab_grupo'] === $keyg+1? 'Minimizar' : 'Expandir'); ?>" data-valor="<?php echo e($keyg+1); ?>" class="btn btn-primary pull-left btn-xs btn-zoom-grupo"><span class="fas fa-<?php echo e($_REQUEST['tab_grupo'] === $keyg+1 ? 'minus' : 'plus'); ?>-square fa-xs"></span></button>
                                                                        <button data-toggle="modal" data-target="#modal-grupo-produto" title="Editar Grupo" data-valor="<?php echo e($g->nm_produto_grupo); ?>" data-nome-mestre="<?php echo e(title_case($d->nm_produto_divisao)); ?>" data-titulo="Grupo" data-nome="grupo" data-id="<?php echo e($g->cd_produto_grupo); ?>" class="<?php echo e(verficaPermissaoBotao('recurso.materiais/grupo-editar')); ?> btn btn-primary btn-xs  btn-modal-grupo-produto pull-right"><span class="fas fa-edit fa-xs"></span></button>
                                                                        <button title="Remover Grupo" data-tabela="produto_grupo" data-chave="cd_produto_grupo" data-valor="<?php echo e($g->cd_produto_grupo); ?>" class="<?php echo e(verficaPermissaoBotao('recurso.materiais/grupo-excluir')); ?> btn btn-danger btn-xs btn-excluir pull-right"><span class="fas fa-trash fa-xs"></span></button>
                                                                    </td>
                                                                    <td id="tr_gr_<?php echo e($keyg+1); ?>" class="tr_gr" style="text-align: left; vertical-align: middle; display: <?php echo e($_REQUEST['tab_grupo'] === $keyg+1 ? 'block' : 'none'); ?>">
                                                                        <?php if(isset($sub_grupo) && !$sub_grupo->IsEmpty()): ?>
                                                                            <div class="table-responsive">
                                                                                <table class="table table-hover">
                                                                                    <tbody>
                                                                                    <?php $__currentLoopData = $sub_grupo; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $keys => $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                                                                     <?php if($g->cd_produto_grupo === $s->cd_produto_grupo): ?>
                                                                                            <tr id="sg_tr_<?php echo e($keys+1); ?>" data-linha="<?php echo e($keys+1); ?>">
                                                                                                <td style="text-align: left; vertical-align: middle; width: 100px">
                                                                                                    <?php echo e($s->nm_produto_sub_grupo); ?>

                                                                                                    <button data-toggle="modal" data-target="#modal-grupo-produto" title="Editar Sub-Grupo" data-valor="<?php echo e($s->nm_produto_sub_grupo); ?>" data-nome-mestre="<?php echo e(title_case($g->nm_produto_grupo)); ?>" data-titulo="Sub-Grupo" data-nome="sub-grupo" data-id="<?php echo e($s->cd_produto_sub_grupo); ?>" class="<?php echo e(verficaPermissaoBotao('recurso.materiais/grupo-editar')); ?> btn btn-primary btn-xs  btn-modal-grupo-produto pull-right"><span class="fas fa-edit fa-xs"></span></button>
                                                                                                    <button title="Remover Sub-Grupo" data-tabela="produto_sub_grupo" data-chave="cd_produto_sub_grupo" data-valor="<?php echo e($s->cd_produto_sub_grupo); ?>" class="<?php echo e(verficaPermissaoBotao('recurso.materiais/grupo-excluir')); ?> btn btn-danger btn-xs btn-excluir pull-right"><span class="fas fa-trash fa-xs"></span></button>
                                                                                                </td>
                                                                                            </tr>
                                                                                        <?php endif; ?>
                                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                                    </tbody>
                                                                                </table>
                                                                            </div>
                                                                        <?php endif; ?>
                                                                    </td>
                                                                </tr>
                                                            <?php endif; ?>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </tbody>
                                                </table>
                                            </div>

                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-md-6">
            <div class='pull-right'><?php if(isset($lista)): ?><?php echo e($lista->links()); ?><?php endif; ?></div>
        </div>
    </div>
    <script src="<?php echo e(js_versionado('produtos.js')); ?>" defer></script>

    <div id="modal-grupo-produto" class="modal fade"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div id="dialog-grupo-produto" class="modal-dialog">
            <div  class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="titulo-modal-grupo-produto"></h3>
                </div>
                <div class="panel-body">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="nome-cadastro-grupo">Informe o nome<span style="color:red">*</span></label>
                            <input type="text" class="form-control" id="nome-cadastro-grupo">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger margin-top-zero pull-left" data-dismiss="modal">Sair</button>
                    <button type="button" id="btn-salvar-grupo-produto" class="btn btn-success pull-right">Salvar</button>
                </div>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>



<?php echo $__env->make('layouts.default', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>