<?php $__env->startSection('conteudo'); ?>

    <?php if($errors->any()): ?>
        <div class="alert alert-danger collapse in" id="collapseExample" xmlns="http://www.w3.org/1999/html">
            <h4 class="alert-heading">Os seguintes erros foram encontrados:</h4>
            <ul>
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li> <?php echo e($error); ?> </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
            <hr>
            <p class="mb-0">Por favor, verifique e tente novamente.</p>
            <p ><a class="btn btn-primary" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="true" aria-controls="collapseExample">
                    Fechar
                </a>
            </p>
        </div>
    <?php endif; ?>

    <?php echo e(Form::open(['id' => 'cadastra_beneficiario', 'class' => 'form-no-submit'])); ?>

    <div class="panel panel-primary">
        <div class="panel-heading">Dados do Titular
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="Contrato">Contrato<span style="color:red">*</span></label>
                        <?php echo e(Form::text('cd_contrato',(isset($beneficiario['cd_contrato']) ? $beneficiario['cd_contrato'] : ""),["name" => "cd_contrato", "maxlength" => "10", "id" => "cd_contrato", "disabled"=>"disabled", 'class'=> ($errors->has("id") ? "form-control is-invalid" : "form-control")])); ?>

                        <?php echo e(Form::hidden('cd_contrato',(isset($beneficiario['cd_contrato']) ? $beneficiario['cd_contrato'] : ""),["name" => "cd_contrato", "id" => "cd_contrato"])); ?>

                        <?php echo e(Form::hidden('id_beneficiario',(isset($beneficiario['id_beneficiario']) ? $beneficiario['id_beneficiario'] : ""),["name" => "id_beneficiario", "id" => "id"])); ?>

                    </div>
                </div>                
                <div class="col-md-2 pull-right">
                    <div class="form-group">
                        <label for="status">Situação</label>
                        <?php echo e(Form::select('id_situacao', ['A' => 'Ativo', 'I' => 'Inativo'], (isset($beneficiario['id_situacao']) ? $beneficiario['id_situacao'] : 'A'),['class'=> ($errors->has("id_situacao") ? "form-control is-invalid" : "form-control"), 'id' => 'id_situacao'])); ?>

                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-2">
                    <div class="input-group">
                        <label for="cd_pessoa">Pessoa<span style="color:red">*</span></label>
                        <?php echo e(Form::text('cd_pessoa',(isset($beneficiario['cd_pessoa']) ? $beneficiario['cd_pessoa'] : ""),["name" => "cd_pessoa", 'disabled', "maxlength" => "10", "id" => "cd_pessoa", 'class'=> ($errors->has("cd_pessoa") ? "form-control is-invalid" : "form-control cd_pessoa")])); ?>

                        <?php echo e(Form::hidden('cd_pessoa',(isset($beneficiario['cd_pessoa']) ? $beneficiario['cd_pessoa'] : ""),["name" => "cd_pessoa", "id" => "cd_pessoa_disabled", "class" => "cd_pessoa"])); ?>

                        <span class="input-group-btn">
                            <button class="btn btn-info margin-top-25 " type="button" data-toggle="modal" data-target="#modal-pesquisa" id="open"><span class="fa fa-search"></span> </button>
                        </span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="nm_pessoa">Nome<span style="color:red">*</span></label>
                        <?php echo e(Form::text('nm_pessoa', (isset($beneficiario['nm_pessoa']) ? $beneficiario['nm_pessoa'] : "") ,["disabled", "id" => "nm_pessoa", 'class'=> ($errors->has("nm_pessoa") ? "form-control is-invalid" : "form-control") ])); ?>

                        <?php echo e(Form::hidden('nm_pessoa', (isset($beneficiario['nm_pessoa']) ? $beneficiario['nm_pessoa'] : "") ,["id" => "nm_pessoa_disabled" ])); ?>

                    </div>
                </div>
                <div class="col-md-2 pull-right">
                    <div class="form-group">
                        <label for="parentesco">Parentesco</label>
                        <?php echo e(Form::select('parentesco', arrayPadrao('parentesco'), (isset($beneficiario['parentesco']) ? $beneficiario['parentesco'] : 1),['class'=> ($errors->has("id_situacao") ? "form-control is-invalid" : "form-control"), 'id' => 'parentesco'])); ?>

                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="cd_plano">Plano<span style="color:red">*</span></label>
                        <?php echo e(Form::select('cd_plano', $planos, (isset($beneficiario['cd_plano']) ? trim($beneficiario['cd_plano']) : ""),['class'=>  "form-control", (isset($beneficiario['cd_plano']) && !empty($beneficiario['id_beneficiario'])) ? "disabled" : "",'id' => 'cd_plano'])); ?>

                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="cd_beneficiario">Código do cartão<span style="color:red">*</span></label>
                        <?php echo e(Form::text('cd_beneficiario',(isset($beneficiario['cd_beneficiario']) ? $beneficiario['cd_beneficiario']  : "") ,["maxlength" => "20", "id" => "cd_beneficiario", 'class'=> ($errors->has("cd_beneficiario") ? "form-control is-invalid" : "form-control")])); ?>

                    </div>
                </div>
                <?php if((session()->get('recurso.beneficiarios-adicionar'))): ?>
                    <?php echo e(Form::submit('Salvar',['class'=>"btn btn-success margin-botton-5 pull-right",'id'=>'salvar'])); ?>

                <?php endif; ?>
                <?php echo e(Form::close()); ?>

            </div>
            <div id="mensagem"></div>
            <?php if(!empty(Session::get('status'))): ?>
                <div class="alert alert-info" id="msg">
                    <?php echo e(Session::get('status')); ?>

                </div>
            <?php endif; ?>
            <?php if((isset($beneficiario['id_beneficiario']) && (!empty($beneficiario['id_beneficiario'])))): ?>
            <div class="panel panel-primary">
                <div class="panel-heading text-center">Cadastro de dependentes</div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped"  style="font-size: 14px">
                            <thead>
                            <tr>
                                <th>Código do cartão</th>
                                <th>Nome</th>
                                <th>Parentesco</th>
                                <th>Situação</th>
                                <th>Ação</th>
                            </tr>
                            </thead>
                            <tbody>

                            <?php if(!isset($dependente) || $dependente->IsEmpty()): ?>
                                <tr><td colspan="5">Sem resultados</td></tr>
                            <?php else: ?>
                                <?php $__currentLoopData = $dependente; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td class='text-center'><?php echo e($d->cd_beneficiario); ?></td>
                                        <td class='text-center'><?php echo e($d->nm_pessoa); ?></td>
                                        <td class='text-center'><?php echo e(arrayPadrao('parentesco')[$d->parentesco]); ?></td>
                                        <td class='text-center <?php echo e(($d->id_situacao == 'A' ? 'text-primary' : 'text-danger')); ?>'>
                                            <?php echo e(($d->id_situacao == 'A' ? 'Ativo' : 'Inativo')); ?>

                                        </td>
                                        <td class='text-center'>
                                            <a href="<?php echo e(route('beneficiarios/cadastro').'/'.$d->id_beneficiario); ?>" class='btn btn-primary btn-sm'>Editar</a>
                                            <button type='button' data-tabela="beneficiario" data-chave="id_beneficiario" data-valor="<?php echo e($d->id_beneficiario); ?>" class='<?php echo e(verficaPermissaoBotao('recurso.beneficiarios-excluir')); ?> btn btn-danger btn-sm btn-excluir'>Excluir</button>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <input type="button" id='salvar-dependente' style="display: none" title='Salvar dependente' value="Salvar dependente" class='btn btn-primary'>
        </div>
        <?php endif; ?>
    </div>

    <?php if((isset($beneficiario['id_beneficiario']) && (!empty($beneficiario['id_beneficiario'])))): ?>
        <button id='cadastra-dependente' title='Cadastrar um novo dependente' class='btn btn-primary'>Adicionar dependente</button>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('painel-modal'); ?>
    <?php echo $__env->make('pessoas.modal-pessoas', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.default', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>