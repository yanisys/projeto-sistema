@extends('layouts.default')

@section('conteudo')

    <div class="panel panel-default ">
        <div class="panel-body">
            {!! Form::open(["method" => "POST", "class" => "form-search"]) !!}
            <div class="container-fluid">
                <div class = row>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Nome do órgão de origem responsável pela informação</label>
                            {!! Form::text('orgao_origem',(!empty($_POST['orgao_origem']) ? $_POST['orgao_origem'] : ''),["name" => "orgao_origem", "maxlength" => "30" ,"placeholder" => "Digite o nome do órgão responsável",'class'=>'form-control']) !!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Sigla do órgão de origem responsável pela informação</label>
                            {!! Form::text('sigla_orgao',(!empty($_POST['sigla_orgao']) ? $_POST['sigla_orgao'] : ''),["name" => "sigla_orgao", "maxlength" => "6", "placeholder" => "Digite a sigla do órgão responsável",'class'=>'form-control']) !!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>CGC/CPF do prestador ou órgão público responsável pela informação</label>
                            {!! Form::text('cgc_cpf',(!empty($_POST['cgc_cpf']) ? $_POST['cgc_cpf'] : ''),["name" => "cgc_cpf", "maxlength" => "14","placeholder" => "CGC/CPF",'class'=>'form-control']) !!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Nome do órgão de saúde destino do arquivo</label>
                            {!! Form::text('orgao_destino',(!empty($_POST['orgao_destino']) ? $_POST['orgao_destino'] : ''),["name" => "orgao_destino", "placeholder" => "Digite o nome do órgão destino",'class'=>'form-control']) !!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Indicador do órgão destino</label>
                            {{ Form::select('indicador', ['M' => 'MUNICIPAL', 'E' => 'ESTADUAL'], (isset($_POST['indicador']) ? $_POST['indicador'] : "E"),['class'=> ($errors->has("indicador") ? "form-control is-invalid" : "form-control"), 'id' => 'indicador']) }}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label>Mês de competência</label>
                            {{  Form::select('mes_competencia', arrayPadrao('mes'), (isset($_POST['mes_competencia']) ? $_POST['mes_competencia'] : date('m')),['class'=> ($errors->has("mes_competencia") ? "form-control is-invalid" : "form-control"), 'id' => 'mes_competencia']) }}
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label>Ano de competência</label>
                            {{  Form::select('ano_competencia', [date('Y') => date('Y'),date('Y') - 1 => date('Y') - 1,date('Y') - 2 => date('Y') - 2,date('Y') - 3 => date('Y') - 3], (isset($_POST['ano_competencia']) ? $_POST['ano_competencia'] : "2"),['class'=> ($errors->has("ano_competencia") ? "form-control is-invalid" : "form-control"), 'id' => 'ano_competencia']) }}
                        </div>
                    </div>
                </div>
            </div>
            <input type="hidden" name="opcao_bpa" id="opcao_bpa">
            <div class="col-sm-4">
                {!!  Form::submit('Gerar arquivo',['id' => 'gerar_arquivo_bpa', 'class'=>"btn btn-default margin-top-25"]) !!}
                {!!  Form::submit('Controle de remessa',['id' => 'gerar_relatorio_controle_bpa', 'class'=>"btn btn-default margin-top-25"]) !!}
                @if(get_config(3,session()->get('estabelecimento')) === 'C')
                    {!!  Form::submit('Relatório',['id' => 'gerar_relatorio_bpa', 'class'=>"btn btn-default margin-top-25"]) !!}
                @endif
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@endsection