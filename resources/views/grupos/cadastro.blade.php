
@extends('layouts.default')

@section('conteudo')
    <div class="col-sm-offset-1 col-sm-10">

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

        {{ Form::open(['id' => 'form-cadastra-grupo', 'class' => 'form-no-submit']) }}
        <div class="panel panel-primary">
            <div class="panel-heading">
                @if((session()->get('recurso.grupos-editar')))
                    <a href="{{ route('grupos/cadastro') }}" class="btn btn-primary pull-right margin-top-10">Novo</a>
                @endif
                <h4>Dados do Grupo de Operadores</h4>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="cd_grupo_op">Código<span style="color:red">*</span></label>
                            {{ Form::text('cd_grupo_op',(isset($grupo['cd_grupo_op']) ? $grupo['cd_grupo_op'] : ""),["name" => "cd_grupo_op", "maxlength" => "10", "id" => "cd_grupo_op",  "disabled" => "disabled", 'class'=> ($errors->has("cd_grupo_op") ? "form-control is-invalid" : "form-control")]) }}
                            {{ Form::hidden('cd_grupo_op',(isset($grupo['cd_grupo_op']) ? $grupo['cd_grupo_op'] : ""),["name" => "cd_grupo_op", "id" => "cd_grupo_op"]) }}
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="form-group">
                            <label for="nm_grupo_op">Nome<span style="color:red">*</span></label>
                            {{ Form::text('nm_grupo_op', (isset($grupo['nm_grupo_op']) ? $grupo['nm_grupo_op'] : "") ,["maxlength" => "40", "id" => "nm_grupo_op", 'class'=> ($errors->has("nm_grupo_op") ? "maiusculas form-control is-invalid" : "maiusculas form-control") ]) }}
                        </div>
                    </div>
                </div>

                @if(!isset($permissoes) || $permissoes->IsEmpty())
                    <p>Sem resultados</p>
                @else
                    <div class="panel-primary" id="accordion">
                        <h3>Permissões</h3>
                        <div class="panel panel-default">
                            @foreach($grupos as $g)
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse-{{$g}}">
                                            <b>{{title_case($g)}}</b>
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapse-{{$g}}" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        @foreach($permissoes as $p)
                                            <?php $igual = strpos( $p->obj_recurso, $g ); ?>
                                            @if($igual===0)
                                                <li class="list-group-item">
                                                {{ title_case($p->ds_recurso) }}
                                                    <div class="material-switch pull-right">
                                                        <input id='checkbox-{{ $p->cd_recurso }}' type="checkbox" name='checkbox-{{ $p->cd_recurso }}' value='{{ $p->cd_recurso }}' {{($p->permitido > 0) ? 'checked' : ''}}/>
                                                        <label for='checkbox-{{ $p->cd_recurso }}' class="label-primary"></label>
                                                    </div>
                                                </li>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
            <div class="panel-footer fixed-panel-footer" >
                <span>Atenção: As alterações nas permissões só serão aplicadas ao usuário após novo login. </span>
                @if((session()->get('recurso.grupos-editar')))
                    {{ Form::submit('Salvar',['class'=>"btn btn-success pull-right"]) }}
                @endif
            </div>
        </div>

        {{ Form::close() }}

    </div>

@endsection