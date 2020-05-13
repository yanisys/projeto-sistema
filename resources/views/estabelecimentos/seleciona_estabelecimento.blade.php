
@extends('layouts.default')

@section('conteudo')
    <div class="col-md-6 col-md-offset-3" id="seleciona-estabelecimento">
        {{ Form::open(['id' => 'cadastra-estabelecimento', 'class' => 'form-no-submit']) }}
        <div class="panel-body">
            <div class="col-md-12">
                <div class="form-group">
                    <label for="classificacao"><h4>Selecione um estabelecimento</h4></label>
                    {{  Form::select('cd_estabelecimento', $estabelecimentos, "",['class'=>  "form-control", 'id' => 'cd_estabelecimento']) }}
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    {{ Form::submit('Selecionar',['class'=>"btn btn-primary margin-top-zero", 'id' => 'salvar-estabelecimento']) }}
                </div>
            </div>
        </div>
        {{ Form::close() }}
    </div>
@endsection
