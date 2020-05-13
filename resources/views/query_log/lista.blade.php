@extends('layouts.default')

@section('conteudo')



    <div class="panel panel-default ">
        <div class="panel-body">
            {!! Form::open(["method" => "POST", "class" => "form-search"]) !!}
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-4">
                        <label for="busca_user">Nome</label>
                        {!! Form::text('id_user',(!empty($_REQUEST['id_user']) ? $_REQUEST['id_user'] : ""),["name" => "id_user", "id" => "busca_user", "placeholder" => "Digite o código do usuário",'class'=>'form-control']) !!}
                    </div>

                    <div class="col-sm-4">
                        <label for="tabelas">Tabela</label>
                        <select name="tabelas" id="tabelas" class="form-control" onchange="descreverTabelas()">
                            <option value="" disabled selected>Selecione uma tabela...</option>

                            @foreach($tabelas as $tabela)
                                @foreach($tabela as $nomeTabela)
                                    <option value="{{$nomeTabela}}">{{$nomeTabela}}</option>
                                @endforeach
                            @endforeach

                        </select>
                    </div>


                    <div class="col-sm-4">
                        <label for="tipo">Tipo de Operação</label>
                        <select name="tipo" id="tipo" class="form-control">
                            <option value="I">Insert</option>
                            <option value="U">Update</option>
                            <option value="D">Delete</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-4 margin-top-25">
                        <label for="dataInicial">Data Inicial</label>
                        <input type="date" class="form-control"
                               value="{{(!empty($_REQUEST['dataInicial']) ? $_REQUEST['dataInicial'] : "")}}"
                               name="dataInicial" id="dataInicial">
                    </div>
                    <div class="col-sm-4 margin-top-25">
                        <label for="dataFinal">Data Final</label>
                        <input type="date" class="form-control"
                               value="{{(!empty($_REQUEST['dataFinal']) ? $_REQUEST['dataFinal'] : "")}}"
                               name="dataFinal"
                               id="dataFinal">
                    </div>
                </div>

                <div id="div-log" class="row ">

                    @for ($i = 0; $i < 4; $i++)

                        <div class="linha-log margin-top-25">
                            <div class="col-sm-4">
                                <label for="campo{{$i}}">Campo</label>
                                <select name="campo{{$i}}" id="campo{{$i}}" class="form-control campos">
                                    <option value="" disabled> Selecione a tabela para ver os campos...</option>
                                </select>
                            </div>

                            <div class="col-sm-4">
                                <label for="operacao{{$i}}">Operação</label>
                                <select name="operacao{{$i}}" id="operacao{{$i}}" class="form-control">
                                    <option value=""></option>
                                    <option value="=">Igual</option>
                                    <option value=">">Maior que</option>
                                    <option value="<">Menor que</option>
                                    <option value=">=">Maior ou igual a</option>
                                    <option value="<=">Menor ou igual a</option>
                                    <option value="!=">Diferente de</option>
                                </select>
                            </div>

                            <div class="col-sm-4 ">
                                <label for="valor{{$i}}">Valor</label>
                                <input type="text" id="valor{{$i}}" name="valor{{$i}}" class="form-control">
                            </div>
                        </div>

                    @endfor


                </div>


                <div class="row">
                    <div class="col-sm-4">
                        {!!  Form::submit('Buscar',['class'=>"btn btn-default margin-top-25"]) !!}
                    </div>
                </div>


            </div>
            {!! Form::close() !!}
        </div>
    </div>



    <div class="table-responsive">
        <table class="table table-bordered table-hover table-striped">
            <thead>
            <tr>
                <th>Usuário</th>
                <th>Data</th>
                <th>Tipo</th>
                <th>Tabela</th>
                <th>Query</th>
                <th>Duração</th>
            </tr>
            </thead>
            <tbody>

            @if(!isset($lista) || $lista->IsEmpty())
                <tr>
                    <td>&nbsp</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            @else
                @foreach($lista as $query)
                    <tr>
                        <td class='text-center'>{{ $query->id_user }}</td>
                        <td class='text-center'>{{ $query->data }}</td>
                        <td class='text-center'>{{ $query->tipo }}</td>
                        <td class='text-center'>{{ $query->tabela }}</td>
                        <td class='text-center'>{{ $query->query }}</td>
                        <td class='text-center'>{{ $query->time }}</td>
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
    </div>

    <div class="row">

        <div class="col-md-6">
            <div class='pull-right'>@isset($lista){{  $lista->links() }}@endisset</div>
        </div>
    </div>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function descreverTabelas() {
            let selectTabelas = $('#tabelas');
            let tabelaSelecionada = selectTabelas.val();

            $.post('/ajaxDesc', {nomeTabela: tabelaSelecionada})
                .done(function (dados) {

                    let campos = $('.campos');
                    campos.html('');
                    campos.append('<option value="" disabled selected>Selecione um campo</option>');

                    for (let i = 0; i < dados.length; i++) {
                        let campo = dados[i].Field;
                        campos.append('<option value="' + campo + '">' + campo + '</option>');
                    }

                }).catch((dados) => {
                console.log('catch: ');
                console.log(dados)
            });
        };

        //const novaLinha = $(".linha-log");

        /*
                $('#btnAdicionarLinha').click((event) => {
                    event.preventDefault();

                    //console.log(novaLinha.html());

                    const divLog = $("#div-log");

                    //divLog.append(novaLinha.html());

                    divLog.append("<div class=\"linha-log margin-top-25\">\n" +
                        "                        <div class=\"col-sm-4\">\n" +
                        "                            <label for=\"campo\">Campo</label>\n" +
                        "                            <select name=\"campo\" id=\"\" class=\"form-control campos\">\n" +
                        "                                <option value=\"\" disables> Selecione a tabela para ver os campos...</option>\n" +
                        "                            </select>\n" +
                        "                        </div>\n" +
                        "\n" +
                        "                        <div class=\"col-sm-4\">\n" +
                        "                            <label for=\"operacao\">Operação</label>\n" +
                        "                            <select name=\"operacao\" id=\"operacao\" class=\"form-control\">\n" +
                        "                                <option value=\"\"></option>\n" +
                        "                                <option value=\"=\">Igual</option>\n" +
                        "                                <option value=\">\">Maior que</option>\n" +
                        "                                <option value=\"<\">Menor que</option>\n" +
                        "                                <option value=\">=\">Maior ou igual a</option>\n" +
                        "                                <option value=\"<=\">Menor ou igual a</option>\n" +
                        "                                <option value=\"!=\">Diferente de</option>\n" +
                        "                            </select>\n" +
                        "                        </div>\n" +
                        "\n" +
                        "                        <div class=\"col-sm-4 \">\n" +
                        "                            <label for=\"valor\">Valor</label>\n" +
                        "                            <input type=\"text\" id=\"valor\" name=\"valor\" class=\"form-control\">\n" +
                        "                        </div>\n" +
                        "                    </div>");

                });
        */
    </script>

@endsection

