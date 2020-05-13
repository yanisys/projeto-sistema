@extends('layouts.default')

@section('conteudo')

    @if ($errors->any())
        <div class="alert alert-danger collapse in" id="collapseExample">
            <h4 class="alert-heading">Os seguintes erros foram encontrados:</h4>
            <ul>
                @foreach ($errors->all() as $error)
                    <li> {{ $error }} </li>
                @endforeach
            </ul>
            <hr>
            <p class="mb-0">Por favor, verifique e tente novamente.</p>
            <p ><a class="btn btn-primary" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="true" aria-controls="collapseExample">
                    Fechar
                </a></p>
        </div>
    @endif

    {{ Form::open(['id' => 'cadastra-estabelecimento', 'class' => 'form-no-submit']) }}
    <div class="panel panel-primary">
        <div class="panel-heading">
            @if((session()->get('recurso.estabelecimentos-editar')))
                <a href="{{ route('estabelecimentos/cadastro') }}" class="btn btn-primary pull-right margin-top-10">Novo</a>
            @endif
                <h4>Dados do estabelecimento</h4>
        </div>
        <div class="panel-body">
            <div class="tab-content">
                <div class="tab-pane fade in active" id="tab_info">
                    <div class="row">
                        <div class="form-group">
                            {{ Form::hidden('cd_estabelecimento',(isset($estabelecimento['cd_estabelecimento']) ? $estabelecimento['cd_estabelecimento'] : ""),["name" => "cd_estabelecimento", "id" => "cd_estabelecimento"]) }}
                        </div>
                        <div class="col-md-2">
                            <div class="input-group">
                                <label for="cd_pessoa">Pessoa<span style="color:red">*</span></label>
                                {{ Form::text('cd_pessoa',(isset($estabelecimento['cd_pessoa']) ? $estabelecimento['cd_pessoa'] : ""),["name" => "cd_pessoa", 'disabled', "maxlength" => "10", "id" => "cd_pessoa", 'class'=> ($errors->has("cd_pessoa") ? "form-control is-invalid" : "form-control cd_pessoa")]) }}
                                {{ Form::hidden('cd_pessoa',(isset($estabelecimento['cd_pessoa']) ? $estabelecimento['cd_pessoa'] : ""),["name" => "cd_pessoa", "id" => "cd_pessoa_disabled", "class" => "cd_pessoa" ]) }}
                                <span class="input-group-btn">
                            <button type="button" class="btn btn-info margin-top-25 btn-modal-pessoa"
                                {{ (isset($estabelecimento['cd_pessoa']) ? 'data-modo=editar data-cd-pessoa='.$estabelecimento['cd_pessoa'] : "data-modo=pesquisar") }}>
                                <span class="fa fa-search"></span> </button>
                        </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nm_estabelecimento">Nome do Estabelecimento<span style="color:red">*</span></label>
                                {{ Form::text('nm_estabelecimento', (isset($estabelecimento['nm_estabelecimento']) ? $estabelecimento['nm_estabelecimento'] : "") ,["maxlength" => "60", "disabled", "id" => "nm_estabelecimento", 'class'=> ($errors->has("nm_estabelecimento") ? "form-control is-invalid" : "form-control") ]) }}
                                {{ Form::hidden('nm_estabelecimento', (isset($estabelecimento['nm_estabelecimento']) ? $estabelecimento['nm_estabelecimento'] : "") ,["id" => "nm_estabelecimento_disabled" ]) }}
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="tp_estabelecimento">Tipo<span style="color:red">*</span></label>
                                {{  Form::select('tp_estabelecimento', arrayPadrao('tipo_estabelecimento'), (isset($estabelecimento['tp_estabelecimento']) ? trim($estabelecimento['tp_estabelecimento']) : ""),['class'=>  "form-control", 'id' => 'tp_estabelecimento']) }}
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="status">Situação<span style="color:red">*</span></label>
                                {{  Form::select('status', arrayPadrao('situacao'), (isset($estabelecimento['status']) ? $estabelecimento['status'] : "A"),['class'=>  "form-control", 'id' => 'status']) }}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="cnes">Cnes<span style="color:red">*</span></label>
                                {{  Form::text('cnes', (isset($estabelecimento['cnes']) ? trim($estabelecimento['cnes']) : ""),['maxlength' => '9','class'=>  "form-control mask-numeros-11", 'id' => 'cnes']) }}
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <div class="panel panel-default">
                            <div class="panel-heading"><h4>Selecione os tipos de planos permitidos para o estabelecimento</h4></div>
                            <div class="panel-body">
                                <?php if(isset($estabelecimento['tp_plano']))
                                    $planos_permitidos = str_split($estabelecimento['tp_plano']) ?>
                                @foreach(arrayPadrao('tipo_plano') as $k => $r)
                                    <?php $existe = false; ?>
                                    @if(isset($planos_permitidos))
                                        @if(in_array($k,$planos_permitidos))
                                            <?php $existe = true?>
                                        @endif
                                    @endif
                                    <div class="col-md-3">
                                        <input id='checkbox-{{ $k }}' type="checkbox" name='checkbox-{{ $k }}' value='{{ $k }}'{{($existe) ? ' checked' : ''}}/>
                                        <label for='checkbox-{{ $k }}'>{{ $r }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel-footer fixed-panel-footer" >
            @if((session()->get('recurso.estabelecimentos-editar')))
                {{ Form::submit('Salvar',['class'=>"btn btn-success pull-right", 'id' => 'salvar-estabelecimento']) }}
            @endif
        </div>

    </div>

    {{ Form::close() }}
    @include('pessoas.modal-pessoas')
@endsection

