<div id="modal-detalhes-evolucao" class="modal fade"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div id="dialog-detalhes-evolucao" class="modal-dialog">
        <div  class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="myModalLabel">Evolução</h3>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="descricao_solicitacao">Detalhes</label>
                            <textarea class="form-control input-sm" maxlength="1950" rows="6" {{$lista[0]->status == 'C' ? 'disabled' : ''}} id="descricao_evolucao"></textarea>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="sala">Selecione uma sala</label>
                    <select id="cd_sala" class="form-control">
                        <option value='0'>NÃO INFORMADO</option>
                        @foreach($salas as $s)
                            <option value='{{$s->cd_sala}}' {{(session()->get('cd_sala')==$s->cd_sala) ? "selected" : 0}}>{{$s->nm_sala}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="cd_leito">Selecione um leito</label>
                    <select id="cd_leito" class="form-control">
                        @foreach(arrayPadrao('leitos') as $l => $n)
                            <option value='{{$l}}' {{(session()->get('cd_sala')==$n) ? "selected" : ""}}>{{$n}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger margin-top-zero pull-left" data-dismiss="modal">Sair</button>
                @if((session()->get('recurso.atendimentos-evolucao-salvar')))
                    <button type="button" id="salvar-evolucao" class="btn btn-success pull-right">Salvar</button>
                @endif
            </div>
        </div>
    </div>
</div>

<script src="{{ js_versionado('modal_evolucao.js') }}" defer></script>
